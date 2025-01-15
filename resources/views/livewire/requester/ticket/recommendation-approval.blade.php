@php
    use App\Models\Role;
    use Illuminate\Support\Carbon;
    use App\Enums\RecommendationApprovalStatusEnum;
@endphp

@if ($recommendations->isNotEmpty() && $this->isRecommendationRequested())
    <div>
        @if ($approvalHistory->isNotEmpty())
            <div class="accordion mb-4" id="approvalHistoryAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="box-shadow: none; font-size: 13px;">
                            Approval history
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#approvalHistoryAccordion">
                        <div class="accordion-body">
                            <ol class="list-group">
                                @foreach ($approvalHistory as $recommendation)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div class="me-auto ms-2" style="font-size: 13px;">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-bold" @style([
                                                    'color: #006102' => $recommendation->approvalStatus->approval_status === RecommendationApprovalStatusEnum::APPROVED->value,
                                                    'color: #F7454A' => $recommendation->approvalStatus->approval_status === RecommendationApprovalStatusEnum::DISAPPROVED->value,
                                                ])>
                                                    {{ $recommendation->approvalStatus->approval_status }}
                                                </span>
                                                @if ($recommendation->approvalStatus->date)
                                                    -
                                                    <span class="fw-semibold text-muted" style="font-size: 12px;">
                                                        {{ $recommendation->approvalStatus->dateApprovedOrDisapproved() }},
                                                        {{ Carbon::parse($recommendation->approvalStatus->date)->format('D') }}
                                                        @
                                                        {{ Carbon::parse($recommendation->approvalStatus->date)->format('g:i A') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span>
                                                    <span style="text-decoration: underline !important;">
                                                        Requested by:
                                                    </span>
                                                    {{ $recommendation->requestedByServiceDeptAdmin->profile->getFullName }}
                                                </span>
                                                <span>
                                                    <span style="text-decoration: underline !important;">
                                                        Request:
                                                    </span>
                                                    {!! nl2br($recommendation->reason) !!}
                                                </span>
                                                @if ($this->isDisapprovedRecommendation($recommendation))
                                                    <span>
                                                        <span style="text-decoration: underline !important;">
                                                            Reason of disapproval:
                                                        </span>
                                                        {!! nl2br($recommendation->approvalStatus->disapproved_reason) !!}
                                                    </span>
                                                @endif
                                                <div>
                                                    <p class="mb-0">
                                                        <button class="btn btn-sm p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#showApprovers{{ $recommendation->id }}" aria-expanded="false" aria-controls="showApprovers{{ $recommendation->id }}" style="font-size: 13px; text-decoration: underline !important;">
                                                            Show approvers
                                                        </button>
                                                    </p>
                                                    <div class="position-absolute" style="min-height: 120px; z-index: 2;">
                                                        <div class="collapse" id="showApprovers{{ $recommendation->id }}">
                                                            <div class="card card-body">
                                                                <div class="d-flex flex-column flex-wrap gap-2">
                                                                    <small class="fw-semibold">Approvers</small>
                                                                    <div class="d-flex flex-wrap gap-3">
                                                                        @foreach ($approvalLevels as $level)
                                                                            @php $approvers = $this->fetchApprovers($level, $recommendation); @endphp
                                                                            @if ($approvers->isNotEmpty())
                                                                                <div class="d-flex flex-column gap-1">
                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                        @if ($this->isLevelApproved($level, $recommendation))
                                                                                            <i class="bi bi-check-circle-fill" style="font-size: 0.75rem; color: green;"></i>
                                                                                        @elseif ($this->isDisApprovedRecommendationLevel($level, $recommendation))
                                                                                            <i class="bi bi-x-circle-fill" style="color: red;"></i>
                                                                                        @else
                                                                                            <i class="bi bi-circle" style="font-size: 0.75rem;"></i>
                                                                                        @endif
                                                                                        <small class="fw-semibold" style="font-size: 0.75rem;">
                                                                                            Level {{ $level }}
                                                                                        </small>
                                                                                    </div>
                                                                                    <div class="d-flex gap-1">
                                                                                        @foreach ($approvers as $approver)
                                                                                            <small class="rounded-5 border border-2 px-2" style="font-size: 0.70rem;">{{ $approver->profile->getFullName }}</small>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span style="font-size: 11px;">
                                            @if ($recommendation->created_at == $recommendation->updated_at)
                                                New
                                            @else
                                                {{ $recommendation->dateUpdated() }}
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
