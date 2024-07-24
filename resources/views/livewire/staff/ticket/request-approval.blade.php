<div>
    <div wire:ignore.self class="modal fade ticket__actions__modal" id="requestForApprovalModal" tabindex="-1"
        aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom__modal">
            <div class="modal-content d-flex flex-column custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Request for approval</h6>
                    <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                        data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal__body">
                    <form wire:submit.prevent="sendRequestRecommendationApproval">
                        <div class="my-2">
                            <label class="ticket__actions__label mb-2">Select approver</label>
                            <div>
                                <div id="select-recommendation-approver" wire:ignore></div>
                            </div>
                            @error('recommendationApprover')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="btn mt-3 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                            <span wire:loading wire:target="sendRequestRecommendationApproval"
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            <span wire:loading.remove wire:target="sendRequestRecommendationApproval">
                                Send Request
                            </span>
                            <span wire:loading wire:target="sendRequestRecommendationApproval">Sending Request...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
        const selectRecommendationApprover = document.querySelector('#select-recommendation-approver');

        const recommendationApproversOption = @json($recommendationApprovers).map(approver => ({
            label: `${approver.profile.first_name} ${approver.profile.middle_name ? approver.profile.middle_name[0] + '.' : ''} ${approver.profile.last_name}`,
            value: approver.id,
            description: `${approver.roles.map(role => role.name).join(', ')} (${approver.bu_departments.map(department => department.name).join(', ')})`
        }));

        VirtualSelect.init({
            ele: selectRecommendationApprover,
            options: recommendationApproversOption,
            search: true,
            markSearchResults: true,
            hasOptionDescription: true,
        });

        selectRecommendationApprover.addEventListener('change', (event) => {
            @this.set('recommendationApprover', parseInt(event.target.value));
        });

        window.addEventListener('close-request-recommendation-approval-modal', () => {
            console.log("Resetted");
            $('#requestForApprovalModal').modal('hide');
            selectRecommendationApprover.reset();
        });
    </script>
@endpush
