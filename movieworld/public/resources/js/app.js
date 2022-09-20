/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/popup-dialog-widget.js":
/*!*********************************************!*\
  !*** ./resources/js/popup-dialog-widget.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * Popup-Dialog Widget
 */
var popupDialogPrefix = 'popup-dialog-';
jQuery(function ($) {
  $(window).on('resize', function () {
    centerPopupDialogs();
  });
});

function getPopupDialogs() {
  var ids = '';

  for (var i = 0; i < dialogs.length; i++) {
    ids += '#' + popupDialogPrefix + dialogs[i] + (i < dialogs.length - 1 ? ', ' : '');
  }

  return ids;
}

function initPopupDialogs() {
  $(getPopupDialogs()).dialog({
    autoOpen: false,
    draggable: false,
    resizable: false,
    modal: true,
    width: 'auto',
    open: function open() {
      // Remove focus from dialog inputs
      $(this).parent().trigger('focus');
    },
    close: function close() {
      $('.popup-dialog-container').remove();
    }
  });
}

function popupDialog(settings) {
  /* Default options */
  var options = $.extend({
    id: '',
    title: '',
    html: '',
    buttons: []
  }, settings);
  processPopupDialog(options);
}

function processPopupDialog(options) {
  $('#' + popupDialogPrefix + options.id).data(options).html(options.html).dialog('option', {
    'title': options.title,
    'buttons': options.buttons
  });

  if (!$('#' + popupDialogPrefix + options.id).dialog('isOpen')) {
    $('#' + popupDialogPrefix + options.id).dialog('open');
  }
}

function closePopupDialog(id) {
  $('#' + popupDialogPrefix + id).dialog('close');
}

function centerPopupDialogs() {
  $(getPopupDialogs()).dialog('option', 'position', {
    my: 'center',
    at: 'center',
    of: window
  });
}

function resetErrors() {
  $('.popup-dialog-container').find('input, textarea').removeClass('invalid');
  $('.popup-dialog-container .invalid_message').html('');
}

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*****************************************************************************************!*\
  !*** multi ./resources/js/popup-dialog-widget.js ./public/js ./resources/sass/app.scss ***!
  \*****************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /var/www/html/movieworld/resources/js/popup-dialog-widget.js */"./resources/js/popup-dialog-widget.js");
!(function webpackMissingModule() { var e = new Error("Cannot find module '/var/www/html/movieworld/public/js'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
module.exports = __webpack_require__(/*! /var/www/html/movieworld/resources/sass/app.scss */"./resources/sass/app.scss");


/***/ })

/******/ });