<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;

class BlocksController extends AppController {

  public function index(){
    $matches = TableRegistry::get('user_blocks')
             ->getBlocks($this->Auth->user('id'), 1);

    $this->set('matches', $matches);
    $this->set('in_user_blocks', true);

    $countries = TableRegistry::get('countries')
               ->find('list', [
                 'keyField' => 'country_code',
                 'valueField' => 'name'
               ])
               ->toArray();
    $this->set('countries', $countries);
  } // index()

  public function more($page=2){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    $matches = TableRegistry::get('user_blocks')
             ->getBlocks($this->Auth->user('id'), $page);

    $this->set('matches', $matches);
    $this->set('in_user_blocks', true);    

    $countries = TableRegistry::get('countries')
               ->find('list', [
                 'keyField' => 'country_code',
                 'valueField' => 'name'
               ])
               ->toArray();
    $this->set('countries', $countries);
  } // more()

  public function add(){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    try {
      if (empty($_GET['blocked_user_id'])){
        throw new Exception('401');
      }

      $blocked_user_id = Security::decrypt(base64_decode($_GET['blocked_user_id']),
                                           ENCRYPT_KEY);
      
      if (empty($blocked_user_id) || !is_numeric($blocked_user_id)){
        throw new Exception('404');
      }

      if ($blocked_user_id == $this->Auth->user('id')){
        throw new Exception('403');
      }
      
      $table = TableRegistry::get('user_blocks');

      // check if this pair doesn't already exist
      $blocked_exists = $table
                      ->find()
                      ->where([
                        'user_id' => $this->Auth->user('id'),
                        'blocked_user_id' => $blocked_user_id
                      ])
                      ->count();
      if ($blocked_exists > 0){
        throw new Exception('409');
      }
      
      $blocked = $table->newEntity([
        'user_id' => $this->Auth->user('id'),
        'blocked_user_id' => $blocked_user_id
      ]);
      if ($table->save($blocked)){
        $this->set('feedback', '200');
      } else {
        throw new Exception('500') ;
      }
    } catch (Exception $e){
      $this->set('error', $e->getMessage());
    }
    
  } // add()

  public function delete(){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    try {
      if (empty($_GET['blocked_user_id'])){
        throw new Exception('401');
      }

      $blocked_user_id = Security::decrypt(base64_decode($_GET['blocked_user_id']),
                                           ENCRYPT_KEY);
      
      if (empty($blocked_user_id) || !is_numeric($blocked_user_id)){
        throw new Exception('404');
      }
      
      $table = TableRegistry::get('user_blocks');
      
      if ($table->deleteAll([
        'user_id' => $this->Auth->user('id'),
        'blocked_user_id' => $blocked_user_id
      ])){
        $this->set('feedback', '200');
      } else {
        throw new Exception('500') ;
      }
    } catch (Exception $e){
      $this->set('error', $e->getMessage());
    }
    
  } // delete()
  
  
} // BlocksControllers{}
?>