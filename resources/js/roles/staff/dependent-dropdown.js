const axios = require('axios').default;

// * START ------------------------------------------------------------------------------------------------------------------------
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
            axios.get(`/staff/manage/user-accounts/approver/${branchId}/bu-departments`)
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
                    console.log(error.response.data);
                });
        } else {
            approverBUDepartmentDropdown.reset();
        }
    });
}

// Start - Edit Approver - Edit Branch and BU Department
const editApproverBranchDropdown = document.getElementById('editApproverBranchDropdown');
const editApproverBUDepartmentDropdown = document.getElementById('editApproverBUDepartmentDropdown');
const editApproverCountBUDepartments = document.getElementById('editApproverCountBUDepartments');
const editApproverNoBUDepartmentMessage = document.getElementById('editApproverNoBUDepartmentMessage');
const approverCurrentBranchId = document.getElementById('approverCurrentBranchId');
const approverCurrentDepartmentId = document.getElementById('approverCurrentDepartmentId');
const approverUserID = document.getElementById('approverUserID');

if (approverUserID) {
    const approverDetailsPath = `/staff/manage/user-accounts/approver/${approverUserID.value}/edit-details`;
    const approverPath = window.location.pathname;

    if (approverPath === approverDetailsPath) {
        window.onload = function () {
            axios.get(`/staff/manage/user-accounts/approver/edit/${approverCurrentBranchId.value}/bu-departments`)
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
                        editApproverBUDepartmentDropdown.setValue(approverCurrentDepartmentId.value);
                        editApproverCountBUDepartments.textContent = `(${departments.length})`;
                        editApproverNoBUDepartmentMessage.textContent = '';

                    } else {
                        editApproverBUDepartmentDropdown.reset();
                        editApproverBUDepartmentDropdown.disable();
                        editApproverCountBUDepartments.textContent = ``;
                        editApproverNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        }
    }
}


if (editApproverBranchDropdown || editApproverBUDepartmentDropdown) {
    editApproverBranchDropdown.addEventListener('change', function () {
        const branchId = this.value;

        editApproverBranchDropdown.addEventListener('reset', function () {
            editApproverBUDepartmentDropdown.disable();
            editApproverNoBUDepartmentMessage.textContent = "";
            editApproverCountBUDepartments.textContent = "";
        });

        if (branchId) {
            axios.get(`/staff/manage/user-accounts/approver/edit/${branchId}/bu-departments`)
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
                        editApproverBUDepartmentDropdown.setOptions(departmentsOption);
                        editApproverCountBUDepartments.textContent = `(${departments.length})`;
                        editApproverNoBUDepartmentMessage.textContent = '';

                    } else {
                        editApproverBUDepartmentDropdown.reset();
                        editApproverBUDepartmentDropdown.disable();
                        editApproverCountBUDepartments.textContent = ``;
                        editApproverNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        } else {
            editApproverBUDepartmentDropdown.reset();
        }
    });
}
// End - Edit for approver
// * END ------------------------------------------------------------------------------------------------------------------------

// * START ------------------------------------------------------------------------------------------------------------------------
// Create Service Department Admin - Assign a Branch, BU/Department, and Service Department
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
                    console.log(error.response.data);
                });
        } else {
            deptAdminBUDepartmentsDropdown.reset();
        }
    });
}

// Start - Edit for Service Department Admin - Edit Branch, BU/Department, and Service Department
const editServiceDeptAdminBranchDropdown = document.getElementById('editServiceDeptAdminBranchDropdown');
const editServiceDeptAdminBUDepartmentDropdown = document.getElementById('editServiceDeptAdminBUDepartmentDropdown');
const editServiceDeptAdminCountBUDepartments = document.getElementById('editServiceDeptAdminCountBUDepartments');
const editServiceDeptAdminNoBUDepartmentMessage = document.getElementById('editServiceDeptAdminNoBUDepartmentMessage');
const serviceDeptAdminCurrentBranchId = document.getElementById('serviceDeptAdminCurrentBranchId');
const serviceDeptAdminCurrentBUDepartmentId = document.getElementById('serviceDeptAdminCurrentBUDepartmentId');
const serviceDeptAdminUserID = document.getElementById('serviceDeptAdminUserID');

