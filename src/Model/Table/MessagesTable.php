<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class MessagesTable extends Table {

  /**
   * @const
   */
  const DB_TIMEZONE = 'America/New_York';
  const NEW_THREAD_INTERVAL = 60; // start new thread if no activity in 60 minutes
  const REFRESH_INTERVAL = 10;    // check for new messages every 10 seconds
  const THREAD_LIMIT = 20;        // # of threads per page to display on Messages page

  /**
   * @param Integer $from_user_id
   * @param Integer $to_user_id
   * @return String|NULL new thread_id
   */
  public function addThread($from_user_id, $to_user_id){
    if (empty($from_user_id) || !is_numeric($from_user_id) ||
        empty($to_user_id) || !is_numeric($to_user_id)){
      return null;
    }

    $conn = ConnectionManager::get('default');
    $thread_id = base64_encode(md5($from_user_id . '-' . $to_user_id . '-' . date('U')));
    $sql = "
insert
  into messages (thread_id, from_user_id, to_user_id, message)
values (:thread_id, :from_user_id, :to_user_id, '{{{ new message }}}')";
    $bind = array(':thread_id'    => $thread_id,
                  ':from_user_id' => $from_user_id,
                  ':to_user_id'   => $to_user_id);
    if ($conn->execute($sql, $bind)){
      return $thread_id;
    }
    return null;
  } // addThread()

  /**
   * @param String $thread_id
   * @param Integer $sender_user_id
   * @param String $message
   * @return Boolean
   */
  public function addMessage($thread_id, $sender_user_id, $message){
    if (empty($thread_id) ||
        empty($sender_user_id) || !is_numeric($sender_user_id) ||
        empty($message)){
      return false;
    }

    $sql = "
insert
  into messages (thread_id, from_user_id, to_user_id, message)
select thread_id, 
       :sender_user_id,
       case
         when from_user_id = :sender_user_id then to_user_id
         else from_user_id
       end,
       :message
  from messages
 where thread_id = :thread_id
   and message = '{{{ new message }}}'
   and (from_user_id = :sender_user_id or
        to_user_id = :sender_user_id)
   and not exists (
         select 'x'
           from messages test
          where test.thread_id = :thread_id
            and test.message = '{{ new message }}
            and test.id < messages.id')";
    $bind = array(':sender_user_id' => $sender_user_id,
                  ':message'        => $message,
                  ':thread_id'      => $thread_id);
    $conn = ConnectionManager::get('default');
    return $conn->execute($sql, $bind);
  } // addMessage();

  /**
   * If the user clicked in the last 15 minutes, the user is online.
   * @param String $thread_id
   * @param Integer $sender_user_id
   * @return Boolean
   */
  public function isRecipientOnline($thread_id, $sender_user_id){
    if (empty($thread_id) ||
        empty($sender_user_id) || !is_numeric($sender_user_id)){
      return false;
    }

    $sql = "
select case
         when TIMESTAMPDIFF(MINUTE, MAX(click_date), CURRENT_TIMESTAMP) < " . self::NEW_THREAD_INTERVAL . " then 1
         else 0
       end is_online
  from user_clicks
 where user_id = (
         select case
                  when to_user_id = :sender_user_id then from_user_id
                  else to_user_id
                end
           from messages
          where thread_id = :thread_id
            and (to_user_id = :sender_user_id or
                 from_user_id = :sender_user_id)
            and message = '{{{ new message }}}'
           limit 1)";
    $bind = array(':thread_id' => $thread_id,
                  ':sender_user_id' => $sender_user_id);
    $conn = ConnectionManager::get('default');
    $row = $conn->execute($sql, $bind)->fetch('assoc');
    if (!empty($row) && $row['is_online']){
      return true;
    }
    return false;
  } // isRecipientOnline()

  /**
   * If there's an active thread in the last 15 minutes, 
   * return the existing thread. Otherwise, start a new thread.
   * @param Integer $from_user_id
   * @param Integer $to_user_id
   * @return String|NULL
   */
  public function getThreadId($from_user_id, $to_user_id){
    if (empty($from_user_id) || !is_numeric($from_user_id) ||
        empty($to_user_id) || !is_numeric($to_user_id)){
      return null;
    }

    // check if there is an active thread going on
    $sql = "
select thread_id
  from messages
 where ( (from_user_id = :from_user_id and to_user_id = :to_user_id) or
         (to_user_id = :from_user_id and from_user_id = :to_user_id) )
   and timestampdiff(MINUTE, created_date, current_timestamp) <= " . self::NEW_THREAD_INTERVAL . "
 limit 1";
    $bind = array(':from_user_id' => $from_user_id,
                  ':to_user_id'   => $to_user_id);
    $conn = ConnectionManager::get('default');    
    $row = $conn->execute($sql, $bind)->fetch('assoc');
    if (!empty($row)){
      return $row['thread_id'];
    }

    // start a new thread
    return $this->addThread($from_user_id, $to_user_id);    
  } // getThreadId()

  /**
   * Get messages sent to $to_user_id that hasn't been seen.
   * If $is_interval, check for the new messages in the last 10 seconds.
   * Otherwise, get all un-opened.
   * @param Integer $to_user_id
   * @return Array
   */
  public function getNewMessageCount($to_user_id){
    if (empty($to_user_id) || !is_numeric($to_user_id)){
      return array();
    }
    $sql = "
select messages.thread_id,
       messages.from_user_id,
       users.name from_user_name   
  from messages 
       inner join users on messages.from_user_id = users.id
 where messages.to_user_id = :to_user_id
   and is_opened = '0'
 group by messages.thread_id,
          messages.from_user_id,
          users.name";
    $bind = array(':to_user_id' => $to_user_id);
    $conn = ConnectionManager::get('default');    
    return $conn->execute($sql, $bind)->fetchAll('assoc');
  } // getNewMessageCount()

  /**
   * Get all the messages by thread_id
   * @param Integer $thread_id
   * @return Array
   */
  public function getMessagesByThread($thread_id){
    if (empty($thread_id)){
      return array();
    }
    $sql = "
select messages.id,
       messages.thread_id,
       messages.from_user_id,
       from_u.name from_user_name,
       messages.to_user_id,
       to_u.name to_user_name,
       messages.message,
       messages.created_date,
       messages.is_opened
  from messages
       inner join users from_u on messages.from_user_id = from_u.id
       inner join users to_u on messages.to_user_id = to_u.id
 where messages.thread_id = :thread_id
 order by messages.created_date";
    $bind = array(':thread_id' => $thread_id);
    $conn = ConnectionManager::get('default');    
    return $conn->execute($sql, $bind)->fetchAll('assoc');
  } // getMessagesByThread()

  /**
   * Get all the threads sent or received by this user.
   * @param Integer $user_id
   * @param Integer $page
   * @return Array
   */
  public function getThreadsByUser($user_id, $page=1){
    if (empty($user_id) || !is_numeric($user_id)){
      return array();
    }
    $sql = "
select messages.id,
       messages.thread_id,
       messages.from_user_id,
       from_u.name from_user_name,
       messages.to_user_id,
       to_u.name to_user_name,
       messages.message,
       messages.created_date,
       messages.is_opened,
       threads.has_unopened,
       threads.last_created_date
  from (
  select thread_id,
         sum(
           case 
             when to_user_id = :user_id and is_opened = '0' then 1
             else 0
         ) has_unopened,
         max(created_date) last_created_date,
         max(id) last_message_id
    from messages
   where from_user_id = :user_id
      or to_user_id = :user_id
   group by thread_id
) as threads 
       inner join messages on threads.last_message_id = messages.id
       inner join users from_u on messages.from_user_id = from_u.id
       inner join users to_u on messages.to_user_id = to_u.id
 where messages.from_user_id = :user_id
    or messages.to_user_id = :user_id
 order by threads.has_unopened desc,
          threads.last_created_date desc,
          threads.thread_id asc";
    $bind = array(':user_id' => $user_id);
    $conn = ConnectionManager::get('default');    
    return $conn->execute($sql, $bind)->fetchAll('assoc');
  } // getThreadByUser()

  /**
   * Mark the message as opened.
   * @param String $thread_id
   * @param Integer $id
   * @return Boolean
   */
  public function open($thread_id, $message_id){
    if (empty($thread_id) ||
        empty($message_id) || !is_numeric($message_id)){
      return false;
    }
    $sql = "
update messages
   set is_opened = '1'
 where id = :message_id
   and thread_id = :thread_id";
    $bind = array(':message_id' => $message_id,
                  ':thread_id'  => $thread_id);
    $conn = ConnectionManager::get('default');    
    return $conn->execute($sql, $bind);
  } // open()

  /**
   * Get unopened messages sent to this user in the past 10 seconds.
   * @param Integer $user_id
   * @param Object $timezone the user's default timezone
   * @return Array Indexed by thread_id
   */
  public function getNewMessages($user_id, \DateTimeZone $timezone=null){
    if (empty($user_id) || !is_numeric($user_id)){
      return array();
    }
    $sql = "
select m.id,
       m.thread_id,
       m.from_user_id,
       u.name from_user_name,
       m.message,
       m.created_date
  from messages m
       inner join users u on m.from_user_id = u.id
 where m.to_user_id = :user_id
   and is_opened = '0'
   and timestampdiff(SECOND, m.created_date, current_timestamp) between 0 and " . self::REFRESH_INTERVAL . "
  order by m.from_user_id, m.thread_id, m.created_date";
    $bind = array(':user_id' => $user_id);
    $conn = ConnectionManager::get('default');
    $ret = array();
    if ($stm = $conn->execute($sql, $bind)){
      while ($row = $stm->fetch('assoc')){
        $thread_id = $row['thread_id'];
        if (!isset($ret[$thread_id])){
          $ret[$thread_id] = array();
        }
        // update created_date with the user's default timezone        
        $created_date = \DateTime::createFromFormat('Y-m-d H:i:s',
                                                   $row['created_date'],
                                                   new \DateTimeZone(self::DB_TIMEZONE));
        if ($timezone){
          $created_date->setTimeZone($timezone);
        }
        $row['created_date'] = $created_date->format('F d, Y h:ia');
        $ret[$thread_id] = $row;
      }
    }
    return $ret;
  } // getNewMessages()
  
} // MessagesTable {}
?>