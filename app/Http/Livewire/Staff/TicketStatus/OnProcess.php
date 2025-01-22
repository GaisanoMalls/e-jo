<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Models\PriorityLevel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class OnProcess extends Component
{
    use TicketsByStaffWithSameTemplates, WithPagination;

    public Collection|LengthAwarePaginator $onProcessTickets;
    public Collection $priorityLevels;
    public string $searchTicket = "";
    public ?int $priorityLevelId = null;
    public ?string $priorityLevelName = null;
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
        $this->paginatePageNumber = $this->pageNumberOptions[0];
        $this->priorityLevels = PriorityLevel::orderBy('value')->get(['id', 'name', 'color']);
    }

    public function hasSearchQuery()
    {
        return $this->priorityLevelId
            || $this->searchTicket
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

    public function filterPriorityLevel(PriorityLevel $priorityLevel)
    {
        $this->priorityLevelId = $priorityLevel->id;
        $this->priorityLevelName = $priorityLevel->name;
    }

    public function filterAllPriorityLevels()
    {
        $this->priorityLevelId = null;
        $this->priorityLevelName = null;
    }

    public function clearFilters()
    {
        $this->reset([
            'priorityLevelId',
            'priorityLevelName',
            'searchTicket'
        ]);
        $this->resetDateFilter();
        $this->resetMonthFilter();
        $this->resetDateRangeFilter();
    }

    public function isEmptyFilteredTickets()
    {
        return $this->onProcessTickets->isEmpty()
            && (
                !$this->searchTicket
                && !$this->priorityLevelId
                && !$this->useDateRange
                && !$this->useDate
                && !$this->useMonth
            );
    }

    public function render()
    {
        $filteredTickets = $this->getOnProcessTickets()->filter(function ($ticket) {
            $matchSearch = stripos($ticket->ticket_number, $this->searchTicket) !== false
                || stripos($ticket->subject, $this->searchTicket) !== false
                || stripos($ticket->branch->name, $this->searchTicket) !== false;

            $matchesPriority = $this->priorityLevelId ? $ticket->priority_level_id == $this->priorityLevelId : true;
            $matchesDateRange = $this->searchTicketByDate(
                ticket: $ticket,
                searchStartDate: $this->searchStartDate,
                searchEndDate: $this->searchEndDate,
                searchDate: $this->searchDate,
                searchMonth: $this->searchMonth,
                useDate: $this->useDate,
                useMonth: $this->useMonth,
                useDateRange: $this->useDateRange,
            );

            return $matchSearch && $matchesPriority && $matchesDateRange;
        });

        $page = request()->get('page', 1);
        $paginatedTickets = new LengthAwarePaginator(
            $filteredTickets->forPage($page, $this->paginatePageNumber),
            $filteredTickets->count(),
            $this->paginatePageNumber,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $this->onProcessTickets = collect($paginatedTickets->items());

        return view('livewire.staff.ticket-status.on-process', [
            'onProcessTickets' => $this->onProcessTickets,
            'paginatedTickets' => $paginatedTickets
        ]);
    }
}