<div>
    <div wire:ignore.self class="modal slideIn animate department__modal" id="addNewBUDepartmentModal" tabindex="-1"
        aria-labelledby="addNewBUDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewDepartmentModalLabel">Add new BU/Department</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveBUDepartment">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="mb-2">
                                <label for="name" class="form-label form__field__label">Name</label>
                                <input type="text" wire:model="name"
                                    class="form-control form__field @error('name') is-invalid @enderror" id="name"
                                    placeholder="Enter BU/department name">
                                @error('name')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label class="form-label form__field__label">Assign to branch</label>
                                <div>
                                    <div id="select-branch" wire:ignore></div>
                                </div>
                                @error ('selectedBranches')
                                <div class="error__message mt-1">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                <span wire:loading wire:target="saveBUDepartment"
                                    class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                </span>
                                Add new
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal" wire:click="cancel">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
<script>
    let branchOption = [
        @foreach ($branches as $branch)
            {
                label: "{{ $branch->name }}",
                value: "{{ $branch->id }}"
            },
        @endforeach
    ];

    VirtualSelect.init({
        ele: '#select-branch',
        options: branchOption,
        search: true,
        multiple: true,
        required: true,
        showValueAsTags: true,
        markSearchResults: true,
        popupDropboxBreakpoint: '3000px',
    });

    let branchSelect = document.querySelector('#select-branch')

    branchSelect.addEventListener('change', () => {
        @this.set('selectedBranches', branchSelect.value);
    });

    // Clear all selected branches in the select option.
    window.addEventListener('clear-branch-select-option', event => {
        branchSelect.reset();
    });
</script>
@endpush