<?php
$this->assign('title', 'Change Password');
$this->assign('css', $this->Html->css('login.css'));
?>
<div class="resetform">
  <h1>Change Password</h1>
  <?php echo $this->Flash->render(); ?>

  <?php if ($is_valid_token){ ?>
    <?php echo $this->element('passwordform') ?>
  <?php } // end if is valid token ?>

  <?php if ($this->request->here(false) != '/users/password'){ ?>
  <p>
    <a href="/users/resetpassword" class="form-control btn btn-default">Request a New Token</a>
  </p>
  <?php } // end if not password page from myprofile ?>
</div><!-- .resetform -->
