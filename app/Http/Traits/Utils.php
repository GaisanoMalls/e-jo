<?php

namespace App\Http\Traits;

use App\Enums\ApprovalStatusEnum;
use App\Models\Role;
use App\Models\SpecialProjectAmountApproval;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Str;

trait Utils
{
    /**
     * Timestamps
     * Methods:
     * - createdAt()
     * - updatedAt()
     * @return string
     */

    public function createdAt($created_field)
    {
        return Carbon::parse($created_field)->format('M d, Y');
    }

    /**
     * @return string
     */
    public function updatedAt($created_field, $updated_field)
    {
        $created_at = Carbon::parse($created_field)->isoFormat('MMM DD, YYYY HH:mm:ss');
        $updated_at = Carbon::parse($updated_field)->isoFormat('MMM DD, YYYY HH:mm:ss');

        return $updated_at === $created_at
            ? "----"
            : Carbon::parse($updated_field)->format('M d, Y | h:i A');
    }

    /**
     * Ticket Number Generator
     * Methods:
     * - generatedTicketNumber()
     * - currentMonth()
     * - alphaNum()
     * @return string
     */
    public static function generatedTicketNumber()
    {
        return self::alphaNum() . "-" . self::currentMonth();
    }

    /**
     * @return string
     */
    private static function currentMonth()
    {
        return date('m');
    }

    /**
     * @throws Exception
     * @return string
     */
    private static function alphaNum()
    {
        $maxAttempts = 20; // Maximum number of attempts to generate a unique value
        $letters = 'abcdefghijklmnopqrstuvwxyz';

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $alpha = strtoupper(str_shuffle($letters));
            $num = mt_rand(100, 999);
            $value = $alpha[0] . $num;

            $ticketExists = Ticket::where('ticket_number', $value)->exists();
            if (!$ticketExists) {
                return $value;
            }
        }

