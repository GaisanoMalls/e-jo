/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./resources/js/toaster-message.js ***!
  \*****************************************/
document.addEventListener('DOMContentLoaded', function () {
  var toasterMessage = document.getElementById('toasterMessage');
  setTimeout(function () {
    var bsToast = new bootstrap.Toast(toasterMessage);
    bsToast.hide();
  }, 15000);
});
/******/ })()
;