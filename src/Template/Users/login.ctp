<?php
$this->assign('title', 'Login');
$this->assign('css', $this->Html->css('login.css'));
?>
<div class="users">
    <h1>Login</h1>
    <?= $this->Flash->render() ?>
    <div class="form">
      <?= $this->Form->create() ?>
      <div class="form-group">
        <?php echo $this->Form->control('email', [
          'type' => 'email',
          'label' => 'Email Address',
          'class' => 'form-control',
          'placeholder' => 'Email Address'
        ]); ?>
      </div>
      <div class="form-group">
        <?php echo $this->Form->control('password', [
          'type' => 'password',
          'label' => 'Password',
          'class' => 'form-control',
          'placeholder' => 'Password'
        ]); ?>
      </div>
      <div class="form-group">
        <input type="submit" class="form-control btn btn-primary" value="Login"/>
      </div>
      <div class="form-group">
        <a href="/users/resetpassword" class="form-control btn btn-default">Forgot Password</a>
      </div>
      <?= $this->Form->end() ?>
      <h3 style="text-align: center">
        New to Dao.Dating? 
        <a href="/users/register">Register Here</a>
      </h3>
      <p style="text-align: center">
        Already registered, but need
        another <a href="/user/confirmregistration">registration code</a>?
      </p>
    </div><!-- .form -->
    <div class="social">
      <div class="form-group">
        <a href="#" class="form-control btn btn-primary">Log in with Facebook</a>
      </div>
      <div class="form-group">
        <a href="#" class="form-control btn btn-primary">Sign in with Google</a>
      </div>
    </div>
</div><!-- .users -->
