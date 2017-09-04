// Common js
require('./components/common/navbar');

var App = {

  toggleMenu: function($menu) {
    if ( !$menu.hasClass('opened') ) {
      $menu.addClass('opened');
    } else {
      $menu.removeClass('opened');
    }
  },

  search: function(domain, q) {
    if(domain == "/posts") {
      window.location.href='/'+locale+'/?s='+q;
    } else {

      window.location.href='/data/'+locale_ckan+domain+'?q='+q+'&sort=title+asc';
    }
    //
  },

  // Fix to 100% width
  resizeArticleContent: function($iframe) {
    var paddingTop = ($iframe.outerHeight() / $iframe.outerWidth())*100 || 56.25;

    var iframeCSS = {
      position: 'absolute',
      top: '0',
      left: '0',
      bottom: '0',
      right: '0',
      width: '100%',
      height: '100%'
    };

    $iframe.wrap('<div class="article__iframe-wrapper" style="padding-top: '+paddingTop+'%;"></div>');
    $iframe.css(iframeCSS);
  }
};

(function($) {

  $('.counter').each(function () {
    $(this).prop('Counter',0).animate({
      Counter: $(this).text()
    }, {
      duration: 1000,
      easing: 'swing',
      step: function (now) {
        $(this).text(Math.ceil(now));
      }
    });
  });

  $('button[data-toggle="collapse"]').on('click', function() {
    var $menu = $('#site-navigation');
    App.toggleMenu($menu);
  });

  $('a[data-value]').on('click', function() {
    var domain = $(this).data('value');
    var title = $(this).text();
    $('#selected-domain').attr('data-value', domain).text(title);
  });

  $('#search').on('click', function() {
    var q = $('#q').val();
    var domain = $('#selected-domain').data('value');
    App.search(domain, q);
  });

  $(document).keypress(function(e) {
    if(e.which == 13) {
      console.log("!");
      if($('#q').is(":focus")){
        var q = $('#q').val();
        var domain = $('#selected-domain').data('value');
        App.search(domain, q);
      }
    }
  });

  $('.btn[data-trigger="popper"]').on('click', function() {
    $('.popper').toggleClass('open');
  });

  $('.navbar-search-btn').on('click', function(e) {
    e.stopPropagation()

    $(this).hide();
    $('.navbar-search-form').css('display', 'table');
    
    $(document).on('click', function(e) {
      var container = $('.navbar-search-form')

      if (!container.is(e.target) && container.has(e.target).length === 0) {
        $('.navbar-search-form').hide();
        $('.navbar-search-btn').show();

        $(document).off('click');
      }
    });
  });

  $('.navbar-search-submit-btn').on('click', function(e) {
    e.preventDefault();
    var q = $('.navbar-search-form > input').val();
    App.search('/posts', q);
  });

  App.resizeArticleContent($('.article iframe'));

})( jQuery );
