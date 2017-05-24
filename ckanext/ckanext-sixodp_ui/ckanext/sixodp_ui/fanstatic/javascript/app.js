(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/*! npm.im/object-fit-images 3.1.3 */
'use strict';

var OFI = 'bfred-it:object-fit-images';
var propRegex = /(object-fit|object-position)\s*:\s*([-\w\s%]+)/g;
var testImg = typeof Image === 'undefined' ? {style: {'object-position': 1}} : new Image();
var supportsObjectFit = 'object-fit' in testImg.style;
var supportsObjectPosition = 'object-position' in testImg.style;
var supportsOFI = 'background-size' in testImg.style;
var supportsCurrentSrc = typeof testImg.currentSrc === 'string';
var nativeGetAttribute = testImg.getAttribute;
var nativeSetAttribute = testImg.setAttribute;
var autoModeEnabled = false;

function createPlaceholder(w, h) {
	return ("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='" + w + "' height='" + h + "'%3E%3C/svg%3E");
}

function polyfillCurrentSrc(el) {
	if (el.srcset && !supportsCurrentSrc && window.picturefill) {
		var pf = window.picturefill._;
		// parse srcset with picturefill where currentSrc isn't available
		if (!el[pf.ns] || !el[pf.ns].evaled) {
			// force synchronous srcset parsing
			pf.fillImg(el, {reselect: true});
		}

		if (!el[pf.ns].curSrc) {
			// force picturefill to parse srcset
			el[pf.ns].supported = false;
			pf.fillImg(el, {reselect: true});
		}

		// retrieve parsed currentSrc, if any
		el.currentSrc = el[pf.ns].curSrc || el.src;
	}
}

function getStyle(el) {
	var style = getComputedStyle(el).fontFamily;
	var parsed;
	var props = {};
	while ((parsed = propRegex.exec(style)) !== null) {
		props[parsed[1]] = parsed[2];
	}
	return props;
}

function setPlaceholder(img, width, height) {
	// Default: fill width, no height
	var placeholder = createPlaceholder(width || 1, height || 0);

	// Only set placeholder if it's different
	if (nativeGetAttribute.call(img, 'src') !== placeholder) {
		nativeSetAttribute.call(img, 'src', placeholder);
	}
}

function onImageReady(img, callback) {
	// naturalWidth is only available when the image headers are loaded,
	// this loop will poll it every 100ms.
	if (img.naturalWidth) {
		callback(img);
	} else {
		setTimeout(onImageReady, 100, img, callback);
	}
}

function fixOne(el) {
	var style = getStyle(el);
	var ofi = el[OFI];
	style['object-fit'] = style['object-fit'] || 'fill'; // default value

	// Avoid running where unnecessary, unless OFI had already done its deed
	if (!ofi.img) {
		// fill is the default behavior so no action is necessary
		if (style['object-fit'] === 'fill') {
			return;
		}

		// Where object-fit is supported and object-position isn't (Safari < 10)
		if (
			!ofi.skipTest && // unless user wants to apply regardless of browser support
			supportsObjectFit && // if browser already supports object-fit
			!style['object-position'] // unless object-position is used
		) {
			return;
		}
	}

	// keep a clone in memory while resetting the original to a blank
	if (!ofi.img) {
		ofi.img = new Image(el.width, el.height);
		ofi.img.srcset = nativeGetAttribute.call(el, "data-ofi-srcset") || el.srcset;
		ofi.img.src = nativeGetAttribute.call(el, "data-ofi-src") || el.src;

		// preserve for any future cloneNode calls
		// https://github.com/bfred-it/object-fit-images/issues/53
		nativeSetAttribute.call(el, "data-ofi-src", el.src);
		if (el.srcset) {
			nativeSetAttribute.call(el, "data-ofi-srcset", el.srcset);
		}

		setPlaceholder(el, el.naturalWidth || el.width, el.naturalHeight || el.height);

		// remove srcset because it overrides src
		if (el.srcset) {
			el.srcset = '';
		}
		try {
			keepSrcUsable(el);
		} catch (err) {
			if (window.console) {
				console.log('http://bit.ly/ofi-old-browser');
			}
		}
	}

	polyfillCurrentSrc(ofi.img);

	el.style.backgroundImage = "url(\"" + ((ofi.img.currentSrc || ofi.img.src).replace(/"/g, '\\"')) + "\")";
	el.style.backgroundPosition = style['object-position'] || 'center';
	el.style.backgroundRepeat = 'no-repeat';

	if (/scale-down/.test(style['object-fit'])) {
		onImageReady(ofi.img, function () {
			if (ofi.img.naturalWidth > el.width || ofi.img.naturalHeight > el.height) {
				el.style.backgroundSize = 'contain';
			} else {
				el.style.backgroundSize = 'auto';
			}
		});
	} else {
		el.style.backgroundSize = style['object-fit'].replace('none', 'auto').replace('fill', '100% 100%');
	}

	onImageReady(ofi.img, function (img) {
		setPlaceholder(el, img.naturalWidth, img.naturalHeight);
	});
}

function keepSrcUsable(el) {
	var descriptors = {
		get: function get(prop) {
			return el[OFI].img[prop ? prop : 'src'];
		},
		set: function set(value, prop) {
			el[OFI].img[prop ? prop : 'src'] = value;
			nativeSetAttribute.call(el, ("data-ofi-" + prop), value); // preserve for any future cloneNode
			fixOne(el);
			return value;
		}
	};
	Object.defineProperty(el, 'src', descriptors);
	Object.defineProperty(el, 'currentSrc', {
		get: function () { return descriptors.get('currentSrc'); }
	});
	Object.defineProperty(el, 'srcset', {
		get: function () { return descriptors.get('srcset'); },
		set: function (ss) { return descriptors.set(ss, 'srcset'); }
	});
}

function hijackAttributes() {
	function getOfiImageMaybe(el, name) {
		return el[OFI] && el[OFI].img && (name === 'src' || name === 'srcset') ? el[OFI].img : el;
	}
	if (!supportsObjectPosition) {
		HTMLImageElement.prototype.getAttribute = function (name) {
			return nativeGetAttribute.call(getOfiImageMaybe(this, name), name);
		};

		HTMLImageElement.prototype.setAttribute = function (name, value) {
			return nativeSetAttribute.call(getOfiImageMaybe(this, name), name, String(value));
		};
	}
}

function fix(imgs, opts) {
	var startAutoMode = !autoModeEnabled && !imgs;
	opts = opts || {};
	imgs = imgs || 'img';

	if ((supportsObjectPosition && !opts.skipTest) || !supportsOFI) {
		return false;
	}

	// use imgs as a selector or just select all images
	if (typeof imgs === 'string') {
		imgs = document.querySelectorAll(imgs);
	} else if (!('length' in imgs)) {
		imgs = [imgs];
	}

	// apply fix to all
	for (var i = 0; i < imgs.length; i++) {
		imgs[i][OFI] = imgs[i][OFI] || {
			skipTest: opts.skipTest
		};
		fixOne(imgs[i]);
	}

	if (startAutoMode) {
		document.body.addEventListener('load', function (e) {
			if (e.target.tagName === 'IMG') {
				fix(e.target, {
					skipTest: opts.skipTest
				});
			}
		}, true);
		autoModeEnabled = true;
		imgs = 'img'; // reset to a generic selector for watchMQ
	}

	// if requested, watch media queries for object-fit change
	if (opts.watchMQ) {
		window.addEventListener('resize', fix.bind(null, imgs, {
			skipTest: opts.skipTest
		}));
	}
}

fix.supportsObjectFit = supportsObjectFit;
fix.supportsObjectPosition = supportsObjectPosition;

hijackAttributes();

module.exports = fix;

},{}],2:[function(require,module,exports){
// Common js
require('./components/common/polyfills');
require('./components/common/popper');

// CKAN specific js
require('./components/ckan/datepicker');
require('./components/ckan/form-action-loading-indicator');
require('./components/ckan/horizaccordion');
require('./components/ckan/image-modal');
require('./components/ckan/show-more');

},{"./components/ckan/datepicker":3,"./components/ckan/form-action-loading-indicator":4,"./components/ckan/horizaccordion":5,"./components/ckan/image-modal":6,"./components/ckan/show-more":7,"./components/common/polyfills":8,"./components/common/popper":9}],3:[function(require,module,exports){
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
},{}],4:[function(require,module,exports){
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
},{}],5:[function(require,module,exports){
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
},{}],6:[function(require,module,exports){
$(function() {
  $(document).ready(function () {

    $(".image-modal-open").click(function () {
      var img = $(this)[0];

      var modal = document.getElementById('image-modal');
      var modalImg = document.getElementById("modal-image-placeholder");

      modal.style.display = "block";
      modalImg.src = img.src;

      var closeModal = document.getElementsByClassName("close")[0];
      closeModal.onclick = function () {
        modal.style.display = "none";
      }
    });
  });
});
},{}],7:[function(require,module,exports){
$(function ($) {
  $(document).ready(function () {
    var updateShowMore = function() {
      if ($('#mobile-indicator').is(':visible')) {
        // Show the show more -link only when the specified height is filled
        var showMoreTextContent = $(".show-more-content .text-content");
        if (showMoreTextContent.outerHeight() > $(".show-more-content").outerHeight()) {
          $(".show-more").show();
          $(".fadeout").show();
        }

        $(".show-more").on("click", function () {
          var $this = $(this);
          var $content = $this.prev("div.show-more-content");
          var $fadeout = $content.find(".fadeout");

          if ($(this).children(".show-more-link").css('display') !== 'none') {
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
      }
      else {
        $(".show-more").hide();
      }
    };

    $(window).resize(function() {
      updateShowMore();
    });

    updateShowMore();
  });
});
},{}],8:[function(require,module,exports){
var objectFitImages = require('object-fit-images');

$(function ($) {
  $(document).ready(function(){
    // Polyfill object-fit
    objectFitImages();
  });
}(jQuery));
},{"object-fit-images":1}],9:[function(require,module,exports){
$(function() {
  $(document).ready(function() {
    $('.btn[data-trigger="popper"]').on('click', function () {
      $('.popper').toggleClass('open');
    });
  });
});
},{}]},{},[2]);
