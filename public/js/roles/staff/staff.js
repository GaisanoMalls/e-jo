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
  var btnSaveAssignTicketToAnotherAgent = document.getElementById('btnSaveAssignTicketToAnotherAgent');
  if (checkBoxTransferTicket) {
    checkBoxTransferTicket.addEventListener('change', function (event) {
      if (event.target.checked) {
        selectAssignToAgent.setAttribute('disabled', '');
        btnSaveAssignTicketToAnotherAgent.setAttribute('disabled', '');
        btnSaveTransferTicket.removeAttribute('disabled');
        transferDepartmentSection.style.display = 'block';
        assignToAgentSection.style.display = 'none';
        ticketPrioritySection.style.display = 'none';
      } else {
        selectAssignToAgent.removeAttribute('disabled');
        btnSaveTransferTicket.setAttribute('disabled', '');
        btnSaveAssignTicketToAnotherAgent.removeAttribute('disabled');
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

  // Add Ticket Status
  var statusColorSelect = document.getElementById('statusColorSelect');
  var statusColorHexVal = document.getElementById('statusColorHexVal');
  if (statusColorSelect || statusColorHexVal) {
    statusColorSelect.addEventListener('input', function () {
      statusColorHexVal.value = this.value;
    });
    statusColorHexVal.addEventListener('input', function () {
      statusColorSelect.value = this.value;
    });
  }
});
/******/ })()
;