@php
    use App\Models\Status;
    use App\Enums\SubtaskStatusEnum;
@endphp

<div>
    <div class="card border-0 p-3">
        @if ($subtasks->isNotEmpty())
            <table class="table-bordered table-sm table-responsive table">
                <thead>
                    <tr>
                        <th scope="col" class="px-3 py-2" style="font-size: 13px;">Name</th>
                        <th scope="col" class="px-3 py-2" style="font-size: 13px;">Team</th>
                        <th scope="col" class="px-3 py-2" style="font-size: 13px;">Assignee</th>
                        <th scope="col" class="px-3 py-2" style="font-size: 13px;">Status</th>
                        @if (auth()->user()->isServiceDepartmentAdmin())
                            <th scope="col" class="px-3 py-2 text-center" style="font-size: 13px;">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subtasks as $subtask)
                        <tr>
                            <td class="px-3 py-2" style="font-size: 13px;">{{ $subtask->name }}</td>
                            <td class="px-3 py-2" style="font-size: 13px;">{{ $subtask->team->name }}</td>
                            <td class="px-3 py-2" style="font-size: 13px;">{{ $subtask->assignedAgent?->profile->getFullName }}</td>
                            <td class="px-3 py-2 d-flex align-items-center" @style(['font-size: 13px', 'background-color: #65b556' => $subtask->status->value === SubtaskStatusEnum::DONE->value, 'color: #FFFFFF' => $subtask->status->value === SubtaskStatusEnum::DONE->value])>
                                {{ $subtask->status }}
                                @if (auth()->user()->isAgent() && $ticket->status_id !== Status::CLOSED || auth()->user()->isServiceDepartmentAdmin() && $ticket->status_id !== Status::CLOSED)
                                    <div class="dropstart ms-auto">
                                        <button type="button"
                                            class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center text-muted"
                                            data-bs-toggle="dropdown" aria-expanded="false" style="height: 20px; width: 20px;">
                                            <i class="bi bi-three-dots-vertical" @style(['color: #FFFFFF' => $subtask->status->value === SubtaskStatusEnum::DONE->value])></i>
                                        </button>
                                        <ul class="dropdown-menu py-2">
                                            @foreach ($subtaskStatuses as $subtastStatus)
                                                <li>
                                                    <button wire:click="changeSubtaskStatus({{ $subtask->id }}, '{{ $subtastStatus }}')"
                                                        class="dropdown-item rounded-2 py-2 d-flex align-items-center subtask__status__button__option"
                                                        type="button" @style(['font-size: 13px', 'color: #8b2732' => $subtastStatus === $subtask->status->value]) @disabled($subtastStatus === $subtask->status->value)>
                                                        {{ $subtastStatus }}
                                                        @if ($subtastStatus === $subtask->status->value)
                                                            <i class="bi bi-check-lg ms-auto"></i>
                                                        @endif
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </td>
                            @if (auth()->user()->isServiceDepartmentAdmin())
                                <td class="px-3" style="font-size: 13px;">
                                    <button wire:click="editSubtask({{ $subtask->id }})"
                                        class="btn btn-sm d-flex align-items-center justify-content-center bg-blue rounded-2 gap-1 mx-auto"
                                        style="font-size: 11px; padding: 1px 6px; background-color: #f3f4f6; border: 1px solid rgb(223, 228, 233);"
                                        data-bs-toggle="modal" data-bs-target="#editSubtaskModal">
                                        <i class="bi bi-pencil"></i>
                                        Edit
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if (auth()->user()->isServiceDepartmentAdmin() && $ticket->status_id !== Status::OVERDUE)
            <button wire:click="addSubtaskButton" class="btn btn-sm d-flex align-items-center justify-content-center bg-danger rounded-2 text-white"
                style="width: 98px; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#createSubtaskModal">
                Add Subtask
            </button>
        @endif
    </div>

    @if (auth()->user()->isServiceDepartmentAdmin())
        {{-- Create Subtask Modal --}}
        <div wire:ignore.self class="modal fade ticket__actions__modal" id="createSubtaskModal" tabindex="-1" aria-labelledby="modalFormLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom__modal">
                <div class="modal-content d-flex flex-column custom__modal__content">
                    <div class="modal__header d-flex justify-content-between align-items-center">
                        <h6 class="modal__title">Create Subtask</h6>
                        <button class="btn d-flex align-items-center justify-content-center modal__close__button" data-bs-dismiss="modal"
                            id="btnCloseModal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal__body">
                        <form wire:submit.prevent="saveSubtask" autocomplete="off">
                            <div class="my-2">
                                <label class="ticket__actions__label mb-2" for="task-name">Task name</label>
                                <input type="text" wire:model="subtaskName" class="form-control form__field" id="task-name"
                                    placeholder="Enter task name">
                                @error('subtaskName')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="my-2">
                                <label class="ticket__actions__label mb-2">
                                    Team
                                </label>
                                <div>
                                    <div id="select-subtask-team" wire:ignore placeholder="Select a team"></div>
                                </div>
                                @error('subtaskTeam')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="my-2">
                                <label class="ticket__actions__label mb-2">
                                    Agent
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <div>
                                    <div id="select-subtask-agent" wire:ignore placeholder="Select an agent"></div>
                                </div>
                                @error('subtaskAgent')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit"
                                class="mt-3 btn btn-sm d-flex align-items-center justify-content-center bg-danger rounded-3 text-white"
                                style="padding: 0.6rem 1rem;
                                    border-radius: 0.563rem;
                                    font-size: 0.875rem;
                                    background-color: #d32839;
                                    color: white;
                                    font-weight: 500;
                                    box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20, 0.07);">
                                Save subtask
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Subtask Modal --}}
        <div wire:ignore.self class="modal fade ticket__actions__modal" id="editSubtaskModal" tabindex="-1" aria-labelledby="modalFormLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered custom__modal">
                <div class="modal-content d-flex flex-column custom__modal__content">
                    <div class="modal__header d-flex justify-content-between align-items-center">
                        <h6 class="modal__title">Edit Subtask</h6>
                        <button class="btn d-flex align-items-center justify-content-center modal__close__button" data-bs-dismiss="modal"
                            id="btnCloseModal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal__body">
                        <form wire:submit.prevent="updateSubtask" autocomplete="off">
                            <div class="my-2">
                                <label class="ticket__actions__label mb-2" for="task-name">Task name</label>
                                <input type="text" wire:model="editSubtaskName" class="form-control form__field" id="task-name"
                                    placeholder="Enter task name">
                                @error('editSubtaskName')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="my-2">
                                <label class="ticket__actions__label mb-2">
                                    Team
                                </label>
                                <div>
                                    <div id="select-edit-subtask-team" wire:ignore placeholder="Select a team"></div>
                                </div>
                                @error('editSubtaskTeam')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="my-2">
                                <label class="ticket__actions__label mb-2">
                                    Agent
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <div>
                                    <div id="select-edit-subtask-agent" wire:ignore placeholder="Select an agent"></div>
                                </div>
                                @error('editSubtaskAgent')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="d-flex align-items-center mt-3 gap-2">
                                <button type="submit"
                                    class="btn btn-sm d-flex align-items-center justify-content-center bg-danger rounded-3 text-white"
                                    style="padding: 0.6rem 1rem;
                                        border-radius: 0.563rem;
                                        font-size: 0.875rem;
                                        background-color: #d32839;
                                        color: white;
                                        font-weight: 500;
                                        box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20, 0.07);">
                                    Update
                                </button>
                                <button type="button" class="btn m-0" data-bs-dismiss="modal"
                                    style="padding: 0.6rem 1rem;
                                            border-radius: 0.563rem;
                                            font-size: 0.875rem;
                                            border: 1px solid #e7e9eb;
                                            background-color: transparent;
                                            color: #d32839;
                                            font-weight: 500;">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


