<?php
$this->Form->unlockField('g-recaptcha-response');
$this->assign('title', 'Register');
$this->assign('css', $this->Html->css('login.css'));
$this->assign('script', '<script src="https://www.google.com/recaptcha/api.js"></script>');

$hour_options = $this->Dao->getHourOptions();
?>
<div class="register">
  <h1>Get Started</h1>
  <?php echo $this->Flash->render(); ?>

  <?php if (empty($register_success)) { ?>
  <?php echo $this->Form->create($user); ?>
  <div class="form-group">
    <?php echo $this->Form->control('email', [
      'type' => 'email',
      'label' => 'Email Address',
      'class' => 'form-control',
      'placeholder' => 'Email Address'
    ]); ?>
  </div>
  <div class="form-group">
    <?php echo $this->Form->control('name', [
      'type' => 'text',
      'label' => 'Name',
      'class' => 'form-control',
      'placeholder' => 'Name'
    ]); ?>
    <div class="hel-block">Name that will be displayed to your matches.</div>
  </div>
  <div class="form-group">
    <?php echo $this->Form->control('hour_num', [
      'type' => 'select',
      'label' => 'Birth Hour',
      'class' => 'form-control',
      'options' => $hour_options
    ]); ?>
  </div>
  <div class="form-group">
    <label for="birth-date">Birth Date</label>
    <div>
    <?php echo $this->Form->Date('birth', [
      'minYear' => 1901,
      'maxYear' => date('Y') - 17, 
      'monthName' => true,
    
      'empty' => [
        'year' => 'Year',
        'month' => 'Month',
        'day'   => 'Day'
      ],
      'year' => [ 'class' => 'form-control year' ],
      'month' => [ 'class' => 'form-control month' ],
      'day' => [ 'class' => 'form-control day' ],
    ]); ?>
    </div>
    <?php echo $this->Form->isFieldError('birth_date') ?
    $this->Form->error('birth_date', 'Required.') : ''; ?>
    <div class="help-block">Must be over 18 to register.</div>
  </div>
  <div class="form-group">
    <?php echo $this->Form->control('country_code', [
      'type' => 'select',
      'label' => 'Country',
      'class' => 'form-control',
      'options' => $country_options,
    'default' => 'US'
    ]); ?>
  </div>
  <div class="form-group">
    <?php echo $this->Form->control('zipcode', [
      'type' => 'text',
      'label' => 'Postal Code',
      'class' => 'form-control'
    ]); ?>
  </div>
  <div class="form-group">
    <label for="gender">I am a</label>
    <div class="radio">
    <?php echo $this->Form->radio('gender',
      [
        ['value' => 'Female', 'text' => 'Woman'],
        ['value' => 'Male',   'text' => 'Man'],
        ['value' => 'Other',  'text' => 'Transgender']
      ]
    ); ?>
    <?php echo $this->Form->isFieldError('gender') ?
      $this->Form->error('gender', 'Required.') : '';  ?>
    </div>
  </div>
  <div class="form-group">
    <label for="into_genders">Looking for a</label>
    <div class="checkbox">
      <label>
    <?php echo $this->Form->checkbox('into_female', [
      'value' => 'Female'
    ]); ?> Woman    
      </label>
      <label>
    <?php echo $this->Form->checkbox('into_male', [
      'value' => 'Male'
    ]); ?> Man    
      </label>
      <label>
    <?php echo $this->Form->checkbox('into_other', [
      'value' => 'Other'
    ]); ?> Transgender
      </label>
    </div>
    <?php echo $this->Form->isFieldError('user__into__genders') ?
    $this->Form->error('user__into__genders', 'Required.') : '' ?>
  </div>
  <div class="g-recaptcha"
     data-sitekey="6LfIHhoTAAAAAHQMWdmEcA5zntaxDSKNualeEEv9"></div>
  <div class="form-group">
    <input type="submit" class="form-control btn btn-primary" value="Register"/>
  </div>
  <?php echo $this->Form->end() ?>
  <?php } // end if there's no $register_success  ?>
</div><!-- .register -->
