<form action="{{ route('user.ticket.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal slideIn animate create__ticket__modal" id="createTicketModal" tabindex="-1"
        aria-labelledby="createtTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-lg">
            <div class="modal-content modal__content">
                <h1 class="modal-title modal__title fs-5 px-3">Create New Ticket</h1>
                <div class="modal-body modal__body">
                    <div class="row">
                        <div class="col-12">
                            @error('sla', 'storeTicket')
                            <div class="alert alert-warning mb-2 py-2 px-3" role="alert">
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            </div>
                            @enderror
                            @error('team', 'storeTicket')
                            <div class="alert alert-warning mb-2 py-2 px-3" role="alert">
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="form-check mt-2 mb-4">
                                <input class="form-check-input" type="checkbox" value="" id="checkOtherBranch">
                                <label class="form-check-label labelCheckOtherBranch" for="checkOtherBranch">
                                    This ticket is intended to other branch
                                </label>
                                <br>
                                <p class="mb-0 mt-1" style="font-size: 13px; line-height: 15px;">
                                    Check if your wish to send this ticket to other branch. Otherwise, leave
                                    unchecked to send this ticket to your currently assigned branch -
                                    <span class="fw-bold text-muted">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ auth()->user()->branch->name }}
                                    </span>.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label input__field__label">Service Department</label>
                                <select name="service_department" id="userCreateTicketServiceDepartmentDropdown">
                                </select>
                                @error('service_department', 'storeTicket')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label input__field__label">
                                    Help Topic
                                    <small id="userCreateTicketHelpTopicCount"></small>
                                    <br>
                                    <small id="userCreateTicketNoHelpTopicMessage" class="text-danger fw-normal"
                                        style="font-size: 12px;"></small>
                                </label>
                                <select name="help_topic" id="userCreateTicketHelpTopicDropdown">
                                    <option value="" disabled selected>Choose a help topic</option>
                                </select>
                                @error('help_topic', 'storeTicket')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                                <input type="hidden" name="team" value="" id="helpTopicTeam">
                                <input type="hidden" name="sla" value="" id="helpTopicSLA">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="mb-3" id="userCreateTicketBranchSelectionContainer">
                                <label class="form-label input__field__label">
                                    To which branch will this ticket be sent?
                                </label>
                                <select name="branch" id="userCreateTicketBranchesDropdown">
                                </select>
                                @error('branch', 'storeTicket')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ticketSubject" class="form-label input__field__label">
                                Subject
                                <span class="text-sm text-muted">
                                    <small>(Issue Summary)</small>
                                </span>
                            </label>
                            <input type="text" name="subject" class="form-control input__field" id="ticketSubject"
                                placeholder="Tell us about your concern" value="{{ old('subject') }}">
                            @error('subject', 'storeTicket')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label input__field__label">
                                Message
                                <span class="text-sm text-muted">
                                    <small>(Tell us more about your concern)</small>
                                </span>
                            </label>
                            <textarea id="myeditorinstance" name="description" placeholder="Type here...">
                                {{ old('description') }}
                            </textarea>
                            @error('description', 'storeTicket')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div>
                                    <label for="ticketSubject" class="form-label input__field__label">
                                        Priority Level
                                        <span class="text-sm text-muted">
                                            <small>(Optional)</small>
                                        </span>
                                    </label>
                                </div>
                                <div class="d-flex">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="priority_level" id="rbntLow"
                                            value="1" checked>
                                        <label class="form-check-label radio__button__label" for="rbntLow">Low</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="priority_level"
                                            id="rbtnMedium" value="2">
                                        <label class="form-check-label radio__button__label"
                                            for="rbtnMedium">Medium</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="priority_level" id="rbtnHigh"
                                            value="3">
                                        <label class="form-check-label radio__button__label" for="rbtnHigh">High</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="priority_level"
                                            id="rbtnUrgent" value="4">
                                        <label class="form-check-label radio__button__label"
                                            for="rbtnUrgent">Urgent</label>
                                    </div>
                                </div>
                                @error('priority_level', 'storeTicket')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-4 mt-auto">
                                <input class="form-control form-control-sm border-0 ticket__file" id="uploadNewPhoto"
                                    type="file" name="file_attachments[]" multiple>
                                @error('file_attachments.*', 'storeTicket')
                                <span class=" error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 p-3">
                    <button type="button" class="btn ticket__modal__button btn__close__ticket__modal"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn ticket__modal__button ">Send Ticket</button>
                </div>
            </div>
        </div>
    </div>
</form>