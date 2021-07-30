function viewport() {
  var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
  return { width:x,height:y };
}

function getRootColorByProp(prop) {
  return getComputedStyle(document.documentElement)
         .getPropertyValue(prop);
}

function isInViewport(node) {
  var rect = node.getBoundingClientRect();
  return (
    (rect.height > 0 || rect.width > 0) &&
    rect.bottom >= 0 && rect.right >= 0 &&
    rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
    rect.left <= (window.innerWidth || document.documentElement.clientWidth)
  )
}

(function($) {
  var _debounce;

  // Handle header sticky.
  var $header = $('#header');
  var scroll = lastScroll = 0;
  var headerH = $header.outerHeight();
  var headerTopH = $header.find('.header__top').outerHeight();
  $(window).scroll(function() {
    scroll = $(this).scrollTop();
    if ( scroll > headerTopH && !$header.hasClass('header--sticky') ) {
      $header.addClass('header--sticky');
    }
    if ( scroll <= headerTopH && $header.hasClass('header--sticky') ) {
      $header.removeClass('header--sticky');
    }
    if ( scroll > lastScroll && scroll > 900 ) {
      $header.addClass('header--off');
    } else if ( (scroll+headerTopH+$(this).height()) < $(document).height() ) {
      $header.removeClass('header--off');
    }
    lastScroll = scroll;
  });

  // Handle language dropdown.
  $('.lang-menu > [role="button"]').click(function(e) {
    $(e.target).next().slideToggle(200);
    $(e.target).parent().toggleClass('lang-menu--open');
  });
  $(document).click(function(e) {
    if ( !$('.lang-menu > [role="button"]').is(e.target) && $('.lang-menu--open').length ) {
      $('.lang-menu--open > [role="button"]').trigger('click');
    }
  });

  // Handle mega menu dropdown.
  function megaMenuDropdownHandler() {
    if (viewport().width >= 992) {
      $('.navbar li[class*="has-children"] > a').off('click').on('click', function(e) {
        if ($(this).attr('href').indexOf('#') !== -1) {
          e.preventDefault();
        }
      });
      $('.navbar li[class*="has-children"] > a').on('mouseenter', function() {
        $(this).parent().addClass('active');
      });
      $('.navbar li[class*="has-children"]').on('mouseleave', function() {
        if ($(this).hasClass('active')) {
          $(this).removeClass('active');
        }
      });
    } else {
      $('.navbar li[class*="has-children"]').off('mouseleave');
      $('.navbar li[class*="has-children"] > a').off('mouseenter').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if ($(this).parent().hasClass('active') && href !== '#') {
          return window.location.href = href;
        }
        return $(this).parent().toggleClass('active');
      });
    }
  }
  function megaMenuDropdownCleaner() {
    if ($('.navbar li[class*="has-children"].active').length) {
      $('.navbar li[class*="has-children"].active').removeClass('active');
    }
  }
  megaMenuDropdownHandler();

  // Handle mega menu offcanvas.
  function megaMenuOffcanvasCleaner() {
    if (viewport().width >= 992 && $('.navbar__nav--offcanvas').length) {
      $('.navbar__toggle').removeClass('navbar__toggle--dismiss');
      $('.navbar__nav').removeClass('navbar__nav--offcanvas');
    }
  }
  $('.navbar__toggle').click(function() {
    $(this).toggleClass('navbar__toggle--dismiss');
    $('.navbar__nav').toggleClass('navbar__nav--offcanvas');
    return $(document).trigger('%DOMAIN_NAME%_mm_toggle');
  });
  $('.sub-menu__panel').click(function() {
    $(this).parent().parent('li[class*="has-children"]').removeClass('active');
  });
  $(document).on('%DOMAIN_NAME%_mm_toggle', function() {
    $('html, body').toggleClass('no-scroll');
    if ( !$('body').hasClass('no-scroll') ) {
      clearTimeout(_debounce);
      _debounce = setTimeout(function() {
        megaMenuDropdownCleaner();
      }, 200);
    }
  });

  // Polyfill object-fit property.
  if (typeof objectFitImages != 'undefined') {
    objectFitImages();
  }

  // Ponyfill CSS vars.
  if (typeof cssVars != 'undefined') {
    cssVars({});
  }

  // Handle resize updates.
  $(window).resize(function() {
    clearTimeout(_debounce);
    _debounce = setTimeout(function() {
      megaMenuDropdownCleaner();
      megaMenuDropdownHandler();
      megaMenuOffcanvasCleaner();
    }, 150);
  });
})(jQuery);
