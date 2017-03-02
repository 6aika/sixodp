var App = {

  toggleMenu: function($menu) {
    if ( !$menu.hasClass('opened') ) {
      $menu.addClass('opened');
    } else {
      $menu.removeClass('opened');
    }
  },

  search: function(domain, q) {
    window.location.href=domain+'?q='+q+'&sort=title+asc';
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

  $('.nav-link--roadmap').on('click', function() {
    $list = $(this).attr('data-toggle');
    $(this).parent().parent().find('.active').removeClass('active');
    $(this).parent().addClass('active');
    $('.fluidtable__row').css('display', 'none');
    $('.fluidtable__row[data-list-id="'+$list+'"]').removeAttr('style');
  });
})( jQuery );
