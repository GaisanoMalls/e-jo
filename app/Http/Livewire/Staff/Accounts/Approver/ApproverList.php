<?php

namespace App\Http\Livewire\Staff\Accounts\Approver;

use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class ApproverList extends Component
{
    use Utils, WithPagination;

    public ?Collection $allPermissions = null;
    public ?int $approverDeleteId = null;
    public ?int $approverAssignPermissionId = null;
    public ?string $approverFullName = null;
    public array $approverPermissions = [];
    public ?string $searchApprover = null;

    public function mount()
    {
        $this->allPermissions = $this->getAllPermissions();
    }

    protected $listeners = ['loadApproverList' => '$refresh'];

    /**
     * Retrieves all permissions from the database and caches them in the object.
     * 
     * This private method fetches all available permissions using Laravel's Eloquent ORM
     * and stores them in the class property `allPermissions` for potential future use.
     * The method follows a caching pattern to avoid repeated database queries.
     *
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of all Permission models
     */
    private function getAllPermissions()
    {
        // Fetch all permissions from the database
        return $this->allPermissions = Permission::all();
    }


    /**
     * Handles post-submission actions after deleting an approver.
     * 
     * This method performs the following actions:
     * 1. Dispatches a browser event to close the modal window.
     * 
     * @return void
     */
    private function actionOnSubmit()
    {
        $this->dispatchBrowserEvent('close-modal');
    }

    /**
     * Prepares an approver user for deletion by storing their ID and full name.
     * 
     * This method sets the approver's ID and full name to class properties,
     * which can be used for confirmation dialogs or subsequent deletion operations.
     * The full name is retrieved from the approver's profile relationship.
     *
     * @param User $approver The approver user to be prepared for deletion
     * @return void
     */
    public function deleteApprover(User $approver)
    {
        // Store the approver's ID for later deletion processing
        $this->approverDeleteId = $approver->id;

        // Store the approver's full name from their profile for display/confirmation purposes
        $this->approverFullName = $approver->profile->getFullName;
    }

    /**
     * Deletes an approver user and handles related approval data cleanup.
     * 
     * This method performs the following actions:
     * 1. Deletes the approver user record
     * 2. Handles special cases for costing approvers by either:
     *    - Nullifying COO approver field if primary costing approver exists
     *    - Deleting approvals if secondary costing approver exists without primary
     * 3. Resets deletion tracking properties
     * 4. Closes the modal and shows success notification
     * 5. Logs any exceptions that occur during the process
     * 
     * @return void
     */
    public function delete()
    {
        try {
            // Delete the approver user record using the stored ID
            User::where('id', $this->approverDeleteId)->delete();

            // Handle special cases for costing approvers
            if ($this->hasCostingApprover1()) {
                // If primary costing approver exists, nullify COO approver field in related approvals
                SpecialProjectAmountApproval::whereNotNull('service_department_admin_approver')
                    ->update(['fpm_coo_approver' => null]);
            }

            // Handle secondary costing approver case when primary doesn't exist
            if ($this->hasCostingApprover2() && !$this->hasCostingApprover1()) {
                // Delete approvals that reference this approver in JSON data
                SpecialProjectAmountApproval::whereJsonContains('fpm_coo_approver->approver_id', $this->approverDeleteId)->delete();
            }

            // Reset deletion tracking properties
            $this->approverDeleteId = null;

            // Perform post-deletion actions (closes modal)
            $this->actionOnSubmit();

            // Show success notification
            noty()->addSuccess('Approver account has been deleted.');

        } catch (Exception $e) {
            // Log any exceptions that occur during the deletion process
            AppErrorLog::getError($e->getMessage());
        }
    }

    /**
     * Builds and returns the initial query for searching approver users.
     * 
     * This private method constructs a complex query to search for approver users based on:
     * - Name matches in profile (first, middle, or last name)
     * - Branch name matches
     * - Business unit department name matches
     * The results are filtered to only include users with the APPROVER role,
     * ordered by creation date (newest first), and paginated.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * Returns paginated list of approver users matching search criteria
     */
    private function getInitialQuery()
    {
        return User::whereHas('profile', function ($profile) {
            // Search profile names (first, middle, or last) for search term
            $profile->where('first_name', 'like', "%{$this->searchApprover}%")
                ->orWhere('middle_name', 'like', "%{$this->searchApprover}%")
                ->orWhere('last_name', 'like', "%{$this->searchApprover}%");
        })
            // Include users matching branch name search
            ->orWhereHas('branches', fn($branch) => $branch->where('name', 'like', "%{$this->searchApprover}%"))
            // Include users matching business unit department name search
            ->orWhereHas('buDepartments', fn($buDept) => $buDept->where('name', 'like', "%{$this->searchApprover}%"))
            // Filter to only users with APPROVER role
            ->role(Role::APPROVER)
            // Order by most recently created first
            ->orderByDesc('created_at')
            // Paginate results (25 per page)
            ->paginate(25);
    }

    public function updatingSearchApprover()
    {
        $this->resetPage();
    }

    public function clearApproverSearch()
    {
        $this->searchApprover = '';
    }

    public function render()
    {
        $approvers = $this->getInitialQuery();

        if (Route::is('staff.manage.user_account.index')) {
            $approvers = User::with('profile')
                ->role(Role::APPROVER)
                ->orderByDesc('created_at')
                ->paginate(15);
        }

        if (Route::is('staff.manage.user_account.approvers')) {
            $approvers = $this->getInitialQuery();
        }

        return view('livewire.staff.accounts.approver.approver-list', [
            'approvers' => $approvers
        ]);
    }
}
