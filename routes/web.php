<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Staff\Agent\AgentTicketController;
use App\Http\Controllers\Staff\Approver\ApproverDashboardController;
use App\Http\Controllers\Staff\Approver\ApproverTicketsController;
use App\Http\Controllers\Staff\Approver\NotificationController as ApproverNotificationController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\DirectoryController;
use App\Http\Controllers\Staff\ServiceDeptAdmin\AnnouncementController;
use App\Http\Controllers\Staff\SysAdmin\AccountAgentController;
use App\Http\Controllers\Staff\SysAdmin\AccountApproverController;
use App\Http\Controllers\Staff\SysAdmin\AccountsController;
use App\Http\Controllers\Staff\SysAdmin\AccountServiceDeptAdminController;
use App\Http\Controllers\Staff\SysAdmin\AccountUserController;
use App\Http\Controllers\Staff\SysAdmin\BranchController;
use App\Http\Controllers\Staff\SysAdmin\BUDepartmentController;
use App\Http\Controllers\Staff\SysAdmin\HelpTopicsController;
use App\Http\Controllers\Staff\SysAdmin\RolesAndPermissionsController;
use App\Http\Controllers\Staff\SysAdmin\ServiceDepartmentController;
use App\Http\Controllers\Staff\SysAdmin\SLAController;
use App\Http\Controllers\Staff\SysAdmin\TagController;
use App\Http\Controllers\Staff\SysAdmin\TeamController;
use App\Http\Controllers\Staff\SysAdmin\TicketStatusController;
use App\Http\Controllers\Staff\SysAdmin\UpdatePasswordController;
use App\Http\Controllers\Staff\TicketController as StaffTicketController;
use App\Http\Controllers\User\AccountController as UserAccountSettingsController;
use App\Http\Controllers\User\Dashboard as UserDashboardController;
use App\Http\Controllers\User\FeedbackController;
use App\Http\Controllers\User\TicketsController as UserTicketsController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;


Route::get('/forgot-password', ForgotPasswordController::class)->name('forgot_password');

// * Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::prefix('/auth')->name('auth.')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });
});

