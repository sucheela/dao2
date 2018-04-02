<?php
namespace App\View\Helper;

use Cake\View\Helper;
use App\Lib\ChineseCalendar;

class DaoHelper extends Helper {

  public function scoreString($score, $hasHour=true){
    if ($hasHour){
      switch ($score) {
      case 24:
        return 'Barely Acceptable';
      case 25:
        return 'Acceptable';
      case 26:
        return 'Compatible';
      case 27:
        return 'Very Compatible';
      case 28:
        return 'Most Compatible';
      default:
        return 'Not a Match';
      }
    } else {
      switch ($score) {
      case 18:
        return 'Barely Acceptable (Excluding Hour)';
      case 19:
        return 'Acceptable (Excluding Hour)';
      case 20:
        return 'Compatible (Excluding Hour)';
      case 21:
        return 'Very Compatible (Excluding Hour)';
      case 22:
        return 'Most Compatible (Excluding Hour)';
      default:
        return 'Not a Match (Excluding Hour)';
      }
    }
  } // scoreString()

  public function scoreStars($score, $hasHour){
    if ($hasHour){
      switch ($score) {
      case 24:
        return 1;
      case 25:
        return 2;
      case 26:
        return 3;
      case 27:
        return 4;
      case 28:
        return 5;
      default:
        return 0;
      }
    } else {
      switch ($score) {
      case 18:
        return 1;
      case 19:
        return 2;
      case 20:
        return 3;
      case 21:
        return 4;
      case 22:
        return 5;
      default:
        return 0;
      }
    }
  }

  public function getBranchName($branch_id){
    return ChineseCalendar::getBranchName($branch_id);
  } // getBranchName()

  public function getHourOptions(){
    return array(
      -1  => 'Hour',
      0   => 'Midnight - 1am',
      2   => '1 - 3am',
      4   => '3 - 5am',
      6   => '5 - 7am',
      8   => '7 - 9am',
      10  => '9 - 11am',
      12  => '11am - 1pm',
      14  => '1 - 3pm',
      16  => '3 - 5pm',
      18  => '5 - 7pm',
      20  => '7 - 9pm',
      22  => '9 - 11pm',
      24  => '11pm - Midnight'
    );
  } // getHourOptions();

  public function getAge($birth_date){
    $birth = date_create($birth_date);
    $now = date_create('now');
    $interval = $birth->diff($now);
    $age = $interval->format('%y');
    return $age;
  } // getAge()

  public function getDistanceOptions(){
    return array(
      '5'    => 'Within 5 miles',
      '20'   => 'Within 20 miles',
      '50'   => 'Within 50 miles',
      '100'  => 'Within 100 miles',
      '200'  => 'Within 200 miles',
      '9999' => 'Anywhere'
    );

  } // getDistanceOptions()

  public function getAgeRangeOptions(){
    $ret = array();
    for ($i=18; $i<100; $i++){
      $ret[$i] = $i;
    }
    return $ret;
  } // getAgeRangeOptions()

}
?>