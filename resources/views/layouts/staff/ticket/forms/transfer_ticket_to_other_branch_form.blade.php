<div class="my-2">
    <div class="d-flex align-items-center gap-1">
        <input class="cb__transfer__ticket__to__department" id="checkBoxTransferTicket" type="checkbox">
        <label class="ticket__actions__label" for="checkBoxTransferTicket">
            Transfer to other branch
        </label>
    </div>
</div>
<div id="transferDepartmentSection">
    <form action="">
        <div class="my-3">
            <label class="ticket__actions__label">
                Priority
                <small>
                    <em>(Current:
                        <span style="color: {{ $ticket->priorityLevel->color }};">
                            {{ $ticket->priorityLevel->name }}
                        </span>
                        )
                    </em>
                </small>
            </label>
            <div class="input-group">
                <select class="form-select p-0 border-0 ticket__dropdown__select">
                    <option selected disabled>Choose a priority level</option>
                    <option value="1">Low Priority</option>
                </select>
            </div>
        </div>
        <div class="my-3">
            <label class="ticket__actions__label">
                Department
            </label>
            <div class="input-group">
                <div class="input-group">
                    <select class="form-select p-0 border-0 ticket__dropdown__select" data-search="true"
                        id="transferTicketDepartmentsDropdown">
                        <option value="{{ $ticket->service_department_id }}" selected>
                            {{ $ticket->serviceDepartment->name }}
                        </option>
                        @foreach ($departments as $department)
                            @if ($department->id !== $ticket->service_department_id)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="my-3">
            <label class="ticket__actions__label">
                Team
                <small id="transferTicketNoTeamsMessage" class="text-danger fw-semibold"
                    style="font-size: 12px;"></small>
                <small id="transferTicketCountTeams"></small>
            </label>
            <div class="input-group">
                <select class="form-select p-0 border-0 ticket__dropdown__select" data-search="true"
                    id="transferTicketTeamsDropdown">
                    <option value="{{ $ticket->team->id }}" selected>{{ $ticket->team->name }}</option>
                    @foreach ($teams as $team)
                        @if ($team->id !== $ticket->team->id)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <button class="btn modal__footer__button modal__btnsubmit__bottom" type="submit" id="btnSaveTransferTicket"
            disabled>
            Save
        </button>
    </form>
</div>
