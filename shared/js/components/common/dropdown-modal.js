$('.dropdown-modal .dropdown-toggle').on('click', function (event) {
  $(this).parent().toggleClass('open');
  event.stopPropagation();
});

$(document).click(function(event) {
  // Close only when click was outside dropdown
  if (!$(event.target).is('.dropdown-modal *')) {
    $('.dropdown-modal').removeClass('open');
  }
});