        return self::alphaNum();
    }


    /**
     * Slug Generator
     * Method:
     * - slugify()
     * @return string
     */
    public static function slugify(string $word)
    {
        return mt_rand(10000, 99999) . "-" . Str::slug($word);
    }

    /**
     * File Upload Dir.
     * Method:
     * - fileDirByUserType()
     * - generateNewProfilePictureName()
     * @return string
     */
    public function fileDirByUserType()
    {
        //        $staffRolePath = '';
        return match (true) {
            Auth::user()->hasRole(Role::SYSTEM_ADMIN) => 'system_admin',
            Auth::user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) => 'service_department_admin',
            Auth::user()->hasRole(Role::APPROVER) => 'approver',
            Auth::user()->hasRole(Role::AGENT) => 'agent',
            Auth::user()->hasRole(Role::USER) => 'requester',
            default => 'guest',
        };
    }

    /**
     * Generate a new name for the uploaded user profile picture using its full name.
     * @return string
     * */
    public function generateNewProfilePictureName($picture)
    {
        return time() . "_" . Str::slug(auth()->user()->profile->getFullName()) . "." . $picture->getClientOriginalExtension();
    }

    public function ticketSLATimer(Ticket $ticket)
    {
        $slaHours = (int) $this->ticket->sla->hours; // Assuming SLA is in hours

        // Get the current date and time
        $currentDate = now()->timestamp;

        // Get the target date from the server or any other data source
        $targetDate = Carbon::parse($ticket->svcdept_date_approved)->addHours($slaHours)->timestamp;

        // Calculate the time remaining
        $timeRemaining = $targetDate - $currentDate;

        // Calculate total SLA time
        $totalSlaTime = $slaHours * 3600; // Convert hours to seconds

        // Calculate time elapsed
        $timeElapsed = $totalSlaTime - $timeRemaining;

        // Calculate percentage of time elapsed
        $percentageElapsed = ($timeElapsed / $totalSlaTime) * 100;

        // Check if the countdown has reached zero
        if ($timeRemaining <= 0) {
            $percentageElapsed = 100; // Set to 100% if overdue
            $timer = 'OVERDUE';
        } else {
            $days = floor($timeRemaining / (60 * 60 * 24));
            $hours = floor(($timeRemaining % (60 * 60 * 24)) / (60 * 60));
            $minutes = floor(($timeRemaining % (60 * 60)) / 60);

            $timer = '';

            if ($days > 0) {
                $timer .= "{$days} days, ";
            }

            if ($hours > 0) {
                $timer .= "{$hours} hours, ";
            }

            $timer .= "{$minutes} minutes";
        }

        return [
            'timer' => $timer,
            'percentageElapsed' => (int) $percentageElapsed
        ];
    }

    /**
     * @return bool
     */
    public function isSlaApproved(Ticket $ticket)
    {
        return ($this->ticket->status_id == Status::APPROVED
            || $this->ticket->approval_status == ApprovalStatusEnum::APPROVED
            && !is_null($this->ticket->svcdept_date_approved))
            ? true
            : false;
    }

    /**
     * Check wether sla is overdue
     * @return bool
     */
    public function isSlaOverdue(Ticket $ticket)
    {
        return $this->ticketSLATimer($this->ticket)['timer'] === 'OVERDUE';
    }

    public function getSLADays(Ticket $ticket)
    {
        return (preg_match('/(\d+)/', $ticket->sla->time_unit, $matches))
            ? $matches[0]
            : 0;
    }

    public function getSLAUnit(Ticket $ticket)
    {
        return trim(str_replace($this->getSLADays($ticket), "", $ticket->sla->time_unit));
    }

    /**
     * @return bool
     */
    public function isApproved2LevelsOfApproverAndHasSpecialProject()
    {
        $ticketHasAllApproved = Ticket::has('helpTopic.specialProject')->withWhereHas('ticketApprovals', fn($ticketApproval) =>
            $ticketApproval->whereNotNull('approval_1->level_1_approver->approver_id')
                ->whereNotNull('approval_1->level_2_approver->approver_id')
                ->whereNotNull('approval_1->level_1_approver->approved_by')
                ->whereNotNull('approval_1->level_2_approver->approved_by')
                ->where([
                    ['approval_1->level_1_approver->is_approved', true],
                    ['approval_1->level_2_approver->is_approved', true],
                    ['approval_1->is_all_approved', true],
                ]))->get();

        return ($ticketHasAllApproved->isNotEmpty())
            ? true
            : false;
    }

    public function isOnlyAgent(int $agentId)
    {
        return auth()->user()->id === $agentId && auth()->user()->hasRole(Role::AGENT);
    }

    public function isSpecialProjectCostingApprover2(int $approverId, Ticket $ticket)
    {
        return auth()->user()->id === $approverId
            && auth()->user()->hasRole(Role::APPROVER)
            && SpecialProjectAmountApproval::where('ticket_id', $ticket->id)->whereJsonContains('fpm_coo_approver->approver_id', $approverId)->exists();
    }

    public function hasCostingApprover1()
    {
        return SpecialProjectAmountApproval::whereNotNull('service_department_admin_approver->approver_id')->exists();
    }

    public function hasCostingApprover2()
    {
        return SpecialProjectAmountApproval::whereNotNull('fpm_coo_approver->approver_id')->exists();
    }

    public function costingApprovers(Ticket $ticket)
    {
        $costingApprovers = SpecialProjectAmountApproval::all();
        $approverIds = array_merge(
            $costingApprovers->pluck('service_department_admin_approver.approver_id')->toArray(),
            ($this->isAmountForCOOApproval($ticket))
            ? $costingApprovers->pluck('fpm_coo_approver.approver_id')->toArray()
            : []
        );

        return User::with('profile')->whereIn('id', $approverIds)->get();
    }

    public function isAmountForCOOApproval(Ticket $ticket)
    {
        return ($ticket->ticketCosting && $ticket->helpTopic && $ticket->isSpecialProject())
            ? ($ticket->ticketCosting->amount >= $ticket->helpTopic->specialProject->amount) // true
            : false;
    }

    public function costingApprovedBy(User $user)
    {
        return SpecialProjectAmountApproval::where(
            fn($approver1) => $approver1->whereJsonContains('service_department_admin_approver->approver_id', $user->id)
                ->where('service_department_admin_approver->is_approved', true)
        )->orWhere(fn($approver2) => $approver2->whereJsonContains('fpm_coo_approver->approver_id', $user->id)->where('fpm_coo_approver->is_approved', true))
            ->exists();
    }

    public static function costingApprover2Only()
    {
        $approverId = User::role(Role::APPROVER)->where('id', auth()->user()->id)->value('id');
        return SpecialProjectAmountApproval::whereJsonContains('fpm_coo_approver->approver_id', $approverId)->exists();
    }
}
