<?php
$age = $this->Dao->getAge($birth_date);
$stars = $this->Dao->scoreStars($total_score, $has_hour);
$encrypted_id = base64_encode(Cake\Utility\Security::encrypt($id, ENCRYPT_KEY));
$view_url = '/users/view/' . $id .'/' . urlencode(h($name));
if (isset($last_visited_date)){
  // Recent Visitors page has this var
  $last_visited = new DateTime($last_visited_date);
  $now = new DateTime();
  $interval = $last_visited->diff($now);
  $last_visited_mins = $interval->format('%i');
  $last_visited_hours = $interval->format('%h');
  $last_visited_days = $interval->format('%d');
  $last_visited_months = $interval->format('%m');
  $last_visited_string = '';

  switch (true){
  case $last_visited_months > 0:
    $last_visited_string = $last_visited_months . " momths ago";
    break;
  case $last_visited_days > 0:
    $last_visited_string = $last_visited_days . " days ago";
    break;
  case $last_visited_hours > 0:
    $last_visited_string = $last_visited_hours . " hours ago";
    break;
  case $last_visited_mins > 0:
    $last_visited_string = $last_visited_mins . " minutes ago";
    break;
  }
} // end if isset($last_visited_date)
?>
<li>
  <a href="<?php echo $view_url; ?>">
    <?php if ($file_id){ ?>
    <img src="/images/view/<?php echo $file_id; ?>" class="image-responsive"/>
    <?php } else { ?>
    <img src="/img/branches/<?php echo
    strtolower($this->Dao->getBranchName($month_branch_id)) ?>" class="image-responsive"/>
    <?php } ?>
  </a>
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

  <div class="action" data-user_id="<?php echo $encrypted_id; ?>">
    <a href="<?php echo $view_url; ?>" class="view" data-toggle="tooltip"
       title="View profile."><span class="fa fa-smile-o"></span></a>
    <?php if (empty($in_user_blocks)){ // if not on My Black List page ?>
      <?php if (in_array($id, $favorites)){ ?>
    <a href="/favorites/delete" class="not-favorite" data-toggle="tooltip"
    title="Remove from my favorites."><span class="fa fa-heart-o"></span></a>
      <?php } else { ?>
    <a href="/favorites/add" class="favorite" data-toggle="tooltip" title="Save to my favorites."><span class="fa fa-heart"></span></a>
      <?php } ?>
    <a href="/messages/send" class="message" data-toggle="tooltip" title="Send a message."><span class="fa fa-envelope"></span></a>
    <a href="/blocks/add" class="block" data-toggle="tooltip" title="Do not show me this profile again."><span class="fa fa-ban"></span></a>
    <?php } else { // else if on My Black List page ?>
    <a href="/blocks/delete" class="not-block" data-toggle="tooltip" title="Remove from My Black List"><span class="fa fa-circle-o"></span></a>
    <?php } // end else if on My Black List Page ?>
  </div><!-- .action -->
</li>
