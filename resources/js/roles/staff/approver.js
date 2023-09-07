const axios = require('axios').default;


document.addEventListener('DOMContentLoaded', function () {
    const countSelectedChbx = document.querySelector('#countSelectedChbx');
    const ticketCheckAll = document.querySelector('#ticketCheckAll');
    const ticketCheckBoxes = document.querySelectorAll('.ticketCheckBox');
    const ticketActionContainer = document.getElementById('ticketActionContainer');

    // ticketActionContainer.style.visibility = 'hidden';
    let countCheckAll = 0;
    let initialIndividualSelection = false;
    let individualCountCheck;

    function updateCount() {
        individualCountCheck = 0;
        let countIndividual = {};

        ticketCheckBoxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                individualCountCheck++;
                countIndividual[checkbox.value] = (countIndividual[checkbox.value] || 0) + 1;
            }
        });

        countSelectedChbx.textContent = `${individualCountCheck} selected`;
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
        const isChecked = ticketCheckAll.checked;

        ticketCheckBoxes.forEach((checkbox) => {
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
            countSelectedChbx.textContent = `${countCheckAll} selected`;
        }
    }

    function handleCheckboxChange() {
        updateCount();
    }

    // ticketCheckAll.addEventListener('change', selectAllCheckboxes);
    ticketCheckBoxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', handleCheckboxChange);
    });
});

// Mark ticket as Viewed when clicked.
document.addEventListener("DOMContentLoaded", function () {
    const clickableTableRowCells = document.querySelectorAll('.clickable_tr');

    if (clickableTableRowCells) {
        clickableTableRowCells.forEach(tdCells => {
            tdCells.addEventListener('click', function () {
                const ticketId = this.getAttribute('data-ticket-id');
                axios.put(`/approver/tickets/${ticketId}/update-status-as-viewed`)
                    .then((response) => {
                        // console.log(response.data);
                    })
                    .catch((error) => {
                        console.error(error.response.data);
                    });
            });
        });
    }
});


const notificationCard = document.querySelectorAll('.notification__card');

if (notificationCard) {
    notificationCard.forEach(cardCells => {
        cardCells.addEventListener('click', function () {
            const notifId = this.getAttribute('data-notification-id');
            if (notifId) {
                axios.put(`/approver/notifications/${notifId}/read`)
                    .then(() => {
                        const notifTitle = document.querySelector('.notification__message');
                        notifTitle.style.fontWeight = '500';
                    })
                    .catch((error) => {
                        console.error(error.response.data);
                    });
            }

        });
    })
}
