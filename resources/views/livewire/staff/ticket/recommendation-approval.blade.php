@php
    use App\Models\Role;
    use App\Enums\RecommendationApprovalStatusEnum;
@endphp

@if ($recommendations->isNotEmpty() && $this->isRecommendationRequested())
    <div>
        @if ($newRecommendation)
            @if (auth()->user()->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) && $newRecommendation)
                <div class="mb-4 d-flex flex-wrap gap-2 border-0 flex-column rounded-3 p-3"
                    style="margin-left: 1px; margin-right: 1px; box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1">
                        @if (
                            $this->isRequesterServiceDeptAdmin() &&
                                $newRecommendation->approval_status === RecommendationApprovalStatusEnum::PENDING->value)
                            <div class="alert d-inline-block mb-0 gap-1 border-0 py-2 px-3" role="alert"
                                style="font-size: 13px; background-color: #cff4fc; color: #055160;">
                                <i class="bi bi-info-circle-fill" style="color: #d32839;"></i>
                                Pending approval
                            </div>
                        @else
                            <span class="border-0 d-flex align-items-center" style="font-size: 0.9rem;">
                                <span class="me-2">
                                    <div class="d-flex align-items-center">
                                        @if ($newRecommendation->requestedByServiceDeptAdmin->profile->picture)
                                            <img src="{{ Storage::url($newRecommendation->requestedByServiceDeptAdmin->profile->picture) }}"
                                                class="image-fluid rounded-circle"
                                                style="height: 26px !important; width: 26px !important;">
                                        @else
                                            <div class="d-flex align-items-center p-2 me-1 justify-content-center text-white rounded-circle"
                                                style="background-color: #196837; height: 26px !important; width: 26px !important; font-size: 0.7rem;">
                                                {{ $newRecommendation->requestedByServiceDeptAdmin->profile->getNameInitial() }}
                                            </div>
                                        @endif
                                        <strong class="text-muted">
                                            {{ $newRecommendation->requestedByServiceDeptAdmin->profile->getFullName }}
                                        </strong>
                                    </div>
                                </span>
                                is requesting for approval
                            </span>
                        @endif
                        <small style="font-weight: 500; color: #4a5568; font-size: 0.75rem;">
                            {{ $newRecommendation->dateCreated() }}
                            ({{ $newRecommendation->created_at->format('D') }},
                            {{ $newRecommendation->created_at->format('g:i A') }})
                        </small>
                    </div>
                    @if ($newRecommendation->reason)
                        <div class="d-flex flex-column gap-1">
                            <span class="fw-semibold" style="font-size: 0.85rem;">Reason:</span>
                            <span style="font-size: 0.85rem;">
                                {!! nl2br($newRecommendation->reason) !!}
                            </span>
                        </div>
                    @endif
                    @if (!$this->isRequesterServiceDeptAdmin() && $isAllowedToApproveRecommendation && $newRecommendation)
                        <div class="d-flex gap-2 mt-2">
                            <button type="button"
                                class="btn d-flex gap-2 align-items-center justify-content-center w-auto"
                                wire:click="approveTicketRecommendation" wire:loading.attr="disabled"
                                style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; color: #FFF; font-weight: 500; background-color: #D32839;">
                                <span wire:loading wire:target="approveTicketRecommendation"
                                    class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                </span>
                                <span wire:loading.remove wire:target="approveTicketRecommendation">Approve</span>
                                <span wire:loading wire:target="approveTicketRecommendation">Processing...</span>
                            </button>
                            <button type="button" class="btn d-flex align-items-center justify-content-center w-auto"
                                data-bs-toggle="modal" data-bs-target="#disapproveTicketRecommendationModal"
                                style="padding-top: 15px; padding-bottom: 15px; font-size: 0.75rem; height: 20px; color: #3e3d3d; font-weight: 500; background-color: #f3f4f6;">
                                Disapprove
                            </button>
                        </div>
                    @endif
                </div>
            @elseif (auth()->user()->hasRole(Role::AGENT))
                @if ($currentRecommendation->approval_status === RecommendationApprovalStatusEnum::APPROVED->value)
                    <div class="alert d-inline-block gap-1 border-0 py-2 px-3" role="alert"
                        style="font-size: 13px; background-color: #dffdef;">
                        <i class="bi bi-check-circle-fill" style="color: #d32839;"></i>
                        Approved
                    </div>
                @endif

                @if ($currentRecommendation->approval_status === RecommendationApprovalStatusEnum::PENDING->value)
                    <div class="alert d-inline-block mb-4 gap-1 border-0 py-2 px-3" role="alert"
                        style="font-size: 13px; background-color: #cff4fc; color: #055160;">
                        <i class="bi bi-info-circle-fill" style="color: #d32839;"></i>
                        Pending Approval
                    </div>
                @endif

                @if ($$currentRecommendation->approval_status === RecommendationApprovalStatusEnum::DISAPPROVED->value)
                    <div class="alert d-inline-block mb-4 gap-1 border-0 py-2 px-3" role="alert"
                        style="font-size: 13px; background-color: #cff4fc; color: #055160;">
                        <i class="bi bi-info-circle-fill" style="color: #d32839;"></i>
                        Disapproved
                    </div>
                @endif
            @endif
        @else
            @if ($currentRecommendation->approval_status === RecommendationApprovalStatusEnum::APPROVED->value)
                <div class="alert d-inline-block gap-1 border-0 py-2 px-3" role="alert"
                    style="font-size: 13px; background-color: #dffdef;">
                    <i class="bi bi-check-circle-fill" style="color: #d32839;"></i>
                    Approved
                </div>
            @endif

            @if ($currentRecommendation->approval_status === RecommendationApprovalStatusEnum::DISAPPROVED->value)
                <div class="alert d-inline-block mb-4 gap-1 border-0 py-2 px-3" role="alert"
                    style="font-size: 13px; background-color: #cff4fc; color: #055160;">
                    <i class="bi bi-info-circle-fill" style="color: #d32839;"></i>
                    Disapproved
                </div>
            @endif
        @endif

        @if ($approvalHistory->isNotEmpty())
            <div class="accordion mb-4" id="approvalHistoryAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"
                            style="box-shadow: none; font-size: 13px;">
                            Approval history
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#approvalHistoryAccordion">
                        <div class="accordion-body">
                            <ol class="list-group">
                                @foreach ($approvalHistory as $recommendation)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="ms-2 me-auto" style="font-size: 13px;">
                                            <div class="fw-bold">{{ $recommendation->approval_status }}</div>
                                            <div class="d-flex flex-column">
                                                <span>
                                                    <span style="text-decoration: underline !important;">
                                                        Request:
                                                    </span>
                                                    {!! nl2br($recommendation->reason) !!}
                                                </span>
                                                @if ($recommendation->disapproved_reason != null)
                                                    <span>
                                                        <span style="text-decoration: underline !important;">
                                                            Reason of disapproval:
                                                        </span>
                                                        {!! nl2br($recommendation->disapproved_reason) !!}
                                                    </span>
                                                @endif
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

        {{-- Reason for disapproval modal --}}
        <div wire:ignore.self class="modal fade ticket__actions__modal" id="disapproveTicketRecommendationModal"
            tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom__modal">
                <div class="modal-content d-flex flex-column custom__modal__content">
                    <div class="modal__header d-flex justify-content-between align-items-center">
                        <h6 class="modal__title">Disapprove ticket request</h6>
                        <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                            data-bs-dismiss="modal" id="btnCloseModal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal__body">
                        <form wire:submit.prevent="disapproveTicketRecommendation">
                            <div class="my-2">
                                <label class="ticket__actions__label mb-2">Reason</label>
                                <div wire:ignore>
                                    <textarea wire:model="disapprovedReason" class="form-control form__field" placeholder="Please state the reason"></textarea>
                                </div>
                                @error('disapprovedReason')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button wire:loading.attr="disabled" wire:target="disapproveTicketRecommendation"
                                type="submit" class="btn mt-3 d-flex align-items-center justify-content-center gap-2"
                                style="padding: 0.6rem 1rem;
                                    border-radius: 0.563rem;
                                    font-size: 0.875rem;
                                    background-color: #d32839;
                                    color: white;
                                    font-weight: 500;
                                    box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20, 0.07);">
                                <span wire:loading wire:target="disapproveTicketRecommendation"
                                    class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                </span>
                                <span wire:loading.remove wire:target="disapproveTicketRecommendation">
                                    Disapprove
                                </span>
                                <span wire:loading wire:target="disapproveTicketRecommendation">Processing...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('livewire-modal')
    <script>
        window.addEventListener('close-ticket-recommendation-disapproval-modal', () => {
                    $('#disapproveTicketRecommendationModal').modal('hide');
    </script>
@endpush
