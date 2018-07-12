  <div class="action" data-user_id="<?php echo $encrypted_id; ?>" data-user_name="<?php echo h($user_name) ?>">
    <?php if (!$in_profile_view){ ?>
    <a href="<?php echo $view_url; ?>" class="view" data-toggle="tooltip"
       title="View profile."><span class="fa fa-smile-o"></span></a>
    <?php } // end if not in User Profile Page ?>
    <?php if (empty($in_user_blocks)){ // if not on My Black List page ?>
      <?php if ($is_favorite){ ?>
    <a href="/favorites/delete" class="not-favorite" data-toggle="tooltip"
    title="Remove from my favorites."><span class="fa fa-heart-o"></span></a>
      <?php } else { // else if not my favorite ?>
    <a href="/favorites/add" class="favorite" data-toggle="tooltip" title="Save to my favorites."><span class="fa fa-heart"></span></a>
      <?php } ?>
    <a href="#" class="message" data-toggle="tooltip" title="Send a message."><span class="fa fa-envelope"></span></a>
    <a href="/blocks/add" class="block" data-toggle="tooltip" title="Do not show me this profile again."><span class="fa fa-ban"></span></a>
    <?php } else { // else if on My Black List page ?>
    <a href="/blocks/delete" class="not-block" data-toggle="tooltip" title="Remove from My Black List"><span class="fa fa-circle-o"></span></a>
    <?php } // end else if on My Black List Page ?>
  </div><!-- .action -->
