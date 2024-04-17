<div>
    <div class="table-responsive custom__table">
        @if ($helpTopics->isNotEmpty())
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Help Topic</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Team</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">SLA</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($helpTopics as $helpTopic)
                        <tr wire:key="help-topic-{{ $helpTopic->id }}">
                            <td>
                                <div
                                    class="d-flex gap-4 justify-content-between align-items-center text-start td__content">
                                    <span>{{ $helpTopic->name }}</span>
                                    @if ($helpTopic->specialProject?->amount)
                                        <div class="d-flex align-items-center rounded-4"
                                            style="background-color: #f1f3ef; padding: 0.1rem 0.4rem;">
                                            <span style="font-size: 11px; color: #D32839;">₱</span>
                                            <span
                                                style="font-size: 11px; color: #D32839;">{{ number_format($helpTopic->specialProject?->amount, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->serviceDepartment?->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->team?->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->sla?->time_unit }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->dateCreated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $helpTopic->dateUpdated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                    <button data-tooltip="Edit" data-tooltip-position="top"
                                        data-tooltip-font-size="11px"
                                        onclick="window.location.href='{{ route('staff.manage.help_topic.edit_details', $helpTopic->id) }}'"
                                        type="button" class="btn action__button">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteHelpTopicModal"
                                        wire:click="deleteHelpTopic({{ $helpTopic->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                <small style="font-size: 14px;">No records for help topics.</small>
            </div>
        @endif
    </div>

    {{-- Delete Help Topic Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__help__topic" id="deleteHelpTopicModal"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="delete">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h6 class="fw-bold mb-4"
                            style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h6>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Are you sure you want to delete this help topic?
                        </p>
                        <strong>{{ $helpTopicName }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                            wire:click="delete">
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
            $('#deleteHelpTopicModal').modal('hide');
        });

        window.addEventListener('show-delete-help-topic-modal', () => {
            $('#deleteHelpTopicModal').modal('show');
        });
    </script>
@endpush
