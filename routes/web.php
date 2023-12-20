<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Staff\Approver\ApproverDashboardController;
use App\Http\Controllers\Staff\Approver\ApproverTicketsController;
use App\Http\Controllers\Staff\Approver\NotificationController as ApproverNotificationController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\DirectoryController;
use App\Http\Controllers\Staff\ServiceDeptAdmin\AnnouncementController;
use App\Http\Controllers\Staff\ServiceDeptAdmin\TicketClarificationController;
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
Route::middleware(['auth', Role::staffsOnly()])->group(function () {
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::prefix('manual-ticket-assign')->name('manual_ticket_assign.')->group(function () {
            Route::controller(StaffTicketController::class)->group(function () {
                Route::get('/', 'ticketsToAssign')->name('to_assign');
            });
        });
        Route::controller(StaffTicketController::class)->group(function () {
            Route::get('/level-approval', 'ticketLevelApproval')->name('ticket_level_approval');
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
            });
        });
        Route::prefix('ticket')->name('ticket.')->group(function () {
            Route::controller(StaffTicketController::class)->group(function () {
                Route::get('/{ticket}/view', 'viewTicket')->name('view_ticket');
                Route::middleware(['auth'])->group(function () {
                    Route::get('/{ticket}/clarifications', TicketClarificationController::class)->name('ticket_clarifications');
                });
            });
        });

        Route::prefix('/announcement')->name('announcement.')->group(function () {
            Route::controller(AnnouncementController::class)->group(function () {
                Route::get('/', 'index')->name('home');
                Route::post('/store', 'store')->name('store');
                Route::put('/{announcement}/edit', 'edit')->name('edit');
                Route::delete('/{announcement}/delete', 'delete')->name('delete');
            });
        });

        Route::prefix('my-bookmarks')->name('my_bookmarks.')->group(function () {
            Route::controller(StaffTicketController::class)->group(function () {
                Route::get('/', 'myBookmarkedTickets')->name('my_bookmarked_tickets');
            });
        });

        // Manage (Admin Role)
        Route::prefix('manage')->name('manage.')->group(function () {
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
                        Route::get('/{approver}/view-details', 'viewDetails')->name('view_details');
                        Route::get('/{approver}/edit-details', 'editDetails')->name('edit_details');
                    });
                });
                // Department Admin Routes
                Route::prefix('service-department-admin')->name('service_department_admin.')->group(function () {
                    Route::controller(AccountServiceDeptAdminController::class)->group(function () {
                        Route::get('/{serviceDeptAdmin}/view-details', 'viewDetails')->name('view_details');
                        Route::get('/{serviceDeptAdmin}/edit-details', 'editDetails')->name('edit_details');
                    });
                });
                // Agent Routes
                Route::prefix('agent')->name('agent.')->group(function () {
                    Route::controller(AccountAgentController::class)->group(function () {
                        Route::get('/{agent}/view-details', 'viewDetails')->name('view_details');
                        Route::get('/{agent}/edit-details', 'editDetails')->name('edit_details');
                    });
                });
                // User/Requester Routes
                Route::prefix('user')->name('user.')->group(function () {
                    Route::controller(AccountUserController::class)->group(function () {
                        Route::get('/{user}/view-details', 'viewDetails')->name('view_details');
                        Route::get('/{user}/edit-details', 'editDetails')->name('edit_details');
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
                    Route::get('/{helpTopic}/edit-details', 'editDetails')->name('edit_details');
                });
            });
            Route::prefix('tag')->name('tag.')->group(function () {
                Route::get('/', TagController::class)->name('index');
            });
            Route::prefix('ticket-statuses')->name('ticket_statuses.')->group(function () {
                Route::get('/', TicketStatusController::class)->name('index');
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
Route::middleware(['auth', Role::approversOnly()])->group(function () {
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
Route::middleware(['auth', Role::requestersOnly()])->group(function () {
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

// * Feedback Routes
Route::middleware(['auth', Role::requestersOnly()])->group(function () {
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::controller(FeedbackController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/to-rate', 'ticketsToRate')->name('to_rate');
            Route::get('/my-reviews', 'reviews')->name('reviews');
            Route::post('/rate/store', 'store')->name('store');
        });
    });
});