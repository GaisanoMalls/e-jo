<div class="modal department__modal" id="editBUDepartment{{ $department->id }}" tabindex="-1"
    aria-labelledby="editBUDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewDepartmentModalLabel">Edit BU/Department</h1>
                <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                    <i class="fa-sharp fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('staff.manage.bu_department.update', $department->id) }}" method="post"
                autocomplete="off" id="modalForm">
                @csrf
                @method('PUT')
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="mb-2">
                            <label for="name" class="form-label form__field__label">Name</label>
                            <input type="text" name="name" class="form-control form__field" id="name"
                                value="{{ $department->name ?? old('name') }}" placeholder="Type here...">
                            @if (session()->has('duplicate_name_error'))
                            <div class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ session()->get('duplicate_name_error') }}
                            </div>
                            @endif
                        </div>
                        <div class="mb-2 mt-3">
                            <label class="form-label form__field__label">
                                Assigned {{ $department->branches->count() > 1 ? 'branches' : 'branch' }}
                            </label>
                            @foreach ($branches as $branch)
                            <div class="form-check me-4">
                                <input class="form-check-input" type="checkbox" value="{{ $branch->id }}"
                                    name="branch[]" {{ in_array($branch->id,
                                $department->branches->pluck('id')->toArray()) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    {{ $branch->name }}
                                </label>
                            </div>
                            @endforeach
                            @error('branch', 'editBUDepartment')
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
                        @if ($errors->editBUDepartment->any() || session()->has('duplicate_name_error'))
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                            data-bs-dismiss="modal"
                            onclick="window.location.href='{{ route('staff.manage.bu_department.index') }}'">Cancel</button>
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