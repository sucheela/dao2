<?php
$this->assign('title', 'My Messages');
$this->assign('css', $this->Html->css('messages.css'));
$this->assign('script', $this->Html->script(['bootstrap-notify.min.js', 'messages.js']))
?>
