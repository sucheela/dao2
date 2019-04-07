$(function(){
  // mark thread as read
  $('.threads').on('click', 'a.do-open', function(e){
    e.preventDefault();
    var btn = $(this);
    var thread_id = $(this).data('thread_id');
    $.ajax({
      url : '/messages/openall',
      method : 'post',
      data : {
        'thread_id' : thread_id
      },
      beforeSend : function(xhr){
        xhr.setRequestHeader('X-CSRF-Token', readCookie('csrfToken'));
      },
      success : function(){
        // hide button
        $(btn).hide();
        // change border color
        $(btn).closest('.thread').removeClass('unread');
        // reduce # message in the menu
        var el = $('.navbar .dropdown-menu .unopened-num');
        var num = $(el).html();
        num = parseInt(num);
        if (num && num > 0){
          $(el).html(num-1);
        }
      },
      error : function(){
        $.notify({
          message : 'Oops! Can\'t mark the message as read.'
        }, {
          type : 'danger',
          newest_on_top : true,
          timer : 2000
        });
      }
    });
  });

  // load previous thread
  $('.prev-thread').click(function(e){
    e.preventDefault();
    var btn = $(this);
    var thread_id = btn.data('thread_id');
    $.ajax({
      url : '/messages/previous/' + thread_id,
      dataType : 'json',
      success : function(ret){
        // previous button
        if (ret.prev_thread_id){
          btn.data('thread_id', ret.prev_thread_id);
        } else {
          btn.hide('slow');
        }
        // prepend messages at the top of the existing messages
        if (ret.messages.length > 0){
          delay_show(ret, 0);
        }
      },
      error : function(){
        $.notify({
          message : 'Sorry! Previous message not found.'
        }, {
          type : 'danger',
          newest_on_top : true,
          timer : 2000
        });
      }
    });
  });

  function delay_show(ret, i){
    setTimeout(function(){
      var msg = ret.messages[i];
      var html = '<div class="preview ' +
          (msg.whose == 'yours' ? 'yours' : 'mine') + '" style="display: none">' +
          '<p>' + html_entities(msg.what) + '</p>' +
          '<div class="when">' + html_entities(msg.when) + '</div>' +
          '</div>';
      $(html).insertAfter($('a.prev-thread').closest('p')).show('slow');
      i++;
      if (i < ret.messages.length){
        delay_show(ret, i);
      }
    }, 200);
  }
  
});
