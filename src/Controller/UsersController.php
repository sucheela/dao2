<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Http\Client;
use Cake\Core\Exception\Exception;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use App\Lib\ChineseCalendar;
use Cake\Utility\Security;
use App\View\Helper\DaoHelper;

class UsersController extends AppController {

  private $_captchaSecret = '6LfIHhoTAAAAACnKIsV8CkNXCHKdbAoZvxIe2e80';
  private $_googleGeoKey  = 'AIzaSyANjcC2ZJdFowd94scmtThmRnZU1nHHPkA';

  public function beforeFilter(Event $event){
    parent::beforeFilter($event);
    // Allow users to register and logout.
    // You should not add the "login" action to allow list. Doing so would
    // cause problems with normal functioning of AuthComponent.
    $this->Auth->allow(['register',
                        'confirmRegistration',
                        'resetPassword',
                        'changePassword',
                        'logout']);
    $this->Auth->config('authenticate', [
      'Form' => [
        'fields' => ['username' => 'email', 'password' => 'password'],
        'finder' => 'auth'
        ]
    ]);
  } // beforeFilter()

  public function login(){
    if ($this->request->is('post')) {      
      $user = $this->Auth->identify();

      // prepare to record the login
        $logTable = TableRegistry::get('user_logins');
        $log = $logTable->newEntity();
        $log->email = $this->request->getData('email');
      
      if ($user) {
        $this->Auth->setUser($user);
        
        // record good login
        $log->user_id = $this->Auth->user('id');
        $log->is_successful = '1';
        $logTable->save($log);
        
        return $this->redirect($this->Auth->redirectUrl());
      }
      
      // record bad login
      $log->is_successful = '0';
      $logTable->save($log);
      $this->Flash->error('Invalid username or password. Please try again. If you have a Google or Facebook account, you may login using Facebook login or Google login. If you are a new user, pleaes register using the link below.');
    }
  } // login()

  public function logout(){
    return $this->redirect($this->Auth->logout());
  } // logout()

