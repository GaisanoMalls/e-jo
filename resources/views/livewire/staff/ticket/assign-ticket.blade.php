<div>
    <div wire:ignore.self class="modal slideIn fade ticket__actions__modal" id="assignTicketModal" tabindex="-1"
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
                            <label class="ticket__actions__label mb-2">Assign to agent</label>
                            <div>
                                <div id="select-agent" wire:ignore></div>
                            </div>
                            @error('agent')
                            <span class="error__message">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <button type="submit"
                            class="btn mt-3 d-flex align-items-center justify-content-center gap-2 modal__footer__button modal__btnsubmit__bottom"
                            id="saveAssignTicketButton">
                            <span wire:loading wire:target="saveAssignTicket" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
<script>
    const teamOption = [
        @foreach ($teams as $team)
            {
                label: "{{ $team->name }}",
                value: "{{ $team->id }}"
            },
        @endforeach
    ];

    VirtualSelect.init({
        ele: '#select-team',
        options: teamOption,
        search: true,
        markSearchResults: true,
        hasOptionDescription: true,
    });

    const teamSelect = document.querySelector('#select-team');

    teamSelect.addEventListener('change', () => {
        @this.set('team', teamSelect.value);
    });

    const agentOption = [
        @foreach($agents as $agent)
            {
                label: "{{ $agent->profile->getFullName() }}",
                value:  "{{ $agent->id }}",
                // description: '<i class="fa-solid fa-people-group me-1 text-muted" style="font-size: 11px;"></i>'
                //                 + "{{ $agent->getTeams() }}"
            },
        @endforeach
    ];

    VirtualSelect.init({
        ele: '#select-agent',
        options: agentOption,
        search: true,
        markSearchResults: true,
        hasOptionDescription: true
    });

    const agentSelect = document.querySelector('#select-agent');

    agentSelect.addEventListener('change', () => {
        @this.set('agent', agentSelect.value);
    });

    const saveAssignTicketButton = document.querySelector('#saveAssignTicketButton');
    saveAssignTicketButton.addEventListener('click', function () {
        teamSelect.reset();
        agentSelect.reset();
    })
</script>
@endpush