$(function(){

  // show bigger image in modal
  $('.images').on('click', ' a.current-img', function(e){
    e.preventDefault();
    $('#photo-modal img').attr('src', $(this).find('img').attr('src'));
    $('#photo-modal').modal('show');
  });

  // remove camera button if browser doesn't have getUserMedia
  if (!hasGetUserMedia()){
    $('a.camera').remove();
  }

  
  $('.images')
    .on('click', 'a.make-default', function(e){
      // make default profile picture
      e.preventDefault();
      var img_id = $(this).closest('div.action').data('id');
      var anchor = this;
      var msg = '';
      $.ajax({
        url : '/images/setDefault',
        data : {
          is_default : 1,
          img_id : img_id
        },
        dataType : 'json',
        success : function(ret){
          switch (ret.msg){
          case '401':
          case '404':
            msg = 'Photo not found.';
            break;
          case '200':
            msg = 'The selected photo is now your default profile photo.';
            // update icons
            $('.action').each(function(){
              if ($(this).data('id') == img_id){
                var old_default = $(this).find('a.make-default');
                // destroy tooltip
                $(old_default).tooltip('destroy');
                $(old_default).replaceWith('<a href="#" class="rm-default" title="Your Current Profile Picture" data-toggle="tooltip"><span class="fa fa-check-square-o"></span></a>');
                $(this).find('rm-default').tooltip();
              } else {
                if ($(this).find('a.rm-default').length){
                  var old_default = $(this).find('a.rm-default');
                  // destroy tooltip
                  $(old_default).tooltip('destroy');
                  $(old_default).replaceWith('<a href="#" class="make-default" title="Make Profile Picture" data-toggle="tooltip"><span class="fa fa-square-o"></span></a>');
                  // new tooltip
                  $(this).find('a.make-default').tooltip();
                }
              }
            });
            break;
          case '500':
          default:
            msg = 'There was an error. Please try again.';
            ret.type = 'danger';
            break;
          }

          $.notify({
            message : msg,
          }, {
            type : ret.type,
            mouse_over : 'pause',
            newest_on_top : true,
            timer : 2000
          });
          
        },
        error : function(){
          $.notify({
            message : 'There was an error. Please try again.'
          }, {
            type : 'danger',
            mouse_over : 'pause',
            newest_on_top : true,
            timer : 2000
          });
        }
      });
    })
    .on('click', 'a.not-default', function(e){
      // remove from default
      e.preventDefault();
    })
    .on('click', 'a.remove', function(e){
      // delete image
      e.preventDefault();
      // show confirm dialog
      $('#delete-photo-modal .modal-body img')
        .attr('src', $(this).closest('li').find('img').attr('src'))
        .data('img_id', $(this).closest('div.action').data('id'));
      
      $('#delete-photo-modal').modal('show');
    })
    .on('click', 'button.close', function(e){
      // remove the photo box
      e.preventDefault();
      $(this).closest('li').hide('slow');
    })

  // do delete
  $('#delete-photo-modal .modal-footer button.btn-primary').click(function(e){
    e.preventDefault();
    var img_id = $(this).closest('div.modal-content').find('img').data('img_id');
    $.ajax({
      url : '/images/delete',
      data : {
        img_id : img_id
      },
      dataType : 'json',
      success : function(ret){
        switch (ret.msg){
        case '401':
        case '404':
          msg = 'Photo not found.';
          break;
        case '200':
          msg = 'The selected photo has been deleted.';
          // remove the image from the screen
          $('div.action[data-id="'+img_id+'"]').closest('li').hide('slow');
          break;
        case '500':
        default:
          msg = 'There was an error. The photo was not deleted. Please try again.';
        }
        $.notify({
          message : msg,
        }, {
          type : ret.type,
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000
        });
      },
      error : function(){
        $.notify({
          message : 'There was an error. Please try again.'
        }, {
          type : 'danger',
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000
        });
      }
    });
    $('#delete-photo-modal').modal('hide');    
  });

  // add new file  
  $('.fileupload.new-files').fileupload({
    url: '/images/add',
    dataType: 'json',
    beforeSend: function(xhr, settings){
      xhr.setRequestHeader('X-CSRF-Token', readCookie('csrfToken'));
    },
    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
    maxFileSize: 999000,
    dropZone: $('div.add-photo .fa-plus'),
    previewMaxWidth: 150,
    previewMaxHeight: 190,
    previewCrop: true
  }).on('fileuploadadd', function (e, data) {
    $.each(data.files, function (index, file) {
      var html = '<div class="photo" style="position: relative;">' +
          '<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">0%</div><p>'+html_entities(file.name)+'</p></div>' +
          '</div>';
      data.context = $('<li></li>').insertAfter('li.add-image:last');
      data.context.append(html);
    });
  }).on('fileuploadprogress', function (e, data) {
    var progress = parseInt(data.loaded / data.total * 100, 10);
    $(data.context).find('.progress-bar')
      .css('width', progress+'%')
      .html(progress+'%');
  }).on('fileuploaddone', function (e, data){
    if (data.result.id) {
      var html = $('#template-photo li').html();
      $(data.context).html(html);
      // update id and image src
      $(data.context).find('div.action').attr('data-id', html_entities(data.result.id));
      $(data.context).find('img').attr('src', '/img/profiles/'+html_entities(data.result.filename));
      // show feedback
        $.notify({
          message : 'Your new photo has been saved.',
        }, {
          type : 'info',
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000
        });      
    } else if (data.result.error) {
      $(data.context).find('.photo').html('<div class="error"><div class="text-danger">Sorry! Upload Error.</div><p>'+html_entities(data.result.error)+'</p></div>');
    }
  }).on('fileuploadfail', function (e, data) {
    $.each(data.files, function (index, file) {
      $(data.context).find('.photo').html('<div class="error"><div class="text-danger">Sorry! Upload Error. </div><p>'+file.error+'</p></div><button class="close">&times;</button>');
    });
  }).on('fileuploadprocessalways', function (e, data) {
    $.each(data.files, function(index, file){
      if (file.preview){
        $(data.context).find('.photo').append(file.preview);
      }
    });
  }).on('fileuploadprocessfail', function(e, data){
    $.each(data.files, function(index, file){
      if (file.error){
        $(data.context).find('.photo').html('<div class="error"><div class="text-danger">Sorry! Upload Error. </div><p>'+file.error+'</p></div><button class="close">&times;</button>');
      }
    });
  });

  // add new picture from camera
  const constraints = {
    video: true,
  };
  const canvas = document.getElementById('captured-canvas');
  const video = document.getElementById('captured-video');
  const img = document.getElementById('captured-img');
  $('li.add-image a.camera').click(function(e){
    // prepare the modal
    $(video).show();
    $('#captured-btn').show();
    $(img).hide();
    $('#captured-confirm').hide();
    
    navigator.mediaDevices.getUserMedia(constraints)
      .then(captureImage);    
  });
  function captureImage(stream){    
    $('#video-modal').modal('show');
    video.srcObject = stream;
  }
  $('#captured-btn').click(function(e){
    e.preventDefault();
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    img.src = canvas.toDataURL('image/png');
    // hide video and the capture button
    $(video).hide();
    $(this).hide();
    // show image and confirm button
    $(img).show();
    $('#captured-confirm').show();
    // Stop all video streams.
    video.srcObject.getVideoTracks().forEach(track => track.stop());
  });
  $('#captured-confirm-btn').click(function(e){
    // save the image
    $.ajax({
      url : '/images/capture',
      data : { captured_image : $(img).attr('src') },
      method : 'POST',
      beforeSend: function(xhr, settings){
        xhr.setRequestHeader('X-CSRF-Token', readCookie('csrfToken'));
      },
      dataType : 'json',
      success : function(ret){
        $('#video-modal').modal('hide');
        if (ret.error){
          $.notify({
            message : html_entities(ret.error)
          }, {
            type : 'danger',
            mouse_over : 'pause',
            newest_on_top : true,
            timer : 2000          
          });          
        } else {
          // add a new photo element
          var html = '<li>' + $('#template-photo li').html() + '</li>';
          var context = $(html).insertAfter('li.add-image:last')
          $(context).find('div.action').attr('data-id', html_entities(ret.id));
          $(context).find('img').attr('src', '/img/profiles/'+html_entities(ret.filename));
          // notify
          $.notify({
            message : 'The captured image has been saved.'
          }, {
            type : 'info',
            mouse_over : 'pause',
            newest_on_top : true,
            timer : 2000          
          });          
        }
      },
      error : function(){
        $('#video-modal').modal('hide');
        $.notify({
          message : 'Error saving the captured image.'
        }, {
          type : 'danger',
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000          
        });
      }
    })
  });
  $('#captured-cancel-btn').click(function(e){
    $('#video-modal').modal('hide');
  });
  $('#video-modal').on('hide.bs.modal', function(e){
    // Stop all video streams.
    video.srcObject.getVideoTracks().forEach(track => track.stop());
  });
  
  function hasGetUserMedia() {
    return !!(navigator.mediaDevices &&
              navigator.mediaDevices.getUserMedia);
  }

});