// * Staff Routes
Route::middleware(['auth', Role::onlyStaffs()])->group(function () {
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::prefix('manual-ticket-assign')->name('manual_ticket_assign.')->group(function () {
            Route::controller(StaffTicketController::class)->group(function () {
                Route::get('/', 'ticketsToAssign')->name('to_assign');
            });
        });
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::controller(StaffTicketController::class)->group(function () {
                Route::get('/approved', 'approvedTickets')->name('approved_tickets');
                Route::get('/disapproved', 'disapprovedTickets')->name('disapproved_tickets');
                Route::get('/open', 'openTickets')->name('open_tickets');
                Route::get('/on-process', 'onProcessTickets')->name('on_process_tickets');
                Route::get('/claimed', 'claimedTickets')->name('claimed_tickets');
                Route::get('/viewed', 'viewedTickets')->name('viewed_tickets');
                // Route::get('/reopened', 'reopenedTickets')->name('reopened_tickets');
                Route::get('/overdue', 'overdueTickets')->name('overdue_tickets');
                Route::get('/closed', 'closedTickets')->name('closed_tickets');
                // Endpoint for axios
                Route::get('/{department}/teams', 'ticketActionGetDepartmentServiceDepartments');
            });
        });
        Route::prefix('ticket')->name('ticket.')->group(function () {
            Route::controller(StaffTicketController::class)->group(function () {
                Route::get('/{ticket}/view', 'viewTicket')->name('view_ticket');
            });
            Route::controller(AgentTicketController::class)->group(function () {
                Route::put('{ticket}/claim', 'claimTicket')->name('claim_ticket');
                Route::put('{ticket}/claim', 'ticketDetialsClaimTicket')->name('ticket_details_claim_ticket');
            });
        });

        // TODO - Disable this route for now until processflow from FPM is applied.
        // Route::prefix('service-dept-head')->name('service_dept_head.')->group(function () {
        //     Route::controller(TicketLevel1ApprovalController::class)->group(function () {
        //         Route::prefix('level-1-approval')->name('level_1_approval.')->group(function () {
        //             Route::get('/', 'index')->name('index');
        //             Route::get('/{ticket}', 'show')->name('show');
        //             Route::post('/{ticket}/send-clarification', 'sendClarification')->name('send_clarification');
        //         });
        //     });
        // });

        Route::prefix('/announcement')->name('announcement.')->group(function () {
            Route::controller(AnnouncementController::class)->group(function () {
                Route::get('/', 'index')->name('home');
                Route::post('/store', 'store')->name('store');
                Route::put('/{announcement}/edit', 'edit')->name('edit');
                Route::delete('/{announcement}/delete', 'delete')->name('delete');
            });
        });

        // Manage (Admin Role)
        Route::prefix('manage')->name('manage.')->group(function () {
            Route::view('/', 'layouts.staff.system_admin.manage.manage_main')->name('home');
            // Agents
            Route::view('/agent', 'layouts.staff.system_admin.manage.agents.agent_list')->name('agents');
            // User Accounts
            Route::prefix('user-accounts')->name('user_account.')->group(function () {
                Route::controller(AccountsController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/approvers', 'approvers')->name('approvers');
                    Route::get('/service-department-admins', 'serviceDepartmentAdmins')->name('service_department_admins');
                    Route::get('/agents', 'agents')->name('agents');
                    Route::get('/requesters', 'users')->name('users');

                });
                // Approver Routes
                Route::prefix('approver')->name('approver.')->group(function () {
                    Route::controller(AccountApproverController::class)->group(function () {
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/{approver}/delete', 'delete')->name('delete');
                        Route::put('/{approver}/update', 'update')->name('update');
                        Route::get('/{approver}/view-details', 'viewDetails')->name('view_details');
                        Route::get('/{approver}/edit-details', 'editDetails')->name('edit_details');

                        // Axios endpoints
                        // (For create approver)
                        Route::get('/{branch}/bu-departments', 'branchDepartments');
                        // (For Edit approver)
                        Route::get('/edit/{branch}/bu-departments', 'branchDepartments');
                    });
                    Route::controller(UpdatePasswordController::class)->group(function () {
                        Route::put('/{user}/update-password', 'updatePassword')->name('update_password');
                    });
                });
                // Department Admin Routes
                Route::prefix('service-department-admin')->name('service_department_admin.')->group(function () {
                    Route::controller(AccountServiceDeptAdminController::class)->group(function () {
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/{serviceDeptAdmin}/delete', 'delete')->name('delete');
                        Route::put('/{serviceDeptAdmin}/update', 'update')->name('update');
                        Route::get('/{serviceDeptAdmin}/view-details', 'viewDetails')->name('view_details');
                        Route::get('/{serviceDeptAdmin}/edit-details', 'editDetails')->name('edit_details');

                        // Axios endpoints
                        // For create service department admin
                        Route::get('/{branch}/bu-departments', 'branchBUDepartments');
                        // For edit service department admin
                        Route::get('/edit/{branch}/bu-departments', 'branchBUDepartments');
                    });
                });
                // Agent Routes
                Route::prefix('agent')->name('agent.')->group(function () {
                    Route::controller(AccountAgentController::class)->group(function () {
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/{agent}/store', 'delete')->name('delete');
                        Route::put('/{agent}/update', 'update')->name('update');
                        Route::get('/{agent}/view-details', 'viewDetails')->name('view_details');
                        Route::get('/{agent}/edit-details', 'editDetails')->name('edit_details');

                        // Axios endpoints
                        // For create agent
                        Route::get('/{branch}/bu-departments', 'branchDepartments');
                        Route::get('/{branch}/teams', 'branchTeams');
                        // For edit agent
                        Route::get('edit/{branch}/bu-departments', 'branchDepartments');
                        Route::get('edit/{branch}/teams', 'branchTeams');
                        Route::get('/{agent}/agent-teams', 'agenTeams');
                    });
                    Route::controller(UpdatePasswordController::class)->group(function () {
                        Route::put('/{user}/update-password', 'updatePassword')->name('update_password');
                    });
                });
                // User/Requester Routes
                Route::prefix('user')->name('user.')->group(function () {
                    Route::controller(AccountUserController::class)->group(function () {
                        Route::post('/store', 'store')->name('store');
                        Route::put('/{user}/update', 'update')->name('update');
                        Route::get('/{user}/view-details', 'viewDetails')->name('view_details');
                        Route::get('/{user}/edit-details', 'editDetails')->name('edit_details');
                        Route::delete('/{user}/delete', 'delete')->name('delete');

                        // Endpoint for axios
                        // For create requester
                        Route::get('/{branch}/bu-departments', 'getBUDepartments');
                        // For edit requester
                        Route::get('/edit/{branch}/bu-departments', 'getBUDepartments');
                        // Route::get('/assign/department/{department}/service-departments', 'getServiceDepartments');
                    });
                });
            });
            Route::prefix('roles-and-permissions')->name('roles_and_permissions.')->group(function () {
                Route::get('/', RolesAndPermissionsController::class)->name('index');
            });
            Route::prefix('service-level-agreements')->name('service_level_agreements.')->group(function () {
                Route::get('/', SLAController::class)->name('index');
            });
            Route::prefix('branch')->name('branch.')->group(function () {
                Route::get('/', BranchController::class)->name('index');
            });
            Route::prefix('bu-department')->name('bu_department.')->group(function () {
                Route::get('/', BUDepartmentController::class)->name('index');
            });
            Route::prefix('service-department')->name('service_department.')->group(function () {
                Route::get('/', ServiceDepartmentController::class)->name('index');
            });
            Route::prefix('team')->name('team.')->group(function () {
                Route::get('/', TeamController::class)->name('index');
            });
            Route::prefix('help-topics')->name('help_topic.')->group(function () {
                Route::controller(HelpTopicsController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::get('/{helpTopic}/edit-details', 'editDetails')->name('edit_details');
                    Route::put('/{helpTopic}/update', 'update')->name('update');
                    Route::delete('/{helpTopic}/delete', 'delete')->name('delete');

                    // Axios endpoints
                    Route::get('/approvers', 'loadApprovers');
                    Route::get('/assign/service-department/{serviceDepartment}/teams', 'teams');
                    Route::get('/{helpTopic}/level-approvers', 'helpTopicApprovers');
                });
            });
            Route::prefix('tag')->name('tag.')->group(function () {
                Route::get('/', TagController::class)->name('index');
            });
            Route::prefix('ticket-statuses')->name('ticket_statuses.')->group(function () {
                Route::controller(TicketStatusController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::put('/{status}/edit', 'update')->name('update');
                    Route::delete('/{status}/delete', 'delete')->name('delete');
                });
            });
        });
        // Directory Routes
        Route::prefix('directory')->name('directory.')->group(function () {
            Route::controller(DirectoryController::class)->group(function () {
                Route::get('/service-departmetn-administrators', 'index')->name('index');
                Route::get('/approvers', 'approvers')->name('approvers');
                Route::get('/agents', 'agents')->name('agents');
                Route::get('/requesters', 'requesters')->name('requesters');
            });
        });
    });
});


