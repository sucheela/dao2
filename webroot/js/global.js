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

function html_entity_decode(str){
  if (str){
    return str.replace(/&#039;/g, "'")
      .replace(/&quot;/g, '"')
      .replace(/&gt;/g, '>')
      .replace(/&lt;/g, '<')
      .replace(/&amp;/g, '&');
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
