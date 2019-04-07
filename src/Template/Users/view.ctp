<?php
$this->assign('title', 'View Profile');
$this->assign('css', $this->Html->css('profile.css'));
$this->assign('script', $this->Html->script(['bootstrap-notify.min.js', 'profile.js']));

// figure out into genders
$into_genders = '';
foreach ($user->user__into__genders as $into_gender){
  $into_genders .= ($into_genders ? ' or ' : '');
  switch ($into_gender->gender){
  case 'Male':
    $into_genders .= ' a man';
    break;
  case 'Female':
    $into_genders .= ' a woman';
    break;
  case 'Other':
    $into_genders .= ' a transgender';
    break;
  }
}

// age
$age = $this->Dao->getAge($user->birth_date->i18nFormat('yyyy-MM-dd'));
// stars
$stars = $this->Dao->scoreStars($score, $user->hour_branch_id);
// id
$encrypted_id = base64_encode(Cake\Utility\Security::encrypt($user->id, ENCRYPT_KEY));

// branch info
$yb = $this->Dao->getBranchName($user->year_branch_id);
$mb = $this->Dao->getBranchName($user->month_branch_id);
$db = $this->Dao->getBranchName($user->day_branch_id);
$hb = $this->Dao->getBranchName($user->hour_branch_id);

// images
if (empty($images)){
  $hero = '<img src="/img/branches/' . strtolower($yb) . '" class="img-responsive"/>';
} else {
  $img = $images[0];
  $hero = '<img src="/img/profiles/' . base64_encode(md5($img['id'])) . '" class="img-responsive"/>';
}

$action_data = array('encrypted_id'    => $encrypted_id,
                     'user_name'       => $user->name,
                     'in_profile_view' => true,
                     'in_user_block'   => false,
                     'view_url'        => null,
                     'is_favorite'     => $is_favorite,
                     'is_blocked'      => $is_blocked);
?>
<div class="profile">
  <h1 class="name"><?php echo h($user->name) ?></h1>
  <div class="row">
    <div class="col-md-6 profile-images">
      <div class="hero-image">
        <?php echo $hero ?>
      </div><!-- .hero-image -->
      <div class="more-images">
        <?php foreach ($images as $img){ ?>
        <a href="#">
          <img src="/img/profiles/<?php echo base64_encode(md5($img['id'])); ?>"
               class="img-responsive"/>
        </a>
        <?php } ?>
      </div><!-- .more-images -->

      <div class="score">
        <div class="stars" data-toggle="tooltip" title="<?php echo $this->Dao->scoreString($score, $user->hour_branch_id); ?>"><?php echo str_repeat('<span class="fa
      fa-star"></span>', $stars) . str_repeat('<span class="fa
      fa-star-o"></span>', 5-$stars) ?></div>     
      </div><!-- .score -->
      
      <?php echo $this->element('actionbox', $action_data); ?>

    </div><!-- .profile-images -->

    <div class="col-md-6 profile-info">
      <ul <?php echo $stars == 0 ? 'class="no-match"' : '' ?>>
        <li>Gender: <?php echo $user->gender ?></li>
        <li>Age: <?php echo $age ?></li>
        <li>Location: <?php echo h($user->address) ?></li>
        <li>Looking for
          <ul>
            <li><?php echo $into_genders ?></li>
            <li>between <?php echo $user->match_min_age ?>
              and <?php echo $user->match_max_age ?> years old</li>
            <li>within <?php echo ($user->distance == 9999 ? 'any distance' :
              ($user->distance . ' miles')) ?></li>
          </ul>
        </li>
      </ul>
      <div class="user-intro">
        <?php echo nl2br(h($user->introduction)) ?>
      </div><!-- .user-intro -->

    </div><!-- .profile-info -->

  </div><!-- .row -->

  <h1><?php echo $user->name ?>'s Earthly Branches</h1>
  <div class="row branch-detail year">
    <div class="col-sm-2">
      <h4><?php echo $yb ?> Year</h4>
      <img src="/img/branches/<?php echo
      strtolower($yb) ?>" class="img-responsive" title="<?php echo $yb
       ?>" data-toggle="tooltip"/>
    </div>
    <div class="col-sm-5">
      <h4>Year of the <?php echo $yb ?></h4>
      <p><?php echo $branches[$user->year_branch_id] ?></p>
    </div>
    <div class="col-sm-5">
      <h4><?php echo $yb ?> with my <?php echo $this->Dao->getBranchName($me->year_branch_id) ?></h4>
      <p><?php echo $year_text ?></p>
    </div>
  </div><!-- .row -->

  <div class="row branch-detail month">
    <div class="col-sm-2">
      <h4><?php echo $mb ?> Month</h4>
      <img src="/img/branches/<?php echo
      strtolower($mb) ?>" class="img-responsive" title="<?php echo $mb
       ?>" data-toggle="tooltip"/>
    </div>
    <div class="col-sm-5">
      <h4>Month of the <?php echo $mb ?></h4>
      <p><?php echo $branches[$user->month_branch_id] ?></p>
    </div>
    <div class="col-sm-5">
      <h4><?php echo $mb ?> with my <?php echo $this->Dao->getBranchName($me->month_branch_id) ?></h4>
      <p><?php echo $month_text ?></p>
    </div>
  </div><!-- .row -->

    <div class="row branch-detail day">
    <div class="col-sm-2">
      <h4><?php echo $db ?> Day</h4>
      <img src="/img/branches/<?php echo
      strtolower($db) ?>" class="img-responsive" title="<?php echo $db
       ?>" data-toggle="tooltip"/>
    </div>
    <div class="col-sm-5">
      <h4>Day of the <?php echo $db ?></h4>
      <p><?php echo $branches[$user->day_branch_id] ?></p>
    </div>
    <div class="col-sm-5">
      <h4><?php echo $db ?> with my <?php echo $this->Dao->getBranchName($me->day_branch_id) ?></h4>
      <p><?php echo $day_text ?></p>
    </div>
  </div><!-- .row -->

    <div class="row branch-detail hour">
    <div class="col-sm-2">
      <h4><?php echo $hb ?> Hour</h4>
      <img src="/img/branches/<?php echo
      strtolower($hb) ?>" class="img-responsive" title="<?php echo $hb
       ?>" data-toggle="tooltip"/>
    </div>
    <div class="col-sm-5">
      <h4>Hour of the <?php echo $hb ?></h4>
      <p><?php echo $branches[$user->hour_branch_id] ?></p>
    </div>
    <div class="col-sm-5">
      <h4><?php echo $hb ?> with my <?php echo $this->Dao->getBranchName($me->hour_branch_id) ?></h4>
      <p><?php echo $hour_text ?></p>
    </div>
  </div><!-- .row -->

  
</div><!-- .profile -->
