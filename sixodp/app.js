var App = {
  
  toggleMenu: function($menu) {
    if ( !$menu.hasClass('opened') ) {
      App.openMenu($menu);
    } else {
      App.closeMenu($menu);
    }
  },

  openMenu: function($menu) {
    $menu.css('max-height', document.getElementById('site-navigation').offsetHeight+'px');
    setTimeout(function(){
      $menu.addClass('opened');
    }, 300);
  },

  closeMenu: function($menu) {
    $menu.removeClass('opened');
    $menu.css('max-height', 0);
  }
};

(function($) {

  $('button[data-toggle="collapse"]').on('click', function() {
    var $menu = $('.site-header-menu');
    App.toggleMenu($menu);
  });
})( jQuery );
