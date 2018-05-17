<?php
$this->Form->unlockField('status');
$this->assign('title', 'Update Profile Status');
$this->assign('css', $this->Html->css('myprofile.css'));
$this->assign('script', $this->Html->script('status.js?v=1'));
?>
<h1>Update Profile Status</h1>

<div class="row">
  <div class="col-md-2 profile-nav">
    <?php echo $this->element('myprofilenav'); ?>
  </div>
  <div class="col-md-offset-2 col-md-4" id="status-form">      
    <?php echo $this->Flash->render(); ?>

   <?php echo $this->Form->create(); ?>
   <div class="form-group">
     <input type="button" value="Activate"
            class="form-control btn btn-primary" <?php echo $status == 'Active'
            ? 'disabled' : '' ?> data-toggle="modal" data-target="#activate-modal"/>
     <div class="help-block"><?php echo $status == 'Active' ? 'Your current
     profile status is active.' : '' ?></div>
   </div>

   <div class="form-group">
     <input type="button" value="Deactivate"
            class="form-control btn btn-warning" <?php echo $status ==
            'Inactive' ? 'disabled' : '' ?> data-toggle="modal" data-target="#deactivate-modal"/>
     <div class="help-block"><?php echo $status == 'Inactive' ? 'Your current
                                   profile is deactivated.' : '' ?></div>
     <div class="help-block">Deactivated profile will not appear in any
       match. Other users will not be able to send you a message.
       However, all the information will be retained. The profile can be
       reactivated at the later time.</div>
   </div>

   <div class="form-group">
     <input type="button" value="Deleted"
            class="form-control btn btn-danger" <?php echo $status == 'Deleted' ?
            'disabled' : '' ?> data-toggle="modal" data-target="#delete-modal"/>
     <div class="help-block">Deleted profile is permanently removed. The action
     is not reversible. Once the request is completed, you 
     won't be able to access the site to this account again unless you
     re-register.</div> 
   </div>

     <?php echo $this->Form->control('status', [ 'type' => 'hidden', 'value' => 'Active' ]); ?>
   <?php echo $this->Form->end(); ?>
  </div><!-- .col-md-10 -->
</div><!-- .row -->

<?php echo $this->element('modal', [
'modal_id'    => 'activate-modal',
'modal_title' => 'Confirm Activation',
'modal_body'  => '<p>Are you sure you want to activate your profile?</p>',
'modal_action'=> 'Yay, Activate!'
]); ?>

<?php echo $this->element('modal', [
'modal_id'    => 'deactivate-modal',
'modal_title' => 'Confirm Deactivation',
'modal_body'  => '<p>Are you sure you want to deactivate your profile?</p>',
'modal_action'=> 'Yes, Deactivate!'
]); ?>

<?php echo $this->element('modal', [
'modal_id'    => 'delete-modal',
'modal_title' => 'Confirm Deletion',
'modal_body'  => '<p>Are you sure you want to delete your profile?</p>
        <p>This action is not reversible. Once the request is complete, you
        will be logged out of the site and will not be able to access your
        profile again.</p>',
'modal_action'=> 'Yes, Delete! Bye!'
]); ?>
