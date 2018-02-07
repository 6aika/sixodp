$(function ($) {
  $(document).ready(function() {
    // Attempt to get the user language, datepicker will default to en-US if not successful
    var locale = window.navigator.userLanguage || window.navigator.language;

    jQuery('.has-datepicker input').datetimepicker({
      locale: locale,
      format: 'YYYY-MM-DD',
      icons: {
        time: 'fa fa-time',
        date: 'fa fa-calendar',
        up: 'fa fa-chevron-up',
        down: 'fa fa-chevron-down',
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-screenshot',
        clear: 'fa fa-trash',
        close: 'fa fa-remove'
      }
    });
  });
});