<?php
$this->assign('title', 'My Messages');
$this->assign('css', $this->Html->css('messages.css'));
$this->assign('script', $this->Html->script(['bootstrap-notify.min.js', 'load-more.js', 'profile.js', 'messages.js']));
?>
<div class="threads lazy-load">
  <h1>My Messages</h1>

  <?php
foreach ($threads as $t){
  echo $this->element('thread', $t);
}
  ?>
  <div class="ajax-loader" style="display: none;"></div>
</div><!-- .threads -->
