document.addEventListener('DOMContentLoaded', function () {
    const selectAssignToAgent = document.getElementById('selectAssignToAgent');
    const checkBoxTransferTicket = document.querySelector('#checkBoxTransferTicket');
    const transferDepartmentSection = document.getElementById('transferDepartmentSection');
    const assignToAgentSection = document.getElementById('assignToAgentSection');
    const ticketPrioritySection = document.getElementById('ticketPrioritySection');
    const btnSaveTransferTicket = document.getElementById('btnSaveTransferTicket');
    const btnSaveAssignTicketToAnotherAgent = document.getElementById('btnSaveAssignTicketToAnotherAgent');

    if (checkBoxTransferTicket) {
        checkBoxTransferTicket.addEventListener('change', (event) => {
            if (event.target.checked) {
                selectAssignToAgent.setAttribute('disabled', '')
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
    const btnSelectTicket = document.getElementById('btnSelectTicket');
    const ticketCheckbox = document.getElementById('ticketCheckbox');

    if (btnSelectTicket) {
        btnSelectTicket.addEventListener('click', () => {
            btnSelectTicket.classList.toggle('ticket__checked');
            ticketCheckbox.classList.toggle('ticket__checkbox');
        });
    }

    const btnCloseModal = document.getElementById('btnCloseModal');
    const clearModalForm = () => {
        const modalForm = document.getElementById('modalForm');
        modalForm.reset();
    }

    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', clearModalForm);
    }

    // Add Ticket Status
    const statusColorSelect = document.getElementById('statusColorSelect');
    const statusColorHexVal = document.getElementById('statusColorHexVal');

    if (statusColorSelect || statusColorHexVal) {
        statusColorSelect.addEventListener('input', function () {
            statusColorHexVal.value = this.value;
        });

        statusColorHexVal.addEventListener('input', function () {
            statusColorSelect.value = this.value;
        });
    }


    const selectAllBranches = document.querySelector('.select__all__branches');
    const checkboxes = document.querySelectorAll("input[type='checkbox']");
    let checkAll;

    selectAllBranches.addEventListener('click', function () {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = checkAll ? false : true;
            this.textContent = checkAll ? "Select all" : "Unselect";
            this.style.background = checkAll ? "transparent" : "#F2F2F2";
        });
        checkAll = !checkAll;
    });

});
