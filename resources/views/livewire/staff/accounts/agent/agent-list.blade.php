<div>
    @if ($agents->isNotEmpty())
        <div
            class="card account__type__card {{ Route::is('staff.manage.user_account.agents') ? 'card__rounded__and__no__border' : '' }}">
            <div class="table-responsive custom__table">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Name
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Service Department
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Branch
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                BU/Department
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Teams
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Sub-teams
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Status
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Permissions
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Date Added
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Date Updated
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $agent)
                            <tr wire:key="agent-{{ $agent->id }}">
                                <td>
                                    <a href="{{ route('staff.manage.user_account.agent.view_details', $agent->id) }}">
                                        <div class="media d-flex align-items-center user__account__media">
                                            <div class="flex-shrink-0">
                                                @if ($agent->profile->picture)
                                                    <img src="{{ Storage::url($agent->profile->picture) }}"
                                                        alt="" class="image-fluid user__picture">
                                                @else
                                                    <div class="user__name__initial" style="background-color: #196837;">
                                                        {{ $agent->profile->getNameInitial() }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column gap-1 ms-3 w-100">
                                                <span class="user__name">{{ $agent->profile->getFullName() }}</span>
                                                <small>{{ $agent->email }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $agent->getServiceDepartments() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $agent->getBranches() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $agent->getBUDepartments() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ Str::limit($agent->getTeams(), 30) }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ Str::limit($agent->getSubteams(), 30) }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $agent->isActive() ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start gap-1 td__content">
                                        <span><i class="bi bi-person-lock text-muted"></i></span>
                                        <span>{{ $agent->getAllPermissions()->count() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $agent->dateCreated() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>
                                            @if ($agent->dateUpdated() > $agent->profile->dateUpdated())
                                                {{ $agent->dateUpdated() }}
                                            @else
                                                {{ $agent->profile->dateUpdated() }}
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                        <button data-tooltip="Edit" data-tooltip-position="top"
                                            data-tooltip-font-size="11px"
                                            onclick="window.location.href='{{ route('staff.manage.user_account.agent.edit_details', $agent->id) }}'"
                                            type="button" class="btn action__button">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button data-tooltip="Delete" data-tooltip-position="top"
                                            data-tooltip-font-size="11px" type="button" class="btn action__button"
                                            data-bs-toggle="modal" data-bs-target="#confirmDeleteAgent"
                                            wire:click="deleteAgent({{ $agent->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert text-center d-flex align-items-center justify-content-center gap-2" role="alert"
            style="background-color: #F5F7F9; font-size: 14px;">
            <i class="fa-solid fa-circle-info"></i>
            Empty records for agents.
        </div>
    @endif

    {{-- Delete Agent Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__user__account" id="confirmDeleteAgentModal"
        tabindex="-1" aria-labelledby="confirmDeleteAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="delete">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h5 class="fw-bold mb-4"
                            style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h5>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Are you sure you want to delete this agent?
                        </p>
                        <strong>{{ $agentFullName }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn w-50 d-flex align-items-center justify-content-center gap-2 btn__confirm__delete btn__confirm__modal">
                            <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true">
                            </span>
                            Yes, delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#confirmDeleteAgentModal').modal('hide');
        });

        window.addEventListener('show-delete-agent-modal', () => {
            $('#confirmDeleteAgentModal').modal('show');
        });
    </script>
@endpush
