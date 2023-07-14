<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\User\FeedbackController;
use App\Http\Controllers\DependentsController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Staff\Approver\ApproverDashboardController;
use App\Http\Controllers\Staff\Approver\ApproverTicketsController;
use App\Http\Controllers\Staff\DirectoryController;
use App\Http\Controllers\Staff\AuthControllerStaff;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\SysAdmin\BUDepartmentBranchController;
use App\Http\Controllers\Staff\SysAdmin\BUDepartmentController;
use App\Http\Controllers\Staff\SysAdmin\TeamController;
use App\Http\Controllers\Staff\SysAdmin\SLAController;
use App\Http\Controllers\Staff\SysAdmin\TagController;
use App\Http\Controllers\Staff\SysAdmin\BranchController;
use App\Http\Controllers\Staff\SysAdmin\AccountsController;
use App\Http\Controllers\Staff\SysAdmin\TeamBranchController;
use App\Http\Controllers\Staff\SysAdmin\HelpTopicsController;
use App\Http\Controllers\Staff\SysAdmin\ServiceDepartmentController;
use App\Http\Controllers\Staff\SysAdmin\AccountUserController;
use App\Http\Controllers\Staff\SysAdmin\AnnouncementController;
use App\Http\Controllers\Staff\SysAdmin\AccountAgentController;
use App\Http\Controllers\Staff\SysAdmin\AccountApproverController;
use App\Http\Controllers\Staff\SysAdmin\AccountDeptAdminController;
use App\Http\Controllers\Staff\SysAdmin\ServiceDepartmentBranchController;
use App\Http\Controllers\Staff\SysAdmin\RolesAndPermissionsController;

use App\Http\Controllers\Staff\SysAdmin\TicketStatusController;
use App\Http\Controllers\Staff\TicketController;
use App\Http\Controllers\User\AuthControllerUser;
use App\Http\Controllers\User\Dashboard as UserDashboardController;
use App\Http\Controllers\User\TicketsController as UserTicketsController;
use App\Http\Controllers\User\AccountController as UserAccountSettingsController;

use App\Models\Role;
use Illuminate\Support\Facades\Route;


Route::get('/', [LandingController::class, 'landingPage'])->name('login');
Route::prefix('/forgot-password')->name('forgot_password.')->group(function () {
    Route::get('/', [ForgotPasswordController::class, 'index'])->name('index');
});

// * Auth routes
Route::prefix('auth')->name('auth.')->group(function () {
    // * Staffs
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::controller(AuthControllerStaff::class)->group(function () {
            Route::get('/', 'login')->name('login');
            Route::post('/authenticate', 'authenticate')->name('authenticate_staff');
            Route::post('/logout', 'logout')->name('logout_staff');
        });
    });

    // * Users
    Route::prefix('requester')->name('requester.')->group(function () {
        Route::controller(AuthControllerUser::class)->group(function () {
            Route::get('/', 'login')->name('login');
            Route::post('/authenticate', 'authenticate')->name('authenticate');
            Route::post('/logout', 'logout')->name('logout');
        });
    });
});

