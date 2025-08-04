<?php

namespace App\Http\Livewire\Approver\Account;

use App\Models\User;
use Livewire\Component;
use App\Models\UserApproval as UserApprovalModel;
use Illuminate\Support\Facades\DB;

class UserApproval extends Component
{
    public function render()
    {
        $userApprovals = UserApprovalModel::with('user.profile')
            ->where('is_approved', false)
            ->latest()
            ->get();

        return view('livewire.approver.account.user-approval', compact('userApprovals'));
    }

    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            $approval = UserApprovalModel::findOrFail($id);
            
            $approval->update([
                'is_approved' => true,
                'date_approved' => now(),
            ]);

            User::findOrFail($approval->user_id)
                ->update(['is_active' => true]);
        });
        noty()->addSuccess('User approved successfully.');
    }

    public function reject($id)
    {
        $approval = UserApprovalModel::findOrFail($id);
        $approval->delete();

        noty()->addSuccess('User rejected successfully.');
    }

    public function view($id)
    {
        return redirect()->route('user-approvals.show', $id);
    }
}
