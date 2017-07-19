(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
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

  $('.counter').each(function () {
    $(this).prop('Counter',0).animate({
      Counter: $(this).text()
    }, {
      duration: 2000,
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

  App.resizeArticleContent($('.article iframe'));

})( jQuery );

},{"./components/common/navbar":2}],2:[function(require,module,exports){
jQuery(document).ready(function($) {
  checkScreenSize();
  $(window).resize(checkScreenSize);

  $('.subnav-toggle').on('click', function () {
    var parent = $(this).parent();

    if(parent.hasClass('active-hover')) {
      parent.removeClass('active-hover');
      $(this).removeClass('rotate');
    }
    else {
      parent.addClass('active-hover');
      $(this).addClass('rotate');
    }
  });

  // Toggle subnav visibility on hover when screen size is above navbar collapse width
  function checkScreenSize() {
    if ($(window).width() >= 992) { // $grid-float-breakpoint width
      $('#main-navbar li').hover(
        function () {
          $(this).addClass('active-hover');
        },
        function () {
          $(this).removeClass('active-hover');
        }
      );
    }
  }
});


},{}]},{},[1]);