// * Staff Routes
Route::middleware(['auth', Role::systemAdmin()])->group(function () {
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::controller(TicketController::class)->group(function () {
                Route::get('/open', 'openTickets')->name('open_tickets');
                Route::get('/on-process', 'onProcessTickets')->name('on_process_tickets');
                Route::get('/approved', 'approvedTickets')->name('approved_tickets');

                Route::get('/{ticketStatusSlug}/{ticketId}/view', 'viewTicket')->name('view_ticket');
                Route::post('/{ticket}/reply/store', 'replyTicket')->name('storeTicketReply');
            });
            // Endpoint for axios
            Route::controller(DependentsController::class)->group(function () {
                Route::get('/{department}/teams', 'ticketActionGetDepartmentServiceDepartments');
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

        // Manage (Admin Role)
        Route::prefix('manage')->name('manage.')->group(function () {
            Route::view('/', 'layouts.staff.system_admin.manage.manage_main')->name('home');
            Route::prefix('branch')->name('branch.')->group(function () {
                Route::controller(BranchController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::put('{branch}/edit', 'edit')->name('edit');
                    Route::delete('{branch}/delete', 'delete')->name('delete');
                });
            });
            Route::prefix('bu-department')->name('bu_department.')->group(function () {
                Route::controller(BUDepartmentController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::delete('/{buDepartment}/delete', 'delete')->name('delete');
                });
                Route::prefix('assign-branch')->name('assign_branch.')->group(function () {
                    Route::controller(BUDepartmentBranchController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/store', 'store')->name('store');
                    });
                });
            });
            Route::prefix('service-department')->name('service_department.')->group(function () {
                Route::controller(ServiceDepartmentController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::delete('/{serviceDepartment}/delete', 'delete')->name('delete');
                });
                Route::prefix('assign-branch')->name('assign_branch.')->group(function () {
                    Route::controller(ServiceDepartmentBranchController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('{serviceDepartmentBranch}/delete', 'delete')->name('delete');

                        // Axios endpoints
                        Route::get('/{branch}/bu-departments', 'branchDepartments');
                    });
                });
            });
            Route::prefix('team')->name('team.')->group(function () {
                Route::controller(TeamController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::delete('{team}/delete', 'delete')->name('delete');
                });
                Route::prefix('assign-branch')->name('assign_branch.')->group(function () {
                    Route::controller(TeamBranchController::class)->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/{team}/delete', 'delete')->name('delete');

                        // Axios endpoints
                        Route::get('/{team}/service-department', 'serviceDepartment');
                        Route::get('/department/{serviceDepartment}/branches', 'branches');
                    });
                });
            });

            // Agents
            Route::view('/agent', 'layouts.staff.system_admin.manage.agents.agent_list')->name('agents');

            // User Accounts
            Route::prefix('user-accounts')->name('user_account.')->group(function () {
                Route::get('/', [AccountsController::class, 'index'])->name('index');
                // Approver Routes
                Route::prefix('approver')->name('approver.')->group(function () {
                    Route::controller(AccountApproverController::class)->group(function () {
                        Route::post('/store', 'store')->name('store');
                        Route::put('/{approver}/edit', 'edit')->name('edit');
                        Route::delete('/{approver}/delete', 'delete')->name('delete');

                        // Axios endpoints
                        Route::get('/{branch}/departments', 'branchDepartments');
                    });
                });
                // Department Admin Routes
                Route::prefix('department-admin')->name('department_admin.')->group(function () {
                    Route::controller(AccountDeptAdminController::class)->group(function () {
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/{departmentAdmin}/store', 'delete')->name('delete');

                        // Axios endpoints
                        Route::get('/{branch}/bu-departments', 'branchBUDepartments');
                    });
                });
                // Agent Routes
                Route::prefix('agent')->name('agent.')->group(function () {
                    Route::controller(AccountAgentController::class)->group(function () {
                        Route::post('/store', 'store')->name('store');
                        Route::delete('/{agent}/store', 'delete')->name('delete');

                        // Axios endpoints
                        Route::get('/{branch}/bu-departments', 'branchDepartments');
                        Route::get('/{branch}/teams', 'branchTeams');
                    });
                });
                // User/Requester Routes
                Route::prefix('user')->name('user.')->group(function () {
                    Route::controller(AccountUserController::class)->group(function () {
                        Route::post('/store', 'store')->name('store');
                    });
                    // Endpoint for axios
                    Route::controller(DependentsController::class)->group(function () {
                        Route::get('/assign/department/{department}/branches', 'getBranches');
                        Route::get('/assign/department/{department}/service-departments', 'getServiceDepartments');
                    });
                });
            });

            Route::prefix('roles-and-permissions')->name('roles_and_permissions.')->group(function () {
                Route::controller(RolesAndPermissionsController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                });
            });

            Route::prefix('help-topics')->name('help_topics.')->group(function () {
                Route::controller(HelpTopicsController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('store', 'store')->name('store');

                    // Axios endpoints
                    Route::get('/approvers', 'loadApprovers');
                    Route::get('/assign/service-department/{serviceDepartment}/teams', 'teams');
                });
            });

            Route::prefix('service-level-agreements')->name('service_level_agreements.')->group(function () {
                Route::controller(SLAController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::delete('/{sla}/delete', 'delete')->name('delete');

                    // Endpoint for axios
                    Route::get('/approvers', 'getLevelApprovers');
                });
            });

            Route::prefix('tags')->name('tags.')->group(function () {
                Route::controller(TagController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                    Route::delete('/{tag}/delete', 'delete')->name('delete');
                });
            });

            Route::prefix('ticket-statuses')->name('ticket_statuses.')->group(function () {
                Route::controller(TicketStatusController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/store', 'store')->name('store');
                });
            });
        });

        // Directory Routes
        Route::prefix('directory')->name('directory.')->group(function () {
            Route::controller(DirectoryController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/approvers', 'approvers')->name('approvers');
                Route::get('/agents', 'agents')->name('agents');
            });
        });
    });
});

Route::middleware(['auth', Role::approver()])->group(function () {
    Route::prefix('approver')->name('approver.')->group(function () {
        Route::get('/dashboard', [ApproverDashboardController::class, 'index'])->name('dashboard');
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::controller(ApproverTicketsController::class)->group(function () {
                Route::get('/open', 'openTickets')->name('open');
                Route::get('/approved', 'approvedTickets')->name('approved');
                Route::get('/disapproved', 'disapprovedTickets')->name('disapproved');

                Route::put('{ticket}/approve', 'approveTicket')->name('approve');
                Route::put('{ticket}/reject', 'disapproveTicket')->name('reject');
            });
        });
    });
});

// * User Routes
Route::middleware(['auth', Role::user()])->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::controller(UserTicketsController::class)->group(function () {
                Route::get('/open', 'openTickets')->name('open_tickets');
                Route::get('/on-process', 'onProcessTickets')->name('on_process_tickets');
                Route::get('/closed', 'closedTickets')->name('closed_tickets');
            });
        });
        Route::prefix('ticket')->name('ticket.')->group(function () {
            Route::controller(UserTicketsController::class)->group(function () {
                Route::post('/store','store')->name('store');

                // Axios endpoints
                Route::get('/branches', 'loadBranches');
                Route::get('/{user}/service-departments', 'loadServiceDepartmentsByUserBranch');
                Route::get('/{serviceDepartment}/help-topics', 'serviceDepartmentHelpTopics');
                Route::get('/{helpTopic}/team', 'helpTopicTeam');
            });
        });
        Route::prefix('account-settings')->name('account_settings.')->group(function () {
            Route::controller(UserAccountSettingsController::class)->group(function () {
                Route::get('/profile', 'profile')->name('profile');
                Route::put('/profile/update', 'updateProfile')->name('updateProfile');
                Route::get('/password', 'password')->name('password');
                Route::put('/password/update', 'updatePassword')->name('updatePassword');
            });
        });
    });
});

// * Feedback Routes
Route::middleware(['auth', Role::user()])->group(function () {
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::controller(FeedbackController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/my-reviews', 'reviews')->name('reviews');
            Route::get('/to-rate', 'ticketsToRate')->name('to_rate');
            Route::post('/rate/store', 'store')->name('store');
        });
    });
});