@push('livewire-select')
    <script>
        const selectSubtaskTeam = document.querySelector('#select-subtask-team');
        const selectSubtaskAgent = document.querySelector('#select-subtask-agent');

        const subtaskTeamOption = @json($subtaskTeams).map(team => ({
            label: team.name,
            value: team.id
        }));

        VirtualSelect.init({
            ele: selectSubtaskTeam,
            options: subtaskTeamOption,
            search: true,
            markSearchResults: true,
        });

        selectSubtaskTeam.addEventListener('change', (event) => {
            @this.set('subtaskTeam', parseInt(event.target.value));
        });

        const subtaskAgentOption = @json($subtaskAgents).map(agent => {
            const middleName = `${agent.profile.middle_name ?? ''}`;
            const firstLetter = middleName.length > 0 ? middleName[0] + '.' : '';

            return {
                label: `${agent.profile.first_name} ${firstLetter} ${agent.profile.last_name}`,
                value: agent.id
            }
        });

        VirtualSelect.init({
            ele: selectSubtaskAgent,
            options: subtaskAgentOption,
            search: true,
            markSearchResults: true,
        });

        selectSubtaskAgent.addEventListener('change', (event) => {
            @this.set('subtaskAgent', parseInt(event.target.value));
        });


        const selectEditSubtaskTeam = document.querySelector('#select-edit-subtask-team');
        const selectEditSubtaskAgent = document.querySelector('#select-edit-subtask-agent');

        VirtualSelect.init({
            ele: selectEditSubtaskTeam,
            options: subtaskTeamOption,
            search: true,
            markSearchResults: true,
        });

        VirtualSelect.init({
            ele: selectEditSubtaskAgent,
            options: subtaskAgentOption,
            search: true,
            markSearchResults: true,
        });

        window.addEventListener('edit-subtask', (event) => {
            const currentSubtaskTeam = event.detail.editSubtaskTeam;
            const currentSubtaskAgent = event.detail.editSubtaskAgent;

            selectEditSubtaskTeam.setValue(currentSubtaskTeam);
            selectEditSubtaskAgent.setValue(currentSubtaskAgent);
        });

        selectEditSubtaskTeam.addEventListener('reset', () => {
            @this.set('editSubtaskTeam', null);
            @this.set('editSubtaskAgent', null);

            selectEditSubtaskAgent.reset();
            selectEditSubtaskAgent.disable();
        })

        selectEditSubtaskTeam.addEventListener('change', (event) => {
            const selectedTeam = event.target.value;

            if (selectedTeam) {
                @this.set('editSubtaskTeam', parseInt(selectedTeam));
                selectEditSubtaskAgent.enable();
            }
        });

        selectEditSubtaskAgent.addEventListener('change', (event) => {
            @this.set('editSubtaskAgent', parseInt(event.target.value));
        });

        window.addEventListener('close-create-subtask-modal', () => {
            $('#createSubtaskModal').modal('hide');
            selectSubtaskTeam.reset();
            selectSubtaskAgent.reset();

        });
        window.addEventListener('close-edit-subtask-modal', () => {
            $('#editSubtaskModal').modal('hide');
            selectEditSubtaskTeam.reset();
            selectEditSubtaskAgent.reset();
        });
    </script>
@endpush