  public function resetPassword(){
    if ($this->request->is('post')){
      if ($this->request->getData('reset_type') == 'registration'){
        $action = 'request a registration code';
        $token  = 'registration code';
        $template = 'register';
        $done = 'complete the registration and update your password';
      } else {
        $action = 'reset password';
        $token = 'password reset';
        $template = 'password';
        $done = ' change the password';
      }
      
      try {
        // check captcha
        $http = new Client();
        $response = $http->post('https://www.google.com/recaptcha/api/siteverify',[
          'secret' => $this->_captchaSecret,
          'response' => $this->request->getData('g-recaptcha-response'),
          'remoteip' => $_SERVER['REMOTE_ADDR']
        ]);
        $captcha = $response->json;
        if ($captcha['success'] == false){
          throw new Exception('Sorry. We do not allow robots to ' . $action . '.');
        }

        // validate email format
        $email = strtolower(trim($this->request->getData('email')));
        if (strlen($email) == 0){
          throw new Exception('Please enter your email address.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
          throw new Exception('Please enter a valid email address.');
        }

        // check if the user exists
        $result = $this->Users->findByEmail($email);
        $user   = $result->first();
        if (!$user->id){
          throw new Exception('Unable to complete the ' . $token . ' request.');
        }
        
        // add row in user_password_resets
        $hash = $this->Users->resetPass($user->id);
        if (empty($hash)){
          throw new Exception('There was an error trying to ' . $action . '. Please try again.');
        }
                
        // email user
        $mailer = new Email();
        $mailer
          ->setTemplate($template, 'default')
          ->emailFormat('both')
          //->setTo($email)
          ->setTo('sucheela.n@gmail.com')
          ->setFrom('register@dao.dating')
          ->setSubject('Dao.Dating ' . ucfirst($token))
          ->setAttachments([
            'logo.png' => [
              'file' => $this->webroot . 'img/logo-long.png',
              'mimetype' => 'image/png',
              'contentId' => 'dao-dating-logo-long'
            ]
          ])
          ->viewVars(['hash' => base64_encode($hash)])
          ->send();

        $this->set('reset_success', true);
        $this->Flash->success('Please check your mailbox for an email from register@dao.dating and follow the instructions in the email to ' . $done . '.');
        
      } catch (Exception $e){
        $this->Flash->error($e->getMessage());
      }

    } // end if posted
  } // resetPassword()

  /**
   * Force changing password after the password reset
   */
  public function changePassword(){
    try {
      // get hash
      $params = $this->request->getQueryParams();
      if (count($params) != 1){
        throw new Exception('Invalid password reset token.');
      }
      $hash = base64_decode(key($params));
      // check valid hash
      $user_id = $this->Users->getResetUserId($hash);
      if (empty($user_id)){
        throw new Exception('Invalid or expired password reset token.');
      }   
      $is_valid_token = true;
      
    } catch (Exception $e){
      $msg = $e->getMessage() . ' Click Request a New Token to request a new password reset token.';
      $this->Flash->error($msg);
      $is_valid_token = false;
    }
    $this->set('is_valid_token', $is_valid_token);

    if ($is_valid_token && $this->request->is('post')) {
      try {
        // passwords are not empty
        if (strlen($this->request->getData('new_password')) == 0 ||
            strlen($this->request->getData('conf_password')) == 0){
          throw new Exception('Please do not leave the password fields blank.');
        }
        
        // password meets requirement
        $pass = $this->request->getData('new_password');
        if (strlen($pass) < 7 || // at least 7 chars
            !preg_match('/\d/', $pass) || // has number
            !preg_match('/[^a-zA-Z\d]/', $pass)){ // has special chars
          throw new Exception('The password does not meet the strength requirement.');
        }
        
        // passwords are the same
        if ($pass != $this->request->getData('conf_password')){
          throw new Exception('The passwords do not match.');
        }

        // save the password
        $user = $this->Users->get($user_id);
        $user->password = (new DefaultPasswordHasher)->hash($pass);
        $user->status = 'Active';
        $this->Users->save($user);
        // deactivate the reset row
        $this->Users->deactivateReset($user_id);
        
        $this->Flash->success('The password has been updated. You can now access the site with your email address and your new password');

        $this->redirect([
          'controller' => 'Users',
          'action'     => 'login']);
      } catch(Exception $e){
        $this->Flash->error($e->getMessage());
      }      
    } // end if posted
    
  } // changePassword()

  /**
   * Password change from my profile
   */
  public function password(){
    if ($this->request->is('post')){
      try {
        // passwords are not empty
        if (strlen($this->request->getData('new_password')) == 0 ||
            strlen($this->request->getData('conf_password')) == 0){
          throw new Exception('Please do not leave the password fields blank.');
        }
        
        // password meets requirement
        $pass = trim($this->request->getData('new_password'));
        if (strlen($pass) < 7 || // at least 7 chars
            !preg_match('/\d/', $pass) || // has number
            !preg_match('/[^a-zA-Z\d]/', $pass)){ // has special chars
          throw new Exception('The password does not meet the strength requirement.');
        }
        
        // passwords are the same
        if ($pass != $this->request->getData('conf_password')){
          throw new Exception('The passwords do not match.');
        }

        // save the password
        $user = $this->Users->get($this->Auth->user('id'));
        $user->password = (new DefaultPasswordHasher)->hash($pass);
        $this->Users->save($user);
        // deactivate the reset row if any
        $this->Users->deactivateReset($this->Auth->user('id'));
        
        $this->Flash->success('The password has been updated. You can now access the site with your email address and the new password');
      } catch (Exception $e){
        $this->Flash->error($e->getMessage());
      }
    } // end if post request

  } // password()

  public function register(){
    $user = $this->Users->newEntity();
    if ($this->request->is('post')){
      try {
        // check captcha
        $http = new Client();
        $response = $http->post('https://www.google.com/recaptcha/api/siteverify',[
          'secret' => $this->_captchaSecret,
          'response' => $this->request->getData('g-recaptcha-response'),
          'remoteip' => $_SERVER['REMOTE_ADDR']
        ]);
        $captcha = $response->json;
        if ($captcha['success'] == false){
          throw new Exception('Sorry. We do not allow robots to register.');
        }

        $user = $this->Users->patchEntity($user, $this->request->getData());
        // get latitude and longitude from zipcode
        $response = $http->get('https://maps.googleapis.com/maps/api/geocode/json?'
                               . '&components=country:'
                               . urlencode($this->request->getData('country_code'))
                               . '|postal_code:'
                               . urlencode($this->request->getData('zipcode'))
                               . '&key=' . $this->_googleGeoKey);
        $location = $response->json;
        if ($location['status'] == 'OK'){
          $user->latitude = $location['results'][0]['geometry']['location']['lat'];
          $user->longitude = $location['results'][0]['geometry']['location']['lng'];
          $user->address = $location['results'][0]['formatted_address'];
        }

        // set branches
        $cal = new ChineseCalendar($this->request->getData('birth.year'),
                                   $this->request->getData('birth.month'),
                                   $this->request->getData('birth.day'),
                                   $this->request->getData('hour_num') == -1
                                   ? null : $this->request->getData('hour_num'));
        $user->year_branch_id  = $cal->getBranch(ChineseCalendar::TYPE_YEAR,
                                                 ChineseCalendar::FORMAT_NUMBER);
        $user->month_branch_id = $cal->getBranch(ChineseCalendar::TYPE_MONTH,
                                                 ChineseCalendar::FORMAT_NUMBER);
        $user->day_branch_id   = $cal->getBranch(ChineseCalendar::TYPE_DATE,
                                                 ChineseCalendar::FORMAT_NUMBER);
        $user->hour_branch_id  = $cal->getBranch(ChineseCalendar::TYPE_HOUR,
                                                 ChineseCalendar::FORMAT_NUMBER);
        // set stems
        $user->year_stem_id  = $cal->getStem(ChineseCalendar::TYPE_YEAR,
                                             ChineseCalendar::FORMAT_NUMBER);
        $user->month_stem_id = $cal->getStem(ChineseCalendar::TYPE_MONTH,
                                             ChineseCalendar::FORMAT_NUMBER);
        $user->day_stem_id   = $cal->getStem(ChineseCalendar::TYPE_DATE,
                                             ChineseCalendar::FORMAT_NUMBER);
        $user->hour_stem_id   = $cal->getStem(ChineseCalendar::TYPE_HOUR,
                                              ChineseCalendar::FORMAT_NUMBER);
        
        // set pending status
        $user->status = 'Pending';
        // TODO: If with Google or Facebook token, set to Active
        
        if ($this->Users->save($user)){
          // TODO: if there's Google or Facebook token, redirect to match page

          // if manual registration,
          // 1. add a row in user_password_resets
          $hash = $this->Users->resetPass($user->id);
          if (empty($hash)){
            throw new Exception('There was an error retrieving the registration code. Please contact Alex@dao.dating for assistance.');
          }
          // 2. send validation email
          $mailer = new Email();
          $mailer
            ->setTemplate('register', 'default')
            ->emailFormat('both')
            //->setTo($this->request->getData('email'))
            ->setTo('sucheela.n@gmail.com')
            ->setFrom('register@dao.dating')
            ->setSubject('Dao.Dating Registration Code')
            ->setAttachments([
              'logo.png' => [
                'file' => $this->webroot . 'img/logo-long.png',
                'mimetype' => 'image/png',
                'contentId' => 'dao-dating-logo-long'
              ]
            ])
            ->viewVars(['hash' => base64_encode($hash)])
            ->send();
          
          $this->set('register_success', true);
          $this->Flash->success('Please check your mailbox for an email from register@dao.dating and follow the instructions in the email to complete your registration.');
          
        } else {
          throw new Exception('There were errors with the registration. Please correct the errors noted in red below before re-submitting.');
        }        

      } catch (Exception $e){
        $this->Flash->error($e->getMessage());
      }        
      
    } // end if the form was posted

    $this->set('user', $user);
    $countries = TableRegistry::get('countries');
    $query = $countries->find('list')->order('name');
    $this->set('country_options', $query->toArray());
    
  } // register()

  public function confirmRegistration(){
    $this->set('reset_type', 'registration');
    $this->setAction('resetpassword');
  } // confirmRegistration()

  public function index(){
    $this->setAction('view');
  } // index()

  public function edit(){
    try {
      $user = $this->Users->get($this->Auth->user('id'), [
        'contain' => ['user_into_genders']
      ]);
    } catch(Exception $e){
      $this->render('404');
      return;
    }

    if ($this->request->is('post')){
      $user = $this->Users->patchEntity($user,
                                        $this->request->getData(),
                                        [ 'id' => $this->Auth->user('id') ]);
      if ($user->isDirty('zipcode')){
        // get latitude and longitude from zipcode
        $http = new Client();
        $response = $http->get('https://maps.googleapis.com/maps/api/geocode/json?'
                               . '&components=country:'
                               . urlencode($this->request->getData('country_code'))
                               . '|postal_code:'
                               . urlencode($this->request->getData('zipcode'))
                               . '&key=' . $this->_googleGeoKey);
        $location = $response->json;
        if ($location['status'] == 'OK'){
          $user->latitude = $location['results'][0]['geometry']['location']['lat'];
          $user->longitude = $location['results'][0]['geometry']['location']['lng'];
          $user->address = $location['results'][0]['formatted_address'];
        }
      } // end if zipcode is dirty

      if ($user->isDirty('birth_date') ||
          $user->isDirty('hour_num')){
        // set branches
        $cal = new ChineseCalendar($this->request->getData('birth.year'),
                                   $this->request->getData('birth.month'),
                                   $this->request->getData('birth.day'),
                                   $this->request->getData('hour_num') == -1
                                   ? null : $this->request->getData('hour_num'));
        $user->year_branch_id  = $cal->getBranch(ChineseCalendar::TYPE_YEAR,
                                                 ChineseCalendar::FORMAT_NUMBER);
        $user->month_branch_id = $cal->getBranch(ChineseCalendar::TYPE_MONTH,
                                                 ChineseCalendar::FORMAT_NUMBER);
        $user->day_branch_id   = $cal->getBranch(ChineseCalendar::TYPE_DATE,
                                                 ChineseCalendar::FORMAT_NUMBER);
        $user->hour_branch_id  = $cal->getBranch(ChineseCalendar::TYPE_HOUR,
                                                 ChineseCalendar::FORMAT_NUMBER);
        // set stems
        $user->year_stem_id  = $cal->getStem(ChineseCalendar::TYPE_YEAR,
                                             ChineseCalendar::FORMAT_NUMBER);
        $user->month_stem_id = $cal->getStem(ChineseCalendar::TYPE_MONTH,
                                             ChineseCalendar::FORMAT_NUMBER);
        $user->day_stem_id   = $cal->getStem(ChineseCalendar::TYPE_DATE,
                                             ChineseCalendar::FORMAT_NUMBER);
        $user->hour_stem_id   = $cal->getStem(ChineseCalendar::TYPE_HOUR,
                                              ChineseCalendar::FORMAT_NUMBER);        
      } // end if birthdate is dirty

      if ($this->Users->save($user)){
        $this->Flash->success('The information below has been updated.');
      } else {
        $this->Flash->error('There were errors with the form. Please correct the errors noted in red below before re-submitting.');
      }
    } // end if posted request

    $this->set('user', $user);
    $user_into_genders = array();
    foreach ($user->user__into__genders as $row){
      $user_into_genders[] = $row->gender;
    }
    $this->set('user_into_genders', $user_into_genders);
    
    // get images
    $images = TableRegistry::get('user_images')
            ->find()
            ->where([
              'user_id' => $this->Auth->user('id'),
              'is_hidden' => '0'
            ])
            ->order([
              'is_default' => 'desc',
              'sort_order' => 'asc',
              'created_date' => 'asc'
            ])
            ->toArray();
    $this->set('images', $images);

    $countries = TableRegistry::get('countries');
    $query = $countries->find('list')->order('name');
    $this->set('country_options', $query->toArray());    
    
  } // edit()

  public function email(){
    try {
      $user = $this->Users->get($this->Auth->user('id'));
      $old_email = $user->email;
    } catch(Exception $e){
      $this->render('404');
      return;
    }

    if ($this->request->is('post')){
      $new_email = strtolower(trim($this->request->getData('email')));
      try {
        // validate email format
        if (strlen($new_email) == 0){
          throw new Exception('Please enter your new email address.');
        }
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)){
          throw new Exception('Please enter a valid email address.');
        }

        // check if the email changes
        if ($new_email == trim(strtolower($old_email))){
          throw new Exception('There is no change in the email address.');
        }
        
        // check if the email exists
        $user_exists = $this->Users->findByEmail($new_email)->count();
        if ($user_exists > 0){
          throw new Exception('Unable to complete the email address change request.');
        }        

        // add row in user_email_changes
        $hash = $this->Users->changeEmail($user->id, $new_email);
        if (empty($hash)){
          throw new Exception('There was an error trying to request the email address change. Please try again.');
        }

        $mailer = new Email();
        // notify the old email address
        $mailer
          ->setTemplate('email_change_notify', 'default')
          ->emailFormat('both')
          //          ->setTo($old_email)
          ->setTo('sucheela.n@gmail.com')
          ->setFrom('alex@dao.dating')
          ->setSubject('Dao.Dating Email Address Change')
          ->setAttachments([
              'logo.png' => [
                'file' => $this->webroot . 'img/logo-long.png',
                'mimetype' => 'image/png',
                'contentId' => 'dao-dating-logo-long'
              ]
            ])
          ->send();
        
        // send validation email
        $mailer
          ->setTemplate('email_change', 'default')
          ->emailFormat('both')
          //          ->setTo($new_email)
          ->setTo('sucheela.n@gmail.com')
          ->setFrom('alex@dao.dating')
          ->setSubject('Dao.Dating Email Address Change Confirmation')
          ->setAttachments([
              'logo.png' => [
                'file' => $this->webroot . 'img/logo-long.png',
                'mimetype' => 'image/png',
                'contentId' => 'dao-dating-logo-long'
              ]
            ])
          ->viewVars(['hash' => base64_encode($hash)])
          ->send();
        
        $this->Flash->success('The email address change request is successful. You will recieve a validation email at the new address. Please click through the link in the email to confirm the request.');
        
      } catch (Exception $e){
        $this->Flash->error($e->getMessage());
      }
            
    } // end if post request

  } // email()

  public function changeEmail(){
    try {
      // get hash
      $params = $this->request->getQueryParams();
      if (count($params) != 1){
        throw new Exception('Invalid password reset token.');
      }
      $hash = base64_decode(key($params));
      // check valid hash
      if ($info = $this->Users->getEmailChangeInfo($hash)){
        $user_id = $info['user_id'];
        $email   = strtolower(trim($info['email']));
      } else {
        throw new Exception('Invalid or expired email change request token.');
      }

      // make sure that this is the same user as the auth user
      if ($user_id != $this->Auth->user('id')){
        throw new Exception('Unauthorized request. Please make sure that your login email address matches the profile email.');
      }
      
      // get the user
      $user = $this->Users->get($user_id);
      $user->email = $email;
      if ($this->Users->save($user)){
        $this->Users->deactivateEmailChange($user_id);        
      } else {
        throw new Exception('Error updating the email address. Please try again.');
      }            

      $this->Flash->success('The email address associated with your account has been updated. You can now login with the new address.');
      $is_valid_token = true;
    } catch (Exception $e){
      $this->Flash->error($e->getMessage());
      $is_valid_token = false;
    }

    $this->set('is_valid_token', $is_valid_token);
    $this->setAction('email');
  } // changeEmail()

  public function status(){
    try {
      $user = $this->Users->get($this->Auth->user('id'));
    } catch (Exception $e){
      $this->render('404');
      return;
    }

    if ($this->request->is('post')){
      $status = $this->request->getData('status');
      try {
        switch ($status){
        case 'Active':
          if ($user->status == 'Deleted'){
            throw new Exception('Sorry. You cannot re-activate a deleted profile.');
          }
          if ($this->Users->activate($user->id)){
            $user->status = 'Active';
            $this->Flash->success('Your profile has been re-activated.');
          } else {
            throw new Exception('There was an error reactivating your profile. Please try again.');
          }
          break;
        case 'Inactive':
          if ($user->status != 'Active'){
            throw new Exception('Sorry. You cannot deactivate a profile that is not currently active.');
          }
          if ($this->Users->deactivate($user->id)){
            // update Auth
            $this->Auth->status = 'Inactive';
            $user->status = 'Inactive';
            $this->Flash->success('Your profile has been deactivated.');
          } else {
            throw new Exception('There was an error deactivating your profile. Please try again.');
          }
          break;
        case 'Deleted':
          if ($user->status == 'Deleted'){
            throw new Exception('This profile has already been deleted. Please tell us how you managed to get to this page. Thanks!');
          }
          if ($hash = $this->Users->requestDelete($this->Auth->user('id'))){
            $this->Flash->success('Not done yet! We just sent you an email confirmation. Please follow the link in the email within 24 hours to complete the deletion.');
            // email user
            $mailer = new Email();
            $mailer
              ->setTemplate('delete', 'default')
              ->emailFormat('both')
              //->setTo($user->email)
              ->setTo('sucheela.n@gmail.com')
              ->setFrom('register@dao.dating')
              ->setSubject('Dao.Dating Profile Deletion Confirmation')
              ->setAttachments([
                'logo.png' => [
                  'file' => $this->webroot . 'img/logo-long.png',
                  'mimetype' => 'image/png',
                  'contentId' => 'dao-dating-logo-long'
                ]
              ])
              ->viewVars(['hash' => base64_encode($hash)])
              ->send();
          } else {
            throw new Exception('There was an error requesting a deletion of your profile. Please try again.');
          }
          break;
        default:
          throw new Exception('There was an error updating the profile status. Please try again.');
          break;
        } // end switch $status

      } catch (Exception $e){
        $this->Flash->error($e->getMessage());
      }
      
    } // end if post request
    $this->set('status', $user->status);
  } // status()

  public function delete(){
    try {
      // get hash
      $params = $this->request->getQueryParams();
      if (count($params) != 1){
        throw new Exception('Invalid profile deletion request token.');
      }
      $hash = base64_decode(key($params));
      // check valid hash
      $user_id = $this->Users->getDeleteUserId($hash);
      if (empty($user_id)){
        throw new Exception('Invalid or expired profile deletion request token.');
      }

      // make sure that this is the same as the auth user
      if ($user_id != $this->Auth->user('id')){
        throw new Exception('Unauthorized request. Please make sure that your login email address matches the profile email.');
      }

      $user = $this->Users->get($user_id);
      if ($user->status == 'Deleted'){
        throw new Exception('This profile has already been deleted. Please tell us how you managed to get here. Thanks!');
      }
      if ($this->Users->deleteUser($user_id)){
        $is_deleted = true;
        $this->Auth->status = 'Deleted';
        $this->Flash->success('The profile has been deleted.');
        header('Refresh: 10; URL: /users/logout');
      }
    } catch (Exception $e){
      $is_deleted = false;
      $this->Flash->error($e->getMessage());
    }
    $this->set('is_deleted', $is_deleted);
  } // delete()
  
  public function view(){
    if (isset($_GET['u']) &&
        ($encrypted_id = $_GET['u'])){
      $id = Security::decrypt(base64_decode($encrypted_id),
                              ENCRYPT_KEY);
    }
    
    if (empty($id) || !is_numeric($id)){
      $id = $this->Auth->user('id');
    }

    try {
      $user = $this->Users->get($id, [
        'contain' => [ 'User_Into_Genders' ]
      ]);
      $this->set('user', $user);
    } catch(Exception $e){
      $this->render('404');
      return;
    }

    // if deactivated or deleted, 404
    if ($user->status == 'Inactive' ||
        $user->status == 'Deleted'){
      $this->render('404');
      return;
    }

    // if the viewing user is deactivated or deleted, 401
    if ($this->Auth->user('status') != 'Active'){
      $this->render('401');
      return;
    }

    if ($id != $this->Auth->user('id')){
      // record the visit
      $visitorTable = TableRegistry::get('user_visitors');
      $visitor = $visitorTable->newEntity();
      $visitor->visitor_user_id = $this->Auth->user('id');
      $visitor->user_id = $user->id;
      $visitorTable->save($visitor);
    }

    // get images
    $images = TableRegistry::get('user_images')
            ->find()
            ->where([
              'user_id' => $id,
              'is_hidden' => '0'
            ])
            ->order([
              'is_default' => 'asc',
              'sort_order' => 'asc',
              'created_date' => 'desc'
            ])
            ->toArray();
    $this->set('images', $images);
    
    // get match score
    $score = $this->Users->getMatchScore($this->Auth->user('id'), $id);
    $this->set('score', $score['total_score']);
    $this->set('year_text', $score['year_text']);
    $this->set('month_text', $score['month_text']);
    $this->set('hour_text', $score['hour_text']);
    $this->set('day_text', $score['day_text']);
    
    // is blocked?
    $is_blocked = TableRegistry::get('user_blocks')
                ->find()
                ->where([
                  'user_id' => $this->Auth->user('id'),
                  'blocked_user_id' => $id
                ])
                ->count();
    $this->set('is_blocked', $is_blocked);
      
    // is favorite?
    $is_favorite = TableRegistry::get('user_favorites')
                 ->find()
                 ->where([
                  'user_id' => $this->Auth->user('id'),
                  'fav_user_id' => $id
                 ])
                 ->count();
    $this->set('is_favorite', $is_favorite);

    // get all branch description
    $branches = TableRegistry::get('branches')
              ->find('list', [
                'keyField' => 'id',
                'valueField' => 'description'
              ])
              ->toArray();
    $this->set('branches', $branches);

    // get the login user
    $me = $this->Users->get($this->Auth->user('id'));
    $this->set('me', $me);
    
  } // view()
}
?>