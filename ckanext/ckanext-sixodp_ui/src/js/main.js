$(function() {

  var Ripplr = require('ripplr');

  Ripplr({
    selector: '.btn',
    effectClass: 'ripplr'
  });

  $('body').on('click', '.dropdown-toggle', function() {
    $('.dropdown-menu').toggleClass('open');
  });
});
