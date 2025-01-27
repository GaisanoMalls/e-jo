<?php

namespace App\Http\Livewire\Staff;

use App\Models\PriorityLevel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class PriorityLevelTicket extends Component
{
    use WithPagination;

    public Collection|LengthAwarePaginator $viewedTickets;
    public Collection $priorityLevels;
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
        return $this->viewedTickets->isEmpty()
            && (
                !$this->searchTicket
                && !$this->useDateRange
                && !$this->useDate
                && !$this->useMonth
            );
    }

    public function render()
    {
        return view('livewire.staff.priority-level-ticket');
    }
}
