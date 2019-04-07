<?php
$this->assign('title', 'Delete Profile');
$this->assign('script', $this->Html->script('delete.js?v=1'));
?>
<h1>Delete Profile</h1>
<div class="center-text">
  <?php echo $this->Flash->render() ?>
  <?php if ($is_deleted){ ?>
  <p>You will be logged out in 10 seconds. <a href="/users/logout">Or you can
  logout now.</a></p>
  <p>Once logged out, you will not be able to access
  the dao.dating with this account again. You can, however, re-register from our
  registration page.</p>
  <p>Bye! Good luck!</p>
  <?php } ?>
</div>