if (serviceDeptAdminUserID) {
    const serviceDeptAdminDetailsPath = `/staff/manage/user-accounts/service-department-admin/${serviceDeptAdminUserID.value}/edit-details`;
    const serviceDeptAdminPath = window.location.pathname;

    if (serviceDeptAdminPath === serviceDeptAdminDetailsPath) {
        window.onload = function () {
            axios.get(`/staff/manage/user-accounts/service-department-admin/edit/${serviceDeptAdminCurrentBranchId.value}/bu-departments`)
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

                        editServiceDeptAdminBUDepartmentDropdown.enable();
                        editServiceDeptAdminBUDepartmentDropdown.setOptions(departmentsOption);
                        editServiceDeptAdminBUDepartmentDropdown.setValue(serviceDeptAdminCurrentBUDepartmentId.value);
                        editServiceDeptAdminCountBUDepartments.textContent = `(${departments.length})`;
                        editServiceDeptAdminNoBUDepartmentMessage.textContent = '';

                    } else {
                        editServiceDeptAdminBUDepartmentDropdown.reset();
                        editServiceDeptAdminBUDepartmentDropdown.disable();
                        editServiceDeptAdminCountBUDepartments.textContent = ``;
                        editServiceDeptAdminNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        }
    }
}

if (editServiceDeptAdminBranchDropdown || editServiceDeptAdminBUDepartmentDropdown) {

    editServiceDeptAdminBranchDropdown.addEventListener('reset', function () {
        editServiceDeptAdminBUDepartmentDropdown.disable();
        editServiceDeptAdminNoBUDepartmentMessage.textContent = '';
        editServiceDeptAdminCountBUDepartments.textContent = '';
    });

    editServiceDeptAdminBranchDropdown.addEventListener('change', function () {
        const branchId = this.value;

        if (branchId) {
            axios.get(`/staff/manage/user-accounts/service-department-admin/edit/${branchId}/bu-departments`)
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

                        editServiceDeptAdminBUDepartmentDropdown.enable();
                        editServiceDeptAdminBUDepartmentDropdown.setOptions(buDepartmentsOption);
                        editServiceDeptAdminCountBUDepartments.textContent = `(${buDepartments.length})`;
                        editServiceDeptAdminNoBUDepartmentMessage.textContent = '';

                    } else {
                        editServiceDeptAdminBUDepartmentDropdown.reset();
                        editServiceDeptAdminBUDepartmentDropdown.disable();
                        editServiceDeptAdminCountBUDepartments.textContent = '';
                        editServiceDeptAdminNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                })
        } else {
            editServiceDeptAdminBUDepartmentDropdown.reset();
        }

    });
}
// End - Edit for Service Department Admin
// * END ------------------------------------------------------------------------------------------------------------------------

// * START ------------------------------------------------------------------------------------------------------------------------
// Create Agent - Assign a Branch, BU/Department, Service Department, and Team
const agentBranchesDropdown = document.getElementById('agentBranchesDropdown');

const agentBUDepartmentsDropdown = document.getElementById('agentBUDepartmentsDropdown');
const agentNoBUDepartmentsMessage = document.getElementById('agentNoBUDepartmentsMessage');
const agentCountBUDepartment = document.getElementById('agentCountBUDepartment');
const agentTeamsDropdown = document.getElementById('agentTeamsDropdown');
const agentNoTeamMessage = document.getElementById('agentNoTeamMessage');
const agentCountTeams = document.getElementById('agentCountTeams');

function clearBUDeptServiceDeptAndTeamWhenResetBranch() {
    agentBUDepartmentsDropdown.disable();
    agentTeamsDropdown.disable();
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
                    console.log(error.response.data);
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
                    console.log(error.response.data);
                });
        } else {
            agentBUDepartmentsDropdown.reset();
            agentTeamsDropdown.reset();
            agentTeamsDropdown.reset();
        }
    });
}

// Start - Edit for Agent - Edit Branch, BU/Department, Team, and Service Department
const editAgentBranchDropdown = document.getElementById('editAgentBranchDropdown');
const editAgentBUDepartmentDropdown = document.getElementById('editAgentBUDepartmentDropdown');
const editAgentTeamsDropdown = document.getElementById('editAgentTeamsDropdown');
const editAgentCountBUDepartments = document.getElementById('editAgentCountBUDepartments');
const editAgentNoBUDepartmentMessage = document.getElementById('editAgentNoBUDepartmentMessage');
const editAgentCountTeams = document.getElementById('editAgentCountTeams');
const editAgentNoTeamsMessage = document.getElementById('editAgentNoTeamsMessage');
const agentCurrentBranchId = document.getElementById('agentCurrentBranchId');
const agentCurrentBUDepartmentId = document.getElementById('agentCurrentBUDepartmentId');
const agentCurrentTeamId = document.getElementById('agentCurrentTeamId');
const agentUserID = document.getElementById('agentUserID');

