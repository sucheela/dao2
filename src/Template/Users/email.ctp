<?php
$this->assign('title', 'Email Address Change');
$this->assign('css', $this->Html->css('myprofile.css'));
?>
<h1>Email Address Change</h1>

<div class="row">
  <div class="col-md-2 profile-nav">
    <?php echo $this->element('myprofilenav') ?>
  </div>
  <div class="col-md-offset-2 col-md-4">      
  <?php echo $this->Flash->render() ?>

  <?php if (!isset($is_valid_token)){ ?>
    <?php echo $this->Form->create() ?>
    <div class="form-group">
      <?php echo $this->Form->control('email', [
      'type' => 'email',
      'label' => 'New Email Address',
      'class' => 'form-control',
      'placeholder' => 'New Email Address'
      ]);       
      ?>
    </div>
    <div class="form-group">
      <input type="submit" class="form-control btn btn-primary" value="Change Email Address"/>
    </div>
    <?php echo $this->Form->end() ?>
  <?php } // end if ! isset($is_valid_token) ?>
  </div><!-- .col-md-10 -->
</div><!-- .row -->
