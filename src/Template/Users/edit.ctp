<?php
$this->assign('title', 'My Profile');
$this->assign('css', $this->Html->css('myprofile.css'));

$hour_options = $this->Dao->getHourOptions();
$age = $this->Dao->getAge($user->birth_date->i18nFormat('yyyy-MM-dd'));

// images
$yb = $this->Dao->getBranchName($user->year_branch_id);
if (empty($images)){
  $hero = '<img src="/img/branches/' . strtolower($yb) . '" class="image-responsive"/>';
} else {
  $img = array_shift($images);
  $hero = '<img src="/images/view/' . $img['file_id'] . '" class="image-responsive"/>';
}
?>
<div class="profile">
  <h1>My Profile</h1>  
  <?php echo $this->Flash->render(); ?>
  
  <div class="row">
    <div class="col-md-2 profile-nav">
      <?php echo $this->element('myprofilenav') ?>
    </div><!-- profile-nav -->
    <div class="col-md-5 profile-images hidden-sm hidden-xs">
      <div class="hero-image">
        <?php echo $hero ?>
      </div><!-- .hero-image -->
      <div class="more-images">
        <?php foreach ($images as $img){ ?>
        <img src="/images/thumb/<?php echo $img['file_id'] ?>" class="image-responsive"/>
        <?php } ?>
      </div><!-- .more-images -->
    </div><!-- .profile-images -->

    <div class="col-md-5">
      <div class="profile-info">
      <?php echo $this->Form->create($user, ['type' => 'post']); ?>
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
          'value' => [
          'year' => $user->birth_date->i18nFormat('yyyy'),
          'month' => $user->birth_date->i18nFormat('MM'),
          'day' => $user->birth_date->i18nFormat('dd')
          ],
          'year' => [ 'class' => 'form-control year' ],
          'month' => [ 'class' => 'form-control month' ],
          'day' => [ 'class' => 'form-control day' ],
          ]); ?>
        </div>
        <div class="help-block">Must be over 18.</div>
        <?php echo $this->Form->isFieldError('birth_date') ?
        $this->Form->error('birth_date') : ''; ?>
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
          $this->Form->error('gender') : '';  ?>
        </div>
      </div>
      <div class="form-group">
        <label for="into_genders">Looking for a</label>
        <div class="checkbox">
          <label>
            <?php echo $this->Form->checkbox('into_female', [
            'value' => 'Female',
            'checked' => in_array('Female', $user_into_genders)
            ]); ?> Woman    
          </label>
          <label>
            <?php echo $this->Form->checkbox('into_male', [
            'value' => 'Male',
            'checked' => in_array('Male', $user_into_genders)
            ]); ?> Man    
          </label>
          <label>
            <?php echo $this->Form->checkbox('into_other', [
            'value' => 'Other',
            'checked' => in_array('Other', $user_into_genders)
            ]); ?> Transgender
          </label>
        </div>
        <?php echo $this->Form->isFieldError('user__into__genders') ?
        $this->Form->error('user__into__genders') : '' ?>
      </div>
      <div class="form-group">
        <label for="match_age">Within Age Range</label>
        <div>
          <span>Between</span>
          <?php $this->Form->control('match_min_age'); ?>
          <input class="form-control form-age-range" type="number" step="1"
          min="18" name="match_min_age" value="<?php echo $user->match_min_age ?>">
          <span>and</span>
          <?php $this->Form->control('match_max_age'); ?>
          <input class="form-control form-age-range" type="number" step="1"
          min="18" name="match_max_age" value="<?php echo $user->match_max_age ?>">
        </div>
        <?php echo $this->Form->isFieldError('match_min_age') ?
        $this->Form->error('match_min_age') : '' ?>
        <?php echo $this->Form->isFieldError('match_max_age') ?
        $this->Form->error('match_max_age') : '' ?>
      </div>
      <div class="form-group">
        <?php echo $this->Form->control('distance', [
        'type' => 'select',
        'label' => 'Within Distance',
        'class' => 'form-control',
        'options' => $this->Dao->getDistanceOptions()
        ]); ?>
      </div>
      <div class="form-group">
        <?php echo $this->Form->control('introduction', [
        'type' => 'textarea',
        'label' => 'Introduction',
        'class' => 'form-control'
        ]); ?>
      </div>
      <div class="form-group">
        <input type="submit" class="form-control btn btn-primary" value="Update My Profile"/>
      </div>
      <?php echo $this->Form->end(); ?>
      </div><!-- .profile-info -->      
    </div><!-- .col-md-5 -->
  </div><!-- .row -->
  
</div><!-- .profile -->
