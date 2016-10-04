$(function() {

  $('body').on('click', '.dropdown-toggle', function() {
    var $dd = $('#'+$(this).attr('data-toggle'));
    var closed = !$dd.hasClass('opened');

    $('.dropdown-menu').removeClass('opened');
    $('.dropdown-toggle').removeClass('active');
    $('.dropdown-toggle').attr('aria-expanded', 'false');
    if ( closed ) {
      $dd.addClass('opened');
      $(this).addClass('active');
      $(this).attr('aria-expanded', 'true');
    }
  });
});


$(function ($) {
  $(document).ready(function(){

  // hide .navbar first
    $('.navbar-fixed-top').hide();

    // fade in .navbar
    $(function () {
      $(window).scroll(function () {
              // set distance user needs to scroll before we fadeIn navbar
        if ($(this).scrollTop() > 300) {
          $('.navbar-fixed-top').fadeIn();
        } else {
          $('.navbar-fixed-top').fadeOut();
        }
      });
    });
  });
}(jQuery));
