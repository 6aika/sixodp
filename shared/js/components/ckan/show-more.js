$(function ($) {
  $(document).ready(function () {
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
  });
});