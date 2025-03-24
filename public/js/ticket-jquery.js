/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/js/ticket-jquery.js ***!
  \***************************************/
document.addEventListener("DOMContentLoaded", function () {
  // Toggle sidebar and header
  var toggleMenuButton = document.getElementById('toggle__more__menu');
  var sidebar = document.querySelector('.sidebar');
  var header = document.getElementById('page__main__header');
  toggleMenuButton.addEventListener('click', function () {
    sidebar.classList.toggle('close');
    header.classList.toggle('close');
  });

  // Clear modal input fields when modal is closed
  var modalForm = document.getElementById('modalForm');
  if (modalForm) {
    modalForm.addEventListener('hidden.bs.modal', function () {
      var form = modalForm.querySelector('form');
      if (form) {
        form.reset();
      }
    });
  }
});
/******/ })()
;