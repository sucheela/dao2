<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class UserImagesTable extends Table {

  /**
   * @param Integer $image_id
   * @param Integer $user_id
   * @return Boolean
   */
  public function setDefault($image_id, $user_id){
    if (empty($image_id) || !is_numeric($image_id) ||
        empty($user_id) || !is_numeric($user_id)){
      return false;
    }

    $sql = "
update user_images
   set is_default = '1'
 where id = :image_id
   and user_id = :user_id";
    $bind = array(':image_id' => $image_id,
                  ':user_id'  => $user_id);
    $conn = ConnectionManager::get('default');
    $ret = true;
    if ($conn->execute($sql, $bind)){
      $sql = "
update user_images
   set is_default = '0'
 where user_id = :user_id
   and id != :image_id";
      if ($conn->execute($sql, $bind)){
        $ret = true;
      } else {
        $ret = false;
      }
    } else {
      $ret = false;
    }
    return $ret;
  } // setDefault()
  
} // UserImagesTable {}
?>