if (agentUserID) {
    const editAgentPath = `/staff/manage/user-accounts/agent/${agentUserID.value}/edit-details`;
    const agentPath = window.location.pathname;

    if (agentPath === editAgentPath) {
        window.onload = function () {
            axios.get(`/staff/manage/user-accounts/agent/edit/${agentCurrentBranchId.value}/bu-departments`)
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

                        editAgentBUDepartmentDropdown.enable();
                        editAgentBUDepartmentDropdown.setOptions(departmentsOption);
                        editAgentBUDepartmentDropdown.setValue(agentCurrentBUDepartmentId.value);
                        editAgentCountBUDepartments.textContent = `(${departments.length})`;
                        editAgentNoBUDepartmentMessage.textContent = '';

                    } else {
                        editAgentBUDepartmentDropdown.reset();
                        editAgentBUDepartmentDropdown.disable();
                        editAgentCountBUDepartments.textContent = ``;
                        editAgentNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });

            axios.get(`/staff/manage/user-accounts/agent/edit/${agentCurrentBranchId.value}/teams`)
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

                        editAgentTeamsDropdown.enable();
                        editAgentTeamsDropdown.setOptions(teamsOption);
                        editAgentTeamsDropdown.setValue(agentCurrentTeamId.value);
                        editAgentCountTeams.textContent = `(${teams.length})`;
                        editAgentNoTeamsMessage.textContent = '';

                    } else {
                        editAgentTeamsDropdown.reset();
                        editAgentTeamsDropdown.disable();
                        editAgentCountTeams.textContent = ``;
                        editAgentNoTeamsMessage.textContent = 'No teams assigned on this branch.';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        }
    }
}

if (editAgentBranchDropdown || editAgentBUDepartmentDropdown || editAgentTeamsDropdown) {
    editAgentBranchDropdown.addEventListener('reset', function () {
        editAgentBUDepartmentDropdown.disable();
        editAgentTeamsDropdown.disable();
        editAgentBUDepartmentDropdown.reset();
        editAgentTeamsDropdown.reset();
        editAgentCountBUDepartments.textContent = '';
        editAgentNoBUDepartmentMessage.textContent = '';
        editAgentCountTeams.textContent = '';
        editAgentNoTeamsMessage.textContent = '';
    });

    editAgentBranchDropdown.addEventListener('change', function () {
        const branchId = this.value;

        if (branchId) {
            axios.get(`/staff/manage/user-accounts/agent/edit/${branchId}/bu-departments`)
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

                        editAgentBUDepartmentDropdown.enable();
                        editAgentBUDepartmentDropdown.setOptions(departmentsOption);
                        editAgentCountBUDepartments.textContent = `(${departments.length})`;
                        editAgentNoBUDepartmentMessage.textContent = '';

                    } else {
                        editAgentBUDepartmentDropdown.reset();
                        editAgentBUDepartmentDropdown.disable();
                        editAgentCountBUDepartments.textContent = ``;
                        editAgentNoBUDepartmentMessage.textContent = '(No BU/departments assigned on this branch)';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });

            axios.get(`/staff/manage/user-accounts/agent/edit/${branchId}/teams`)
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

                        editAgentTeamsDropdown.enable();
                        editAgentTeamsDropdown.setOptions(teamsOption);
                        editAgentCountTeams.textContent = `(${teams.length})`;
                        editAgentNoTeamsMessage.textContent = '';

                    } else {
                        editAgentTeamsDropdown.reset();
                        editAgentTeamsDropdown.disable();
                        editAgentCountTeams.textContent = ``;
                        editAgentNoTeamsMessage.textContent = 'No teams assigned on this branch.';
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        } else {
            editAgentBUDepartmentDropdown.reset();
            editAgentTeamsDropdown.reset();
        }
    });
}
// End - Edit for Agent
// * END --------------------------------------------------------------------------------------------------------------------------

// * START ------------------------------------------------------------------------------------------------------------------------
// User/Requester - Assign BU/Department and Branch
const userDepartmentsDropdown = document.getElementById('userDepartmentsDropdown');
const userBranchesDropdown = document.getElementById('userBranchesDropdown');
const userNoBUDepartmentsMessage = document.getElementById('userNoBUDepartmentsMessage');
const userCountBUDepartments = document.getElementById('userCountBUDepartments');

function userClearBranchWhenResetDepartment() {
    userDepartmentsDropdown.disable();
    userNoBUDepartmentsMessage.textContent = "";
    userCountBUDepartments.textContent = "";
}

