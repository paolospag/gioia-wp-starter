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
  var resizeTimer;

  // Handle sub-menu
  function submenuDropdownHandler() {
    if (viewport().width > 991) {
      $('.navbar__nav > li.menu-item-has-children > a').off('click').on('click', function(e) {
        e.preventDefault();
      });
      $('.navbar__nav > li.menu-item-has-children > a').on('mouseenter', function() {
        $(this).parent().addClass('active');
      });

      $('.navbar__nav > li.menu-item-has-children').on('mouseleave', function() {
        if ($(this).hasClass('active')) {
          $(this).removeClass('active');
        }
      });
    } else {
      $('.navbar__nav > li.menu-item-has-children').off('mouseleave');
      $('.navbar__nav > li.menu-item-has-children > a').off('mouseenter').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        if ($(this).parent().hasClass('active') && href !== '#') {
          return window.location.href = href;
        }
        return $(this).parent().toggleClass('active');
      });
    }
  }
  submenuDropdownHandler();

  // Handle menu toggle
  function menuToggleHandler(e) {
    if (viewport().width > 991) {
      return true;
    }
    if (e.target.tagName == 'A' && e.target.getAttribute('href') == '#') {
      return false;
    }
    $('.navbar__toggle').toggleClass('dismiss');
    if (!$('.navbar__nav').hasClass('open')) {
      $('.navbar__nav').css('visibility', 'visible').addClass('open');
    } else {
      $('.navbar__nav').toggleClass('open');
    }
    if ($('.navbar__nav').hasClass('open')) {
      $('body').addClass('no-scroll');
    } else {
      $('body').removeClass('no-scroll');
      setTimeout(function() {
        $('.navbar__nav').css('visibility', 'hidden');
      }, 250);
    }
  }
  $('.navbar__toggle, .navbar__nav > li:not(.menu-item-has-children) > a').click(menuToggleHandler);

  // Handle search toggle
  function searchToggleHandler(e) {
    e.preventDefault();
    $('body').toggleClass('overlay');
  }
  $('.navbar__search, .btn-search.dismiss').click(searchToggleHandler);
  $(document).on('click', 'body.overlay', function(e) {
    if (e.target && $(e.target).hasClass('overlay')) {
      searchToggleHandler(e);
    }
  });

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

  /**
   * Custom JS here.
   */

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
      if ($('.navbar__nav > li.menu-item-has-children.active').length) {
        $('.navbar__nav > li.menu-item-has-children.active').removeClass('active');
      }
      submenuDropdownHandler();
      if (viewport().width >= 992 && $('.navbar__nav').hasClass('open')) {
        $('.navbar__nav').attr('style', '').removeClass('open');
        $('.navbar__toggle').removeClass('dismiss');
      }
      /* resize callback */
    }, 150);
  });
})(jQuery);
