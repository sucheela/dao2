<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CountriesTable extends Table {
  public function initialize(array $config){
    $this->setPrimaryKey('country_code');
    $this->setDisplayField('name');
  } // initialize()
}
?>