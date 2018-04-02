<?php
$ret = array();
if (isset($error)){
  $ret = array('type' => 'danger',
               'msg'  => $error);
} else {
  if (isset($feedback)){
    $ret = array('type' => 'info',
                 'msg'  => $feedback);
  }
}

print json_encode($ret);
?>