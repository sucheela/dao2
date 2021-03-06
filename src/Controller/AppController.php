<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

  /**
   * Initialization hook method.
   *
   * Use this method to add common initialization code like loading components.
   *
   * e.g. `$this->loadComponent('Security');`
   *
   * @return void
   */
  public function initialize()
  {
    parent::initialize();

    $this->loadComponent('RequestHandler');
    $this->loadComponent('Flash');
    $this->loadComponent('Auth', [
      'loginRedirect' => [
        'controller' => 'Matches',
        'action' => 'index'
      ],
      'logoutRedirect' => [
        'controller' => 'Pages',
        'action' => 'display',
        'home'
      ],
      'unauthorizedRedirect' => [
        'controller' => 'Pages',
        'action' => 'display',
        'home'
      ]
    ]);

    /*
     * Enable the following components for recommended CakePHP security settings.
     * see https://book.cakephp.org/3.0/en/controllers/components/security.html
     */
    $this->loadComponent('Security');
    $this->loadComponent('Csrf');

    if ($user = $this->Auth->user()){
      $this->set('is_loggedin', true);
      $this->set('msg_num', 0);

      // record click
      $clickTab = TableRegistry::get('user_clicks');
      $click = $clickTab->newEntity();
      $click->user_id = $this->Auth->user('id');
      $click->uri = $_SERVER['REQUEST_URI'];
      $click->query_string = $_SERVER['QUERY_STRING'];
      $click->referer = isset($_SERVER['HTTP_REFERER'])
                      ? $_SERVER['HTTP_REFERER'] : null;
      $clickTab->save($click);

      // get the number of unopened emails
      $msgTab = TableRegistry::get('messages');
      $msg_num = $msgTab->getNewMessageCount($this->Auth->user('id'));
      $this->set('msg_num', $msg_num);
    } else {
      $this->set('is_loggedin', false);
    }
  } // initialize()

  public function beforeFilter(Event $event)
  {
    $this->Auth->allow(['display']);    
  }
  
}
