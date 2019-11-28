$(function() {
  $(document).ready(function () {

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
  });
});
