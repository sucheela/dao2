<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;

class MessagesController extends AppController {

  public function beforeFilter(Event $event)
  {
    parent::beforeFilter($event);
    $this->Security->setConfig('unlockedActions', ['add', 'open', 'openall']);
  }

  public function index($page=1){
    $threads = $this->Messages->getUserMessages($this->Auth->user('id'), $page);
    /*
    // get score information
    $utab = TableRegistry::get('users');
    $ret = array();
    foreach ($threads as $i => $t){
      $score = $utab->getMatchScore($this->Auth->user('id'),
                                    $t['user_id']);
      $threads[$i]['total_score'] = $score['year_score'] +
                                  $score['month_score'] +
                                  $score['hour_score'] +
                                  $score['day_score'];
      $threads[$i]['has_hour'] = $score['has_hour'];
    }
    */
    $this->set('threads', $threads);
    $this->set('user_id', $this->Auth->user('id'));

    // get favorites
    $favs = TableRegistry::get('user_favorites');
    $result = $favs
            ->find()
            ->select('fav_user_id')
            ->where(['user_id' => $this->Auth->user('id')])
            ->all();
    $favorites = $result->extract('fav_user_id')->toArray();
    $this->set('favorites', $favorites);

  } // index ()

  public function more($page=2){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    $threads = $this->Messages->getUserMessages($this->Auth->user('id'), $page);
    $this->set('threads', $threads);
    $this->set('user_id', $this->Auth->user('id'));

    // get favorites
    $favs = TableRegistry::get('user_favorites');
    $result = $favs
            ->find()
            ->select('fav_user_id')
            ->where(['user_id' => $this->Auth->user('id')])
            ->all();
    $favorites = $result->extract('fav_user_id')->toArray();
    $this->set('favorites', $favorites);
  } // more ()

  public function view($thread_id){
    $messages = $this->Messages->getMessagesByThread($thread_id);
    if (empty($messages)){
      $this->render('404');
    }
    
    // figure out the other side
    $user_id = null;
    $user_name = null;
    $month_branch_id = null;
    $image_id = null;
    $msg = $messages[0];
    if ($msg['from_user_id'] == $this->Auth->user('id')){
      $user_id = $msg['to_user_id'];
      $user_name = $msg['to_user_name'];
      $month_branch_id = $msg['to_month_branch_id'];
    } else {
      $user_id = $msg['from_user_id'];
      $user_name = $msg['from_user_name'];
      $month_branch_id = $msg['from_month_branch_id'];
    }
    $images = TableRegistry::get('user_images')
            ->find()
            ->where([
              'user_id' => $user_id,
              'is_hidden' => '0',
              'is_default' => 1
            ])
            ->toArray();
    if (!empty($images)){
      $image_id = $images[0]['id'];
    }

    while (count($messages) < \App\Model\Table\MessagesTable::MESSAGE_LIMIT){
      $thread_id = $this->Messages->getPreviousThreadId($thread_id,
                                                        $user_id,
                                                        $this->Auth->user('id'));
      if ($thread_id){
        $prev_messages = $this->Messages->getMessagesByThread($thread_id);
        if ($prev_messages){
          $messages = array_merge($prev_messages, $messages);
        }
      } else {
        break;
      }
    }    
    
    $this->set('messages', $messages);
    $this->set('user_id', $user_id);
    $this->set('user_name', $user_name);
    $this->set('image_id', $image_id);
    $this->set('month_branch_id', $month_branch_id);

    if ($tz = $this->Auth->user('timezone')){
      $this->set('timezone', new \DateTimeZone($tz));
    }

    $prev_thread_id = $this->Messages->getPreviousThreadId($thread_id,
                                                           $user_id,
                                                           $this->Auth->user('id'));
    $this->set('prev_thread_id', $prev_thread_id);
    
    // get favorites
    $favs = TableRegistry::get('user_favorites');
    $result = $favs
            ->find()
            ->select('fav_user_id')
            ->where(['user_id' => $this->Auth->user('id')])
            ->all();
    $favorites = $result->extract('fav_user_id')->toArray();
    $this->set('favorites', $favorites);
  } // view()

