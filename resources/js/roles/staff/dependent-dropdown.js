const axios = require('axios').default;

// ------------------------------------------------------------------------------------------------------------------------
// Create Approver - Assign a Branch and BU Department
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

// Edit Approver - Edit Branch and BU Department
const editApproverBranchDropdown = document.getElementById('editApproverBranchDropdown');
const editApproverBUDepartmentDropdown = document.getElementById('editApproverBUDepartmentDropdown');
const currentBranchId = document.getElementById('currentBranchId');
const currentDepartmentId = document.getElementById('currentDepartmentId');

window.onload = function () {
    axios.get(`/staff/manage/user-accounts/approver/edit/${currentBranchId.value}/departments`)
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

                editApproverBUDepartmentDropdown.enable();
                editApproverBUDepartmentDropdown.setOptions(departmentsOption);
                editApproverBUDepartmentDropdown.setValue(currentDepartmentId.value);
                approverCountBUDepartments.textContent = `(${departments.length})`;
                approverNoBUDepartmentMessage.textContent = '';

            } else {
                editApproverBUDepartmentDropdown.reset();
                editApproverBUDepartmentDropdown.disable();
                approverCountBUDepartments.textContent = ``;
                approverNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
            }
        })
}

if (editApproverBranchDropdown || editApproverBUDepartmentDropdown) {
    editApproverBranchDropdown.addEventListener('change', function () {
        const branchId = this.value;

        editApproverBranchDropdown.addEventListener('reset', function () {
            editApproverBUDepartmentDropdown.disable();
            approverNoBUDepartmentMessage.textContent = "";
            approverCountBUDepartments.textContent = "";
        });

        if (branchId) {
            axios.get(`/staff/manage/user-accounts/approver/edit/${branchId}/departments`)
                .then((response) => {
                    const departments = response.data;
                    const departmentsOption = [];

                    if (departments && departments.length > 0) {
                        departments.forEach(function (department) {
                            departmentsOption.push({
                                value: department.id,
                                label: department.name,
                            });
                        });

                        editApproverBUDepartmentDropdown.enable();
                        editApproverBUDepartmentDropdown.setValue(4)
                        editApproverBUDepartmentDropdown.setOptions(departmentsOption);
                        approverCountBUDepartments.textContent = `(${departments.length})`;
                        approverNoBUDepartmentMessage.textContent = '';

                    } else {
                        editApproverBUDepartmentDropdown.reset();
                        editApproverBUDepartmentDropdown.disable();
                        approverCountBUDepartments.textContent = ``;
                        approverNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        } else {
            editApproverBUDepartmentDropdown.reset();
        }
    })
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
            axios.get(`/staff/manage/user-accounts/service-department-admin/${branchId}/bu-departments`)
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
// Team - Assign Branch
const teamsDropdown = document.getElementById('teamsDropdown');
const serviceDepartmentFieldContainer = document.getElementById('serviceDepartmentFieldContainer');
const serviceDepartmentField = document.getElementById('serviceDepartmentField');

if (serviceDepartmentFieldContainer || teamsDropdown) {
    serviceDepartmentFieldContainer.style.display = 'none';
}

if (teamsDropdown) {
    teamsDropdown.addEventListener('change', function () {
        const teamId = this.value;

        if (teamId) {
            axios.get(`/staff/manage/team/assign-branch/${teamId}/service-department`)
                .then((response) => {
                    const department = response.data;

                    serviceDepartmentField.value = department.name;
                    serviceDepartmentFieldContainer.style.display = 'block';
                })
                .catch((error) => {
                    console.log(error);
                });
        } else {
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
                .catch((error) => {
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
                                Level ${i} approver/s
                            </label>
                            <select select id="level${i}Approver" name="approvers[${i}][]" placeholder="Choose an approver" multiple>
                            </select>
                        </div>
                    </div>
                `;

                selectApproverContainer.insertAdjacentHTML('beforeend', html);

                VirtualSelect.init({
                    ele: `#level${i}Approver`,
                    showValueAsTags: true,
                    markSearchResults: true,
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
                                    label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`
                                });
                            });

                            levelOfApproverSelect.setOptions(approversOption);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        }
    });
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
