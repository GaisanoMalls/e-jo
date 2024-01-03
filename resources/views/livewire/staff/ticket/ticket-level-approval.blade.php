<div>
    <div class="card border-0 p-0 card__ticket__details">
        <div class="d-flex flex-column gap-3 ticket__details__card__body__right">
            <label class="ticket__actions__label">Level of Approval</label>
            <div class="d-flex flex-column gap-1">
                <small>Level 1 {{ $level1Approvers->count() > 1 ? 'Approvers' : 'Approver' }}</small>
                @foreach ($level1Approvers as $level1Approver)
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <small>
                            {{ $level1Approver->profile->getFullName() }}
                            @if ($level1Approver->id == auth()->user()->id)
                                <span class="text-muted">(You)</span>
                            @endif
                        </small>
                        @if ($level1Approver->id == auth()->user()->id)
                            <button class="btn btn-sm" type="button">Approve</button>
                        @endif
                    </div>
                @endforeach
            </div>
            {{-- <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <small>Level 2 Approver</small>
                <small>For Approval</small>
            </div> --}}
        </div>
    </div>
</div>
