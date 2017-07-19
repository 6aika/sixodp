jQuery(document).ready(function($) {
  checkScreenSize();
  $(window).resize(checkScreenSize);

  $('.subnav-toggle').on('click', function () {
    var parent = $(this).parent();

    if(parent.hasClass('active-hover')) {
      parent.removeClass('active-hover');
      $(this).removeClass('rotate');
    }
    else {
      parent.addClass('active-hover');
      $(this).addClass('rotate');
    }
  });

  // Toggle subnav visibility on hover when screen size is above navbar collapse width
  function checkScreenSize() {
    if ($(window).width() >= 992) { // $grid-float-breakpoint width
      $('#main-navbar li').hover(
        function () {
          $(this).addClass('active-hover');
        },
        function () {
          $(this).removeClass('active-hover');
        }
      );
    }
  }
});

