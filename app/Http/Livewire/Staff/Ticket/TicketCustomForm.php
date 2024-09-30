<?php

namespace App\Http\Livewire\Staff\Ticket;

use App\Http\Traits\AppErrorLog;
use App\Models\FieldHeaderValue;
use App\Models\FieldRowValue;
use App\Models\IctRecommendation;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TicketCustomForm extends Component
{
    public Ticket $ticket;
    public ?IctRecommendation $ictRecommendationServiceDeptAdmin = null;
    public array $customFormHeaderFields = [];
    public array $customFormRowFields = [];

    protected $listeners = ['refreshCustomForm' => '$refresh'];

    public function mount()
    {
        $this->customFormHeaderFields = FieldHeaderValue::with('field')->where('ticket_id', $this->ticket->id)->get()->toArray();
        $this->customFormRowFields = FieldRowValue::with('field')->where('ticket_id', $this->ticket->id)->get()->toArray();
    }

    public function getFilteredRowFields()
    {
        $headers = [];
        $fields = [];

        foreach ($this->customFormRowFields as $fieldData) {
            $fieldName = $fieldData['field']['name'];
            $rowId = $fieldData['row'];

            if (!isset($fields[$rowId])) {
                $fields[$rowId] = [];
            }

            if (!in_array($fieldName, $headers)) {
                $headers[] = $fieldName;
            }

            $fields[$rowId][$fieldName] = $fieldData['value'];
        }

        sort($headers);

        return ['headers' => $headers, 'fields' => $fields];
    }

    public function approveIctRecommendation()
    {
        try {
            DB::transaction(function () {
                IctRecommendation::where('ticket_id', $this->ticket->id)->update(['is_approved' => true]);
                $this->emit('refreshCustomForm');
            });
        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
            Log::error('Error while sending recommendation request.', [$e->getLine()]);
        }
    }

    public function isTicketIctRecommendationIsApproved()
    {
        return IctRecommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_approved', true]
        ])->exists();
    }

    public function isRecommendationRequested()
    {
        return IctRecommendation::where([
            ['ticket_id', $this->ticket->id],
            ['is_requesting_ict_approval', true],
        ])->exists();
    }

    /**
     * Verify whether the business unit of the ticket requester matches the business unit of the Service Department Admin.
     */
    public function isRequesterServiceDeptAdmin()
    {
        return User::where('id', auth()->user()->id)
            ->withWhereHas('branches', function ($branch) {
                $branch->whereIn('branches.id', $this->ticket->user->branches->pluck('id')->toArray());
            })
            ->withWhereHas('buDepartments', function ($department) {
                $department->whereIn('departments.id', $this->ticket->user->buDepartments->pluck('id')->toArray());
            })
            ->withWhereHas('roles', fn($role) => $role->where('name', Role::SERVICE_DEPARTMENT_ADMIN))
            ->exists();
    }

    public function render()
    {
        $this->ictRecommendationServiceDeptAdmin = IctRecommendation::with('requestedByServiceDeptAdmin.profile')
            ->where('ticket_id', $this->ticket->id)
            ->first();

        return view('livewire.staff.ticket.ticket-custom-form');
    }
}
