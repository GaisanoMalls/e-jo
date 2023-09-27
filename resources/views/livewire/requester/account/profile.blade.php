<div>
    <form wire:submit.prevent="saveProfile">
        <div class="card d-flex flex-column gap-4 account__info__fields__container">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="mr-2">
                    @if ($picture)
                    <img src="{{ $picture->temporaryUrl() }}" class="upload__new__photo" alt="">
                    @elseif (auth()->user()->profile->picture)
                    <img src="{{ Storage::url(auth()->user()->profile->picture) }}" class="upload__new__photo"
                        id="uploadedNewPhoto" alt="">
                    @endif
                </div>
                <div class="d-flex flex-wrap flex-column">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-control form-control-sm border-0 upload__photo__field"
                            id="upload_{{ $imageUpload }}" type="file" wire:model="picture" accept="image/*">
                        <div wire:loading wire:target="picture">
                            <div class="d-flex align-items-center gap-2">
                                <div class="spinner-border text-info" style="height: 20px; width: 20px;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <small>Uploading...</small>
                            </div>
                        </div>
                    </div>
                    <small class="text-danger" id="inputFileErrorMsg"></small>
                    @error('picture')
                    <span class="text-danger custom__field__message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="account__form__container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="firstName" class="form-label input__field__label">First Name</label>
                            <input type="text" wire:model="first_name"
                                class="form-control @error('first_name') is-invalid @enderror input__field "
                                id="firstName">
                            @error('first_name')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="middleName" class="form-label input__field__label">Middle Name</label>
                            <input type="text" wire:model="middle_name"
                                class="form-control @error('middle_name') is-invalid @enderror input__field"
                                id="middleName">
                            @error('middle_name')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="lastName" class="form-label input__field__label">Last Name</label>
                            <input type="text" wire:model="last_name"
                                class="form-control @error('last_name') is-invalid @enderror input__field"
                                id="lastName">
                            @error('last_name')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="suffix" class="form-label input__field__label">Suffix</label>
                            <input type="text" wire:model="suffix"
                                class="form-control @error('suffix') is-invalid @enderror input__field" id="suffix">
                            @error('suffix')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label input__field__label">Email</label>
                            <input type="text" wire:model="email"
                                class="form-control @error('email') is-invalid @enderror input__field" id="email">
                            @error('email')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="mobileNumber" class="form-label input__field__label">Mobile No.</label>
                            <input type="text" wire:model="mobile_number"
                                class="form-control @error('mobile_number') is-invalid @enderror input__field"
                                maxlength="11" id="mobileNumber">
                            @error('mobile_number')
                            <span class="text-danger custom__field__message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" wire:loading.attr="disabled"
                            class="btn w-auto d-flex align-items-center justify-content-center gap-2 btn__save__account">
                            <span wire:loading wire:target="saveProfile" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>