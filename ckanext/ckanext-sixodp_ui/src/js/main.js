$(function() {

  var Ripplr = require('ripplr');

  Ripplr({
    selector: '.btn',
    effectClass: 'ripplr'
  });

  $('body').on('click', '.dropdown-toggle', function() {
    var $dd = $('#'+$(this).attr('data-toggle'));
    var closed = !$dd.hasClass('opened');

    $('.dropdown-menu').removeClass('opened');
    $('.dropdown-toggle').removeClass('active');
    if ( closed ) {
      $dd.addClass('opened');
      $(this).addClass('active');
    }
  });
});
