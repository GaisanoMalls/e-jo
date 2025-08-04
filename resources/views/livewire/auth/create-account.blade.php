{{-- <div>
    <form wire:submit.prevent="submit" novalidate>
        @if (session()->has('error'))
            <ul class='form__errors mb-2 text-center'>
                <li>{{ session('error') }}</li>
            </ul>
        @endif
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">First name</label>
                    <input type="text" wire:model.defer="first_name"
                        class="form-control login__input__field @error('first_name') is-invalid @enderror"
                        placeholder="Enter first name (required)">
                    @error('first_name')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Middle name</label>
                    <input type="text" wire:model.defer="middle_name"
                        class="form-control login__input__field @error('middle_name') is-invalid @enderror"
                        placeholder="Enter middle name">
                    @error('middle_name')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Last name</label>
                    <input type="text" wire:model.defer="last_name"
                        class="form-control login__input__field @error('last_name') is-invalid @enderror"
                        placeholder="Enter last name (required)">
                    @error('last_name')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Suffix</label>
                    <select wire:model.defer="suffix" class="form-control login__input__field @error('suffix') is-invalid @enderror">
                        <option value="" hidden>Select suffix</option>
                        @foreach($requesterSuffixes as $suffix)
                            <option value="{{ $suffix->name }}">{{ $suffix->name }}</option>
                        @endforeach
                    </select>
                    @error('suffix')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="my-2">
                    <label class="form-label input__login__label">Email address</label>
                    <input type="email" wire:model.defer="email"
                        class="form-control login__input__field @error('email') is-invalid @enderror"
                        placeholder="Enter email (required)">
                    @error('email')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Branch</label>
                    <select wire:model.defer="branch" class="form-control login__input__field @error('branch') is-invalid @enderror">
                        <option value="" hidden>Select branch</option>
                        @foreach($requesterBranches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">BU/Department</label>
                    <select wire:model.defer="department" class="form-control login__input__field @error('department') is-invalid @enderror">
                        <option value="" hidden>Select BU/Department</option>
                        @foreach($BUDepartments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn mt-3 w-100 btn-block login__button">
            Create Account
        </button>
        <a href="{{ route('login') }}" class="link mt-4 d-flex align-items-center justify-content-center gap-2">
            <i class="fa-solid fa-angle-left"></i>
            Back to sign in
        </a>
    </form>
</div> --}}

<div>
    <form wire:submit.prevent="submit" novalidate>
        @if (session()->has('error'))
            <ul class='form__errors mb-2 text-center'>
                <li>{{ session('error') }}</li>
            </ul>
        @endif
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">First name</label>
                    <input type="text" wire:model.defer="first_name"
                        class="form-control login__input__field @error('first_name') is-invalid @enderror"
                        placeholder="Enter first name (required)">
                    @error('first_name')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Middle name</label>
                    <input type="text" wire:model.defer="middle_name"
                        class="form-control login__input__field @error('middle_name') is-invalid @enderror"
                        placeholder="Enter middle name">
                    @error('middle_name')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Last name</label>
                    <input type="text" wire:model.defer="last_name"
                        class="form-control login__input__field @error('last_name') is-invalid @enderror"
                        placeholder="Enter last name (required)">
                    @error('last_name')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Suffix</label>
                    <select wire:model.defer="suffix" class="form-control login__input__field @error('suffix') is-invalid @enderror">
                        <option value="" hidden>Select suffix</option>
                        @foreach($requesterSuffixes as $suffix)
                            <option value="{{ $suffix->name }}">{{ $suffix->name }}</option>
                        @endforeach
                    </select>
                    @error('suffix')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Email address</label>
                    <input type="email" wire:model.defer="email"
                        class="form-control login__input__field @error('email') is-invalid @enderror"
                        placeholder="Enter email (required)">
                    @error('email')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Branch</label>
                    <select wire:model="branch" wire:change="loadDepartments" class="form-control login__input__field @error('branch') is-invalid @enderror">
                        <option value="" hidden>Select branch</option>
                        @foreach($requesterBranches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>

                    @error('branch')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">BU/Department</label>
                    <select wire:model="department" wire:change="loadApprovers" class="form-control login__input__field @error('department') is-invalid @enderror">
                        <option value="" hidden>Select BU/Department</option>
                        @foreach($BUDepartments as $department)
                            <option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
                        @endforeach
                    </select>
                    @error('department')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="my-2">
                    <label class="form-label input__login__label">Manager</label>
                    <select wire:model="approver" class="form-control login__input__field @error('approver') is-invalid @enderror">
                        <option value="" hidden>Select a manager</option>
                        @foreach($approvers as $approver)
                            <option value="{{ $approver->id }}">{{ $approver->profile->first_name }} {{ $approver->profile->last_name }}</option>
                        @endforeach
                    </select>
                    @error('approver')
                        <span class="error__message text-danger custom__field__message"><i class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn mt-3 w-100 btn-block login__button">
            Create Account
        </button>
        <a href="{{ route('login') }}" class="link mt-4 d-flex align-items-center justify-content-center gap-2">
            <i class="fa-solid fa-angle-left"></i>
            Back to sign in
        </a>
    </form>
</div>
