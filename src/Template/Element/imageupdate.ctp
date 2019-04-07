<?php
if (empty($id)){
  $img_id = null;
  $encrypted_id = null;
  $src = null;
} else {
  $img_id = base64_encode(md5($id));
  $encrypted_id = base64_encode(Cake\Utility\Security::encrypt($id, ENCRYPT_KEY));
  $src = '/img/profiles/' . $img_id;
}
?>
    <li class="current-image">
      <a href="#" class="current-img">        
        <div class="photo">
          <img class="img-responsive" src="<?php echo $src ?>"/>
        </div>
      </a>
      <div class="action" data-id="<?php echo $encrypted_id ?>">
        <!--
        <span title="Replace Photo" class="fileinput-button file" data-toggle="tooltip">
          <span class="fa fa-image"></span>
          <input class="fileupload" type="file" name="file" accept="image/*" capture>
        </span>
        <a href="#" class="camera" title="Take a Picture" data-toggle="tooltip">
          <span class="fa fa-camera"></span>
        </a>
        -->
        <?php if ($is_default){ ?>
        <a href="#" class="rm-default" title="Your Current Profile Picture" data-toggle="tooltip">
          <span class="fa fa-check-square-o"></span>
        </a>
        <?php } else { ?>
        <a href="#" class="make-default" title="Make Profile Picture" data-toggle="tooltip">
          <span class="fa fa-square-o"></span>
        </a>
        <?php } ?>
        <a href="#" class="remove" title="Delete" data-toggle="tooltip">
          <span class="fa fa-times-circle-o"></span>
        </a>
      </div><!-- .action -->
    </li>
