$(function(){

  // images
  $('.more-images a').click(function(e){
    e.preventDefault();
    var src = $(this).find('img').attr('src');
    $('.hero-image img').attr('src', html_entities(src));
  });

  // blocking users
  $('.matches ul, .profile, .thread').on('click', 'a.block', function(e){
    e.preventDefault();
    var user_id = $(this).closest('.action').data('user_id');
    var user_name = $(this).closest('.action').data('user_name');
    user_name = html_entities(html_entity_decode(user_name));
    var anchor = this;
    var msg = '';
    $.ajax({
      url : '/blocks/add',
      data : {
        blocked_user_id : user_id
      },
      dataType : 'json',
      success : function(ret){
        switch (ret.msg){
        case '401':
        case '404':
          msg = 'Blocked user not found.';
          break;
        case '403':
          msg = 'Sorry, you can\'t block yourself. :(';
          break;
        case '405':
          msg = 'Sorry, you must activate your profile first.';
          break;
        case '409':
          msg = 'You had already blocked ' + user_name + '.';
          break;
        case '200':
          msg = 'The user, ' + user_name + ', will not be shown to you again. To modify your Black List, please visit My Black List under My Account.';
          break;
        case '500':
        default:
          msg = 'There was an error. Please try again.';
          break;
        }
        
        $.notify({
          message : msg,
          url : '/blocks'
        }, {
          type : ret.type,
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000
        });

        if (ret.type == 'info'){
          $(anchor).tooltip('destroy');
          if (li){
            // hide this user on the screen
            $(li).hide('slow');
          } else {
            // replace block icon with not-block
            $(anchor).replaceWith('<a href="/blocks/delete" class="not-block" data-toggle="tooltip" title="Remove from my Black List."><span class="fa fa-circle-o"></span></a>');
            // enable tooltip
            $('a.not-block').tooltip();
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
    });
  });

  // unblocking user
  $('.matches, .profile, .thread').on('click', 'a.not-block', function(e){
    e.preventDefault();
    var user_id = $(this).closest('.action').data('user_id');
    var user_name = $(this).closest('.action').data('user_name');
    user_name = html_entities(html_entity_decode(user_name));
    var anchor = this;
    var msg = '';
    $.ajax({
      url : '/blocks/delete',
      data : {
        blocked_user_id : user_id
      },
      dataType : 'json',
      success : function(ret){
        switch (ret.msg){
        case '401':
        case '404':
          msg = 'Blocked user not found.';
          break;
        case '405':
          msg = 'Sorry, you must activate your profile first.';
          break;
        case '200':
          msg = 'The user, ' + user_name + ', has been removed from your Black List.';
          if ($('.blocks').length == 0){
            // if not on My Black List page
            msg += 'To modify your Black List, please visit My Black List under My Account.';
          }
          break;
        case '500':
        default:
          msg = 'There was an error. Please try again.';
          break;
        }
        
        $.notify({
          message : msg,
          url : '/blocks'
        }, {
          type : ret.type,
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000
        });

        if (ret.type == 'info'){
          if ($('.blocks').length == 0){
            // if not on My Black List page
            $(anchor).tooltip('destroy');
            // replace not-block icon with block
            $(anchor).replaceWith('<a href="/blocks/add" class="block" data-toggle="tooltip" title="Do not show me this profile again."><span class="fa fa-ban"></span></a>');
            // enable tooltip
            $('a.block').tooltip();
          } else { // else if on My Black List page
            $(li).hide('slow');
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
    });
  });
  

  // favortie users
  $('.matches, .profile, .thread').on('click', 'a.favorite', function(e){
    e.preventDefault();
    var user_id = $(this).closest('.action').data('user_id');
    var user_name = $(this).closest('.action').data('user_name');
    user_name = html_entities(html_entity_decode(user_name));
    var fav_a = this;
    var msg = '';
    $.ajax({
      url : '/favorites/add',
      data : {
        fav_user_id : user_id
      },
      dataType : 'json',
      success : function(ret){
        switch (ret.msg){
        case '401':
        case '404':
          msg = 'Favorite user not found.';
          break;
        case '403':
          msg = 'Sorry, you can\'t favorite yourself. :(';
          break;
        case '405':
          msg = 'Sorry, you must activate your profile first.';
          break;
        case '409':
          msg = 'You had already added ' + user_name + ' to your favorites.';
          break;
        case '200':
          msg = 'The user, ' + user_name + ', has been added to your Favorite List. To modify your list, please visit My Favoties under My Account.';
          break;
        case '500':
          msg = 'There was an error. Please try again.';
          break;
        }
        
        $.notify({
          message : msg,
          url : '/favorites'
        }, {
          type : ret.type,
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000
        });

        if (ret.type == 'info'){
          // destroy the tooltip
          $(fav_a).tooltip('destroy');
          // replace favorite icon with not-favorite
          $(fav_a).replaceWith('<a href="/favorites/delete" class="not-favorite" data-toggle="tooltip" title="Remove from my favorites."><span class="fa fa-heart-o"></span></a>');
          // enable tootip
          if (li){
            $(li).find('a.not-favorite').tooltip();
          } else {
            $('a.not-favorite').tooltip();
          }
        }
      },
      error : function(xhr, text, error){
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
  });

  // remove from favorites
  $('.matches, .profile, .thread').on('click', 'a.not-favorite', function(e){
    e.preventDefault();
    var user_id = $(this).closest('.action').data('user_id');
    var user_name = $(this).closest('.action').data('user_name');
    user_name = html_entities(html_entity_decode(user_name));
    var anchor = this;
    var msg = '';
    
    $.ajax({
      url : '/favorites/delete',
      data : {
        fav_user_id : user_id
      },
      dataType : 'json',
      success : function(ret){
        switch (ret.msg){
        case '401':
        case '404':
          msg = 'Favorite user not found.';
          break;
        case '405':
          msg = 'Sorry, you must activate your profile first.';
          break;
        case '200':
          msg = user_name + ', has been removed from your Favorite List.';
          if ($('.favorites').length == 0){
            // if not already on My Favorites page
            msg += 'To modify your list, please visit My Favoties under My Account.';
          }
          break;
        case '500':
          msg = 'There was an error. Please try again.';
          break;
        }
        
        $.notify({
          message : msg,
          url : '/favorites'
        }, {
          type : ret.type,
          mouse_over : 'pause',
          newest_on_top : true,
          timer : 2000
        });

        if (ret.type == 'info'){
          if ($('.favorites').length == 0){ // if not on My Favorites page
            // destroy the tooltip
            $(anchor).tooltip('destroy');
            // replace not-favorite icon with favorite
            $(anchor).replaceWith('<a href="/favorites/add" class="favorite" data-toggle="tooltip" title="Add to my favorites."><span class="fa fa-heart"></span></a>');
            // enable tootip
            if (li){
              $(li).find('a.favorite').tooltip();
            } else {
              $('a.favorite').tooltip();
            }
          } else { // if on My Favorites page
            $(li).hide('slow');
          }
        }
      },
      error : function(xhr, text, error){
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
    
  });
    
});
