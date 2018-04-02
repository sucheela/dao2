<?php
$this->Form->unlockField('g-recaptcha-response');
if (isset($reset_type) && $reset_type == 'registration'){
  $title = 'Confirm Registration';
  $token = 'a new registration code';
  $action = 'Send New Registration Code';
} else {
  $title = 'Forgot Password';
  $token = 'a password reset';
  $action = 'Reset Password';
}
$this->assign('title', $title);
$this->assign('css', $this->Html->css('login.css'));
$this->assign('script', '<script src="https://www.google.com/recaptcha/api.js"></script>');
?>
<div class="resetform">
  <h1><?php echo $title ?></h1>
  <?php echo $this->Flash->render() ?>

  <?php if (empty($reset_success) || $reset_success != 1){ ?>
  <?php echo $this->Form->create() ?>
  <div class="form-group">
    <?php echo $this->Form->control('email', [
                  'type'  => 'email',
                  'label' => 'Email Address',
                  'class' => 'form-control',
                  'placeholder' => 'Email Address']); ?>
    <span class="help-block">To request <?php echo $token; ?>, please enter your
    email address, verify that you are not a robot and click <?php echo ucfirst($action); ?>
      button below.</span>    
  </div>
  <div class="g-recaptcha"
     data-sitekey="6LfIHhoTAAAAAHQMWdmEcA5zntaxDSKNualeEEv9"></div>
  <?php echo (isset($reset_type) && $reset_type == 'registration')
        ? $this->Form->control('reset_type', [
                  'type'  => 'hidden',
                  'value' => 'registration'])
        : ''; ?>
  <div class="form-group">
    <input type="submit" class="form-control btn btn-primary" value="<?php echo $action ?>"/>
  </div>
  <?php echo $this->Form->end() ?>
  <?php } ?>
</div><!-- .users -->
