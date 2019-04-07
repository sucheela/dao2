  <?php echo $this->Form->create(); ?>
  <div class="form-group">
    <?php echo $this->Form->control('new_password', [
      'type' => 'password',
      'label' => 'New Password',
      'class' => 'form-control',
      'placeholder' => 'New Password'
    ]); ?>
    <span class="help-block">Password must be at least 7 characters and must
      include at lease one number and at least one special character.</span>    
  </div>
  <div class="form-group">
    <?php echo $this->Form->control('conf_password', [
      'type' => 'password',
      'label' => 'Confirm Password',
      'class' => 'form-control',
      'placeholder' => 'Confirm Password'
    ]); ?>
  </div>
  <div class="form-group">
    <input type="submit" class="form-control btn btn-primary" value="Change Password"/>
  </div>
  <?php echo $this->Form->end(); ?>
