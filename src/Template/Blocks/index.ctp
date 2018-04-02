<?php
$this->assign('title', 'My Black List');
$this->assign('css', $this->Html->css('matches.css'));
$this->assign('script', $this->Html->script([
  'bootstrap-notify.min.js',
  'load-more.js',
  'profile.js?v=5']));
?>
<div class="matches blocks">
  <h1>My Black List</h1>
  <h4>Here are profiles that you do not want to see</h4>
  
  <ul>
  <?php
foreach ($matches as $m){
  echo $this->element('matchbox', $m);
}
  ?>
  </ul>
  <div class="ajax-loader" style="display: none;"></div>
</div>
