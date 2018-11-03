<?php
$ret = array();
if (isset($id) && isset($filename)){
  $ret['id'] = $id;
  $ret['filename'] = $filename;
} else {
  if (isset($error)){
    $ret['error'] = $error;
  } else {
    $ret['error'] = 'Unknown error';
  }
}

echo json_encode($ret);
?>