// * Approver Routes
Route::middleware(['auth', Role::approver()])->group(function () {
    Route::prefix('approver')->name('approver.')->group(function () {
        Route::get('/dashboard', [ApproverDashboardController::class, 'index'])->name('dashboard');
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::controller(ApproverTicketsController::class)->group(function () {
                Route::get('/open', 'openTickets')->name('open');
                Route::get('/viewed', 'viewedTickets')->name('viewed');
                Route::get('/approved', 'approvedTickets')->name('approved');
                Route::get('/disapproved', 'disapprovedTickets')->name('disapproved');
                Route::get('/on-process', 'onProcessTickets')->name('on_process');
            });
        });
        Route::prefix('ticket')->name('ticket.')->group(function () {
            Route::controller(ApproverTicketsController::class)->group(function () {
                Route::get('/{ticket}/view', 'viewTicketDetails')->name('view_ticket_details');
            });
        });
        Route::prefix('notifications')->name('notification.')->group(function () {
            Route::controller(ApproverNotificationController::class)->group(function () {
                Route::post('/mark-all-as-read', 'markAllAsRead')->name('mark_all_as_read');
                Route::delete('/clear-notifications', 'clearNotifications')->name('clear');
                Route::put('/{notificaion}/read', 'readNotification')->name('read');
            });
        });
    });
});

// * User Routes
Route::middleware(['auth', Role::user()])->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', UserDashboardController::class)->name('dashboard');
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::controller(UserTicketsController::class)->group(function () {
                Route::get('/open', 'openTickets')->name('open_tickets');
                Route::get('/on-process', 'onProcessTickets')->name('on_process_tickets');
                Route::get('/viewed', 'viewedTickets')->name('viewed_tickets');
                Route::get('/approved', 'approvedTickets')->name('approved_tickets');
                Route::get('/claimed', 'claimedTickets')->name('claimed_tickets');
                Route::get('/disapproved', 'disapprovedTickets')->name('disapproved_tickets');
                Route::get('/closed', 'closedTickets')->name('closed_tickets');
            });
        });
        Route::prefix('ticket')->name('ticket.')->group(function () {
            Route::controller(UserTicketsController::class)->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::get('/{ticket}/view', 'viewTicket')->name('view_ticket');
                Route::get('/{ticket}/view/clarifications', 'ticketClarifications')->name('ticket_clarifications');

                // Axios endpoints
                Route::get('/branches', 'loadBranches');
                Route::get('/{helpTopic}/sla', 'helpTopicSLA');
                Route::get('/{helpTopic}/team', 'helpTopicTeam');
            });
        });
        Route::prefix('account-settings')->name('account_settings.')->group(function () {
            Route::controller(UserAccountSettingsController::class)->group(function () {
                Route::get('/profile', 'profile')->name('profile');
                Route::get('/password', 'password')->name('password');
            });
        });
    });
});

Route::prefix('ticket')->group(function () {
    Route::controller(UserTicketsController::class)->group(function () {
        Route::get('/service-departments', 'loadServiceDepartments');
        Route::get('/{serviceDepartment}/help-topics', 'serviceDepartmentHelpTopics');
    });
});


// * Feedback Routes
Route::middleware(['auth', Role::user()])->group(function () {
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::controller(FeedbackController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/to-rate', 'ticketsToRate')->name('to_rate');
            Route::get('/my-reviews', 'reviews')->name('reviews');
            Route::post('/rate/store', 'store')->name('store');
        });
    });
});