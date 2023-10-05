<div>
    <div wire:ignore.self class="modal fade disapprove__reason__modal" id="disapproveTicketModal" tabindex="-1"
        aria-labelledby="disapproveTicketLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 modal__content">
                <form wire:submit.prevent="disapproveTicket">
                    <div class="modal-body border-0 px-4 pt-4 pb-1 mb-3">
                        <h6 class="mb-3 title">
                            Reason why you disapprove this ticket
                        </h6>
                        <div wire:ignore>
                            <textarea wire:model="reasonDescription" id="reasonDescription"></textarea>
                        </div>
                        @error('reasonDescription')
                        <span class="error__message">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="d-flex align-items-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-auto btn__cancel__logout btn__confirm__modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 w-auto btn__confirm__logout btn__confirm__modal">
                            <span wire:loading wire:target="disapproveTicket" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Disapprove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('livewire-textarea')
<script>
    tinymce.init({
        selector: '#reasonDescription',
        plugins: 'lists',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
        height: 350,
        forced_root_block: false,
        setup: function (editor) {
            editor.on('init change', function () {
                editor.save();
            });
            editor.on('change', function (e) {
                @this.set('reasonDescription', editor.getContent());
            });
        }
    });
</script>
@endpush

@push('livewire-modal')
<script>
    window.addEventListener('close-modal', event => {
        $('#disapproveTicketModal').modal('hide');
    });
</script>
@endpush