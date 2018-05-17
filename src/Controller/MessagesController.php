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
    $this->Security->setConfig('unlockedActions', ['add', 'open']);
  }

  public function index(){

  } // index

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