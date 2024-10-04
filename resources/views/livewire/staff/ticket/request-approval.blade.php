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
                            <label class="ticket__actions__label mb-2">Level of approval</label>
                            <div>
                                <div id="recommendation-select-level-of-approval" wire:ignore></div>
                            </div>
                            @error('level')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div wire:ignore id="recommendation-level-approver-container"></div>
                        <div class="my-2">
                            <label class="ticket__actions__label mb-2">Reason</label>
                            <div wire:ignore>
                                <textarea wire:model="reason" class="form-control form__field" placeholder="Please state the reason"></textarea>
                            </div>
                            @error('reason')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn mt-3 d-flex align-items-center justify-content-center gap-2"
                            style="padding: 0.6rem 1rem;
                                border-radius: 0.563rem;
                                font-size: 0.875rem;
                                background-color: #d32839;
                                color: white;
                                font-weight: 500;
                                box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20, 0.07);">
                            <span wire:loading wire:target="sendRequestRecommendationApproval"
                                class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            <span wire:loading.remove wire:target="sendRequestRecommendationApproval">
                                Submit
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
        const selecteRecommendationLevelOfApproval = document.querySelector('#recommendation-select-level-of-approval');
        const recommendationLevelOfApprovalOption = @json($levelOfApproval).map(level => ({
            label: `Level ${level}`,
            value: level,
        }));

        VirtualSelect.init({
            ele: selecteRecommendationLevelOfApproval,
            options: recommendationLevelOfApprovalOption,
            search: true,
        });

        window.addEventListener('close-request-recommendation-approval-modal', () => {
            $('#requestForApprovalModal').modal('hide');
        });

        const dyanamicLevelApproverSelectContainer = document.querySelector('#recommendation-level-approver-container');
        selecteRecommendationLevelOfApproval.addEventListener('change', (event) => {
            @this.set('level', parseInt(event.target.value));

            window.addEventListener('load-recommendation-approvers', (event) => {
                const recommendationApprovers = event.detail.recommendationApprovers;
                const level = event.detail.level;
                const approvers = {};
                let selectedApprovers = [];

                dyanamicLevelApproverSelectContainer.innerHTML = '';
                selectedApprovers = [];

                for (let level = 1; level <= selecteRecommendationLevelOfApproval.value; level++) {
                    const approverFieldWrapper = document.createElement('div');
                    approverFieldWrapper.className = 'col-md-12';
                    approverFieldWrapper.innerHTML = `
                        <div class="mb-2">
                            <label for="department" class="form-label form__field__label">Level ${level} Approver</label>
                            <div>
                                <div id="select-recommendation-approval-level-${level}" wire:ignore></div>
                            </div>
                        </div>`;

                    dyanamicLevelApproverSelectContainer.appendChild(approverFieldWrapper);

                    approvers[`level${level}`] = document.querySelector(
                        `#select-recommendation-approval-level-${level}`);

                    const recommendationApproverOption = recommendationApprovers.map((approver) => ({
                        label: `${approver.profile.first_name} ${approver.profile.middle_name ? approver.profile.middle_name[0] + '.' : ''} ${approver.profile.last_name}`,
                        value: approver.id,
                        description: `${approver.roles.map(role => role.name).join(', ')} (${approver.bu_departments.map(department => department.name).join(', ')})`
                    }));

                    VirtualSelect.init({
                        ele: approvers[`level${level}`],
                        options: recommendationApproverOption,
                        search: true,
                        multiple: true,
                        showValueAsTags: true,
                        markSearchResults: true,
                        hasOptionDescription: true
                    });

                    if (approvers[`level${level}`]) {
                        approvers[`level${level}`].addEventListener('change', () => {
                            selectedApprovers[level - 1] = approvers[`level${level}`].value;
                            @this.set(`level${level}Approvers`, approvers[`level${level}`].value);
                        });
                    }
                }
            });
        });

        selecteRecommendationLevelOfApproval.addEventListener('reset', () => {
            for (let i = 1; i <= 5; i++) {
                @this.set(`level${i}Approvers`, []);
            }
            dyanamicLevelApproverSelectContainer.innerHTML = '';
        });
    </script>
@endpush
