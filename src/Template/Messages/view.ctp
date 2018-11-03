<?php
$this->assign('title', 'Messages from ');
$this->assign('css', $this->Html->css('messages.css'));
$this->assign('script', $this->Html->script(['bootstrap-notify.min.js', 'profile.js', 'messages.js']));

if ($image_id){
  $img_src = '/img/thumbs/' . base64_encode(md5($image_id));  
} else {
  $img_src = "/img/branches/" . strtolower($this->Dao->getBranchName($month_branch_id));
}
$encrypted_id = base64_encode(Cake\Utility\Security::encrypt($user_id, ENCRYPT_KEY));
$view_url = '/users/view/' . $user_id .'/' . urlencode(h($user_name));
$is_favorite = (isset($favorites) &&
                is_array($favorites) &&
                in_array($user_id, $favorites)
                ? true : false);

$action_data = array('encrypted_id'    => $encrypted_id,
                     'user_name'       => $user_name,
                     'in_profile_view' => false,
                     'in_user_block'   => false,
                     'view_url'        => $view_url,
                     'is_favorite'     => $is_favorite);

?>
<h1>My Messages</h1>
<div class="threads">
<div class="thread row">
  <div class="col-sm-3" style="text-align: center;">
    <a href="<?php echo $view_url ?>">
      <div class="thumb">
        <img class="img-responsive" src="<?php echo $img_src; ?>"/>
      </div>
    </a>
    <div class="who"><?php echo h($user_name); ?></div>
    <?php echo $this->element('actionbox', $action_data); ?>
  </div>
  <div class="col-sm-9 messages">
    <?php if ($prev_thread_id){ ?>
    <p style="text-align: right;">
      <a href="#" data-thread_id="<?php echo $prev_thread_id; ?>" class="btn
      btn-sm btn-default prev-thread">Load Previous Thread...</a>
    </p>
    <?php } // end if there's previous thread ?>
    <?php
       foreach ($messages as $m){
         $date = \DateTime::createFromFormat('Y-m-d H:i:s', $m['created_date']);
         if (isset($timezone)){
           $date->setTimeZone($timezone);
         }
    ?>
    <div class="preview <?php echo ($m['from_user_id'] == $user_id ? 'yours' :
                'mine'); ?>">
      <p><?php echo h($m['message']); ?></p>
      <div class="when"><?php echo $date->format('F d, Y H:ia'); ?></div>
    </div>
    <?php } // end foreach $messages ?>
  </div><!-- .messages -->
  
</div>
</div>
