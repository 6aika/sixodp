$(function() {

  var Ripplr = require('ripplr');

  Ripplr({
    selector: '.btn',
    effectClass: 'ripplr'
  });

  $('body').on('click', '.dropdown-toggle', function() {
    var $dd = $('#'+$(this).attr('data-toggle'));
    var first = $dd.hasClass('opened') ? 'open' : 'opened';
    var second = $dd.hasClass('opened') ? 'opened' : 'open';

    $dd.toggleClass(first);
    setTimeout(function() {
      $dd.toggleClass(second);
    }, 200);
  });
});
