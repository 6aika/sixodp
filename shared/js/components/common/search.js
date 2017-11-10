jQuery(document).ready(function($) {

  search = function(domain, q) {
    var wordpressIndicator = $('#wordpress-indicator').is(':visible');

    // Handle search differently when in wordpress
    if (wordpressIndicator)  {
      if(domain == "/posts") {
        window.location.href= '/' + locale + '/?s=' + q;
      }
      else {
        window.location.href= '/data/' + locale_ckan+domain + '?q=' + q + '&sort=title+asc';
      }
    }
    else {
      window.location.href='/' + jQuery('html').attr('lang') + '/?s=' + q;
    }
  };

  getLocale = function() {
    return jQuery('html').attr('lang');
  };

  $('#search').on('click', function() {
    var q = $('#q').val();
    var domain = $('#selected-domain').data('value');
    search(domain, q);
  });

  $(document).keypress(function(e) {
    if(e.which == 13) {
      if($('#q').is(":focus")){
        var q = $('#q').val();
        var domain = $('#selected-domain').data('value');
        search(domain, q);
      }
      else if ($('#navbar-search-q').is(':focus')) {
        var q = $('#navbar-search-q').val();
        search('/posts', q);
      }
    }
  });

  $('.navbar-search-btn').on('click', function(e) {
    e.stopPropagation();

    $(this).hide();
    var container = $('.navbar-search-form');
    container.css('display', 'table');

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
    search('/posts', q);
  });
});