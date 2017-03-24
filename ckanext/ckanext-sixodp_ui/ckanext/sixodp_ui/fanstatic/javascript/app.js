(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
$(function() {

  $('body').on('click', '.dropdown-toggle', function() {
    var $dd = $('#'+$(this).attr('data-toggle'));
    var closed = !$dd.hasClass('opened');

    $('.dropdown-menu').removeClass('opened');
    $('.dropdown-toggle').removeClass('active');
    $('.dropdown-toggle').attr('aria-expanded', 'false');
    if ( closed ) {
      $dd.addClass('opened');
      $(this).addClass('active');
      $(this).attr('aria-expanded', 'true');
    }
  });
});

$(function ($) {
  $(document).ready(function(){

    // Attempt to get the user language, datepicker will default to en-US if not successful
    var language = window.navigator.userLanguage || window.navigator.language;
    jQuery('input[type=date]').datepicker({
      format: 'yyyy-mm-dd',
      weekStart: 1,
      language: language
    });

    // hide .navbar first
    $('.navbar-fixed-top').hide();

    // fade in .navbar
    $(function () {
      $(window).scroll(function () {
        // set distance user needs to scroll before we fadeIn navbar
        if ($(this).scrollTop() > 300) {
          $('.navbar-fixed-top').fadeIn();
        } else {
          $('.navbar-fixed-top').fadeOut();
        }
      });
    });

    $(".form-actions button[type=submit]").one('click', function() {
      $(this).append(' ').append($('<span id="loading-indicator" ' +
          'class="icon icon-spinner icon-spin"></span>') );
    });

    $( "#resource-edit" ).one('submit', function() {
      var fileInput = $('#resource-edit input:file').get(0);
      if(fileInput.files.length > 0) {
          var fileSize = fileInput.files[0].size;
          $('#field-file_size').val(fileSize);
          var html = $('<div class="upload-times"><ul>' +
              '<li>24/1 Mbit/s: ' + Math.ceil(fileSize / 125000 / 60) + ' min</li>' +
              '<li>10/10 Mbit/s: ' + Math.ceil(fileSize / 1250000 / 60) + ' min</li>' +
              '<li>100/100 Mbit/s: ' + Math.ceil(fileSize / 12500000 / 60) + ' min</li>' +
              '</ul></div>');

          $("#submit-info").append(html).show();
      }
    });

    // Show the show more -link only when the specified height is filled
    var showMoreTextContent = $(".show-more-content .text-content");
    if( showMoreTextContent.outerHeight() > $(".show-more-content").outerHeight() ) {
        $(".show-more").show();
        $(".fadeout").show();
    }

    $(".show-more").on("click", function() {
        var $this = $(this);
        var $content = $this.prev("div.show-more-content");
        var $fadeout = $content.find(".fadeout");

        if($(this).children(".show-more-link").css('display') !== 'none'){
            $fadeout.hide();
            $content.addClass("show-content");
            $content.removeClass("hide-content");
        } else {
            $fadeout.show();
            $content.addClass("hide-content");
            $content.removeClass("show-content");
        }
        $(this).children().toggle();
    });

    $(".image-modal-open").click(function() {
        var img = $(this)[0];

        var modal = document.getElementById('image-modal');
        var modalImg = document.getElementById("modal-image-placeholder");

        modal.style.display = "block";
        modalImg.src = img.src;

        var closeModal = document.getElementsByClassName("close")[0];
        closeModal.onclick = function() {
            modal.style.display = "none";
        }
    });
  });
}(jQuery));

},{}]},{},[1]);
