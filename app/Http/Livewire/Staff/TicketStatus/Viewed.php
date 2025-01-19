<?php

namespace App\Http\Livewire\Staff\TicketStatus;

use App\Http\Traits\TicketsByStaffWithSameTemplates;
use App\Models\PriorityLevel;
use Illuminate\Support\Collection;
use Livewire\Component;

class Viewed extends Component
{
    use TicketsByStaffWithSameTemplates;

    public Collection $viewedTickets;
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
    public array $pageNumberOptions = [5, 10, 20, 50];
    public int $paginatePageNumber = 5;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->priorityLevels = PriorityLevel::orderBy('value')->get(['id', 'name', 'color']);
    }

    public function clearSearchTicket()
    {
        $this->searchTicket = "";
        $this->priorityLevelId = null;
        $this->priorityLevelName = null;
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

    public function isEmptyFilteredTickets()
    {
        return $this->viewedTickets->isEmpty()
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
        $this->viewedTickets = $this->getViewedTickets()->filter(function ($ticket) {
            $matchSearch = stripos($ticket->ticket_number, $this->searchTicket) !== false
                || stripos($ticket->subject, $this->searchTicket) !== false
                || stripos($ticket->branch->name, $this->searchTicket) !== false;

            $matchesPriority = $this->priorityLevelId ? $ticket->priority_level_id == $this->priorityLevelId : true;
            $matchesDateRange = $this->searchTicketByDate($ticket, $this->startDate, $this->endDate);

            return $matchSearch && $matchesPriority && $matchesDateRange;
        });

        return view('livewire.staff.ticket-status.viewed', [
            'viewedTickets' => $this->viewedTickets
        ]);
    }
}