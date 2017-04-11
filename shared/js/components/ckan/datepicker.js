$(function ($) {
  $(document).ready(function() {
    // Attempt to get the user language, datepicker will default to en-US if not successful
    var language = window.navigator.userLanguage || window.navigator.language;
    jQuery('.has-datepicker input').datepicker({
      format: 'yyyy-mm-dd',
      weekStart: 1,
      language: language,
      todayHighlight: true
    });
  });
})