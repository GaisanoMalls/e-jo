<div>
    <div class="table-responsive custom__table">
        @if (!$serviceLevelAgreements->isEmpty())
        <table class="table table-striped mb-0" id="table">
            <thead>
                <tr>
                    <th class="border-0 table__head__label" style="padding: 17px 30px;">Hours</th>
                    <th class="border-0 table__head__label" style="padding: 17px 30px;">Time Unit</th>
                    <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                    <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($serviceLevelAgreements as $sla)
                <tr>
                    <td>
                        <div class="d-flex align-items-center text-start td__content">
                            <span>{{ $sla->countdown_approach }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-start td__content">
                            <span>{{ $sla->time_unit }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-start td__content">
                            <span>{{ $sla->dateCreated() }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-start td__content">
                            <span>{{ $sla->dateUpdated() }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                            <button data-tooltip="Edit" data-tooltip-position="top" data-tooltip-font-size="11px"
                                type="button" class="btn action__button" data-bs-toggle="modal"
                                data-bs-target="#editSLAModal" wire:click="editSLA({{ $sla->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                data-bs-target="#deleteSLAModal" wire:click="deleteSLA({{ $sla->id }})">
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
            <small style="font-size: 14px;">No SLA records.</small>
        </div>
        @endif
    </div>

    {{-- Edit SLA Modal --}}
    <div wire:ignore.self class="modal slideIn animate sla__modal" id="editSLAModal" tabindex="-1"
        aria-labelledby="editSLAModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewSLAModalLabel">Edit Service Level Agreement</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="updateSLA">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="countdown_approach" class="form-label form__field__label">Hours</label>
                                    <input type="text" wire:model="countdown_approach" class="form-control form__field
                                        @error('countdown_approach') is-invalid @enderror" id="countdown_approach"
                                        placeholder="e.g. 24">
                                    @error('countdown_approach')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                    @enderror
                                    @if (session()->has('duplicate_name_error'))
                                    <div class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ session()->get('duplicate_name_error') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label for="time_unit" class="form-label form__field__label">
                                        Time unit
                                    </label>
                                    <input type="text" wire:model="time_unit" class="form-control form__field
                                        @error('time_unit') is-invalid @enderror" id="time_unit"
                                        placeholder="e.g. 1 Day">
                                    @error('time_unit')
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
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                <span wire:loading wire:target="updateSLA" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Update
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" data-bs-dismiss="modal"
                                wire:click="clearFormFields">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete SLA Modal --}}
    <div wire:ignore.self class="modal slideIn animate modal__confirm__delete__sla" id="deleteSLAModal" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-body border-0 text-center pt-4 pb-1">
                    <h6 class="fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                        Confirm Delete
                    </h6>
                    <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                        Are you sure you want to delete this SLA?
                    </p>
                    <strong>{{ $countdown_approach }} - {{ $time_unit }}</strong>
                </div>
                <hr>
                <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                    <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"
                        class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                        wire:click="delete">
                        <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true">
                        </span>
                        Yes, delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
<script>
    window.addEventListener('close-modal', event =>{
        $('#editSLAModal').modal('hide');
        $('#deleteSLAModal').modal('hide');
    });

    window.addEventListener('show-edit-sla-modal', event =>{
        $('#editSLAModal').modal('show');
    });

    window.addEventListener('show-delete-sla-modal', event =>{
        $('#deleteSLAModal').modal('show');
    });

</script>
@endpush