document.addEventListener('DOMContentLoaded', function () {
    const selectAssignToAgent = document.getElementById('selectAssignToAgent');
    const checkBoxTransferTicket = document.querySelector('#checkBoxTransferTicket');
    const transferDepartmentSection = document.getElementById('transferDepartmentSection');
    const assignToAgentSection = document.getElementById('assignToAgentSection');
    const ticketPrioritySection = document.getElementById('ticketPrioritySection');
    const btnSaveTransferTicket = document.getElementById('btnSaveTransferTicket');

    if (checkBoxTransferTicket) {
        checkBoxTransferTicket.addEventListener('change', (event) => {
            if (event.target.checked) {
                selectAssignToAgent.setAttribute('disabled', '')
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
});

// Enable bootstrap tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))