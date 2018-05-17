<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class UserVisitorsTable extends Table {

  const LIMIT = 8;
  
  /**
   * Get visitors and their scoring for the $user_id
   * @param Integer $user_id
   * @param Integer $page_num
   * @return Array
   */
  public function getVisitors($user_id, $page_num=1){
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
  end has_hour,
  vit.last_visited_date
from user_visitors vit
inner join users u on vit.visitor_user_id = u.id
inner join users me on vit.user_id = me.id
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
-- my matches are not blocked by me
not exists (
  select 'x'
    from user_blocks
   where me.id = user_blocks.user_id
     and u.id = user_blocks.blocked_user_id)
-- the matches are still active
and u.status = 'Active'
and vit.user_id = :user_id
-- don't count me visiting myself
and vit.visitor_user_id != :user_id
";

    $sql = "select mtch.*,
 mtch.month_score + mtch.year_score + mtch.hour_score + mtch.day_score as total_score
from ($sql) as mtch
order by last_visited_date desc";

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
    
  } // getVisitors()
} // UserVisitorsTable {}
?>