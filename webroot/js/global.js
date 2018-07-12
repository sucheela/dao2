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
