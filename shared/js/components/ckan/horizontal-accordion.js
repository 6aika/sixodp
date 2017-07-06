$(function ($) {
  $(document).ready(function () {

    var showOpenHorizontalAccordionButton = function (e) {
      e.find("#show-text").show();
      e.find("#close-text").hide();
    };

    var showCloseHorizontalAccordionButton = function (e) {
      e.find("#show-text").hide();
      e.find("#close-text").show();
    };

    // Toggle horizontal accordion collapse button text
    $("#horizontal-accordion-collapse-btn").click(function () {
      if ($(this).hasClass("collapsed")) {
        return showCloseHorizontalAccordionButton($(this));
      }
      showOpenHorizontalAccordionButton($(this));
    });

    // Close horizontal accordion if query parameters present
    if (window.location.search) {
      $('#horizontal-accordion').addClass('collapsed');
      $('#horizontal-accordion').removeClass('in');
      $("#horizontal-accordion-collapse-btn").addClass('collapsed');
      showOpenHorizontalAccordionButton($("#horizontal-accordion-collapse-btn"));
    }
  });
});