<?php
$this->assign('title', 'My Favorites');
$this->assign('css', $this->Html->css('matches.css'));
$this->assign('script', $this->Html->script([
  'bootstrap-notify.min.js',
  'load-more.js',
  'profile.js?v=5']));
?>
<div class="matches favorites">
  <h1>My Favorites</h1>
  <h4>Here are your favorite profiles</h4>
  
  <ul>
  <?php
foreach ($matches as $m){
  echo $this->element('matchbox', $m);
}
  ?>
  </ul>
  <div class="ajax-loader" style="display: none;"></div>
</div>
