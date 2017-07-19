$('#main-navbar li').hover(
  function () {
    $(this).addClass('active-hover');
  },
  function () {
    $(this).removeClass('active-hover');
  }
);