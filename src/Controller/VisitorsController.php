<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security;

class VisitorsController extends AppController {

  public function index(){
    $matches = TableRegistry::get('user_visitors')
             ->getVisitors($this->Auth->user('id'), 1);
    $this->set('matches', $matches);

    $favs = TableRegistry::get('user_favorites');
    $result = $favs
            ->find()
            ->select('fav_user_id')
            ->where(['user_id' => $this->Auth->user('id')])
            ->all();
    $favorites = $result->extract('fav_user_id')->toArray();
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

    $matches = TableRegistry::get('user_visitors')
             ->getVisitors($this->Auth->user('id'), $page);
    $this->set('matches', $matches);

    $favs = TableRegistry::get('user_favorites');
    $result = $favs
            ->find()
            ->select('fav_user_id')
            ->where(['user_id' => $this->Auth->user('id')])
            ->all();
    $favorites = $result->extract('fav_user_id')->toArray();
    $this->set('favorites', $favorites);

    $countries = TableRegistry::get('countries')
               ->find('list', [
                 'keyField' => 'country_code',
                 'valueField' => 'name'
               ])
               ->toArray();
    $this->set('countries', $countries);
  } // more()

} // VisitorsController

?>