const axios = require('axios').default;

const uploadNewPhoto = document.getElementById('uploadNewPhoto');
const uploadedNewPhoto = document.getElementById('uploadedNewPhoto');
const inputFileErrorMsg = document.getElementById('inputFileErrorMsg');

if (uploadNewPhoto) {
    uploadNewPhoto.addEventListener('change', (e) => {
        const file = e.target.files[0];
        const fReader = new FileReader();

        fReader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;

            img.addEventListener('load', () => {
                uploadedNewPhoto.src = img.src;
                inputFileErrorMsg.textContent = "";
                inputFileErrorMsg.style.display = "none";
            });

            img.addEventListener('error', () => {
                uploadedNewPhoto.src = "";
                inputFileErrorMsg.style.display = "block";
                inputFileErrorMsg.textContent = "File type is not accepted. Please select an image file.";
            });
        };

        if (file) {
            fReader.readAsDataURL(file);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const toasterMessage = document.getElementById('toasterMessage');

    setTimeout(() => {
        var bsToast = new bootstrap.Toast(toasterMessage);
        bsToast.hide();
    }, 8000);
});

// ------------------------------------------------------------------------------------------------------------------------
// User/Requester Page (For Ticket Creation)
const userCreateTicketServiceDepartmentDropdown = document.getElementById('userCreateTicketServiceDepartmentDropdown');
const userCreateTicketHelpTopicDropdown = document.getElementById('userCreateTicketHelpTopicDropdown');
const userCreateTicketNoHelpTopicMessage = document.getElementById('userCreateTicketNoHelpTopicMessage');
const userCreateTicketHelpTopicCount = document.getElementById('userCreateTicketHelpTopicCount');
const helpTopicTeam = document.getElementById('helpTopicTeam');
const helpTopicSLA = document.getElementById('helpTopicSLA');

function userCreateTicketClearHelpTopicWhenResetDepartment() {
    userCreateTicketHelpTopicDropdown.disable();
    userCreateTicketNoHelpTopicMessage.textContent = '';
    userCreateTicketHelpTopicCount.textContent = '';
    helpTopicTeam.value = '';
    helpTopicSLA.value = '';
}

// Load the deparments based on authenticated user's branch.
window.onload = function () {
    axios.get(`/ticket/service-departments`)
        .then((response) => {
            const serviceDepartments = response.data;
            const serviceDepartmentsOption = [];

            if (serviceDepartments && serviceDepartments.length > 0) {
                serviceDepartments.forEach(function (serviceDepartment) {
                    serviceDepartmentsOption.push({
                        value: serviceDepartment.id,
                        label: serviceDepartment.name
                    });
                });

                userCreateTicketServiceDepartmentDropdown.setOptions(serviceDepartmentsOption);
            }
        })
        .catch((error) => {
            console.log(error)
        });
}

if (userCreateTicketServiceDepartmentDropdown) {
    userCreateTicketHelpTopicDropdown.disable();
    userCreateTicketServiceDepartmentDropdown.addEventListener('reset', userCreateTicketClearHelpTopicWhenResetDepartment);
    userCreateTicketHelpTopicDropdown.addEventListener('reset', function () {
        helpTopicTeam.value = '';
        helpTopicSLA.value = '';
    });

    userCreateTicketServiceDepartmentDropdown.addEventListener('change', function () {
        const servideDepartmentId = this.value;

        if (servideDepartmentId) {
            axios.get(`/ticket/${servideDepartmentId}/help-topics`)
                .then((response) => {
                    const helpTopics = response.data;
                    const helpTopicsOption = [];

                    if (helpTopics && helpTopics.length > 0) {
                        helpTopics.forEach(function (helpTopic) {
                            helpTopicsOption.push({
                                value: helpTopic.id,
                                label: helpTopic.name
                            });
                        });

                        userCreateTicketHelpTopicDropdown.enable();
                        userCreateTicketHelpTopicDropdown.setOptions(helpTopicsOption);
                        userCreateTicketHelpTopicCount.textContent = `(${helpTopicsOption.length})`;
                        userCreateTicketNoHelpTopicMessage.textContent = '';

                    } else {
                        userCreateTicketHelpTopicDropdown.reset();
                        userCreateTicketHelpTopicDropdown.disable();
                        userCreateTicketHelpTopicCount.textContent = '';
                        userCreateTicketNoHelpTopicMessage.textContent = 'No help topics assigned on the selected service department.';
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        } else {
            userCreateTicketHelpTopicDropdown.reset();
        }
    });

    userCreateTicketHelpTopicDropdown.addEventListener('change', function () {
        const helpTopicId = this.value;

        if (helpTopicId) {
            axios.get(`/user/ticket/${helpTopicId}/team`)
                .then((response) => {
                    const team = response.data;
                    helpTopicTeam.value = team.id;
                })
                .catch((error) => {
                    console.log(error);
                });

            axios.get(`/user/ticket/${helpTopicId}/sla`)
                .then((response) => {
                    const sla = response.data;
                    helpTopicSLA.value = sla.id;
                })
                .catch((error) => {
                    console.log(error);
                })
        }
    });
}

const checkOtherBranch = document.getElementById('checkOtherBranch');
const userCreateTicketBranchSelectionContainer = document.getElementById('userCreateTicketBranchSelectionContainer');
const userCreateTicketBranchesDropdown = document.getElementById('userCreateTicketBranchesDropdown');

if (userCreateTicketBranchesDropdown || userCreateTicketBranchSelectionContainer || checkOtherBranch) {
    userCreateTicketBranchesDropdown.disable();
    userCreateTicketBranchSelectionContainer.style.display = 'none';

    if (checkOtherBranch) {
        checkOtherBranch.addEventListener('change', (e) => {
            if (e.target.checked) {
                userCreateTicketBranchSelectionContainer.style.display = 'block';
                userCreateTicketBranchesDropdown.enable();

                axios.get(`/user/ticket/branches`)
                    .then((response) => {
                        const branches = response.data;
                        const branchesOptions = [];

                        if (branches && branches.length > 0) {
                            branches.forEach(function (branch) {
                                branchesOptions.push({
                                    value: branch.id,
                                    label: branch.name
                                });
                            });

                            userCreateTicketBranchesDropdown.enable();
                            userCreateTicketBranchesDropdown.setOptions(branchesOptions);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                userCreateTicketBranchSelectionContainer.style.display = 'none';
                userCreateTicketBranchesDropdown.reset();
                userCreateTicketBranchesDropdown.disable();
            }
        });
    }
}
