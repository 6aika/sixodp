$(function() {
  $(document).ready(function() {
    $('.btn[data-trigger="popper"]').on('click', function () {
      $('.popper').toggleClass('open');
    });
  });
});