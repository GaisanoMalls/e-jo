<div>
    <div wire:ignore.self class="modal fade help__topic__modal" id="customFieldModal" tabindex="-1"
        aria-labelledby="customFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewHelpTopicModalLabel">Add custom field</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                {{-- <form wire:submit.prevent="saveHelpTopic">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="form-check" style="white-space: nowrap;">
                                            <input wire:model="isSpecialProject" wire:change="specialProject"
                                                class="form-check-input check__special__project" type="checkbox"
                                                role="switch" id="specialProjectCheck" wire:loading.attr="disabled">
                                            <label class="form-check-label" for="specialProjectCheck">
                                                Check if the help topic is a special project
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8" id="helpTopicNameContainer" wire:ignore.self>
                                        <div class="mb-2">
                                            <label for="helpTopicName"
                                                class="form-label form__field__label">Name</label>
                                            <input type="text" wire:model="name" class="form-control form__field"
                                                id="helpTopicName" placeholder="Enter help topic name">
                                            @error('name')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-2">
                                            <label for="sla" class="form-label form__field__label">
                                                Serice Level Agreement (SLA)
                                            </label>
                                            <div>
                                                <div id="select-help-topic-sla" wire:ignore></div>
                                            </div>
                                            @error('sla')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="department" class="form-label form__field__label">
                                                Service Department
                                            </label>
                                            <div>
                                                <div id="select-help-topic-service-department" wire:ignore></div>
                                            </div>
                                            @error('service_department')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="teamSelectContainer" wire:ignore>
                                        <div class="mb-2">
                                            <label for="team" class="form-label form__field__label">
                                                Team
                                                <span class="fw-normal" style="font-size: 13px;" id="countTeams"></span>
                                            </label>
                                            <div>
                                                <div id="select-help-topic-team" placeholder="Select (optional)"
                                                    wire:ignore></div>
                                            </div>
                                            @error('team')
                                                <span class="error__message">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div wire:ignore class="mt-2" id="specialProjectContainer">
                                        <div class="py-2">
                                            <hr>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="amount" class="form-label form__field__label">
                                                        Amount
                                                    </label>
                                                    <input type="text" wire:model="amount"
                                                        class="form-control form__field amount__field" id="amount"
                                                        placeholder="Enter amount">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <button type="submit"
                                    class="btn d-flex align-items-center justify-content-center gap-2 m-0 btn__modal__footer btn__send">
                                    <span wire:loading wire:target="saveHelpTopic"
                                        class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                    </span>
                                    Add New
                                </button>
                                <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                    data-bs-dismiss="modal" wire:click="cancel">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>
</div>
