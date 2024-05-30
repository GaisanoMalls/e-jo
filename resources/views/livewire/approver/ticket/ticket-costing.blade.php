@php
    use App\Models\Role;
@endphp

<div>
    @if (!is_null($ticket->ticketCosting))
        <div class="card border-0 p-0 card__ticket__details">
            <div class="row p- gap-2 justify-content-center ticket__costing__container">
                <div class="col-12">
                    <div class="d-flex gap-3 flex-wrap justify-content-between">
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">
                                Actual Cost
                            </small>
                            <div class="d-flex align-items-center gap-1">
                                <span class="currency text-muted">₱</span>
                                <span class="amount">
                                    {{ $ticket->ticketCosting?->getAmount() }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">Attachment</small>
                            @if ($ticket->ticketCosting->fileAttachments->count() > 0)
                                <small class="mb-1 mt-2 costing__labels show__costing__attachments"
                                    data-bs-toggle="modal" data-bs-target="#costingPreviewFileAttachmentModal">
                                    <i class="fa-solid fa-file-zipper"></i>
                                    {{ $ticket->ticketCosting->fileAttachments->count() }}
                                    attached
                                    {{ $ticket->ticketCosting->fileAttachments->count() > 1 ? 'files' : 'file' }}
                                </small>
                            @else
                                <small
                                    class="mt-1 d-flex align-items-baseline justify-content-center costing__labels btn__show__add__costing__file__modal">
                                    No attachments
                                </small>
                            @endif
                        </div>
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">Date created</small>
                            <small class="mb-1 mt-1 costing__labels mt-2">
                                {{ $ticket->ticketCosting?->dateCreated() }}
                                <span style="font-size: 11px;">
                                    ({{ $ticket->ticketCosting?->created_at->format('D') }} @
                                    {{ $ticket->ticketCosting?->created_at->format('g:i A') }})
                                </span>
                            </small>
                        </div>
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">
                                Approver
                            </small>
                            <div class="d-flex align-items-center gap-1 costing__approver__container ">
                                @if ($this->costingApprovers($ticket))
                                    @foreach ($this->costingApprovers($ticket) as $costingApprover)
                                        @if ($costingApprover->profile->picture)
                                            <div class="d-flex position-relative">
                                                <img class="costing__approver__picture rounded-circle"
                                                    src="https://avatars.githubusercontent.com/u/63698615?s=400&u=49142410ee5c191a78412e36511c8b927fc6b1b1&v=4"
                                                    data-tooltip="{{ $costingApprover->profile->getFullName() }}"
                                                    data-tooltip-position="top" data-tooltip-font-size="11px">
                                                @if (
                                                    $this->approvedByCostingApprover1($costingApprover, $ticket) ||
                                                        $this->approvedByCostingApprover2($costingApprover, $ticket))
                                                    <div class="position-absolute d-flex align-items-center justify-content-center rounded-circle costing__approver__approved__badge"
                                                        style="background-color: green">
                                                        <i class="bi bi-check-lg"></i>
                                                    </div>
                                                @else
                                                    <div class="position-absolute rounded-circle costing__approver__approved__badge bx-flashing"
                                                        style="background-color: #FFA500">
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="d-flex position-relative">
                                                <small
                                                    class="d-flex align-items-center justify-content-center gap-1 rounded-circle costing__approver__initial"
                                                    style="background-color: {{ $costingApprover->hasRole(Role::SERVICE_DEPARTMENT_ADMIN) ? '#9DA85C' : '#3B4053' }}"
                                                    data-tooltip="{{ $costingApprover->profile->getFullName() }}  {{ $this->isDoneCostingApproval1($ticket) ? '(Approved)' : '(For approval)' }}"
                                                    data-tooltip-position="top" data-tooltip-font-size="11px">
                                                    {{ $costingApprover->profile->getNameInitial() }}
                                                </small>
                                                @if (
                                                    $this->approvedByCostingApprover1($costingApprover, $ticket) ||
                                                        $this->approvedByCostingApprover2($costingApprover, $ticket))
                                                    <div class="position-absolute d-flex align-items-center justify-content-center rounded-circle costing__approver__approved__badge"
                                                        style="background-color: green">
                                                        <i class="bi bi-check-lg"></i>
                                                    </div>
                                                @else
                                                    <div class="position-absolute rounded-circle costing__approver__approved__badge bx-flashing"
                                                        style="background-color: #FFA500">
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if ($this->isSpecialProjectCostingApprover2(auth()->user()->id, $ticket))
                            <div class="d-flex flex-column justify-content-between gap-2">
                                <small class="text-muted text-sm costing__header__label">
                                    @if ($this->isCostingApproval2Approved())
                                        Status
                                    @else
                                        Action
                                    @endif
                                </small>
                                @if ($this->isCostingApproval2Approved())
                                    <small
                                        class="d-flex align-items-center justify-content-center gap-1 rounded-4 approved__costing__status">
                                        <i class="fa-solid fa-circle-check me-1" style="color: green;"></i>
                                        Approved
                                    </small>
                                @else
                                    <div class="d-flex align-items-center gap-2">
                                        <button wire:click="approveCostingApproval2"
                                            class="btn btn-sm d-flex align-items-center justify-content-center gap-1 rounded-2 btn__approve__costing">
                                            <i class="bi bi-check2-circle" wire:loading.class="d-none"
                                                wire:target="approveCostingApproval2"></i>
                                            <div wire:loading wire:target="approveCostingApproval2"
                                                class="spinner-border spinner-border-sm loading__spinner"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            Approve
                                        </button>
                                        <button data-bs-toggle="modal" data-bs-target="#costingReasonOfDisapprovalModal"
                                            class="btn btn-sm d-flex align-items-center justify-content-center gap-1 rounded-2 btn__disapprove__costing">
                                            <i class="bi bi-x-lg"></i>
                                            Disapprove
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Ticket Costing Files Modal -->
        <div wire:ignore.self class="modal fade ticket__costing__modal" tabindex="-1"
            id="costingPreviewFileAttachmentModal" aria-labelledby="modalFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered custom__modal">
                @if ($ticket->ticketCosting->fileAttachments->count() !== 0)
                    <div class="modal-content custom__modal__content">
                        <div class="modal__header d-flex justify-content-between align-items-center">
                            <h6 class="modal__title">
                                {{ $ticket->ticketCosting->fileAttachments->count() > 1 ? 'File attachments' : 'File attachment' }}
                                ({{ $ticket->ticketCosting->fileAttachments->count() }})
                            </h6>
                        </div>
                        <div class="modal__body mt-3">
                            <ul class="list-group list-group-flush">
                                @foreach ($ticket->ticketCosting->fileAttachments->sortByDesc('created_at') as $file)
                                    <li
                                        class="list-group-item d-flex align-items-center px-0 py-3 justify-content-between">
                                        <a href="{{ Storage::url($file->file_attachment) }}" target="_blank">
                                            <div class="d-flex align-items-center gap-2">
                                                @switch(pathinfo(basename($file->file_attachment),
                                                    PATHINFO_EXTENSION))
                                                    @case('jpeg')
                                                        <img src="{{ Storage::url($file->file_attachment) }}"
                                                            class="file__preview">
                                                    @break

                                                    @case('jpg')
                                                        <img src="{{ Storage::url($file->file_attachment) }}"
                                                            class="file__preview">
                                                    @break

                                                    @case('png')
                                                        <img src="{{ Storage::url($file->file_attachment) }}"
                                                            class="file__preview">
                                                    @break

                                                    @case('pdf')
                                                        <i class="bi bi-filetype-pdf" style="font-size: 35px;"></i>
                                                    @break

                                                    @case('doc')
                                                        <i class="bi bi-filetype-doc" style="font-size: 35px;"></i>
                                                    @break

                                                    @case('docx')
                                                        <i class="bi bi-filetype-docx" style="font-size: 35px;"></i>
                                                    @break

                                                    @case('xlsx')
                                                        <i class="bi bi-filetype-xlsx" style="font-size: 35px;"></i>
                                                    @break

                                                    @case('xls')
                                                        <i class="bi bi-filetype-xls" style="font-size: 35px;"></i>
                                                    @break

                                                    @case('csv')
                                                        <i class="bi bi-filetype-csv" style="font-size: 35px;"></i>
                                                    @break

                                                    @default
                                                @endswitch
                                                <p class="mb-0" style="font-size: 14px;">
                                                    {{ basename($file->file_attachment) }}
                                                </p>
                                            </div>
                                        </a>
                                        <div
                                            class="d-flex align-items-center gap-4 file__attachment__actions__container">
                                            @if ($this->isOnlyAgent($ticket->agent_id))
                                                <a type="button" class="file__attachment__action__button"
                                                    wire:key="{{ $file->id }}"
                                                    wire:click="deleteCostingAttachent({{ $file->id }})">
                                                    <i class="bi bi-trash" wire:loading.class="d-none"
                                                        wire:target="deleteCostingAttachent({{ $file->id }})"></i>
                                                    <div wire:loading
                                                        wire:target="deleteCostingAttachent({{ $file->id }})"
                                                        class="spinner-border spinner-border-sm loading__spinner"
                                                        role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </a>
                                            @endif
                                            <a type="button" class="file__attachment__action__button"
                                                href="{{ Storage::url($file->file_attachment) }}" download
                                                target="_blank">
                                                <i class="bi bi-cloud-arrow-down"
                                                    style="font-size: 18px !important;"></i>
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Costing - Reason of Disapproval --}}
        <div wire:ignore.self class="modal fade clarification__modal" id="costingReasonOfDisapprovalModal"
            tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered custom__modal">
                <div class="modal-content d-flex flex-column custom__modal__content">
                    <div class="modal__header d-flex justify-content-between align-items-center">
                        <h6 class="modal__title">Reason of disapproval</h6>
                        <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                            data-bs-dismiss="modal" id="btnCloseModal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal__body">
                        <form wire:submit.prevent="disapproveCostingApproval2">
                            <div class="my-2">
                                <div wire:ignore>
                                    <textarea wire:model="reasonOfDisapproval" id="reasonOfDisapproval"></textarea>
                                </div>
                                @error('reasonOfDisapproval')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <button type="submit"
                                class="btn mt-4 d-flex align-items-center justify-content-center gap-2 btn__send__ticket__reply">
                                <span wire:loading wire:target="disapproveCostingApproval2"
                                    class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                </span>
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('livewire-textarea-disapproval')
    <script>
        tinymce.init({
            selector: '#reasonOfDisapproval',
            plugins: 'lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
            height: 350,
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
                editor.on('change', function(e) {
                    @this.set('reasonOfDisapproval', editor.getContent());
                });
            }
        });
    </script>
@endpush

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#costingReasonOfDisapprovalModal').modal('hide');
            tinymce.get("reasonOfDisapproval").setContent("");
        });
    </script>
@endpush
