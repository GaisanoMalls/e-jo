@php
    use App\Models\Role;
@endphp

<div>
    @if ($this->isRecommendationRequested())
        @if ($this->isTicketRecommendationIsApproved())
            <div class="alert d-inline-block gap-1 border-0 py-2 px-3" role="alert"
                style="font-size: 13px; background-color: #dffdef;">
                <i class="bi bi-check-circle-fill" style="color: #d32839;"></i>
                Approved
            </div>
        @else
            @if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN))
                @if ($this->isRequesterServiceDeptAdmin())
                    <div class="alert d-inline-block mb-4 gap-1 border-0 py-2 px-3" role="alert"
                        style="font-size: 13px; background-color: #cff4fc; color: #055160;">
                        <i class="bi bi-info-circle-fill" style="color: #d32839;"></i>
                        Pending approval
                    </div>
                @else
                    <div class="mb-4 d-flex flex-wrap gap-2 border-0 flex-row rounded-3 align-items-center justify-content-between p-3"
                        style="margin-left: 1px; margin-right: 1px; box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.05) 0px 8px 32px;">
                        @foreach ($recommendationApprovers as $recommendation)
                            <span class="border-0 d-flex align-items-center" style="font-size: 0.9rem;">
                                <span class="me-2">
                                    <div class="d-flex align-items-center">
                                        @if ($recommendation->approver->profile->picture)
                                            <img src="{{ Storage::url($recommendation->approver->profile->picture) }}"
                                                class="image-fluid rounded-circle"
                                                style="height: 26px !important; width: 26px !important;">
                                        @else
                                            <div class="d-flex align-items-center p-2 me-1 justify-content-center text-white rounded-circle"
                                                style="background-color: #196837; height: 26px !important; width: 26px !important; font-size: 0.7rem;">
                                                {{ $recommendation->approver->profile->getNameInitial() }}
                                            </div>
                                        @endif
                                        <strong class="text-muted">
                                            {{ $recommendation->approver->profile->getFullName }}
                                        </strong>
                                    </div>
                                </span>
                                is requesting for approval
                            </span>
                        @endforeach
                        <button class="btn d-flex align-items-center justify-content-center"
                            wire:click="approveRecommendation"
                            style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; color: #FFF; font-weight: 500; background-color: #D32839;">
                            <span wire:loading wire:target="approveRecommendation"
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            <span wire:loading.remove wire:target="approveRecommendation">Approve</span>
                            <span wire:loading wire:target="approveRecommendation">Processing...</span>
                        </button>
                    </div>
                @endif
            @elseif (auth()->user()->hasRole(Role::AGENT))
                @if ($this->isTicketRecommendationIsApproved())
                    <div class="alert d-inline-block gap-1 border-0 py-2 px-3" role="alert"
                        style="font-size: 13px; background-color: #dffdef;">
                        <i class="bi bi-check-circle-fill" style="color: #d32839;"></i>
                        Approved
                    </div>
                @else
                    <div class="alert d-inline-block mb-4 gap-1 border-0 py-2 px-3" role="alert"
                        style="font-size: 13px; background-color: #cff4fc; color: #055160;">
                        <i class="bi bi-info-circle-fill" style="color: #d32839;"></i>
                        Pending Approval
                    </div>
                @endif
            @endif
        @endif
    @endif
</div>
