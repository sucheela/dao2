<?php
$this->assign('title', 'My Matches');
$this->assign('css', $this->Html->css('matches.css'));
$this->assign('script', $this->Html->script([
  'bootstrap-notify.min.js',
  'load-more.js',
  'profile.js']));
?>
<div class="matches lazy-load">
  <h1>My Matches</h1>
  <h4>Here are your matches based on your birth date and time.</h4>
  
  <ul>
  <?php
foreach ($matches as $m){
  echo $this->element('matchbox', $m);
}
  ?>
  </ul>
  <div class="ajax-loader" style="display: none;"></div>
</div>
