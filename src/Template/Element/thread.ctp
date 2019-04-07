<?php
if ($image_id){
  $img_src = '/img/thumbs/' . base64_encode(md5($image_id));  
} else {
  $img_src = "/img/branches/" . strtolower($this->Dao->getBranchName($month_branch_id));
}
$last_msg_date = new DateTime($last_created_date);
$now = new DateTime();
$last_date_string = $this->Dao->relativeTime($last_msg_date, $now);
$encrypted_id = base64_encode(Cake\Utility\Security::encrypt($user_id, ENCRYPT_KEY));
$view_url = '/users/view/' . urlencode(h($user_name)) . '?u=' . $encrypted_id;
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
<div class="thread row  <?php echo $has_unopened ? 'unread' : ''; ?>">
  <div class="col-sm-3">
    <a href="<?php echo $view_url; ?>">
      <div class="thumb">
        <img class="img-responsive" src="<?php echo $img_src; ?>"/>
      </div>
    </a>
    <?php echo $this->element('actionbox', $action_data); ?>
  </div>
  <div class="col-sm-9">
    <div class="row info">
      <div class="col-sm-9 who"><?php echo h($user_name); ?></div>
      <div class="col-sm-3 when"><?php echo $last_date_string; ?></div>
    </div><!-- .info -->
    <a href="/messages/view/<?php echo $thread_id ?>">
      <div class="preview <?php echo $msg_is_yours ? 'yours' : 'mine' ?>">
        <?php echo h($message); ?>
      </div>
    </a>
    <?php if ($has_unopened){ ?>
    <div class="pull-right hidden-xs">
      <a class="btn btn-default btn-sm do-open" href="#"
         data-thread_id="<?php echo $thread_id ?>">
        Mark as Read
      </a>
    </div>
    <?php } // end if has_unopened ?>
  </div>
</div><!-- .thread -->
