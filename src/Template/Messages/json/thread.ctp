<?php
$ret = array();
if (isset($error)){
  $ret = array('error' => $error);
} else {
  if (isset($thread_id)){
    $ret = array('thread_id' => $thread_id);
  }
}

print json_encode($ret);
?>