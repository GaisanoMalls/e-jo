<div>
    <div class="card border-0 p-0 card__ticket__details card__ticket__details__right">
        <div class="ticket__details__card__body__right">
            <div class="mb-3 d-flex justify-content-between">
                <small class="ticket__actions__label">
                    {{ $ticket->helpTopic->levels->count() }}
                    Approvals
                </small>
            </div>
            @foreach ($ticket->helpTopic->levels as $level)
            Level: {{ $level->value }}
            Approvers:
            <ul>
                @foreach ($levelApprovers as $levelApprover)
                @foreach ($approvers as $approver)
                @if ($levelApprover->user_id === $approver->id)
                @if ($levelApprover->level_id === $level->id)
                <li>{{ $approver->profile->getFullName() }}</li>
                @endif
                @endif
                @endforeach
                @endforeach
            </ul>
            @endforeach
        </div>
    </div>
</div>