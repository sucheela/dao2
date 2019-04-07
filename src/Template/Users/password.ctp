<?php
$this->assign('title', 'Change Password');
$this->assign('css', $this->Html->css('myprofile.css'));
?>
<h1>Email Address Change</h1>

<div class="row">
  <div class="col-md-2 profile-nav">
    <?php echo $this->element('myprofilenav') ?>
  </div>
  <div class="col-md-offset-2 col-md-4">      
  <?php echo $this->Flash->render() ?>
  
  <?php echo $this->element('passwordform') ?>
  </div><!-- .col-md-10 -->
</div><!-- .row -->
