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

        if($(this).children(".show-more-link").css('display') !== 'none'){
            $content.addClass("show-content");
            $content.removeClass("hide-content");
        } else {
            $content.addClass("hide-content");
            $content.removeClass("show-content");
        }
        $(this).children().toggle();
    });

  });
}(jQuery));
