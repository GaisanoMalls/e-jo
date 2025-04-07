<?php

namespace App\Http\Livewire\Requester\TicketStatus;

use App\Enums\ApprovalStatusEnum;
use App\Http\Traits\Requester\Tickets;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Livewire\Component;

class Approved extends Component
{
    use Tickets;

    public bool $allApprovedTickets = true;
    public bool $withCosting = false;
    public bool $withOutCosting = false;
    public Collection $approvedTickets;

    public function mount()
    {
        $this->approvedTickets = $this->loadApprovedTickets();
    }

    /**
     * Filters and loads all approved tickets.
     *
     * This function resets the costing filters (`withCosting` and `withOutCosting`) and sets
     * the `allApprovedTickets` flag to true. It then calls the `loadApprovedTickets` method
     * to fetch and display all approved tickets for the currently logged-in user.
     *
     * @return void
     */
    public function filterAllApprovedTickets()
    {
        // Disable filters for tickets with or without costing.
        $this->withCosting = false;
        $this->withOutCosting = false;

        // Enable the filter to show all approved tickets.
        $this->allApprovedTickets = true;

        // Load all approved tickets.
        $this->loadApprovedTickets();
    }

    /**
     * Filters and loads approved tickets with costing.
     *
     * This function enables the filter to show only approved tickets that have associated costing.
     * It disables the filters for all approved tickets and tickets without costing, then calls
     * the `loadApprovedTickets` method to fetch and display the filtered tickets.
     *
     * @return void
     */
    public function filterApprovedTicketsWithCosting()
    {
        // Disable the filter for all approved tickets.
        $this->allApprovedTickets = false;

        // Disable the filter for tickets without costing.
        $this->withOutCosting = false;

        // Enable the filter for tickets with costing.
        $this->withCosting = true;

        // Load the approved tickets with costing.
        $this->loadApprovedTickets();
    }

    /**
     * Filters and loads approved tickets without costing.
     *
     * This function enables the filter to show only approved tickets that do not have associated costing.
     * It disables the filters for all approved tickets and tickets with costing, then calls
     * the `loadApprovedTickets` method to fetch and display the filtered tickets.
     *
     * @return void
     */
    public function filterApprovedTicketsWithoutCosting()
    {
        // Disable the filter for all approved tickets.
        $this->allApprovedTickets = false;

        // Disable the filter for tickets with costing.
        $this->withCosting = false;

        // Enable the filter for tickets without costing.
        $this->withOutCosting = true;

        // Load the approved tickets without costing.
        $this->loadApprovedTickets();
    }

    /**
     * Loads approved tickets based on the selected filter.
     *
     * This function retrieves approved tickets for the currently logged-in user based on the applied filter:
     * 1. If the `allApprovedTickets` flag is true, it fetches all approved tickets using the `getApprovedTickets` method.
     * 2. If the `withCosting` flag is true, it fetches approved tickets that have associated costing.
     * 3. If the `withOutCosting` flag is true, it fetches approved tickets that do not have associated costing.
     *
     * @return \Illuminate\Support\Collection A collection of approved tickets based on the selected filter.
     */
    public function loadApprovedTickets()
    {
        // Check if the filter is set to load all approved tickets.
        if ($this->allApprovedTickets) {
            $this->approvedTickets = $this->getApprovedTickets();
        }

        // Check if the filter is set to load approved tickets with costing.
        if ($this->withCosting) {
            $this->approvedTickets = Ticket::whereHas('ticketCosting') // Filter tickets with costing.
                ->with(['replies', 'priorityLevel']) // Load related replies and priority level.
                ->where([
                    ['approval_status', ApprovalStatusEnum::APPROVED], // Only approved tickets.
                    ['user_id', auth()->user()->id] // Belonging to the logged-in user.
                ])
                ->orderByDesc('created_at') // Order by the latest created tickets.
                ->get();
        }

        // Check if the filter is set to load approved tickets without costing.
        if ($this->withOutCosting) {
            $this->approvedTickets = Ticket::whereDoesntHave('ticketCosting') // Filter tickets without costing.
                ->with(['replies', 'priorityLevel']) // Load related replies and priority level.
                ->where([
                    ['approval_status', ApprovalStatusEnum::APPROVED], // Only approved tickets.
                    ['user_id', auth()->user()->id] // Belonging to the logged-in user.
                ])
                ->orderByDesc('created_at') // Order by the latest created tickets.
                ->get();
        }

        // Return the collection of approved tickets.
        return $this->approvedTickets;
    }

    public function render()
    {
        return view('livewire.requester.ticket-status.approved');
    }
}