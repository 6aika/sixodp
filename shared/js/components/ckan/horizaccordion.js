$(function ($) {
  $(document).ready(function () {

    var showOpenHorizaccordionButton = function (horizaccordionElement) {
      horizaccordionElement.find("#show-text").show();
      horizaccordionElement.find("#close-text").hide();
    }

    var showCloseHorizaccordionButton = function (horizaccordionElement) {
      horizaccordionElement.find("#show-text").hide();
      horizaccordionElement.find("#close-text").show();
    }

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
      showOpenHorizaccordionButton($('#horizaccordion'));
    }
  });
});