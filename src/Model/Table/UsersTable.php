<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use ArrayObject;
use Cake\I18n\FrozenDate;

class UsersTable extends Table {

  const LIMIT = 8;
  
  public function initialize(array $config){
    $this
      ->hasMany('User_Into_Genders')
      ->setSaveStrategy('replace');
    $this
      ->belongsTo('Countries')
      ->setForeignKey('country_code');
  } // initialize()

  public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options){
    if (isset($data['email'])){
      // lowercase email
      $data['email'] = strtolower(trim($data['email']));
    }
    // trim username
    $data['name'] = trim($data['name']);
    // calculate birth_date
    $data['birth_date'] = FrozenDate::parseDate(
      $data['birth']['year'] . '-' .
      $data['birth']['month'] . '-' .
      $data['birth']['day'], 'yyyy-MM-dd');
    // into_genders field
    $data['user__into__genders'] = array();
    if ($data['into_female']){
      $data['user__into__genders'][] = array('user_id' => $options['id'],
                                             'gender'  => 'Female');
    }
    if ($data['into_male']){
      $data['user__into__genders'][] = array('user_id' => $options['id'],
                                             'gender'  => 'Male');
    }
    if ($data['into_other']){
      $data['user__into__genders'][] = array('user_id' => $options['id'],
                                             'gender'  => 'Other');
    }    
  } // beforeMarshal()
  
  public function validationDefault(Validator $validator){
    $validator
      ->notEmpty('email', 'Required.')
      ->email('email');
    $validator
      ->notEmpty('name', 'Required.')
      ->lengthBetween('name', [4, 50], 'Must be between 4-50 characters.');
    $validator
      ->notEmpty('birth_date', 'Required.')
      ->date('birth_date', 'Invalid date.');
    $validator
      ->add('birth_date', 'mustbe18', [
        'rule' => function($value, $context) {
          if ($value->wasWithinLast('18 years')){
            return false;
          }
          return true;
        },
        'message' => 'Must be over 18'
      ]);
    $validator
      ->range('hour_num', [-1, 24], 'Invalid hour.');
    $validator
      ->notEmpty('country_code', 'Required.');
    $validator
      ->notEmpty('zipcode', 'Required.');
    $validator
      ->notEmpty('gender', 'Required.')
      ->inList('gender', ['Female', 'Male', 'Other'], 'Invalid gender.');
    $validator
      ->notEmpty('user__into__genders', 'Required');
    $validator
      ->integer('match_min_age', 'Invalid number.')
      ->range('match_min_age', [18, null], 'Invalid age range.');
    $validator
      ->integer('match_max_age', 'Invalid number.');
    $validator
      ->add('match_max_age', 'matchAgeRange', [
        'rule' => function($value, $context){
          if ($value >= $context['data']['match_min_age']){
            return true;
          }
          return false;
        },
        'message' => 'Invalid age range.'
      ]);
    $validator
      ->integer('distance', 'Invalid number.')
      ->range('distance', [0, 9999], 'Invalid distance range.');
      
    return $validator;
  } // validationDefault()

  public function buildRules(RulesChecker $rules){
    $rules->add($rules->isUnique(['email']));
    $rules->add($rules->isUnique(['name']));
    return $rules;
  } // buildRules()

  public function findAuth(Query $query, array $options){
    $query
      ->select(['id', 'email', 'password'])
      ->where(['status' => 'Active']);
    return $query;
  } // findAuth()
  
  /**
   * @param Integer $user_idx
   * @return String|NULL return a new hash
   */
  public function resetPass($user_id){
    if (empty($user_id) || !is_numeric($user_id)){
      return null;
    }

    $this->deactivateReset($user_id);
    
    $conn = ConnectionManager::get('default');
    // insert a new row
    $pass = $this->_getPass();
    $sql = "
insert
  into user_password_resets
       (user_id, password)
values (:user_id, :password)";
    $bind = array(':user_id' => $user_id,
                  ':password' => $pass);
    if ($conn->execute($sql, $bind)){
      return $pass;
    }
    return null;
  } // resetPass()

  /**
   * @param Integer $useR_id
   * @return Boolean
   */
  public function deactivateReset($user_id){
    if (empty($user_id) || !is_numeric($user_id)){
      return false;
    }
    $conn = ConnectionManager::get('default');
    // set all the rows for this email inactive
    $sql = "
update user_password_resets
   set is_active = '0'
 where is_active = '1'
   and user_id = :user_id";
    $bind = array(':user_id' => $user_id);
    return $conn->execute($sql, $bind);    
  } // deactivateReset()

  /**
   * @param String $hash
   * @return Integer|NULL
   */
  public function getResetUserId($hash){
    if (strlen($hash) == 0){
      return null;
    }
    
    $conn = ConnectionManager::get('default');
    $sql = "
select user_id
  from user_password_resets
 where password = :hash
   and is_active = '1'
   and unix_timestamp(created_date) > unix_timestamp()-(60*60*24)";
    $bind = array(':hash' => $hash);
    $row = $conn->execute($sql, $bind)->fetch('assoc');
    if (!empty($row)){
      return $row['user_id'];
    }
    return null;
  } // getResetUserId()
  
  private function _getPass(){
    $str = '';
    for ($i=0; $i<8; $i++){
      $str .= chr(rand(33, 126));
    }
    return (new DefaultPasswordHasher)->hash($str);
  } // _getPass()

  /**
   * @param Integer $user_idx
   * @return String|NULL return a new hash
   */
  public function changeEmail($user_id, $email){
    if (empty($user_id) || !is_numeric($user_id) ||
        empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
      return null;
    }
    // deactivate other requests from the same user
    $this->deactivateEmailChange($user_id);
      
    // insert a new row
    $conn = ConnectionManager::get('default');
    $pass = $this->_getPass();
    $sql = "
insert
  into user_email_changes
       (user_id, email, password)
values (:user_id, :email, :password)";
    $bind = array(':user_id' => $user_id,
                  ':email'   => $email,
                  ':password'=> $pass);
    if ($conn->execute($sql, $bind)){
      return $pass;
    }
    return null;
  } // changeEmail()
  
  /**
   * @param Integer $user_id
   * @return Boolean
   */
  public function deactivateEmailChange($user_id){
    if (empty($user_id) || !is_numeric($user_id)){
      return false;
    }
    $conn = ConnectionManager::get('default');
    // set all the rows for this user inactive
    $sql = "
update user_email_changes
   set is_active = '0'
 where is_active = '1'
   and user_id = :user_id";
    $bind = array(':user_id' => $user_id);
    return $conn->execute($sql, $bind);
  } // deactivateEmailChange()

  /**
   * @param String $hash
   * @return Array
   */
  public function getEmailChangeInfo($hash){
    if (strlen($hash) == 0){
      return array();
    }

    $conn = ConnectionManager::get('default');
    $sql = "
select user_id, email
  from user_email_changes
 where password = :hash
   and is_active = '1'
   and unix_timestamp(created_date) > unix_timestamp()-(60*60*24)";
    $bind = array(':hash' => $hash);
    return $conn->execute($sql, $bind)->fetch('assoc');
  } // getEmailChangeInfo()

  /**
   * @param Integer $user_id
   * @return String|NULL return a new hash.
   */
  public function requestDelete($user_id){
    if (empty($user_id) || !is_numeric($user_id)){
      return null;
    }
    // deactivate other delete requests from the same user
    $this->deactivateDelete($user_id);

    // insert a new row
    $conn = ConnectionManager::get('default');
    $pass = $this->_getPass();
    $sql = '
insert
  into user_deletes (user_id, password)
values (:user_id, :has)';
    $bind = array(':user_id' => $user_id,
                  ':hash'    => $hash);
    if ($conn->execute($sql, $bind)){
      return $pass;
    }
    return null;
  } // requestDelete()

  /**
   * @param Integer $user_id
   * @return Boolean
   */
  public function deactivateDelete($user_id){
    if (empty($user_id) || !is_numeric($user_id)){
      return false;
    }
    $conn = ConnectionManager::get('default');
    $sql = "
update user_deletes
   set is_active = '0'
 where is_active = '1'
   and user_id = :user_id";
    $bind = array(':user_id' => $user_id);
    return $conn->execute($sql, $bind);
  } // deactivateDelete()

  /**
   * @param String $hash
   * @return Integer|NULL
   */
  public function getDeleteUserId($hash){
    if (strlen($hash) == 0){
      return null;
    }
    $conn = ConnectionManager::get('default');
    $sql = "
select user_id
  from user_deletes
 where password = :hash
   and is_active = '1'
   and unix_timestamp(created_date) > unix_timestamp()-(60*60*24)";
    $bind = array(':hash' => $hash);
    if ($row =  $conn->execute($sql, $bind)->fetch('assoc')){
      return $row['user_id'];
    }
    return null;
  } // getDeleteUserId()

  /**
   * @param Integer $user_id
   * @return Boolean
   */
  public function activate($user_id){
    if (empty($user_id) || !is_numeric($user_id)){
      return false;
    }
    $conn = ConnectionManager::get('default');
    $sql = "
update users
   set status = 'Active',
       deactivated_date = null,
       deleted_date = null
 where id = :user_id
   and status = 'Inactive'";
    $bind = array(':user_id' => $user_id);
    return $conn->execute($sql, $bind);
  } // activate()

  /**
   * @param Integer $user_id
   * @return Boolean
   */
  public function deactivate($user_id){
    if (empty($user_id) || !is_numeric($user_id)){
      return false;
    }
    $conn = ConnectionManager::get('default');
    $sql = "
update users
   set status = 'Inactive',
       deactivated_date = current_timestamp,
       deleted_date = null
 where id = :user_id
   and status = 'Active'";
    $bind = array(':user_id' => $user_id);
    return $conn->execute($sql, $bind);
  } // deactivate()

  /**
   * @param Integer $user_id
   * @return Boolean
   */
  public function delete($user_id){
    if (empty($user_id) || !is_numeric($user_id)){
      return false;
    }
    $conn = ConnectionManager::get('default');
    $sql = "
update users
   set status = 'Deleted',
       deleted_date = current_timestamp,
       email = concat(email, '-deactivated-', id)
 where id = :user_id
   and status in ('Active', 'Inactive')";
    $bind = array(':user_id' => $user_id);
    return $conn->execute($sql, $bind);
  } // delete()
  
  /**
   * Get matches and their scoring for the $user_id
   * @param Integer $user_id
   * @param Integer $page_num
   * @return Array
   */
  public function getMatches($user_id, $page_num=1){
    if (empty($user_id) || !is_numeric($user_id)){
      return array();
    }

    $conn = ConnectionManager::get('default');
    $sql = "
select u.id,
 u.name,
 u.birth_date,
 u.zipcode,
 u.country_code,
 u.address,
 r.id file_id,
 r.file_name,
 u.month_branch_id,
 -- year score
 case yb.score
   -- if oppisite sign, incompatible when I'm younger and Yang
   when 0 then if (u.birth_date < me.birth_date, 0, 2+8)
   else yb.score+8
 end as year_score,
  -- month score
 case mb.score
   when 0 then if (u.birth_date < me.birth_date, 0, 2+6)
   else mb.score+6
 end as month_score,
  -- hour score
 case hb.score
   when 0 then if (u.birth_date < me.birth_date, 0, 2+4)
   else if (hb.score is null, 0, hb.score+4)
  end as hour_score,
  -- day score
 case db.score
   when 0 then if (u.birth_date < me.birth_date, 0, 2+2)
   else db.score+2
  end as day_score,
  case 
    when me.hour_branch_id is null then 0
    else 1
  end has_hour
from users u
inner join users me on u.id != me.id and me.id = :user_id
-- the matches are in the gender that I'm interested
inner join user_into_genders ug on u.id = ug.user_id
  and ug.gender = me.gender
-- I'm in the gender that my matches are interested
inner join user_into_genders mg on u.gender = mg.gender
  and mg.user_id = me.id
-- branch month rules
inner join branch_rules mb on u.month_branch_id = mb.match_branch_id
  and mb.my_branch_id = me.month_branch_id
-- branch year rules
inner join branch_rules yb on u.year_branch_id = yb.match_branch_id
  and yb.my_branch_id = me.year_branch_id
-- branch hour rules
left outer join branch_rules hb on u.hour_branch_id = hb.match_branch_id
  and me.hour_branch_id = hb.my_branch_id
-- branch day rules
inner join branch_rules db on u.day_branch_id = db.match_branch_id
  and db.my_branch_id = me.day_branch_id
-- get images
left outer join user_images r on u.id = r.user_id and r.is_default = '1'
where 
-- I'm in the age that interests the matches
year(curdate())-year(me.birth_date) between u.match_min_age and u.match_max_age
-- the matches are in the age that interests me
and year(curdate()) - year(u.birth_date) between me.match_min_age and me.match_max_age 
-- I'm not blocked by the matches
and not exists (
  select 'x'
    from user_blocks
   where u.id = user_blocks.user_id
     and me.id = user_blocks.blocked_user_id
)
-- my matches are not blocked by me
and not exists (
  select 'x'
    from user_blocks
   where me.id = user_blocks.user_id
     and u.id = user_blocks.blocked_user_id)
-- the matches are not too far
and (me.distance = 9999 
 or ( 3959 * acos( cos( radians(me.latitude) ) * cos( radians(u.latitude) ) 
   * cos( radians(u.longitude) - radians(me.longitude)) + sin(radians(me.latitude)) 
   * sin( radians(u.latitude)))) <= me.distance)
-- the matches are still active
and u.status = 'Active'";

    $sql = "select mtch.*,
 mtch.month_score + mtch.year_score + mtch.hour_score + mtch.day_score as total_score
from ($sql) as mtch
where ((mtch.month_score + mtch.year_score + mtch.hour_score + mtch.day_score) >= 24 and has_hour = 1) 
   or ((mtch.month_score + mtch.year_score + mtch.hour_score + mtch.day_score) >= 18 and has_hour = 0)
order by total_score desc";

    // displaying 12 matches at a time
    $skip = 0;
    if (is_numeric($page_num) && $page_num > 1){
      $skip = ($page_num-1) * self::LIMIT;
    }
    $sql .= "
limit " . self::LIMIT . " offset $skip";
    
    $bind = array(':user_id' => $user_id);
    $ret = $conn->execute($sql, $bind)->fetchAll('assoc');
    return $ret;
    
  } // getMatches
  

  /**
   * Get match score and match description between $user_id and $match_user_id.
   * @param $user_id
   * @param $match_user_id
   * @return Array
   */
  public function getMatchScore($user_id, $match_user_id){
    if (empty($user_id) || !is_numeric($user_id) ||
        empty($match_user_id) || !is_numeric($match_user_id)){
      return array();
    }

    $conn = ConnectionManager::get('default');
    $sql = "
select u.id,
 -- year score
 case yb.score
   -- if oppisite sign, incompatible when I'm younger and Yang
   when 0 then if (u.birth_date < me.birth_date, 0, 2+8)
   else yb.score+8
 end as year_score,
 yb.description year_text,
  -- month score
 case mb.score
   when 0 then if (u.birth_date < me.birth_date, 0, 2+6)
   else mb.score+6
 end as month_score,
 mb.description month_text,
  -- hour score
 case hb.score
   when 0 then if (u.birth_date < me.birth_date, 0, 2+4)
   else if (hb.score is null, 0, hb.score+4)
  end as hour_score,
 hb.description hour_text,
  -- day score
 case db.score
   when 0 then if (u.birth_date < me.birth_date, 0, 2+2)
   else db.score+2
  end as day_score,
  db.description day_text,
  case 
    when me.hour_branch_id is null then 0
    else 1
  end has_hour
from users u
inner join users me on me.id = :user_id
-- branch month rules
inner join branch_rules mb on u.month_branch_id = mb.match_branch_id
  and mb.my_branch_id = me.month_branch_id
-- branch year rules
inner join branch_rules yb on u.year_branch_id = yb.match_branch_id
  and yb.my_branch_id = me.year_branch_id
-- branch hour rules
left outer join branch_rules hb on u.hour_branch_id = hb.match_branch_id
  and me.hour_branch_id = hb.my_branch_id
-- branch day rules
inner join branch_rules db on u.day_branch_id = db.match_branch_id
  and db.my_branch_id = me.day_branch_id
where 
u.id = :match_user_id
and u.status = 'Active'";

    $sql = "select mtch.*,
 mtch.month_score + mtch.year_score + mtch.hour_score + mtch.day_score as total_score
from ($sql) as mtch";

    $bind = array(':user_id' => $user_id,
                  ':match_user_id' => $match_user_id);
    $ret = $conn->execute($sql, $bind)->fetch('assoc');
    return $ret;
  } // getMatchScore()
}
?>
