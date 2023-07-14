/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./resources/js/roles/staff/approver.js ***!
  \**********************************************/
document.addEventListener('DOMContentLoaded', function () {
  var countSelectedChbx = document.querySelector('#countSelectedChbx');
  var ticketCheckAll = document.querySelector('#ticketCheckAll');
  var ticketCheckBoxes = document.querySelectorAll('.ticketCheckBox');
  var ticketActionContainer = document.getElementById('ticketActionContainer');

  // ticketActionContainer.style.visibility = 'hidden';
  var countCheckAll = 0;
  var initialIndividualSelection = false;
  var individualCountCheck;
  function updateCount() {
    individualCountCheck = 0;
    var countIndividual = {};
    ticketCheckBoxes.forEach(function (checkbox) {
      if (checkbox.checked) {
        individualCountCheck++;
        countIndividual[checkbox.value] = (countIndividual[checkbox.value] || 0) + 1;
      }
    });
    countSelectedChbx.textContent = "".concat(individualCountCheck, " selected");
    ticketCheckAll.checked = individualCountCheck === ticketCheckBoxes.length || initialIndividualSelection;
    if (individualCountCheck === 0) {
      countSelectedChbx.textContent = '';
      ticketCheckAll.checked = false;
    }

    // if (individualCountCheck > 0 || ticketCheckAll.checked) {
    //     ticketActionContainer.style.visibility = 'visible';
    // } else {
    //     ticketActionContainer.style.visibility = 'hidden';
    // }
  }

  function selectAllCheckboxes() {
    var isChecked = ticketCheckAll.checked;
    ticketCheckBoxes.forEach(function (checkbox) {
      checkbox.checked = isChecked;
      if (checkbox.checked) {
        countCheckAll++;
      }
    });
    updateCount();
    if (!ticketCheckAll.checked) {
      countCheckAll = '';
      countSelectedChbx.textContent = '';
    } else {
      countSelectedChbx.textContent = "".concat(countCheckAll, " selected");
    }
  }
  function handleCheckboxChange() {
    updateCount();
  }
  ticketCheckAll.addEventListener('change', selectAllCheckboxes);
  ticketCheckBoxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', handleCheckboxChange);
  });
});
/******/ })()
;