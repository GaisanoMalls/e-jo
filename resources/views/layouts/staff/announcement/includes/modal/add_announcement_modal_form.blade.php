<div class="modal fade announcement__modal" id="addNewAnnouncement" tabindex="-1"
    aria-labelledby="addNewAnnouncementLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content modal__content">
            <div class="modal-header modal__header p-0 border-0">
                <h1 class="modal-title modal__title" id="addNewAnnouncementLabel">Create New</h1>
                <div class="d-flex align-items-center gap-3">
                    <form action="" method="post">
                        <button type="submit" class="btn btn-sm rounded-4">Save as Draft</button>
                    </form>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('staff.announcement.store') }}" method="post">
                @csrf
                <div class="modal-body modal__body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="title" class="form-label form__field__label">Title</label>
                                <input type="text" name="title" class="form-control form__field" id="title"
                                    value="{{ old('title') }}" placeholder="Type here...">
                                @error('title', 'storeAnnouncement')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="department" class="form-label form__field__label">Department</label>
                                <select name="department" placeholder="Choose a department" data-search="true"
                                    data-silent-initial-value-set="true">
                                    <option value="" selected disabled>Choose a department</option>
                                    @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department')==$department->id ?
                                        'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('department', 'storeAnnouncement')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="myeditorinstance" class="form-label form__field__label">Description</label>
                                <textarea id="myeditorinstance" name="description" placeholder="Type here...">
                                {{ old('description') }}
                                </textarea>
                                @error('description', 'storeAnnouncement')
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
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="form-check form-switch me-4" style="white-space: nowrap;">
                            <input class="form-check-input is__urgent__check" name="send_email_copy" type="checkbox"
                                role="switch" id="sendEmailCopy">
                            <label class="form-check-label is__urgent__check__label" for="sendEmailCopy">
                                Send an email copy to recepients
                            </label>
                        </div>
                        <div class="form-check form-switch" style="white-space: nowrap;">
                            <input class="form-check-input is__urgent__check" name="is_important" value="1"
                                type="checkbox" role="switch" id="markAsImportant">
                            <label class="form-check-label is__urgent__check__label" for="markAsImportant">
                                Mark as Important
                            </label>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn m-0 btn__modal__footer btn__cancel"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn m-0 btn__modal__footer btn__send">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>