<div>
    @livewire('staff.accounts.requester.update-requester-password', ['user' => $user])
    <div class="row accounts__section justify-content-center">
        <div class="col-xxl-9 col-lg-12">
            <div class="card d-flex flex-column gap-2 users__account__card">
                <div class="user__details__container d-flex flex-wrap mb-4 justify-content-between">
                    <h6 class="card__title">Requester's Information</h6>
                    <small class="text-muted" style="font-size: 12px;">
                        Last updated:
                        @if ($user->dateUpdated() > $user->profile->dateUpdated())
                        {{ $user->dateUpdated() }}
                        @else
                        {{ $user->profile->dateUpdated() }}
                        @endif
                    </small>
                </div>
                <form wire:submit.prevent="updateRequesterAccount">
                    <input type="hidden" id="userID" value="{{ $user->id }}">
                    <div class="row gap-4 user__details__container">
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Profile</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label form__field__label">First
                                            name</label>
                                        <input type="text" wire:model.defer="first_name"
                                            class="form-control form__field" id="first_name"
                                            placeholder="Enter first name (required)">
                                        @error('first_name')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label form__field__label">Middle
                                            name</label>
                                        <input type="text" wire:model.defer="middle_name"
                                            class="form-control form__field" id="middle_name"
                                            placeholder="Enter middle name (optional)">
                                        @error('middle_name')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label form__field__label">Last name</label>
                                        <input type="text" wire:model.defer="last_name" class="form-control form__field"
                                            id="last_name" placeholder="Enter last name (required)">
                                        @error('last_name')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label form__field__label">Suffix</label>
                                        <div>
                                            <div id="select-requester-suffix" wire:ignore></div>
                                        </div>
                                        @error('suffix')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Login Credentials</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label form__field__label">Email</label>
                                        <input type="email" wire:model.defer="email" class="form-control form__field"
                                            id="email" placeholder="Enter email (required)">
                                        @error('email')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <button type="button"
                                            class="btn m-0 btn__details btn__update__password d-flex align-items-center gap-2 justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                            <i class="fa-solid fa-key"></i>
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-3 fw-bold text-muted" style="font-size: 15px;">Work Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" value="{{ $user->branch_id }}" id="userCurrentBranchId">
                                        <label class="form-label form__field__label">Branch</label>
                                        <div>
                                            <div id="select-requester-branch" wire:ignore></div>
                                        </div>
                                        @error('branch')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" value="{{ $user->department_id }}"
                                            id="userCurrentBUDepartmentId">
                                        <label for="branch" class="form-label form__field__label">
                                            BU/Department
                                            {{-- @if ($BUDepartments)
                                            <span class="fw-normal" style="font-size: 13px;">
                                                ({{ $BUDepartments->count() }})</span>
                                            @endif --}}
                                        </label>
                                        <div>
                                            <div id="select-requester-bu-department" wire:ignore></div>
                                        </div>
                                        @error('bu_department')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn m-0 btn__details btn__cancel" id="btnCloseModal"
                                    data-bs-dismiss="modal"
                                    onclick="window.location.href='{{ route('staff.manage.user_account.users') }}'">Cancel</button>
                                <button type="submit" class="btn m-0 btn__details btn__send">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
<script>
    const requesterSuffixOption = [
        @foreach ($requesterSuffixes as $suffix)
            {
                label: "{{ $suffix->name }}",
                value: "{{ $suffix->name }}"
            },
        @endforeach
    ];

</script>
@endpush

{{-- Modal Scripts --}}
@push('livewire-modal')
<script>
    window.addEventListener('close-modal', event => {
        $('#editPasswordModal').modal('hide');
    });
</script>
@endpush