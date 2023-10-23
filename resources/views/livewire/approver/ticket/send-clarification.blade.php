<div>
    <div wire:ignore.self class="modal fade clarification__modal" id="ticketClarificationModal" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered custom__modal">
            <div class="modal-content d-flex flex-column custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Write your reply</h6>
                    <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                        data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @livewire('approver.ticket.latest-clarification', ['ticket' => $ticket])
                <div class="modal__body">
                    <form wire:submit.prevent="sendClarification">
                        <div class="my-2">
                            <div wire:ignore>
                                <textarea wire:model="description" id="description"></textarea>
                            </div>
                            @error('description')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <div class="d-flex align-items-center gap-3">
                                <label class="ticket__actions__label">Attach file</label>
                                <div wire:loading wire:target="clarificationFiles">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="spinner-border text-info" style="height: 15px; width: 15px;"
                                            role="status">
                                        </div>
                                        <small style="fot-size: 12px;">Uploading...</small>
                                    </div>
                                </div>
                            </div>
                            <input class="form-control ticket__file__input w-auto my-3" type="file"
                                wire:model="clarificationFiles" multiple id="upload-{{ $upload }}">
                            @error('clarificationFiles.*')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="btn mt-4 d-flex align-items-center justify-content-center gap-2 btn__send__ticket__reply">
                            <span wire:loading wire:target="sendClarification" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-textarea')
<script>
    tinymce.init({
        selector: '#description',
        plugins: 'lists',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
        height: 350,
        setup: function (editor) {
            editor.on('init change', function () {
                editor.save();
            });
            editor.on('change', function (e) {
                @this.set('description', editor.getContent());
            });
        }
    });
</script>
@endpush

@push('livewire-modal')
<script>
    window.addEventListener('close-modal', () => {
        $('#ticketClarificationModal').modal('hide');
    });

    window.addEventListener('reload-modal', () =>{
        tinymce.get("description").setContent("");
    });
</script>
@endpush