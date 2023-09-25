<div>
    <div wire:ignore.self class="modal modal-xl ticket__actions__modal" id="replyTicketModal" tabindex="-1"
        aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom__modal">
            <div class="modal-content d-flex flex-column custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Write your reply</h6>
                    <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                        data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @if ($latestReply)
                <div class="my-4 d-flex flex-column gap-3 reply__ticket__info">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            @if ($latestReply->user->profile->picture)
                            <img src="{{ Storage::url($latestReply->user->profile->picture) }}"
                                class="me-2 sender__profile" alt="">
                            @else
                            <div class="user__name__initial d-flex align-items-center p-2 me-2 rounded-3 justify-content-center
                                                        text-white"
                                style="background-color: #24695C; height: 30px; width: 30px; border: 2px solid #d9ddd9; font-size: 12px;">
                                {{ $latestReply->user->profile->getNameInitial() }}</div>
                            @endif
                            <p class="mb-0" style="font-size: 14px; font-weight: 500;">
                                {{ $latestReply->user->profile->first_name }}
                                <em>
                                    <small class="text-muted" style="font-size: 12px;">(Latest reply)</small>
                                </em>
                            </p>
                        </div>
                        <p class="mb-0 time__sent">{{ $latestReply->created_at->diffForHumans(null, true) }} ago</p>
                    </div>
                    <div class="mb-0 ticket__description">{!! $latestReply->description !!}</div>
                </div>
                @endif
                <div class="modal__body">
                    <form wire:submit.prevent="replyTicket">
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
                                <div wire:loading wire:target="replyFiles">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="spinner-border text-info" style="height: 15px; width: 15px;"
                                            role="status">
                                        </div>
                                        <small style="fot-size: 12px;">Uploading...</small>
                                    </div>
                                </div>
                            </div>
                            <input class="form-control ticket__file__input w-auto my-3" type="file"
                                wire:model="replyFiles" multiple>
                            @error('replyFiles.*')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="btn mt-4 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                            <span wire:loading wire:target="replyTicket" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Send
                            <div wire:loading.remove>
                                <i class="bi bi-send-fill"></i>
                            </div>
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
        forced_root_block: false,
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

{{-- Modal Scripts --}}
@push('livewire-modal')
<script>
    window.addEventListener('close-modal', event =>{
        $('#replyTicketModal').modal('hide');
    });
</script>
@endpush