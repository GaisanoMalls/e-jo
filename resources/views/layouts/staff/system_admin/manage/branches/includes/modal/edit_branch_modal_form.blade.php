<div class="modal department__modal" data-bs-backdrop="static" data-bs-keyboard="false"
    id="editBranchModal{{ $branch->id }}" tabindex="-1" aria-labelledby="addNewBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewBranchModalLabel">Edit branch</h1>
            </div>
            <form action="{{ route('staff.manage.branch.update', $branch->id) }}" method="post" autocomplete="off"
                id="modalForm">
                @csrf
                @method('PUT')
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="mb-2">
                            <label for="name" class="form-label form__field__label">Name</label>
                            <input type="text" name="name" class="form-control form__field" id="name"
                                value="{{ $branch->name }}" placeholder="Type here...">
                            @error('name', 'editBranch')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn m-0 btn__modal__footer btn__send">Save</button>
                        @if ($errors->editBranch->any())
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal"
                            onclick="window.location.href='{{ route('staff.manage.branch.index') }}'">Cancel</button>
                        @else
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal">Cancel</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>