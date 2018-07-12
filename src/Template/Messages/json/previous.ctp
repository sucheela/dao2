<?php
if (empty($messages)){
  header('HTTP/1.0 404 Not Found');
  exit;
}

$ret = array('prev_thread_id' => $prev_thread_id,
             'messages'       => array());
foreach ($messages as $msg){
  $date = \DateTime::createFromFormat('Y-m-d H:i:s', $msg['created_date']);
  if (isset($timezone)){
    $date->setTimeZone($timezone);
  }
  // newest message first
  $tmp = array('whose' => ($msg['from_user_id'] == $user_id
                           ? 'yours' : 'mine'),
               'when'  => $date->format('F d, Y H:ia'),
               'what'  => $msg['message']);
  array_unshift($ret['messages'], $tmp);
}

print json_encode($ret);
?>