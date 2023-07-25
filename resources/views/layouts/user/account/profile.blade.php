<form action="{{ route('user.account_settings.updateProfile') }}" method="post" enctype="multipart/form-data"
    id="updateProfileForm">
    @method('PUT')
    @csrf
    <div class="card d-flex flex-column gap-4 account__info__fields__container">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="mr-2">
                <img src="{{ asset('storage/' . auth()->user()->profile->picture) }}" class="upload__new__photo"
                    id="uploadedNewPhoto" alt="">
            </div>
            <div class="d-flex flex-wrap flex-column">
                <input class="form-control form-control-sm border-0 upload__photo__field" id="uploadNewPhoto"
                    type="file" name="picture" accept="image/*">
                <small class="text-danger" id="inputFileErrorMsg"></small>
            </div>
        </div>
        <div class="account__form__container">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="firstName" class="form-label input__field__label">First Name</label>
                        <input type="text" name="first_name" class="form-control input__field" id="firstName"
                            value="{{ auth()->user()->profile->first_name }}">
                        @error('first_name', 'updateProfile')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="middleName" class="form-label input__field__label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control input__field" id="middleName"
                            value="{{ auth()->user()->profile->middle_name }}">
                        @error('middle_name', 'updateProfile')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lastName" class="form-label input__field__label">Last Name</label>
                        <input type="text" name="last_name" class="form-control input__field" id="lastName"
                            value="{{ auth()->user()->profile->last_name }}">
                        @error('last_name', 'updateProfile')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="suffix" class="form-label input__field__label">Suffix</label>
                        <input type="text" name="suffix" class="form-control input__field" id="suffix"
                            value="{{ auth()->user()->profile->suffix }}">
                        @error('suffix', 'updateProfile')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label input__field__label">Email</label>
                        <input type="text" name="email" class="form-control input__field" id="email"
                            value="{{ auth()->user()->email }}">
                        @error('email', 'updateProfile')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mobileNumber" class="form-label input__field__label">Mobile No.</label>
                        <input type="text" name="mobile_number" class="form-control input__field" maxlength="11"
                            id="mobileNumber" value="{{ auth()->user()->profile->mobile_number }}">
                        @error('mobile_number', 'updateProfile')
                        <span class="text-danger custom__field__message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn w-auto btn__save__account">Save Profile</button>
                </div>
            </div>
        </div>
    </div>
</form>