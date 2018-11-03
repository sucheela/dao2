<div class="modal" tabindex="-1" role="dialog" id="<?php echo $modal_id ?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <?php echo isset($modal_title) ? '<h3 class="modal-title">'
        . $modal_title . '</h3>' : '' ?>
        <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div><!-- .modal-header -->
      <div class="modal-body">
        <?php echo $modal_body ?>
      </div><!-- .modal-body -->
      <?php if (isset($modal_action)){ ?>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"><?php echo $modal_action ?></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div><!-- .modal-footer -->
      <?php } ?>
    </div><!-- .modal-content -->
  </div><!-- .modal-dialog -->
</div><!-- .modal -->
