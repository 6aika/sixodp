jQuery(document).ready(function($) {
  checkScreenSize();
  $(window).resize(checkScreenSize);

  $('.subnav-toggle').on('click', function () {
    var parent = $(this).parent();

    if(parent.hasClass('active-hover')) {
      parent.removeClass('active-hover');
      //$(this).removeClass('rotate');
      $(this).attr('aria-expanded', false)
    }
    else {
      parent.addClass('active-hover');
      //$(this).addClass('rotate');
      $(this).attr('aria-expanded', true)
    }
  });

  // Toggle subnav visibility on hover when screen size is above navbar collapse width
  function checkScreenSize() {
    var isMobile = $('#mobile-indicator').is(':visible');

    var mouseEnterHandler = function () {
      $(this).addClass('active-hover');
    };

    var mouseLeaveHandler = function () {
      $(this).removeClass('active-hover');
    };

    navbarLink = $('#main-navbar li');

    if(isMobile === false) {
      navbarLink.bind({ mouseenter: mouseEnterHandler, mouseleave: mouseLeaveHandler});
    }
    else {
      navbarLink.unbind('mouseenter').unbind('mouseleave');
    }
  }
});

