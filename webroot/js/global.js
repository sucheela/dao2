// global.js --- 
// 
// Filename: global.js
// Author: sucheela
// Created: Thu Nov 26 16:17:07 2015 (-0500)
// Last-Updated: Mon Feb 19 23:17:20 2018 (-0500)
//           By: sucheela
// 
// 
// 
$(function(){

  /*
  $('.navbar-static-top').affix({
    offset: $('nav').position()
  });

  // smooth scroll
  $('.navbar a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top - 100
        }, 1000);

      }
    }
  });
  
  // login
  $('a.login').click(function(e){
    e.preventDefault();
    gapi.signin2.render('g-login-button', {
      'scrop' : 'email',
      'width' : 250,
      'height' : 50,
      'longtitle' : true,
      'onsuccess' : onSignIn
    });
    $('#login-modal').modal('show');
  });

  // ui range-slider
  $('.range-slider').each(function(){
    var el = $(this);
    var options = {
    };
    $(this).slider({
      range : true,
      min : $(el).data('min'),
      max : $(el).data('max'),
      values : [$(el).data('min_val'), $(el).data('max_val')],
      slide : function(event, ui){
        var input = $('#' + $(this).data('input'));
        $(input).val(ui.values[0] + '-' + ui.values[1]);
      }
    });
  });
  */

  // more links
  $('.btn-more').click(function(e){
    e.preventDefault();
    var div = $('#' + $(this).data('src'));
    var txt = $(this).html();
    if ($(div).css('display') == 'none'){
      $(div).show('slow');
      $(this).html($(this).data('alt'));
      $(this).data('alt', txt);
    } else {
      $(div).hide('slow');
      $(this).html($(this).data('alt'));
      $(this).data('alt', txt);
    }
  });

  // tooltip
  $('[data-toggle="tooltip"]').tooltip();  
  
});
