var App = {

  toggleMenu: function($menu) {
    if ( !$menu.hasClass('opened') ) {
      $menu.addClass('opened');
    } else {
      $menu.removeClass('opened');
    }
  }
};

(function($) {

  $('button[data-toggle="collapse"]').on('click', function() {
    var $menu = $('#site-navigation');
    App.toggleMenu($menu);
  });
})( jQuery );
