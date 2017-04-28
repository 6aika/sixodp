$(function ($) {
  $(document).ready(function () {

    var showOpenHorizaccordionButton = function (e) {
      e.find("#show-text").show();
      e.find("#close-text").hide();
    };

    var showCloseHorizaccordionButton = function (e) {
      e.find("#show-text").hide();
      e.find("#close-text").show();
    };

    // Toggle horizaccordion collapse button text
    $("#horizaccordion-collapse-btn").click(function () {
      if ($(this).hasClass("collapsed")) {
        return showCloseHorizaccordionButton($(this));
      }
      showOpenHorizaccordionButton($(this));
    });

    // Close horizaccordion if query parameters present
    if (window.location.search) {
      $('#horizaccordion').addClass('collapsed');
      $('#horizaccordion').removeClass('in');
      $("#horizaccordion-collapse-btn").addClass('collapsed');
      showOpenHorizaccordionButton($("#horizaccordion-collapse-btn"));
    }
  });
});