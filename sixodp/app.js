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
        window.location.href='/?s='+q;
    } else {
        window.location.href=domain+'?q='+q+'&sort=title+asc';
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

  $('.nav-link--roadmap').on('click', function() {
    $('.fluidtable__body').css('opacity', 0);
    $list = $(this).attr('data-toggle');
    $(this).parent().parent().find('.active').removeClass('active');
    $(this).parent().addClass('active');
    setTimeout(function() {
      $('.fluidtable__row').css('display', 'none');
      $('.fluidtable__row[data-list-id="'+$list+'"]').removeAttr('style');
    }, 150);

    setTimeout(function() {
      $('.fluidtable__body').css('opacity', 1);
    }, 150);

  });

  App.resizeArticleContent($('.article iframe'));

})( jQuery );
