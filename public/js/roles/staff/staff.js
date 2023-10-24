/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************************!*\
  !*** ./resources/js/roles/staff/staff.js ***!
  \*******************************************/
document.addEventListener('DOMContentLoaded', function () {
  var selectAssignToAgent = document.getElementById('selectAssignToAgent');
  var checkBoxTransferTicket = document.querySelector('#checkBoxTransferTicket');
  var transferDepartmentSection = document.getElementById('transferDepartmentSection');
  var assignToAgentSection = document.getElementById('assignToAgentSection');
  var ticketPrioritySection = document.getElementById('ticketPrioritySection');
  var btnSaveTransferTicket = document.getElementById('btnSaveTransferTicket');
  if (checkBoxTransferTicket) {
    checkBoxTransferTicket.addEventListener('change', function (event) {
      if (event.target.checked) {
        selectAssignToAgent.setAttribute('disabled', '');
        btnSaveTransferTicket.removeAttribute('disabled');
        transferDepartmentSection.style.display = 'block';
        assignToAgentSection.style.display = 'none';
        ticketPrioritySection.style.display = 'none';
      } else {
        selectAssignToAgent.removeAttribute('disabled');
        btnSaveTransferTicket.setAttribute('disabled', '');
        transferDepartmentSection.style.display = 'none';
        assignToAgentSection.style.display = 'block';
        ticketPrioritySection.style.display = 'block';
      }
    });
  }

  // Select ticket with
  var btnSelectTicket = document.getElementById('btnSelectTicket');
  var ticketCheckbox = document.getElementById('ticketCheckbox');
  if (btnSelectTicket) {
    btnSelectTicket.addEventListener('click', function () {
      btnSelectTicket.classList.toggle('ticket__checked');
      ticketCheckbox.classList.toggle('ticket__checkbox');
    });
  }
  var btnCloseModal = document.getElementById('btnCloseModal');
  var clearModalForm = function clearModalForm() {
    var modalForm = document.getElementById('modalForm');
    modalForm.reset();
  };
  if (btnCloseModal) {
    btnCloseModal.addEventListener('click', clearModalForm);
  }

  // // Helpt Topic - Special Project
  // const specialProjectContainer = document.getElementById('specialProjectContainer');
  // const specialProjectCheck = document.getElementById('specialProjectCheck');
  // const helpTopicName = document.getElementById('helpTopicName');

  // if (specialProjectCheck && helpTopicName) {
  //     specialProjectContainer.style.display = 'none';
  //     specialProjectCheck.addEventListener('change', (event) => {
  //         if (event.target.checked) {
  //             specialProjectContainer.style.display = 'block';
  //             helpTopicName.value = "Special Project";
  //         } else {
  //             specialProjectContainer.style.display = 'none';
  //             helpTopicName.value = null;
  //         }
  //     });
  // }
});
/******/ })()
;