(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/**
*
* Ripplr is an animation library for Javascript
* that creates a ripple effect.
*
* Functionality is restricted to be used
* on an HTML element that meets the
* following criteria of CSS rules:
*
* - display: inline-block || block;
* - position: relative;
* - overflow: hidden;
*
* The script injects a div inside the clicked
* element and sets it up with styles and
* classes that create the effect, after which
* the element gets removed from the DOM.
*
* @author http://twitter.com/hetenho
*
*/
module.exports = function(opts) {
  this.selector = opts.selector || '.btn';
  this.effectClass = opts.effectClass || 'ripplr';

  attachClickHandlers();

  /*
  * Add click handlers to elements matching the selector.
  */
  function attachClickHandlers() {
    var matches = document.querySelectorAll(this.selector);
    for (var i=0; i < matches.length; i++) {
      var el = matches[i];
      el.addEventListener('click', function(e) {
        handleClick(e, el);
      });
    }
  }


  /**
  * Handle the click event, this is where the magic happens.
  * @param el   The clicked element
  */
  function handleClick(e, el) {
    var offset = getOffset(el),
        div = document.createElement('div'),
        xPos = e.pageX - offset.left,
        yPos = e.pageY - offset.top;

    div.classList.add(this.effectClass);
    div.style.height = el.height;
    div.style.width = el.height;
    div.style.top = e.pageY - offset.top;
    div.style.left = e.pageX - offset.left;
    el.appendChild(div);

    setTimeout(function() {
      div.parentNode.removeChild(div);
    }, 1800);
  }


  /**
  * Returns the clicked elements offset values.
  * @param el   The clicked element
  */
  function getOffset(el) {
    var rect = el.getBoundingClientRect(),
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
  }
};

},{}],2:[function(require,module,exports){
$(function() {

  var Ripplr = require('ripplr');

  Ripplr({
    selector: '.btn',
    effectClass: 'ripplr'
  });

  $('body').on('click', '.dropdown-toggle', function() {
    var $dd = $('#'+$(this).attr('data-toggle'));
    var first = $dd.hasClass('opened') ? 'open' : 'opened';
    var second = $dd.hasClass('opened') ? 'opened' : 'open';

    $dd.toggleClass(first);
    setTimeout(function() {
      $dd.toggleClass(second);
    }, 200);
  });
});

},{"ripplr":1}]},{},[2]);
