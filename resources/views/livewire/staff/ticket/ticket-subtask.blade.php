<div>
    <div class="card border-0 p-3">
        <table class="table-bordered table-sm table-responsive table">
            <thead>
                <tr>
                    <th scope="col" class="px-3 py-2" style="font-size: 12px;">Name</th>
                    <th scope="col" class="px-3 py-2" style="font-size: 12px;">Team</th>
                    <th scope="col" class="px-3 py-2" style="font-size: 12px;">Assignee</th>
                    <th scope="col" class="px-3 py-2" style="font-size: 12px;">Status</th>
                    <th scope="col" class="px-3 py-2" style="font-size: 12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-2" style="font-size: 12px;">Service Department Admin</td>
                    <td class="px-3 py-2" style="font-size: 12px;">ICT Support</td>
                    <td class="px-3 py-2" style="font-size: 12px;">Sam Sabellano</td>
                    <td class="px-3 py-2" style="font-size: 12px;">Open</td>
                    <td class="px-3 py-2" style="font-size: 12px;">...</td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-sm d-flex align-items-center justify-content-center bg-danger rounded-2 text-white" style="width: 98px; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#create-subtask">Add Subtask</button>
    </div>

    <div wire:ignore.self class="modal fade ticket__actions__modal" id="create-subtask" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom__modal">
            <div class="modal-content d-flex flex-column custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Ticket Assigning</h6>
                    <button class="btn d-flex align-items-center justify-content-center modal__close__button" data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal__body">
                    <form wire:submit.prevent="">
                        {{-- <div class="my-2">
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
                        </div> --}}
                        <button class="btn btn-sm d-flex align-items-center justify-content-center bg-danger rounded-2 text-white"
                            style="padding: 0.6rem 1rem;
                                border-radius: 0.563rem;
                                font-size: 0.875rem;
                                background-color: #d32839;
                                color: white;
                                font-weight: 500;
                                box-shadow: 0 0.25rem 0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20, 0.07);" data-bs-toggle="modal"
                            data-bs-target="#create-subtask">
                            Save Subtask
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
