<?php
$ret = array('is_online'    => ($is_online ? 1 : 0),
             'created_date' => $created_date);
print json_encode($ret);
?>