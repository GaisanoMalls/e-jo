const axios = require('axios').default;

// * START ------------------------------------------------------------------------------------------------------------------------
// Add Help Topic
const helpTopicServiceDepartmentDropdown = document.getElementById('helpTopicServiceDepartmentDropdown');
const helpTopicTeamsDropdown = document.getElementById('helpTopicTeamsDropdown');
const helpTopicNoTeamsMessage = document.getElementById('helpTopicNoTeamsMessage');
const helpTopicCountTeams = document.getElementById('helpTopicCountTeams');

function helpTopicClearTeamsWhenResetDepartment() {
    helpTopicTeamsDropdown.disable();
    helpTopicNoTeamsMessage.textContent = "";
    helpTopicCountTeams.textContent = "";
}

if (helpTopicServiceDepartmentDropdown || helpTopicTeamsDropdown) {
    helpTopicTeamsDropdown.disable();
    helpTopicServiceDepartmentDropdown.addEventListener('reset', helpTopicClearTeamsWhenResetDepartment);

    helpTopicServiceDepartmentDropdown.addEventListener('change', function () {
        const serviceDepartmentId = this.value;

        if (serviceDepartmentId) {
            axios.get(`/staff/manage/help-topics/assign/service-department/${serviceDepartmentId}/teams`)
                .then((response) => {
                    const serviceDepartments = response.data;
                    const serviceDepartmentsOption = [];

                    if (serviceDepartments && serviceDepartments.length > 0) {
                        serviceDepartments.forEach(function (serviceDepartment) {
                            serviceDepartmentsOption.push({
                                value: serviceDepartment.id,
                                label: serviceDepartment.name,
                            });
                        });

                        helpTopicTeamsDropdown.enable();
                        helpTopicTeamsDropdown.setOptions(serviceDepartmentsOption);
                        helpTopicCountTeams.textContent = `(${serviceDepartmentsOption.length})`;
                        helpTopicNoTeamsMessage.textContent = "";

                    } else {
                        helpTopicTeamsDropdown.reset();
                        helpTopicTeamsDropdown.disable();
                        helpTopicCountTeams.textContent = "";
                        helpTopicNoTeamsMessage.textContent = "No teams assigned on this service department.";
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        } else {
            helpTopicTeamsDropdown.reset();
        }
    });
}

// Start - Edit for help topic
const helpTopicCurrentTeamId = document.getElementById('helpTopicCurrentTeamId');
const helpTopicCurrentServiceDepartmentId = document.getElementById('helpTopicCurrentServiceDepartmentId');
const editHelpTopicCountTeams = document.getElementById('editHelpTopicCountTeams');
const editHelpTopicNoTeamsMessage = document.getElementById('editHelpTopicNoTeamsMessage');
const editHelpTopicServiceDepartmentsDropdown = document.getElementById('editHelpTopicServiceDepartmentsDropdown');
const editHelpTopicTeamsDropdown = document.getElementById('editHelpTopicTeamsDropdown');
const editHelpTopicLevelOfApprovalDropdown = document.getElementById('editHelpTopicLevelOfApprovalDropdown');
const editSelectApproverContainer = document.getElementById('editSelectApproverContainer');
const helpTopicID = document.getElementById('helpTopicID');

if (editHelpTopicServiceDepartmentsDropdown || editHelpTopicTeamsDropdown) {
    editHelpTopicServiceDepartmentsDropdown.addEventListener('reset', function () {
        editHelpTopicTeamsDropdown.disable();
        editHelpTopicTeamsDropdown.reset();
        editHelpTopicCountTeams.textContent = '';
        editHelpTopicNoTeamsMessage.textContent = '';
    })

    if (helpTopicID) {
        const editHelpTopicPath = `/staff/manage/help-topics/${helpTopicID.value}/edit-details`;
        const helpTopicPath = window.location.pathname;

        if (helpTopicPath === editHelpTopicPath) {
            window.onload = function () {
                axios.get(`/staff/manage/help-topics/assign/service-department/${helpTopicCurrentServiceDepartmentId.value}/teams`)
                    .then((response) => {
                        const teams = response.data;
                        const teamsOption = [];

                        if (teams && teams.length > 0) {
                            teams.forEach(function (team) {
                                teamsOption.push({
                                    value: team.id,
                                    label: team.name,
                                });
                            });

                            editHelpTopicTeamsDropdown.enable();
                            editHelpTopicTeamsDropdown.setOptions(teamsOption);
                            editHelpTopicTeamsDropdown.setValue(helpTopicCurrentTeamId.value);
                            editHelpTopicCountTeams.textContent = `(${teams.length})`;
                            editHelpTopicNoTeamsMessage.textContent = '';

                        } else {
                            editHelpTopicTeamsDropdown.reset();
                            editHelpTopicTeamsDropdown.disable();
                            editHelpTopicCountTeams.textContent = '';
                            editHelpTopicNoTeamsMessage.textContent = 'No teams assigned on this service department.';
                        }
                    })
                    .catch((error) => {
                        console.log(error.response.data);
                    });

                // Load current approvers.
                const levelOfApproval = parseInt(editHelpTopicLevelOfApprovalDropdown.value);
                if (levelOfApproval) {
                    for (let i = 1; i <= levelOfApproval; i++) {
                        const html = `
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label form__field__label">
                                        Level ${i} approver/s
                                    </label>
                                    <select select id="editLevel${i}Approver" name="approvers${i}[]" placeholder="Choose an approver" multiple>
                                    </select>
                                </div>
                            </div>`;

                        editSelectApproverContainer.insertAdjacentHTML('beforeend', html);

                        VirtualSelect.init({
                            ele: `#editLevel${i}Approver`,
                            showValueAsTags: true,
                            markSearchResults: true,
                            hasOptionDescription: true
                        });

                        const editLevelOfApproverSelect = document.getElementById(`editLevel${i}Approver`);

                        axios.get('/staff/manage/help-topics/approvers')
                            .then((response) => {
                                const approvers = response.data;
                                const approversOption = [];

                                if (approvers && approvers.length > 0) {
                                    approvers.forEach(function (approver) {
                                        const middleName = `${approver.profile.middle_name ?? ''}`;
                                        const firstLetter = middleName.length > 0 ? middleName[0] + '.' : '';

                                        approversOption.push({
                                            value: approver.id,
                                            label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`,
                                            description: approver.email
                                        });
                                    });

                                    editLevelOfApproverSelect.setOptions(approversOption);

                                    axios.get(`/staff/manage/help-topics/${helpTopicID.value}/level-approvers`)
                                        .then((response) => {
                                            const currentApprovers = response.data;
                                            const selectedCurrentApprovers = [];

                                            // Find the current approvers from the approver's list assigned on this help topic.
                                            approversOption.forEach(function (approver) {
                                                currentApprovers.forEach(function (currentApprover) {
                                                    if (approver.value == currentApprover.id && currentApprover.level == i) {
                                                        // Put the approvers into the array with its level number where they belong.
                                                        selectedCurrentApprovers.push({
                                                            id: currentApprover.id,
                                                            level: currentApprover.level
                                                        });

                                                        editLevelOfApproverSelect.setValue(currentApprover.id);
                                                    }
                                                });
                                            });
                                        })
                                        .catch((error) => {
                                            console.log(error);
                                        });
                                }
                            })
                            .catch((error) => {
                                console.log(error.response.data);
                            });

                        // axios.get(`/staff/manage/help-topics/${helpTopicID.value}/level-approvers`)
                        //     .then((response) => {
                        //         const currentApprovers = response.data;
                        //         console.log(currentApprovers);
                        //     })
                        //     .catch((error) => {
                        //         console.log(error.response.data);
                        //     });
                    }
                }
            }
        } else {
            editHelpTopicServiceDepartmentsDropdown.reset();
            editHelpTopicTeamsDropdown.reset();
        }
    }

    editHelpTopicServiceDepartmentsDropdown.addEventListener('change', function () {
        const serviceDepartmentId = this.value;

        if (serviceDepartmentId) {
            axios.get(`/staff/manage/help-topics/assign/service-department/${serviceDepartmentId}/teams`)
                .then((response) => {
                    const teams = response.data;
                    const teamsOption = [];

                    if (teams && teams.length > 0) {
                        teams.forEach(function (team) {
                            teamsOption.push({
                                value: team.id,
                                label: team.name
                            });
                        });

                        editHelpTopicTeamsDropdown.enable();
                        editHelpTopicTeamsDropdown.setOptions(teamsOption);
                        editHelpTopicCountTeams.textContent = `(${teams.length})`;
                        editHelpTopicNoTeamsMessage.textContent = '';

                    } else {
                        editHelpTopicTeamsDropdown.reset();
                        editHelpTopicTeamsDropdown.disable();
                        editHelpTopicCountTeams.textContent = '';
                        editHelpTopicNoTeamsMessage.textContent = 'No teams assigned on this service department.';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        } else {
            editHelpTopicServiceDepartmentsDropdown.reset();
            editHelpTopicTeamsDropdown.reset();
        }
    });

    if (editHelpTopicLevelOfApprovalDropdown) {
        editHelpTopicLevelOfApprovalDropdown.addEventListener('change', function () {
            const levelOfApproval = parseInt(this.value);
            editSelectApproverContainer.innerHTML = '';

            if (levelOfApproval) {
                for (let i = 1; i <= levelOfApproval; i++) {
                    const html = `
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label form__field__label">
                                Level ${i} approver/s
                            </label>
                            <select select id="editLevel${i}Approver" name="approvers${i}[]" placeholder="Choose an approver" multiple>
                            </select>
                        </div>
                    </div>`;

                    editSelectApproverContainer.insertAdjacentHTML('beforeend', html);

                    VirtualSelect.init({
                        ele: `#editLevel${i}Approver`,
                        showValueAsTags: true,
                        markSearchResults: true,
                        hasOptionDescription: true
                    });

                    const editLevelOfApproverSelect = document.getElementById(`editLevel${i}Approver`);

                    axios.get('/staff/manage/help-topics/approvers')
                        .then((response) => {
                            const approvers = response.data;
                            const editApproversOption = [];

                            if (approvers && approvers.length > 0) {
                                approvers.forEach(function (approver) {
                                    const middleName = `${approver.profile.middle_name ?? ''}`;
                                    const firstLetter = middleName.length > 0 ? middleName[0] + '.' : '';

                                    editApproversOption.push({
                                        value: approver.id,
                                        label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`,
                                        description: approver.email
                                    });
                                });

                                editLevelOfApproverSelect.setOptions(editApproversOption);
                            }
                        })
                        .catch((error) => {
                            console.log(error.response.data);
                        });
                }
            }
        });
    }
}

// End - Edit for help topic
// * END ------------------------------------------------------------------------------------------------------------------------

// Assign approvers for help topic
const levelOfApproverDropdown = document.getElementById('levelOfApproverDropdown');
const selectApproverContainer = document.getElementById('selectApproverContainer');

if (levelOfApproverDropdown) {
    levelOfApproverDropdown.addEventListener('change', function () {
        const levelOfApproval = parseInt(this.value);
        selectApproverContainer.innerHTML = '';

        if (levelOfApproval) {
            for (let i = 1; i <= levelOfApproval; i++) {
                const html = `
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label form__field__label">
                                Level ${i} approver/s
                            </label>
                            <select select id="level${i}Approver" name="approvers${i}[]" placeholder="Choose an approver" multiple>
                            </select>
                        </div>
                    </div>`;

                selectApproverContainer.insertAdjacentHTML('beforeend', html);

                VirtualSelect.init({
                    ele: `#level${i}Approver`,
                    showValueAsTags: true,
                    markSearchResults: true,
                    hasOptionDescription: true
                });

                const levelOfApproverSelect = document.getElementById(`level${i}Approver`);

                axios.get('/staff/manage/help-topics/approvers')
                    .then((response) => {
                        const approvers = response.data;
                        const approversOption = [];

                        if (approvers && approvers.length > 0) {
                            approvers.forEach(function (approver) {
                                const middleName = `${approver.profile.middle_name ?? ''}`;
                                const firstLetter = middleName.length > 0 ? middleName[0] + '.' : '';

                                approversOption.push({
                                    value: approver.id,
                                    label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`,
                                    description: approver.email
                                });
                            });

                            levelOfApproverSelect.setOptions(approversOption);
                        }
                    })
                    .catch((error) => {
                        console.log(error.response.data);
                    });
            }
        }
    });
}

// ------------------------------------------------------------------------------------------------------------------------
