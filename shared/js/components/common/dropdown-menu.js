$(function() {
  $(document).ready(function() {
    $('body').on('click', '.dropdown-toggle', function () {
      var $dd = $('#' + $(this).attr('data-toggle'));
      var closed = !$dd.hasClass('opened');

      $('.dropdown-menu').removeClass('opened');
      $('.dropdown-toggle').removeClass('active');
      $('.dropdown-toggle').attr('aria-expanded', 'false');
      if (closed) {
        $dd.addClass('opened');
        $(this).addClass('active');
        $(this).attr('aria-expanded', 'true');
      }
    });
  });
});