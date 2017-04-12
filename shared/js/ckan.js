var objectFitImages = require('object-fit-images');

// Common js
require('./components/common/popper');

// CKAN specific js
require('./components/ckan/datepicker');
require('./components/ckan/form-action-loading-indicator');
require('./components/ckan/horizaccordion');
require('./components/ckan/image-modal');
require('./components/ckan/show-more');

$(function ($) {
  $(document).ready(function(){
    // Polyfill object-fit
    objectFitImages();
  });
}(jQuery));
