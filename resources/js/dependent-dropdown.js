const axios = require('axios').default;

// ------------------------------------------------------------------------------------------------------------------------
// Approver - Assign a Branch and BU Department
const approverBranchDropdown = document.getElementById('approverBranchDropdown');
const approverBUDepartmentDropdown = document.getElementById('approverBUDepartmentDropdown');
const approverNoBUDepartmentMessage = document.getElementById('approverNoBUDepartmentMessage');
const approverCountBUDepartments = document.getElementById('approverCountBUDepartments');

function approverClearDepartmentsWhenResetBranch() {
    approverBUDepartmentDropdown.disable();
    approverNoBUDepartmentMessage.textContent = "";
    approverCountBUDepartments.textContent = "";
}

if (approverBranchDropdown || approverBUDepartmentDropdown) {
    approverBUDepartmentDropdown.disable();

    approverBranchDropdown.addEventListener('reset', approverClearDepartmentsWhenResetBranch);

    approverBranchDropdown.addEventListener('change', function () {
        const branchId = this.value;

        if (branchId) {
            axios.get(`/staff/manage/user-accounts/approver/${branchId}/departments`)
                .then((response) => {
                    const departments = response.data;
                    const departmentsOption = [];

                    if (departments && departments.length > 0) {
                        departments.forEach(function (department) {
                            departmentsOption.push({
                                value: department.id,
                                label: department.name
                            });
                        });

                        approverBUDepartmentDropdown.enable();
                        approverBUDepartmentDropdown.setOptions(departmentsOption);
                        approverCountBUDepartments.textContent = `(${departments.length})`;
                        approverNoBUDepartmentMessage.textContent = '';

                    } else {
                        approverBUDepartmentDropdown.reset();
                        approverBUDepartmentDropdown.disable();
                        approverCountBUDepartments.textContent = ``;
                        approverNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        } else {
            approverBUDepartmentDropdown.reset();
        }
    });
}

// ------------------------------------------------------------------------------------------------------------------------
// Department Admin - Assign a Branch, BU/Department, and Service Department
const deptAdminBranchesDropdown = document.getElementById('deptAdminBranchesDropdown');
const deptAdminBUDepartmentsDropdown = document.getElementById('deptAdminBUDepartmentsDropdown');
const deptAdminNoBUDepartmentsMessage = document.getElementById('deptAdminNoBUDepartmentsMessage');
const deptAdminCountBUDepartments = document.getElementById('deptAdminCountBUDepartments');

function departmentAdminClearBUAndServiceDeptWhenResetBranch() {
    deptAdminBUDepartmentsDropdown.disable();
    deptAdminNoBUDepartmentsMessage.textContent = '';
    deptAdminCountBUDepartments.textContent = '';
}

if (deptAdminBranchesDropdown || deptAdminBUDepartmentsDropdown) {
    deptAdminBUDepartmentsDropdown.disable();

    deptAdminBranchesDropdown.addEventListener('reset', departmentAdminClearBUAndServiceDeptWhenResetBranch);

    deptAdminBranchesDropdown.addEventListener('change', function () {
        const branchId = this.value;

        if (branchId) {
            // Get the BU/Departments from based on the branch.
            axios.get(`/staff/manage/user-accounts/department-admin/${branchId}/bu-departments`)
                .then((response) => {
                    const buDepartments = response.data;
                    const buDepartmentsOption = []

                    if (buDepartments && buDepartments.length > 0) {
                        buDepartments.forEach(function (buDepartment) {
                            buDepartmentsOption.push({
                                value: buDepartment.id,
                                label: buDepartment.name
                            });
                        });

                        deptAdminBUDepartmentsDropdown.enable();
                        deptAdminBUDepartmentsDropdown.setOptions(buDepartmentsOption);
                        deptAdminCountBUDepartments.textContent = `(${buDepartments.length})`;
                        deptAdminNoBUDepartmentsMessage.textContent = '';

                    } else {
                        deptAdminBUDepartmentsDropdown.reset();
                        deptAdminBUDepartmentsDropdown.disable();
                        deptAdminCountBUDepartments.textContent = '';
                        deptAdminNoBUDepartmentsMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        } else {
            deptAdminBUDepartmentsDropdown.reset();
        }
    });
}

// ------------------------------------------------------------------------------------------------------------------------
// Agent - Assign a Branch, BU/Department, Service Department, and Team
const agentBranchesDropdown = document.getElementById('agentBranchesDropdown');

const agentBUDepartmentsDropdown = document.getElementById('agentBUDepartmentsDropdown');
const agentNoBUDepartmentsMessage = document.getElementById('agentNoBUDepartmentsMessage');
const agentCountBUDepartment = document.getElementById('agentCountBUDepartment');

const agentTeamsDropdown = document.getElementById('agentTeamsDropdown');
const agentNoTeamMessage = document.getElementById('agentNoTeamMessage');
const agentCountTeams = document.getElementById('agentCountTeams');

function clearBUDeptServiceDeptAndTeamWhenResetBranch() {
    agentBUDepartmentsDropdown.disable();
    agentNoBUDepartmentsMessage.textContent = '';
    agentCountBUDepartment.textContent = '';
    agentNoTeamMessage.textContent = '';
    agentCountTeams.textContent = '';
}

if (agentBranchesDropdown || agentBUDepartmentsDropdown || agentTeamsDropdown) {
    agentBUDepartmentsDropdown.disable();
    agentTeamsDropdown.disable();

    agentBranchesDropdown.addEventListener('reset', clearBUDeptServiceDeptAndTeamWhenResetBranch);

    agentBranchesDropdown.addEventListener('change', function () {
        const branchId = this.value;

        if (branchId) {
            axios.get(`/staff/manage/user-accounts/agent/${branchId}/bu-departments`)
                .then((response) => {
                    const buDepartments = response.data;
                    const buDepartmentsOption = [];

                    if (buDepartments && buDepartments.length > 0) {
                        buDepartments.forEach(function (buDepartment) {
                            buDepartmentsOption.push({
                                value: buDepartment.id,
                                label: buDepartment.name
                            });
                        });

                        agentBUDepartmentsDropdown.enable();
                        agentBUDepartmentsDropdown.setOptions(buDepartmentsOption);
                        agentCountBUDepartment.textContent = `(${buDepartments.length})`;
                        agentNoBUDepartmentsMessage.textContent = '';

                    } else {
                        agentBUDepartmentsDropdown.reset();
                        agentBUDepartmentsDropdown.disable();
                        agentCountBUDepartment.textContent = ``;
                        agentNoBUDepartmentsMessage.textContent = 'No BU/departments assigned on this branch';
                    }
                })
                .catch((error) => {
                    console.log(error);
                });

            axios.get(`/staff/manage/user-accounts/agent/${branchId}/teams`)
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

                        agentTeamsDropdown.enable();
                        agentTeamsDropdown.setOptions(teamsOption);
                        agentCountTeams.textContent = `(${teams.length})`;
                        agentNoTeamMessage.textContent = '';

                    } else {
                        agentTeamsDropdown.reset();
                        agentTeamsDropdown.disable();
                        agentCountTeams.textContent = ``;
                        agentNoTeamMessage.textContent = 'No teams assigned on this branch.';
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        } else {
            agentBUDepartmentsDropdown.reset();
            agentServiceDepartmentsDropdown.reset();
            agentTeamsDropdown.reset();
            agentTeamsDropdown.reset();
        }
    });
}

// ------------------------------------------------------------------------------------------------------------------------
// User/Requester - Assign BU/Department and Branch
const userDepartmentsDropdown = document.getElementById('userDepartmentsDropdown');
const userBranchesDropdown = document.getElementById('userBranchesDropdown');
const userNoBranchMessage = document.getElementById('userNoBranchMessage');
const userCountBranches = document.getElementById('userCountBranches');

function userClearBranchWhenResetDepartment() {
    userBranchesDropdown.disable();
    userNoBranchMessage.textContent = "";
    userCountBranches.textContent = "";
}

if (userDepartmentsDropdown || userBranchesDropdown) {
    userBranchesDropdown.disable();
    userDepartmentsDropdown.addEventListener('reset', userClearBranchWhenResetDepartment);

    userDepartmentsDropdown.addEventListener('change', function () {
        const departmentId = this.value;

        if (departmentId) {
            axios.get(`/staff/manage/user-accounts/user/assign/department/${departmentId}/branches`)
                .then((response) => {
                    const branches = response.data;
                    const branchesOption = [];

                    if (branches && branches.length > 0) {
                        branches.forEach(function (branch) {
                            branchesOption.push({
                                value: branch.id,
                                label: branch.name
                            });
                        });

                        userBranchesDropdown.enable();
                        userBranchesDropdown.setOptions(branchesOption);
                        userCountBranches.textContent = `(${branchesOption.length})`;
                        userNoBranchMessage.textContent = "";
                    } else {
                        userBranchesDropdown.reset();
                        userBranchesDropdown.disable();
                        userCountBranches.textContent = "";
                        userNoBranchMessage.textContent = "(No branch)";
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        } else {
            userBranchesDropdown.reset();
        }
    });
}

// ------------------------------------------------------------------------------------------------------------------------
// Service Department - Assign Branch and BU/Department
const serviceDepartmentBranchesDropdown = document.getElementById('serviceDepartmentBranchesDropdown');
const serviceDepartmentBranchBUDepartmentsDropdown = document.getElementById('serviceDepartmentBranchBUDepartmentsDropdown');
const serviceDepartmentBranchCountBranchDepartments = document.getElementById('serviceDepartmentBranchCountBranchDepartments');
const serviceDepartmentBranchNoDepartmentsMessage = document.getElementById('serviceDepartmentBranchNoDepartmentsMessage');

if (serviceDepartmentBranchesDropdown) {
    serviceDepartmentBranchBUDepartmentsDropdown.disable();

    serviceDepartmentBranchesDropdown.addEventListener('reset', function () {
        serviceDepartmentBranchCountBranchDepartments.textContent = '';
        serviceDepartmentBranchNoDepartmentsMessage.textContent = '';
        serviceDepartmentBranchBUDepartmentsDropdown.reset();
        serviceDepartmentBranchBUDepartmentsDropdown.disable();
    });

    serviceDepartmentBranchesDropdown.addEventListener('change', function () {
        const branchId = this.value;

        if (branchId) {
            axios.get(`/staff/manage/service-department/assign-branch/${branchId}/bu-departments`)
                .then((response) => {
                    const buDepartments = response.data;
                    const buDepartmentsOption = [];

                    if (buDepartments && buDepartments.length > 0) {
                        buDepartments.forEach(function (buDepartment) {
                            buDepartmentsOption.push({
                                value: buDepartment.id,
                                label: buDepartment.name
                            });
                        });

                        serviceDepartmentBranchBUDepartmentsDropdown.enable();
                        serviceDepartmentBranchBUDepartmentsDropdown.setOptions(buDepartmentsOption);
                        serviceDepartmentBranchCountBranchDepartments.textContent = `(${buDepartments.length})`;
                        serviceDepartmentBranchNoDepartmentsMessage.textContent = '';

                    } else {
                        serviceDepartmentBranchBUDepartmentsDropdown.reset();
                        serviceDepartmentBranchBUDepartmentsDropdown.disable();
                        serviceDepartmentBranchCountBranchDepartments.textContent = '';
                        serviceDepartmentBranchNoDepartmentsMessage.textContent = 'No bu/department assigned on the selected branch.';
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        } else {
            serviceDepartmentBranchBUDepartmentsDropdown.reset();
        }
    });
}

// ------------------------------------------------------------------------------------------------------------------------
// Team - Assign Branch
const teamsDropdown = document.getElementById('teamsDropdown');
const serviceDepartmentFieldContainer = document.getElementById('serviceDepartmentFieldContainer');
const serviceDepartmentField = document.getElementById('serviceDepartmentField');
const branchDropdown = document.getElementById('branchDropdown');
const noBranchMessage = document.getElementById('noBranchMessage');
const countBranches = document.getElementById('countBranches');

function clearBranchWhenResetTeam() {
    branchDropdown.disable();
    noBranchMessage.textContent = "";
    countBranches.textContent = "";
}

if (branchDropdown || serviceDepartmentFieldContainer || teamsDropdown) {
    branchDropdown.disable();
    serviceDepartmentFieldContainer.style.display = 'none';
    teamsDropdown.addEventListener('reset', clearBranchWhenResetTeam)
}

if (branchDropdown || teamsDropdown) {
    teamsDropdown.addEventListener('change', function () {
        const teamId = this.value;

        if (teamId) {
            axios.get(`/staff/manage/team/assign-branch/${teamId}/service-department`)
                .then((response) => {
                    const department = response.data;

                    serviceDepartmentField.value = department.name;
                    serviceDepartmentFieldContainer.style.display = 'block';

                    axios.get(`/staff/manage/team/assign-branch/department/${department.id}/branches`)
                        .then((response) => {
                            const branches = response.data;
                            const branchesOption = [];

                            if (branches && branches.length > 0) {
                                branches.forEach(function (branch) {
                                    branchesOption.push({
                                        value: branch.id,
                                        label: branch.name
                                    });
                                });

                                branchDropdown.enable();
                                branchDropdown.setOptions(branchesOption);
                                countBranches.textContent = `(${branchesOption.length})`;
                                noBranchMessage.textContent = "";

                            } else {
                                branchDropdown.reset();
                                branchDropdown.disable();
                                countBranches.textContent = "";
                                noBranchMessage.textContent = "No branch assigned on this service department.";
                            }
                        })
                        .catch((error) => {
                            console.log(error.response.data);
                        });
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        } else {
            branchDropdown.reset();
            serviceDepartmentField.value = "";
            serviceDepartmentFieldContainer.style.display = 'none';
        }
    });
}


// ------------------------------------------------------------------------------------------------------------------------
// Help Topic Section
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
                .then((error) => {
                    console.log(error.response.data);
                });
        } else {
            helpTopicTeamsDropdown.reset();
        }
    });
}

// Assign approvers for help topic
const levelOfApproverDropdown = document.getElementById('levelOfApproverDropdown');
const selectApproverContainer = document.getElementById('selectApproverContainer');
const tagifySample = document.getElementById('tagifySample');

if (levelOfApproverDropdown) {
    levelOfApproverDropdown.addEventListener('change', function () {
        const approverNumber = parseInt(this.value);
        selectApproverContainer.innerHTML = '';

        if (approverNumber) {
            for (let i = 1; i <= approverNumber; i++) {
                const html = `
                    <div class="col-md-12">
                        <div class="mb-2">
                            <label class="form-label form__field__label">
                                Level ${i} approver
                            </label>
                            <select select id="level${i}Approver" name="approver" placeholder="Choose an approver" multiple>
                                <option selected>Sam Sabellano</option>
                                <option selected>Onnie Bunny</option>
                                <option selected>John Doe</option>
                            </select>
                        </div>
                    </div>
                `;

                selectApproverContainer.insertAdjacentHTML('beforeend', html);

                VirtualSelect.init({
                    ele: `#level${i}Approver`,
                    showValueAsTags: true,
                });
            }
        }
    });
}


// ------------------------------------------------------------------------------------------------------------------------
// User/Requester Page (For Ticket Creation)
const authUserId = document.getElementById('secret'); // This is the authenticated user id
const userCreateTicketServiceDepartmentDropdown = document.getElementById('userCreateTicketServiceDepartmentDropdown');
const userCreateTicketHelpTopicDropdown = document.getElementById('userCreateTicketHelpTopicDropdown');
const userCreateTicketNoHelpTopicMessage = document.getElementById('userCreateTicketNoHelpTopicMessage');
const userCreateTicketHelpTopicCount = document.getElementById('userCreateTicketHelpTopicCount');
const helpTopicTeam = document.getElementById('helpTopicTeam');

function userCreateTicketClearHelpTopicWhenResetDepartment() {
    userCreateTicketHelpTopicDropdown.disable();
    userCreateTicketNoHelpTopicMessage.textContent = '';
    userCreateTicketHelpTopicCount.textContent = '';
}

// Load the deparments based on authenticated user's branch.
window.onload = function () {
    if (authUserId) {
        axios.get(`/user/ticket/${authUserId.value}/service-departments`)
            .then((response) => {
                const serviceDepartments = response.data;
                const serviceDepartmentsOption = []

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
}

if (userCreateTicketServiceDepartmentDropdown) {
    userCreateTicketHelpTopicDropdown.disable();
    userCreateTicketServiceDepartmentDropdown.addEventListener('reset', userCreateTicketClearHelpTopicWhenResetDepartment);

    userCreateTicketServiceDepartmentDropdown.addEventListener('change', function () {
        const servideDepartmentId = this.value;

        if (servideDepartmentId) {
            axios.get(`/user/ticket/${servideDepartmentId}/help-topics`)
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
            console.log("checked");
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

// ------------------------------------------------------------------------------------------------------------------------
// View Ticket Section (Ticket Action)
const transferTicketDepartmentsDropdown = document.getElementById('transferTicketDepartmentsDropdown');
const transferTicketTeamsDropdown = document.getElementById('transferTicketTeamsDropdown');
const transferTicketNoTeamsMessage = document.getElementById('transferTicketNoTeamsMessage');
const transferTicketCountTeams = document.getElementById('transferTicketCountTeams');

function transFerTicketClearTeamsWhenResetDepartment() {
    transferTicketTeamsDropdown.disable();
    transferTicketNoTeamsMessage.textContent = '';
    transferTicketCountTeams.textContent = '';
}

if (transferTicketDepartmentsDropdown || transferTicketTeamsDropdown) {
    transferTicketDepartmentsDropdown.addEventListener('reset', transFerTicketClearTeamsWhenResetDepartment)

    transferTicketDepartmentsDropdown.addEventListener('change', function () {
        const departmentId = this.value;

        if (departmentId) {
            axios.get(`/staff/tickets/${departmentId}/teams`)
                .then((response) => {
                    const teams = response.data;
                    const serviceDepartmentsOption = [];

                    if (teams && teams.length > 0) {
                        teams.forEach(function (team) {
                            serviceDepartmentsOption.push({
                                value: team.id,
                                label: team.name
                            });
                        });

                        transferTicketTeamsDropdown.enable();
                        transferTicketTeamsDropdown.setOptions(serviceDepartmentsOption);
                        transferTicketCountTeams.textContent = `(${serviceDepartmentsOption.length})`;
                        transferTicketNoTeamsMessage.textContent = "";

                    } else {
                        transferTicketTeamsDropdown.reset();
                        transferTicketTeamsDropdown.disable();
                        transferTicketCountTeams.textContent = "";
                        transferTicketNoTeamsMessage.textContent = "(No teams)";
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        } else {
            transferTicketTeamsDropdown.reset();
        }
    });
}