if (userDepartmentsDropdown || userBranchesDropdown) {
    userDepartmentsDropdown.disable();
    userBranchesDropdown.addEventListener('reset', userClearBranchWhenResetDepartment);

    userBranchesDropdown.addEventListener('change', function () {
        const branchId = this.value;

        if (branchId) {
            axios.get(`/staff/manage/user-accounts/user/${branchId}/bu-departments`)
                .then((response) => {
                    const departments = response.data;
                    const departmentsOption = [];

                    if (departments && departments.length > 0) {
                        departments.forEach(function (branch) {
                            departmentsOption.push({
                                value: branch.id,
                                label: branch.name
                            });
                        });

                        userDepartmentsDropdown.enable();
                        userDepartmentsDropdown.setOptions(departmentsOption);
                        userCountBUDepartments.textContent = `(${departmentsOption.length})`;
                        userNoBUDepartmentsMessage.textContent = "";

                    } else {
                        userDepartmentsDropdown.reset();
                        userDepartmentsDropdown.disable();
                        userCountBUDepartments.textContent = "";
                        userNoBUDepartmentsMessage.textContent = "(No branch)";
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        } else {
            userDepartmentsDropdown.reset();
        }
    });
}

// Start - Edit User/Requester - Edit Branch and BU/Department
const editUserBranchDropdown = document.getElementById('editUserBranchDropdown');
const editUserBUDepartmentDropdown = document.getElementById('editUserBUDepartmentDropdown');
const editUserCountBUDepartments = document.getElementById('editUserCountBUDepartments');
const editUserNoBUDepartmentMessage = document.getElementById('editUserNoBUDepartmentMessage');
const userCurrentBranchId = document.getElementById('userCurrentBranchId');
const userCurrentBUDepartmentId = document.getElementById('userCurrentBUDepartmentId');
const userID = document.getElementById('userID');

if (userID) {
    const editUserPath = `/staff/manage/user-accounts/user/${userID.value}/edit-details`;
    const userPath = window.location.pathname;

    if (userPath === editUserPath) {
        window.onload = function () {
            axios.get(`/staff/manage/user-accounts/user/edit/${userCurrentBranchId.value}/bu-departments`)
                .then((response) => {
                    const departments = response.data;
                    const departmentsOption = [];

                    if (departments && departments.length > 0) {
                        departments.forEach(function (branch) {
                            departmentsOption.push({
                                value: branch.id,
                                label: branch.name
                            });
                        });

                        editUserBUDepartmentDropdown.enable();
                        editUserBUDepartmentDropdown.setOptions(departmentsOption);
                        editUserBUDepartmentDropdown.setValue(userCurrentBUDepartmentId.value);
                        editUserCountBUDepartments.textContent = `(${departmentsOption.length})`;
                        editUserNoBUDepartmentMessage.textContent = "";

                    } else {
                        editUserBUDepartmentDropdown.reset();
                        editUserBUDepartmentDropdown.disable();
                        editUserCountBUDepartments.textContent = "";
                        editUserNoBUDepartmentMessage.textContent = "(No branch)";
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        }
    }
}

if (editUserBranchDropdown || editUserBUDepartmentDropdown) {
    editUserBranchDropdown.addEventListener('reset', function () {
        editUserBUDepartmentDropdown.disable();
        editUserNoBUDepartmentMessage.textContent = "";
        editUserCountBUDepartments.textContent = "";
    });

    editUserBranchDropdown.addEventListener('change', function () {
        const branchId = this.value;

        if (branchId) {
            axios.get(`/staff/manage/user-accounts/user/edit/${branchId}/bu-departments`)
                .then((response) => {
                    const departments = response.data;
                    const departmentsOption = [];

                    if (departments && departments.length > 0) {
                        departments.forEach(function (branch) {
                            departmentsOption.push({
                                value: branch.id,
                                label: branch.name
                            });
                        });

                        editUserBUDepartmentDropdown.enable();
                        editUserBUDepartmentDropdown.setOptions(departmentsOption);
                        editUserCountBUDepartments.textContent = `(${departmentsOption.length})`;
                        editUserNoBUDepartmentMessage.textContent = "";

                    } else {
                        editUserBUDepartmentDropdown.reset();
                        editUserBUDepartmentDropdown.disable();
                        editUserCountBUDepartments.textContent = "";
                        editUserNoBUDepartmentMessage.textContent = "(No branch)";
                    }
                })
                .catch((error) => {
                    console.log(error.response.data);
                });
        } else {
            editUserBUDepartmentDropdown.reset();
        }
    });
}
// End - Edit for user/requester
// * END ------------------------------------------------------------------------------------------------------------------------

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
                                    label: team.name
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
                                            label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`
                                        });
                                    });

                                    const selectedCurrentApprovers = [];
                                    axios.get(`/staff/manage/help-topics/${helpTopicID.value}/level-approvers`)
                                        .then((response) => {
                                            const currentApprovers = response.data;

                                            approversOption.forEach(function (approver) {
                                                currentApprovers.forEach(function (currentApprover) {
                                                    if (approver.value == currentApprover.id && currentApprover.level == i) {
                                                        selectedCurrentApprovers.push(currentApprover.id);
                                                    }
                                                });
                                            });
                                        })
                                        .catch((error) => {
                                            console.log(error.response.data);
                                        });

                                    editLevelOfApproverSelect.setOptions(approversOption);
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
                                        label: `${approver.profile.first_name} ${firstLetter} ${approver.profile.last_name}`
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
                        console.log(error.response.data);
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
                    console.log(error.response.data);
                });
        } else {
            transferTicketTeamsDropdown.reset();
        }
    });
}
