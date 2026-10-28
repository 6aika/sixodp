jQuery(document).ready(function($) {

  search = function(q) {
    window.location.href= '/' + locale + '/?s=' + encodeURI(q);
  };

  getLocale = function() {
    return jQuery('html').attr('lang');
  };

  $('#search').on('click', function() {
    var q = $('#q').val();
    search(q);
  });

  $(document).keypress(function(e) {
    if(e.which == 13) {
      if($('#q').is(":focus")){
        var q = $('#q').val();
        search(q);
      }
      else if ($('#navbar-search-q').is(':focus')) {
        var q = $('#navbar-search-q').val();
        search(q);
      }
    }
  });

  $('.navbar-search-btn').on('click', function(e) {
    e.stopPropagation();

    $(this).hide();
    var container = $('.navbar-search-form');
    container.css('display', 'flex');

    $(document).on('click', function(e) {
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
        $('.navbar-search-btn').show();

        $(document).off('click');
      }
    });
  });

  $('.navbar-search-submit-btn').on('click', function(e) {
    e.preventDefault();
    var q = $('#navbar-search-q').val();
    search(q);
  });
});