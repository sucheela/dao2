<?php
$age = $this->Dao->getAge($birth_date);
$stars = $this->Dao->scoreStars($total_score, $has_hour);
$encrypted_id = base64_encode(Cake\Utility\Security::encrypt($id, ENCRYPT_KEY));
$view_url = '/users/view/' . urlencode(h($name)) . '?u=' . $encrypted_id;
if (isset($last_visited_date)){
  // Recent Visitors page has this var
  $last_visited = new DateTime($last_visited_date);
  $now = new DateTime();
  $last_visited_string = $this->Dao->relativeTime($last_visited, $now);
} // end if isset($last_visited_date)
$is_favorite = (isset($favorites) &&
                is_array($favorites) &&
                in_array($id, $favorites)
                ? true : false);

$action_data = array('encrypted_id'    => $encrypted_id,
                     'user_name'       => $name,
                     'in_profile_view' => (isset($in_profile_view)
                                           ? $in_profile_view : false),
                     'in_user_block'   => (isset($in_user_block)
                                           ? $in_user_block : false),
                     'view_url'        => $view_url,
                     'is_favorite'     => $is_favorite);
?>
<li>
  <div class="photo">
    <a href="<?php echo $view_url; ?>">
      <?php if ($file_id){ ?>
      <img src="/img/profiles/<?php echo base64_encode(md5($file_id)); ?>" class="img-responsive"/>
      <?php } else { ?>
      <img src="/img/branches/<?php echo
      strtolower($this->Dao->getBranchName($month_branch_id)) ?>" class="img-responsive"/>
      <?php } ?>
    </a>
  </div><!-- .photo -->
  <div class="title">
    <span class="name"><?php echo h($name); ?></span>,
    <span class="age"><?php echo $age; ?></span>
  </div>
  <div class="location"><?php echo ($address ? h($address) :
       $countries[h($country_code)]); ?></div>
  <?php if (isset($last_visited_string)){ ?>
  <div class="last-visited">
    Visited <?php echo $last_visited_string ?>
  </div>
  <?php } ?>
  <div class="score">
    <div class="stars" data-toggle="tooltip" title="<?php echo $this->Dao->scoreString($total_score, $has_hour); ?>"><?php echo str_repeat('<span class="fa
      fa-star"></span>', $stars) . str_repeat('<span class="fa
      fa-star-o"></span>', 5-$stars) ?></div>     
  </div>

  <?php echo $this->element('actionbox', $action_data); ?>
</li>
