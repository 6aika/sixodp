jQuery(document).ready(function($) {
  checkScreenSize();
  $(window).resize(checkScreenSize);

  function checkScreenSize(){
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

