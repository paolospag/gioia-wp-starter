function viewport() {
  var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
  return { width:x,height:y };
}

(function($) {
  var resizeTimer;

  /**
   * Custom JS here
   */

  // Handle language switcher
  $('.lang-menu > [role="button"]').click(function(e) {
    $(e.target).next().slideToggle(200);
    $(e.target).parent().toggleClass('open');
  });
  $(document).click(function(e) {
    if ( !$('.lang-menu > [role="button"]').is(e.target) && $('.lang-menu').hasClass('open') ) {
      $('.lang-menu.open > [role="button"]').trigger('click');
    }
  });

  // Polyfill object-fit property
  if (typeof objectFitImages != 'undefined') {
    objectFitImages();
  }

  // Ponyfill CSS vars
  if (typeof cssVars != 'undefined') {
    cssVars({});
  }

  // Handle resize updates
  $(window).resize(function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      /* resize callback */
    }, 150);
  });
})(jQuery);
