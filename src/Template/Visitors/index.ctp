<?php
$this->assign('title', 'Recent Visitors');
$this->assign('css', $this->Html->css('matches.css'));
$this->assign('script', $this->Html->script([
  'bootstrap-notify.min.js',
  'load-more.js',
  'profile.js']));
?>
<div class="matches visitors">
  <h1>Recent Visitors</h1>
  <h4>Here are users who have viewed your profile</h4>
  
  <ul>
  <?php
foreach ($matches as $m){
  echo $this->element('matchbox', $m);
}
  ?>
  </ul>
  <div class="ajax-loader" style="display: none;"></div>
</div>
