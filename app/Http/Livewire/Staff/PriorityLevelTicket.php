<?php

namespace App\Http\Livewire\Staff;

use App\Enums\ApprovalStatusEnum;
use App\Models\PriorityLevel;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class PriorityLevelTicket extends Component
{
    use WithPagination;

    protected Collection|LengthAwarePaginator $priorityLevelTickets;
    public Collection $priorityLevels;
    public PriorityLevel $priorityLevel;
    public string $searchTicket = "";
    public ?string $searchDate = null;
    public ?string $searchMonth = null;
    public ?string $searchStartDate = null;
    public ?string $searchEndDate = null;
    public bool $useDate = false;
    public bool $useMonth = false;
    public bool $useDateRange = false;

    // Pagination
    public array $pageNumberOptions = [30, 50, 70, 100];
    public int $paginatePageNumber;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->priorityLevelTickets = collect();
        $this->paginatePageNumber = $this->pageNumberOptions[0];
    }

    public function clearSearchTicket()
    {
        $this->searchTicket = "";
    }

    public function hasSearchQuery()
    {
        return $this->searchTicket
            || $this->searchDate
            || $this->searchMonth
            || $this->searchStartDate
            || $this->searchEndDate;
    }

    public function selectPaginateNumber(int $selectedNumber)
    {
        $this->paginatePageNumber = $selectedNumber;
    }


    public function toggleDate()
    {
        $this->useDate = !$this->useDate;
        $this->resetMonthFilter();
        $this->resetDateRangeFilter();
    }

    public function toggleMonth()
    {
        $this->useMonth = !$this->useMonth;
        $this->resetDateFilter();
        $this->resetDateRangeFilter();
    }

    public function toggleDateRange()
    {
        $this->useDateRange = !$this->useDateRange;
        $this->resetDateFilter();
        $this->resetMonthFilter();
    }

    private function resetDateFilter()
    {
        $this->reset([
            'useDate',
            'searchDate'
        ]);
    }

    private function resetMonthFilter()
    {
        $this->reset([
            'useMonth',
            'searchMonth'
        ]);
    }

    private function resetDateRangeFilter()
    {
        $this->reset([
            'useDateRange',
            'searchStartDate',
            'searchEndDate'
        ]);
    }

    public function clearFilters()
    {
        $this->reset('searchTicket');
        $this->resetDateFilter();
        $this->resetMonthFilter();
        $this->resetDateRangeFilter();
    }

    public function isEmptyFilteredTickets()
    {
        return $this->priorityLevelTickets->isEmpty()
            && (
                !$this->searchTicket
                && !$this->useDateRange
                && !$this->useDate
                && !$this->useMonth
            );
    }

    public function render()
    {
        $currentUser = User::find(auth()->user()->id);

        if ($currentUser->isServiceDepartmentAdmin()) {
            $this->priorityLevelTickets = Ticket::where(function ($statusQuery) {
                $statusQuery->whereNotIn('status_id', [Status::OVERDUE, Status::CLOSED, Status::DISAPPROVED])
                    ->whereIn('approval_status', [ApprovalStatusEnum::FOR_APPROVAL, ApprovalStatusEnum::APPROVED]);
            })
                ->where('priority_level_id', $this->priorityLevel->id)
                ->whereHas('user', function ($user) {
                    $user->withTrashed()
                        ->whereHas('branches', function ($branch) {
                            $branch->whereIn('branches.id', auth()->user()->branches->pluck('id')->toArray());
                        })
                        ->whereHas('buDepartments', function ($department) {
                            $department->whereIn('departments.id', auth()->user()->buDepartments->pluck('id')->toArray());
                        })
                        ->orWhereHas('tickets', function ($ticket) {
                            $ticket->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                                ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray());
                        });
                })
                ->whereHas('ticketApprovals.helpTopicApprover', function ($approver) {
                    $approver->where('user_id', auth()->user()->id);
                })
                ->where('ticket_number', 'like', "%{$this->searchTicket}%")
                ->when($this->useDate, fn($query) => $query->whereDate('created_at', $this->searchDate))
                ->when($this->useMonth, fn($query) => $query->whereMonth('created_at', $this->searchMonth))
                ->when($this->useDateRange, fn($query) => $query->whereBetween('created_at', [$this->searchStartDate, $this->searchEndDate]))
                ->orderByDesc('created_at')
                ->paginate($this->paginatePageNumber);
        }

        if ($currentUser->isAgent()) {
            $this->priorityLevelTickets = Ticket::where(function ($statusQuery) {
                $statusQuery->whereNotIn('status_id', [Status::OVERDUE, Status::CLOSED])
                    ->whereIn('approval_status', [ApprovalStatusEnum::APPROVED]);
            })
                ->where('priority_level_id', $this->priorityLevel->id)
                ->whereIn('branch_id', auth()->user()->branches->pluck('id')->toArray())
                ->whereIn('service_department_id', auth()->user()->serviceDepartments->pluck('id')->toArray())
                ->whereHas('agent', fn($agent) => $agent->where('id', auth()->user()->id))
                ->whereHas('ticketApprovals', function ($approval) {
                    $approval->orWhere('is_approved', true);
                })
                ->where('ticket_number', 'like', "%{$this->searchTicket}%")
                ->when($this->useDate, fn($query) => $query->whereDate('created_at', $this->searchDate))
                ->when($this->useMonth, fn($query) => $query->whereMonth('created_at', $this->searchMonth))
                ->when($this->useDateRange, fn($query) => $query->whereBetween('created_at', [$this->searchStartDate, $this->searchEndDate]))
                ->orderByDesc('created_at')
                ->paginate($this->paginatePageNumber);
        }

        if ($currentUser->isSystemAdmin()) {
            $this->priorityLevelTickets = Ticket::where(function ($statusQuery) {
                $statusQuery->whereNotIn('status_id', [Status::OVERDUE, Status::CLOSED])
                    ->whereIn('approval_status', [ApprovalStatusEnum::APPROVED, ApprovalStatusEnum::FOR_APPROVAL]);
            })
                ->where('priority_level_id', $this->priorityLevel->id)
                ->where('ticket_number', 'like', "%{$this->searchTicket}%")
                ->when($this->useDate, fn($query) => $query->whereDate('created_at', $this->searchDate))
                ->when($this->useMonth, fn($query) => $query->whereMonth('created_at', $this->searchMonth))
                ->when($this->useDateRange, fn($query) => $query->whereBetween('created_at', [$this->searchStartDate, $this->searchEndDate]))
                ->orderByDesc('created_at')
                ->paginate($this->paginatePageNumber);
        }

        return view('livewire.staff.priority-level-ticket', [
            'priorityLevelTickets' => $this->priorityLevelTickets,
        ]);
    }
}
