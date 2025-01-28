<?php

namespace App\Http\Livewire\Requester\Ticket;

use App\Http\Requests\Requester\ReplyTicketRequest;
use App\Http\Traits\AppErrorLog;
use App\Http\Traits\Utils;
use App\Mail\Requester\RequesterReplyMail;
use App\Models\ActivityLog;
use App\Models\Reply;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\AppNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SendTicketReply extends Component
{
    use WithFileUploads, Utils;

    public Ticket $ticket;
    public int $upload = 0;
    public ?string $description = null;
    public $replyFiles = [];

    public function rules()
    {
        return (new ReplyTicketRequest())->rules();
    }

    public function messages()
    {
        return (new ReplyTicketRequest())->messages();
    }

    private function triggerEvents()
    {
        $events = [
            'loadTicketLogs',
            'loadTicketDetails',
            'loadDiscussionsCount',
            'loadTicketDiscussions',
            'loadTicketStatusHeaderText',
        ];

        foreach ($events as $event) {
            $this->emit($event);
        }
    }

    private function actionOnSubmit()
    {
        $this->replyFiles = [];
        $this->upload++;
        $this->triggerEvents();
        $this->reset('description');
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('reload-modal');
    }

    public function sendTicketReply()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->ticket->update(['status_id' => Status::ON_PROCESS]);

                $reply = Reply::create([
                    'user_id' => auth()->user()->id,
                    'ticket_id' => $this->ticket->id,
                    'description' => $this->description,
                ]);

                if ($this->replyFiles) {
                    collect($this->replyFiles)->each(function ($uploadedReplyFile) use ($reply) {
                        $fileName = $uploadedReplyFile->getClientOriginalName();
                        $fileAttachment = Storage::putFileAs(
                            "public/ticket/{$this->ticket->ticket_number}/reply_attachments/" . $this->fileDirByUserType(),
                            $uploadedReplyFile,
                            $fileName
                        );
                        $reply->fileAttachments()->create(['file_attachment' => $fileAttachment]);
                    });
                }

                $latestReply = Reply::where('ticket_id', $this->ticket->id)
                    ->withWhereHas('user', fn($user) => $user->role(Role::USER))
                    ->latest('created_at')->first();

                $latestStaff = $reply->whereHas('user', fn($user) => $user->where('id', '!=', auth()->user()->id))
                    ->where('ticket_id', $this->ticket->id)
                    ->latest('created_at')->first();

                $serviceDepartmentAdmins = User::role(Role::SERVICE_DEPARTMENT_ADMIN)
                    ->with(['branches', 'buDepartments', 'serviceDepartments'])
                    ->get();

                $serviceDepartmentAdmins->each(function ($serviceDepartmentAdmin) use ($latestStaff) {
                    if (
                        $this->ticket->whereIn('service_department_id', $serviceDepartmentAdmin->serviceDepartments->pluck('id')->toArray())
                            ->whereIn('branch_id', $serviceDepartmentAdmin->branches->pluck('id')->toArray())
                            ->orWhereHas('user', function ($user) use ($serviceDepartmentAdmin) {
                                $user->whereHas('branches', function ($branch) use ($serviceDepartmentAdmin) {
                                    $branch->whereIn('branches.id', $serviceDepartmentAdmin->branches->pluck('id')->toArray());
                                })
                                    ->whereHas('buDepartments', function ($department) use ($serviceDepartmentAdmin) {
                                        $department->whereIn('departments.id', $serviceDepartmentAdmin->buDepartments->pluck('id')->toArray());
                                    });
                            })
                            ->exists()
                    ) {
                        Notification::send(
                            $serviceDepartmentAdmin,
                            new AppNotification(
                                ticket: $this->ticket,
                                title: "Ticket #{$this->ticket->ticket_number} (Replied)",
                                message: "Ticket reply from {$this->ticket->user->profile->getFullName}",
                            )
                        );
                        Mail::to($serviceDepartmentAdmin)
                            ->send(new RequesterReplyMail(
                                $this->ticket,
                                $serviceDepartmentAdmin,
                                $this->description
                            ));
                    }
                });

                ActivityLog::make(ticket_id: $this->ticket->id, description: "replied to {$latestReply->user->profile->getFullName}");
            });

            $this->actionOnSubmit();

        } catch (Exception $e) {
            AppErrorLog::getError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.requester.ticket.send-ticket-reply');
    }
}
