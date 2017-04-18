var objectFitImages = require('object-fit-images');

$(function ($) {
  $(document).ready(function(){
    // Polyfill object-fit
    objectFitImages();
  });
}(jQuery));