  public function previous($thread_id){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    $messages = $this->Messages->getMessagesByThread($thread_id);
    if (empty($messages)){
      return;
    }
    
    $msg = $messages[0];
    if ($msg['from_user_id'] == $this->Auth->user('id')){
      $user_id = $msg['to_user_id'];
    } else {
      $user_id = $msg['from_user_id'];
    }

    while (count($messages) < \App\Model\Table\MessagesTable::MESSAGE_LIMIT){
      $thread_id = $this->Messages->getPreviousThreadId($thread_id,
                                                        $user_id,
                                                        $this->Auth->user('id'));
      if ($thread_id){
        $prev_messages = $this->Messages->getMessagesByThread($thread_id);
        if ($prev_messages){
          $messages = array_merge($prev_messages, $messages);
        }
      } else {
        break;
      }
    }
    $this->set('messages', $messages);
    $this->set('user_id', $user_id);
    if ($tz = $this->Auth->user('timezone')){
      $this->set('timezone', new \DateTimeZone($tz));
    }

    $prev_thread_id = $this->Messages->getPreviousThreadId($thread_id,
                                                           $user_id,
                                                           $this->Auth->user('id'));
    $this->set('prev_thread_id', $prev_thread_id);    
    
  } // previous()

  public function thread(){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    try {
      if (empty($_GET['to_user_id'])){
        throw new Exception('401');
      }

      // only active users can add
      if ($this->Auth->user('status') != 'Active'){
        throw new Exception('405');
      }

      $to_user_id = Security::decrypt(base64_decode($_GET['to_user_id']),
                                      ENCRYPT_KEY);
      if (empty($to_user_id) || !is_numeric($to_user_id)){
        throw new Exception('404');
      }

      if ($to_user_id == $this->Auth->user('id')){
        throw new Exception('403');
      }

      if ($thread_id = $this->Messages->getThreadID($this->Auth->user('id'),
                                                    $to_user_id)){
        $this->set('thread_id', $thread_id);
      } else {
        throw new Exception('500');
      }
    } catch (Exception $e){
      $this->set('error', $e->getMessage());
    }
  } // thread()

  public function add(){
    if ($this->request->is('post')){
      $thread_id = $this->request->getData('thread_id');
      $message = $this->request->getData('message');

      if (strlen(trim($message)) == 0){
        // not adding an empty message
      } else {
        $this->Messages->addMessage($thread_id,
                                    $this->Auth->user('id'),
                                    $message);
      }
      
      // see if the recipient is online
      $this->set('is_online',
                 $this->Messages->isRecipientOnline($thread_id,
                                                    $this->Auth->user('id')));
      // message sent time according to the user's timezone
      if ($timezone = $this->Auth->user('timezone')){
        $now = new \DateTime('now', new \DateTimeZone($timezone));
      } else {
        $now = new \DateTime();
      }
      $this->set('created_date', $now->format('F d, Y h:ia'));
    }
  } // add()

  public function open(){
    if ($this->request->is('post')){
      $thread_id = $this->request->getData('thread_id');
      $message_id = $this->request->getData('message_id');

      $this->Messages->open($thread_id, $message_id);
    }
  } // open

  public function openAll(){
    if ($this->request->is('post')){
      $thread_id = $this->request->getData('thread_id');

      $this->Messages->openAll($thread_id, $this->Auth->user('id'));
    }
    $this->render('open');
  }

  public function refresh(){
    if ($timezone = $this->Auth->user('timezone')){
      $timezone = new \DateTimeZone($timezone);
    } 
    $messages = $this->Messages->getNewMessages($this->Auth->user('id'));
    $this->set('messages', $messages);
  } // refresh()

  public function recent($thread_id){
    $messages = $this->Messages->getMessagesByThread($thread_id);
    
    // get rid of the first empty message
    if ($messages[0]['message'] == '{{{ new message }}}'){
      array_shift($messages);
    }
    
    // display up to the most recent 10 messages
    $count = count($messages);
    if ($count > 10){
      $messages = array_slice($messages, -10);
    }
    
    // identify whose message it is - mine or yours
    // and adjust timezone
    foreach ($messages as $i => $msg){
      $messages[$i]['whose'] = ($msg['from_user_id'] == $this->Auth->user('id')
                                ? 'mine' : 'yours');
      $created_date = \DateTime::createFromFormat('Y-m-d H:i:s',
                                                  $msg['created_date'],
                                                  new \DateTimeZone($this->Messages::DB_TIMEZONE));
      if ($timezone = $this->Auth->user('timezone')){
        $created_date->setTimeZone(new \DateTimeZone($timezone));
      }
      $messages[$i]['created_date'] = $created_date->format('F d, Y h:ia');
    }
    $this->set('messages', $messages);
  } // recent()
  
} // MessagesController {}
?>