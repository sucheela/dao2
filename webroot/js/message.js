// message.js --- 
// 
// Filename: message.js
// Source: http://qnimate.com/facebook-style-chat-box-popup-using-javascript-and-css/
// 
// 
$(function(){
  //recalculate when window is loaded and also when window is resized.
  $(window).on('resize', function(){
    calculate_popups();
  });

  $(window).on('load', function(){
    calculate_popups();
  });

  // attach initiating message event
  $('.matches ul, .profile').on('click', 'a.message', function(e){
    e.preventDefault();
    var user_id = $(this).closest('div.action').data('user_id');
    var name = '';
    if ($(this).closest('li').find('div.title>span.name').length){
      // in matches, favorites, recent visitors pages
      name = $(this).closest('li').find('div.title>span.name').length.html();
    } else {
      // in profile page
      name = $(this).closest('.profile').find('h1.name').html();
    }
    // get thread id
    $.ajax({
      url : '/messages/thread',
      data : {
        to_user_id : user_id
      },
      dataType : 'json',
      success : function(ret){
        if (ret.error !== undefined){
          var msg;
          switch (ret.error){
          case '401':
          case '404':
            msg = 'User not found.';
            break;
          case '403':
            msg = 'Sorry, you can\'t send messages to yourself. :(';
            break;
          case '405':
            msg = 'Sorry, you must activate your profile first.';
            break;
          case '500':
          default:
            msg = 'There was an error. Please try again.';
            break;            
          }
          $.notify({
            message : msg
          }, {
            type : 'error',
            mouse_over : 'pause',
            newest_on_top : true,
            timer : 2000
          });
          
        } else {
          if (ret.thread_id){
            register_popup(ret.thread_id, name);        
            // focus on the textarea
            $('.chat-popup[data-thread_id="'+ret.thread_id+'"] textarea').focus();
          }
        }
      },
      error : function(error){
        $.notify({
          message : 'There was an error. Please try again.'
        }, {
          type : 'danger',
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000
        });
      }
    })
    $(this).tooltip('hide');
  });

  // attach close popup
  $('body').on('click', '.chat-popup a.close-popup', function(e){
    e.preventDefault();
    var id = $(this).closest('.chat-popup').data('thread_id');
    close_popup(id);
  });

  // attach minimize popup
  $('body').on('click', '.chat-popup a.min-popup', function(e){
    e.preventDefault();
    $(this).closest('.chat-popup').css('height', '35px');
    $(this).hide();
    $(this).siblings('a.max-popup').show();
  });

  // attach maximize popup
  $('body').on('click', '.chat-popup a.max-popup', function(e){
    e.preventDefault();
    $(this).closest('.chat-popup').css('height', '285px');
    $(this).hide();
    $(this).siblings('a.min-popup').show();
  });

  // atatch sending message event
  $('body').on('keyup', '.chat-popup .popup-foot textarea', function(e){
    e.preventDefault();
    var thread_id = $(this).closest('.chat-popup').data('thread_id');
    var message = $(this).val();
    if (e.which == 13){
      $.ajax({
        url : '/messages/add',
        method : 'post',
        data : {
          'thread_id' : thread_id,
          'message'   : message
        },
        beforeSend: function(xhr){
          xhr.setRequestHeader('X-CSRF-Token', readCookie('csrfToken'));
        },
        dataType : 'json',
        success : function(ret){
          // add message to popup-messages
          show_message(thread_id, message, 'mine', ret.created_date);
          // clear the message from textarea
          $(e.target).val('');
          if (ret.is_online == 0){
            show_message(thread_id, 'The user is offline.', 'system', '');
          }
        },
        error : function(){
          // add error message to popup messages
          show_message(thread_id, 'Ooops! Something is wrong.', 'error', '');
        }
      });
    }    
  });

  // check for new messages every 10 seconds
  setInterval(function(){
    $.ajax({
      url : '/messages/refresh',
      dataType : 'json',
      success : function(ret){
        if (ret !== undefined){
          for (var thread_id in ret){
            var msg = ret[thread_id];
            //console.log(msg);
            if ($('[data-thread_id="'+thread_id+'"]').length){
              register_popup(thread_id, msg.from_user_name);
              // if this thread is already opened
              show_message(thread_id, msg.message, 'yours', msg.created_date, msg.id);
            } else {
              // notify of the new message
              $.notify({
                message : '<a href="#" class="notify-new-message" data-thread_id="' +
                  thread_id + '">You have a new message from <strong><span class="from-name">' +
                  html_entities(msg.from_user_name) +
                  '</span></strong>. Click here to open the message.</a>'
              }, {
                type : 'info',
                mouse_over : 'pause',
                newest_on_top : true,
                timer : 5000
              });
            }
          } // end foreach thread in ret
        } // end if has new messages
      } // succhess
    })
  }, 10000);

  // attach initiating message to .nodify-new-message
  $('body').on('click', 'a.notify-new-message', function(e){
    e.preventDefault();
    var thread_id = $(this).data('thread_id');
    register_popup(thread_id, $(this).find('span.from-name').html());
    // focus on the textarea
    $('.chat-popup[data-thread_id="'+thread_id+'"] textarea').focus();    
  });

  //this function can remove a array element.
  function array_remove(array, from, to) {
    var rest = array.slice((to || from) + 1 || array.length);
    array.length = from < 0 ? array.length + from : from;
    return array.push.apply(array, rest);
  };
  
  //this variable represents the total number of popups can be displayed according to the viewport width
  var total_popups = 0;

  //arrays of popups ids
  var popups = [];

  //this is used to close a popup
  function close_popup(id)
  {
    for(var iii = 0; iii < popups.length; iii++)
    {
      if(id == popups[iii])
      {
        array_remove(popups, iii);

        $('[data-thread_id="'+id+'"]').hide();
        
        calculate_popups();
        
        return;
      }
    }  
  }

  //displays the popups. Displays based on the maximum number of popups that can be displayed on the current viewport width
  function display_popups()
  {
    var right = 15;
    
    var iii = 0;
    for(iii; iii < total_popups; iii++)
    {
      if(popups[iii] != undefined)
      {
        var element = $('[data-thread_id="'+popups[iii]+'"]');
        $(element).css('right', right+"px");
        right = right + 310;
        $(element).show();
      }
    }
    
    for(var jjj = iii; jjj < popups.length; jjj++)
    {
      var element = $('[data-thread_id="'+popups[jjj]+'"]');
      $(element).hide();
    }
  }

  //creates markup for a new popup. Adds the id to popups array.
  function register_popup(id, name)
  {
    
    for(var iii = 0; iii < popups.length; iii++)
    {  
      //already registered. Bring it to front.
      if(id == popups[iii])
      {
        array_remove(popups, iii);
        
        popups.unshift(id);
        
        calculate_popups();        
        
        return;
      }
    }              
    
    var element = '<div class="popup-box chat-popup" data-thread_id="'+ id +'">' +
        '<div class="popup-head">' +
        '<div class="popup-head-left">'+ name +'</div>' +
        '<div class="popup-head-right">'+
        '<a href="#" class="min-popup" title="Minimize"><span class="fa fa-minus"></span></a>' +
        '<a href="#" class="max-popup" title="Maximize" style="display: none;"><span class="fa fa-plus"></span></a>' +
        '<a href="#" class="close-popup" title="Close"><span class="fa fa-close"></span></a></div>' +
        '<div style="clear: both"></div></div><div class="popup-messages"></div>' +
        '<div class="popup-foot"><textarea placeholder="Type your message here. Hit Enter to send."></textarea></div></div>';

    $('body').append(element);
    
    popups.unshift(id);
    
    calculate_popups();

    // get recent messages for this thread
    $.ajax({
      url : '/messages/recent/' + id,
      dataType : 'json',
      success : function(messages){
        for (var i in messages){
          var msg = messages[i];
          show_message(id,
                       msg.message,
                       msg.whose,
                       msg.created_date,
                       msg.whose == 'yours' ? msg.id : '');
        }
      }
    });
  }

  //calculate the total number of popups suitable and then populate the toatal_popups variable.
  function calculate_popups()
  {
    var width = window.innerWidth;

//    if(width < 540)
//    {
//      total_popups = 0;
//    }
//    else
//    {
      width = width - 15;
      //320 is width of a single popup box
      total_popups = parseInt(width/320);
//    }
    
    display_popups();
    
  }

  function show_message(id, msg, whose, when, message_id){
    var cname = 'system';
    switch (whose){
    case 'yours':
    case 'mine':
    case 'system':
      cname = whose;
      break;
    case 'error':
      cname = 'system text-danger';
      break;
    }
    
    var html = '<div class="msg ' + cname + '" style="display: none;">' +
        '<p>' + html_entities(msg) + '</p>' +
        (when ? '<div class="when">' + html_entities(when) + '</div>' : '') +
        '</div>';
    var box = $('.chat-popup[data-thread_id="'+id+'"] .popup-messages');
    box.append(html);    
    box.find('.msg').last().show();
    if (message_id){
      open_message(id, message_id);
    }
    box.animate({
      scrollTop : box.find('.msg').last().offset().top
    }, 1000);
  } // show_message()

  function open_message(thread_id, message_id){
    $.ajax({
      url : '/messages/open',
      method : 'post',
      data : {
        'thread_id'  : thread_id,
        'message_id' : message_id
      },
      beforeSend : function(xhr){
        xhr.setRequestHeader('X-CSRF-Token', readCookie('csrfToken'));
      }
    });
  } // open_message()

  function html_entities(str){
    if (str){
      return str.replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }
    return str;
  }  

  function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
  }  
});
