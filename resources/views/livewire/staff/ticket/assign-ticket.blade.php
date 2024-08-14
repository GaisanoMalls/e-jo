<div>
    <div wire:ignore.self class="modal fade ticket__actions__modal" id="assignTicketModal" tabindex="-1"
        aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom__modal">
            <div class="modal-content d-flex flex-column custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Ticket Assigning</h6>
                    <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                        data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal__body">
                    <form wire:submit.prevent="saveAssignTicket">
                        <div class="my-2">
                            <label class="ticket__actions__label mb-2">Service Department</label>
                            <div>
                                <div id="select-service-department" wire:ignore></div>
                            </div>
                            @error('team')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="my-2">
                            <label class="ticket__actions__label mb-2">Assign to team</label>
                            <div>
                                <div id="select-team" wire:ignore></div>
                            </div>
                            @error('team')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="my-2">
                            <label class="ticket__actions__label mb-2">
                                Assign to agent <span class="text-muted">(Optional)</span>
                                @if ($agents?->count() > 0)
                                    <span class="fw-normal" style="font-size: 13px;">
                                        ({{ $agents->count() }})
                                    </span>
                                @endif
                            </label>
                            <div>
                                <div id="select-agent" placeholder="Select" wire:ignore></div>
                            </div>
                            @error('agent')
                                <span class="error__message">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="btn mt-3 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom">
                            <span wire:loading wire:target="saveAssignTicket" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Assign
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
        const serviceDepartmentSelect = document.querySelector('#select-service-department');
        const teamSelect = document.querySelector('#select-team');
        const checkMultipleTeams = document.querySelector('#checkMultipleTeams');
        const agentSelect = document.querySelector('#select-agent');

        const serviceDepartmentOption = @json($serviceDepartments).map(serviceDepartment => ({
            label: serviceDepartment.name,
            value: serviceDepartment.id
        }));

        const teamOption = @json($teams).map(team => ({
            label: team.name,
            value: team.id
        }));

        if (@json($isSpecialProject)) {
            VirtualSelect.init({
                ele: '#select-team',
                options: teamOption,
                search: true,
                multiple: true, // Select multiple teams if ticket has special project
                markSearchResults: true,
                hasOptionDescription: true,
            });
        } else {
            VirtualSelect.init({
                ele: '#select-team',
                options: teamOption,
                search: true,
                markSearchResults: true,
                hasOptionDescription: true,
            });
        }
        teamSelect.setValue(@json($currentlyAssignedTeams))

        VirtualSelect.init({
            ele: serviceDepartmentSelect,
            options: serviceDepartmentOption,
            search: true,
            markSearchResults: true,
            hasOptionDescription: true
        });

        serviceDepartmentSelect.setValue(@json($currentlyAssignedServiceDepartment->id))

        serviceDepartmentSelect.addEventListener('change', (event) => {
            const serviceDepartmentId = parseInt(event.target.value);
            @this.set('selectedServiceDepartment', serviceDepartmentId);

            if (serviceDepartmentId) {
                window.addEventListener('selected-service-department', (event) => {
                    const teams = event.detail.teams;
                    console.log(teams);

                    const teamOption = [];

                    if (teams.length > 0) {
                        teamSelect.enable()
                        teams.forEach(function(team) {
                            teamOption.push({
                                label: team.name,
                                value: team.id,
                            });
                        });

                        teamSelect.setOptions(teamOption);
                        teamSelect.setValue(@json($currentlyAssignedTeams))

                    } else {
                        teamSelect.reset();
                        teamSelect.disable()
                    }
                });
            }
        });

        // Initialize the agent select dropdown
        VirtualSelect.init({
            ele: agentSelect,
            options: @json($agents),
            search: true,
            markSearchResults: true,
            hasOptionDescription: true
        });
        agentSelect.disable();

        window.addEventListener('get-current-team-or-agent', (event) => {
            teamSelect.setValue(event.detail.ticket.team_id);
        });

        agentSelect.addEventListener('change', (event) => {
            @this.set('agent', event.target.value);
        });

        teamSelect.addEventListener('change', (event) => {
            const teamId = event.target.value;
            @this.set('selectedTeams', teamId);

            if (teamId) {
                agentSelect.enable();
                window.addEventListener('get-agents-from-team', (event) => {
                    const agents = event.detail.agents;
                    const agentOption = [];

                    if (agents.length > 0) {
                        agents.forEach(function(agent) {
                            VirtualSelect.init({
                                ele: agentSelect,
                                search: true,
                                markSearchResults: true,
                                hasOptionDescription: true,
                            });

                            const middleName = `${agent.profile.middle_name ?? ''}`;
                            const firstLetter = middleName.length > 0 ? middleName[0] + '.' : '';

                            agentOption.push({
                                label: `${agent.profile.first_name} ${firstLetter} ${agent.profile.last_name}`,
                                value: agent.id,
                                description: `${agent.teams.map(team => team.name).join(', ')}`
                            });
                        });
                        agentSelect.setOptions(agentOption);
                        agentSelect.setValue(@json($currentlyAssignedAgent));

                    } else {
                        agentSelect.reset();
                        agentSelect.disable()
                    }
                });
            }
        });

        serviceDepartmentSelect.addEventListener('reset', () => {
            teamSelect.setOptions([]);
            teamSelect.disable();
        })

        teamSelect.addEventListener('reset', () => {
            agentSelect.setOptions([]);
            agentSelect.close();
        });

        window.addEventListener('select-multiple-team', () => {
            teamSelect.disable();
        });
    </script>
@endpush
