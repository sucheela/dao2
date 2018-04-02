<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;

class FavoritesController extends AppController {

  public function index(){
    $matches = TableRegistry::get('user_favorites')
             ->getFavorites($this->Auth->user('id'), 1);
    $favorites = array();
    foreach ($matches as $row){
      $favorites[] = $row['id'];
    }
    $this->set('matches', $matches);
    $this->set('favorites', $favorites);

    $countries = TableRegistry::get('countries')
               ->find('list', [
                 'keyField' => 'country_code',
                 'valueField' => 'name'
               ])
               ->toArray();
    $this->set('countries', $countries);
  } // index()

  public function more($page=2){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    $matches = TableRegistry::get('user_favorites')
             ->getFavorites($this->Auth->user('id'), $page);
    $favorites = array();
    foreach ($matches as $row){
      $favorites[] = $row['id'];
    }
    $this->set('matches', $matches);
    $this->set('favorites', $favorites);    

    $countries = TableRegistry::get('countries')
               ->find('list', [
                 'keyField' => 'country_code',
                 'valueField' => 'name'
               ])
               ->toArray();
    $this->set('countries', $countries);
  } // more()

  public function add(){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    try {
      if (empty($_GET['fav_user_id'])){
        throw new Exception('401');
      }
            
      $fav_user_id = Security::decrypt(base64_decode($_GET['fav_user_id']),
                                       ENCRYPT_KEY);
      if (empty($fav_user_id) || !is_numeric($fav_user_id)){
        throw new Exception('404');
      }

      if ($fav_user_id == $this->Auth->user('id')){
        throw new Exception('403');
      }

      $table = TableRegistry::get('user_favorites');

      // check if this pair doesn't already exist
      $fav_exists = $table
                  ->find()
                  ->where([
                    'user_id' => $this->Auth->user('id'),
                    'fav_user_id' => $fav_user_id
                  ])
                  ->count();
      if ($fav_exists > 0){
        throw new Exception('409');
      }

      $fav = $table->newEntity([
        'user_id' => $this->Auth->user('id'),
        'fav_user_id' => $fav_user_id
      ]);
      if ($table->save($fav)){
        $this->set('feedback', '200');
      } else {
        throw new Exception('500');
      }
      
    } catch (Exception $e){
      $this->set('error', $e->getMessage());
    }
  } // add()

  public function delete(){
    // use ajax layout
    $this->viewBuilder()->setLayout('ajax');

    try {
      if (empty($_GET['fav_user_id'])){
        throw new Exception('401');
      }
            
      $fav_user_id = Security::decrypt(base64_decode($_GET['fav_user_id']),
                                       ENCRYPT_KEY);
      if (empty($fav_user_id) || !is_numeric($fav_user_id)){
        throw new Exception('404');
      }

      $table = TableRegistry::get('user_favorites');

      if ($table->deleteAll([
        'user_id' => $this->Auth->user('id'),
        'fav_user_id' => $fav_user_id
      ])){
        $this->set('feedback', '200');
      } else {
        throw new Exception('500');
      }
      
    } catch (Exception $e){
      $this->set('error', $e->getMessage());
    }
    
  } // delete()
} // FavoritesController {}
?>