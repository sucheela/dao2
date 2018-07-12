$(function(){

  // load more
  var has_more = 1;
  var load_below = 1;
  var page = 1;
  $(window).scroll(function(){

    if (has_more && load_below){
      var scroll_y = $(window).scrollTop() + $(window).height();
      load_below = $('div.lazy-load').offset().top + $('div.lazy-load').height();
      if (scroll_y >= load_below){
        $('.ajax-loader').show();
        load_below = 0;
        var url = window.location.pathname;
        if (url.endsWith('/') === false){
          url += '/';
        }
        url += 'more/' + (++page);
        $.ajax({
          url : url,
          success : function(html){
            $('.ajax-loader').hide();
            if (html){
              // preload images
              html = '<div>' + html + '</div>';
              $(html).find('li').each(function(){
                $(this).css('display', 'none');
                var img = new Image();
                img.src = $(this).find('img').attr('src');
                $(this).appendTo('.matches ul');
                $(this).show('slow');
              });
              $('[data-toggle="tooltip"]').tooltip();
              // cut off at 10 pages
              if (page >= 10){
                has_more = 0;
              }
            } else {
              has_more = 0;
            }
          },
          error : function(){
            has_more = 0;
            $('.ajax-loader').hide();
          }
        });
      }
    }
  });
  
});
