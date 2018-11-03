<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;

class ImagesController extends AppController {

  public function beforeFilter(Event $event)
  {
    parent::beforeFilter($event);
    $this->Security->setConfig('unlockedActions', ['add', 'capture']);
  }
  
  
  public function index(){
    $images = TableRegistry::get('user_images')
            ->find()
            ->where([
              'user_id' => $this->Auth->user('id'),
              'is_hidden' => '0'
            ])
            ->order([
              'is_default' => 'asc',
              'sort_order' => 'asc',
              'created_date' => 'desc'
            ])
            ->toArray();
    $this->set('images', $images);
  } // index()

  public function add(){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');
    
    if (isset($_FILES['newfiles'])){
      $file = $_FILES['newfiles'];
      switch ($file['error']){
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        $error = 'File too large';
        break;
      case UPLOAD_ERR_PARTIAL:
      case UPLOAD_ERR_NO_FILE:
        $error = 'File upload error.';
        break;
      case UPLOAD_ERR_NO_TMP_DIR:
      case UPLOAD_ERR_CANT_WRITE:
      case UPLOAD_ERR_EXTENSION:
        $error = 'Permission denied.';
        break;
      case UPLOAD_ERR_OK:
        try {
          // check file size
          $size = filesize($file['tmp_name']);
          if ($size > 999000){
            throw new Exception('File too large');
          }

          // check mime type
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $mime = finfo_file($finfo, $file['tmp_name']);
          switch ($mime){
          case 'image/png':
            if (!$image = @imagecreatefrompng($file['tmp_name'])){
              throw new Exception('Invalid image type');
            }
            break;
          case 'image/jpg':
          case 'image/jpeg':
            if (!$image = @imagecreatefromjpeg($file['tmp_name'])){
              throw new Exception('Invalid image type');
            }
            break;
          case 'image/gif':
            if (!$image = @imagecreatefromgif($file['tmp_name'])){
              throw new Exception('Invalid image type');
            }
            break;
          case 'image/bmp':
            if (!$image = @imagecreatefrombmp($file['tmp_name'])){
              throw new Exception('Invalid image type');
            }
            break;
          default:
            throw new Exception('Invalid image type');
          }
                
          // save to database
          $table = TableRegistry::get('user_images');
          $entity = $table->newEntity([
            'user_id' => $this->Auth->user('id'),
          ]);
          if ($result = $table->save($entity)){
            // get the last inserted id
            $image_id = $result->id;
          } else {
            throw new Exception('Database error.');
          }

          $filename = base64_encode(md5($image_id));
          $filepath = WWW_ROOT . 'img/profiles/' . $filename;
          // save to file system
          if (imagepng($image, $filepath) &&
              file_exists($filepath)){
            $this->set('id', base64_encode(Security::encrypt($image_id,
                                                             ENCRYPT_KEY)));
            $this->set('filename', $filename);
          } else {
            $table->delete($entity);
            throw new Exception('Permission error.');
          }

        } catch (Exception $e){
          $error = $e->getMessage();
        }        
      }
    } // end if there's file upload    
    if (isset($error)){
      $this->set('error', $error);
    }
  } // add()

  public function capture(){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    try {
      if (empty($_POST['captured_image'])){
        throw new Exception('Captured image not found.');
      }
      
      $data = $_POST['captured_image'];
      $pos  = strpos($data, 'data:image/png;base64,');
      if ($pos !== 0){
        throw new Exception('Image capture error.');
      }
      // get rid of data:image/png;base64,
      $data = substr($data, strpos($data, ','));
      $length = strlen($data);
      if ($length == 0 || $length > 999000){
        throw new Exception('Captured image too large.');
      }
      $image = @imagecreatefromstring(base64_decode($data));
      if ($image === false){
        throw new Exception('Invalid captured image data.');
      }

      // save to database
      $table = TableRegistry::get('user_images');
      $entity = $table->newEntity([
        'user_id' => $this->Auth->user('id')
      ]);
      if ($result = $table->save($entity)){
        $image_id = $result->id;
      } else {
        throw new Exception('Database error.');
      }

      $filename = base64_encode(md5($image_id));
      $filepath = WWW_ROOT . 'img/profiles/' . $filename;
      // save to file system
      if (imagepng($image, $filepath) &&
          file_exists($filepath)){
        $this->set('id', base64_encode(Security::encrypt($image_id,
                                                         ENCRYPT_KEY)));
        $this->set('filename', $filename);
      } else {
        $table->delete($entity);
        throw new Exception('Permission error.');
      }
    } catch (Exception $e){
      $this->set('error', $e->getMessage());
    }
  } // capture()

  public function delete(){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    try {
      if ($img_id = filter_input(INPUT_GET, 'img_id')){
        $id = Security::decrypt(base64_decode($img_id),
                                ENCRYPT_KEY);
        if (empty($id) || !is_numeric($id)){
          // image_id is bad
          throw new Exception('401');
        }        
      } else {
        // invalid paramter
        throw new Exception('401');
      }
      
      // check if the image belongs to this user
      $table = TableRegistry::get('user_images');
      $image_exists = $table
                    ->find()
                    ->where([
                      'id' => $id,
                      'user_id' => $this->Auth->user('id')
                    ])
                    ->count();

      if ($image_exists == 0){
        // image not found
        throw new Exception('404');
      }

      if ($table->deleteAll([ 'id' => $id ])){
        // remove the physical file
        $file = WWW_ROOT . 'img/profiles/' . base64_encode(md5($id));
        @unlink($file);
        $this->set('feedback', '200');
      } else {
        throw new Exception('500');
      }
      
    } catch (Exception $e){
      $this->set('error', $e->getMessage());
    }

    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');
    $this->render('default');
  } // delete()

  public function setDefault(){
    
    try {
      if ($img_id = filter_input(INPUT_GET, 'img_id')){
        $id = Security::decrypt(base64_decode($img_id),
                                ENCRYPT_KEY);
        if (empty($id) || !is_numeric($id)){
          // image_id is bad
          throw new Exception('401');
        }
      } else {
        // invalid paramter
        throw new Exception('401');
      }

      // check if the aimage belongs to this user
      $table = TableRegistry::get('user_images');
      $image_exists = $table
                    ->find()
                    ->where([
                      'id' => $id,
                      'user_id' => $this->Auth->user('id')
                    ])
                    ->count();

      if ($image_exists == 0){
        // image not found
        throw new Exception('404');
      }
      
      if ($table->setDefault($id, $this->Auth->user('id'))){
        $this->set('feedback', '200');
      } else {
        throw new Exception('500');
      }

    } catch (Exception $e){
      $this->set('error', $e->getMessage());
    }

    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');
    $this->render('default');
  } // setDefault()
  
} // ImagesController {}
?>