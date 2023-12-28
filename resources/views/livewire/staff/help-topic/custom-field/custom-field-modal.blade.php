<div>
    <div wire:ignore.self class="modal fade help__topic__modal" id="customFieldModal" tabindex="-1"
        aria-labelledby="customFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0 mb-3">
                    <h1 class="modal-title modal__title" id="addNewHelpTopicModalLabel">Manage custom field</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                @livewire('staff.help-topic.custom-field.custom-field-add-form')
                @livewire('staff.help-topic.custom-field.custom-field-list')
            </div>
        </div>
    </div>
</div>
