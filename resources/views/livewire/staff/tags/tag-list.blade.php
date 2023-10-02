<div>
    <div class="table-responsive custom__table">
        @if (!$tags->isEmpty())
        <table class="table table-striped mb-0" id="table">
            <thead>
                <tr>
                    <th class="border-0 table__head__label" style="padding: 17px 30px;">Name</th>
                    <th class="border-0 table__head__label" style="padding: 17px 30px;">Tickets</th>
                    <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                    <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tags as $tag)
                <tr wire:key="{{ $tag->id }}">
                    <td>
                        <div class="d-flex align-items-center text-start td__content">
                            <span>{{ $tag->name }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-start td__content">
                            {{-- <span>{{ $tag->tickets->count() }}</span> --}}
                            <span>----</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-start td__content">
                            <span>{{ $tag->dateCreated() }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-start td__content">
                            <span>{{ $tag->dateUpdated() }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                            <button data-tooltip="Edit" data-tooltip-position="top" data-tooltip-font-size="11px"
                                type="button" class="btn action__button" data-bs-toggle="modal"
                                data-bs-target="#updateTagModal" wire:click="editTag({{ $tag->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button data-bs-toggle="modal" data-bs-target="#deleteTagModal"
                                class="btn btn-sm action__button mt-0" wire:click="deleteTag({{ $tag->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
            <small style="font-size: 14px;">No records for departments.</small>
        </div>
        @endif
    </div>

    {{-- Edit Tag Modal --}}
    <div wire:ignore.self class="modal slideIn animate tag__modal" id="updateTagModal" tabindex="-1"
        aria-labelledby="addNewTagModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewTagModalLabel">Edit tag</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="updateTag">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="name" class="form-label form__field__label">Name</label>
                                    <input type="text" class="form-control form__field" id="name"
                                        placeholder="Enter tag name" wire:model="name">
                                    @error('name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                <span wire:loading wire:target="updateTag" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Update
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal" wire:click="clearFormField">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Tag Modal --}}
    <div wire:ignore.self class="modal slideIn animate modal__confirm__delete__tag" id="deleteTagModal" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-body border-0 text-center pt-4 pb-1">
                    <h6 class="fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                        Confirm Delete
                    </h6>
                    <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                        Are you sure you want to delete the tag?
                    </p>
                    <strong>{{ $name }}</strong>
                </div>
                <hr>
                <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                    <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"
                        class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                        wire:click="delete">
                        <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true">
                        </span>
                        Yes, delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
<script>
    window.addEventListener('close-modal', event => {
        $('#updateTagModal').modal('hide');
        $('#deleteTagModal').modal('hide');
    });

    window.addEventListener('show-edit-tag-modal', event => {
        $('#updateTagModal').modal('show');
    });

    window.addEventListener('show-delete-tag-modal', event => {
        $('#deleteTagModal').modal('show');
    });
</script>
@endpush