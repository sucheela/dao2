<?php
$this->assign('title', 'My Photos');
$this->assign('css', $this->Html->css(['myprofile.css', 'images.css']));
$this->assign('script', $this->Html->script(['bootstrap-notify.min.js',
                                             'load-image.all.min.js',
                                             'jquery.ui.widget.js',
                                             'jquery.fileupload.js',
                                             'jquery.fileupload-process.js',
                                             'jquery.fileupload-validate.js',
                                             'jquery.fileupload-image.js',
                                             'images.js']));
?>
<div class="profile">
  <h1>My Photos</h1>
  <?php echo $this->Flash->render(); ?>

  <div class="row">
    <div class="col-md-2 profile-nav">
      <?php echo $this->element('myprofilenav') ?>
    </div><!-- profile-nav -->
    <div class="col-md-10 images">
  <ul>
    <li class="add-image">
      <div class="add-img">
        <div class="photo add-photo fileinput-image">
          <span class="fa fa-plus"></span>
          <input type="file" class="fileupload new-files" name="newfiles" multiple  accept="image/*" capture>          
        </div>
      </div>
      <div class="action">
        <span title="Upload New Photo" class="file fileinput-button" data-toggle="tooltip">
          <span class="fa fa-image"></span>
          <input type="file" class="fileupload new-files" name="newfiles" multiple>
        </span>
        <a href="#" title="Take a Picture" data-toggle="tooltip" class="camera">
          <span class="fa fa-camera"></span>
        </a>
      </div><!-- .action -->.
    </li>
  <?php
  foreach ($images as $img){
    $data = array('id' => $img->id,
                  'is_default' => $img->is_default);
    echo $this->element('imageupdate', $data);
  }
  ?>
  </ul>
    </div><!-- .col-md-10.images -->
  </div><!-- .row -->
  
</div><!-- .profile -->      

<?php
// print an empty li to use as template
$data = array('id' => null,
              'is_default' => 0);
echo '<div id="template-photo" style="display: none;">'
  . $this->element('imageupdate', $data)
  . '</div>';
      
echo $this->element('modal',
                      array('modal_id' => 'photo-modal',
                            'modal_body' => '<img src=""/>'));
echo $this->element('modal',
                      array('modal_id' => 'delete-photo-modal',
                            'modal_title' => 'Confirm Delete',
                            'modal_body' => '<p>Are you sure you want to delete the selected photo?</p>
<img src="" data-img_id="" style="width: 50%; margin: auto; display: block;"/>',
                            'modal_action' => 'Delete!'));

// modal for video capture
echo $this->element('modal',
                    array('modal_id'    => 'video-modal',
                          'modal_title' => 'Take a Picture',
                          'modal_body'  => '
<video autoplay id="captured-video" style="width: 100%;"></video>
<img src="" id="captured-img" style="display:none;" class="img-responsive"/>
<canvas style="display: none" id="captured-canvas"></canvas>
<p class="text-center">
  <button class="btn btn-primary" id="captured-btn">Take a Picture</button>
</p>
<p class="text-center" id="captured-confirm" style="display: none;">
  <button class="btn btn-primary" id="captured-confirm-btn">Save the Picture</button>
  <button class="btn btn-secondary" id="captured-cancel-btn">Cancel</button>
</p>'));
?>
