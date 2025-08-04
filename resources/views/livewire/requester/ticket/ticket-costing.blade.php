@php
    use App\Enums\SpecialProjectStatusEnum;
@endphp

<div wire:poll.visible.10s>
    @if (!is_null($ticket->ticketCosting))
        <div class="card border-0 p-0 card__ticket__details">
            <div class="row gap-2 justify-content-center ticket__costing__container">
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
                                <small class="mb-1 mt-2 costing__labels show__costing__attachments" data-bs-toggle="modal"
                                    data-bs-target="#costingPreviewFileAttachmentModal">
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
                                                    data-tooltip="{{ $costingApprover->profile->getFullName }}  {{ $this->isDoneCostingApproval1($ticket) ? '(Approved)' : 'For approval' }}"
                                                    data-tooltip-position="top" data-tooltip-font-size="11px">
                                                @if ($this->approvedByCostingApprover1($costingApprover, $ticket) || $this->approvedByCostingApprover2($costingApprover, $ticket))
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
                                                    style="background-color: {{ $costingApprover->isServiceDepartmentAdmin() ? '#9DA85C' : '#3B4053' }}"
                                                    data-tooltip="{{ $costingApprover->profile->getFullName }}  {{ $this->isDoneCostingApproval1($ticket) ? '(Approved)' : '(For approval)' }}"
                                                    data-tooltip-position="top" data-tooltip-font-size="11px">
                                                    {{ $costingApprover->profile->getNameInitial() }}
                                                </small>
                                                @if ($this->approvedByCostingApprover1($costingApprover, $ticket) || $this->approvedByCostingApprover2($costingApprover, $ticket))
                                                    <div class="position-absolute d-flex align-items-center justify-content-center rounded-circle costing__approver__approved__badge"
                                                        style="background-color: green">
                                                        <i class="bi bi-check-lg"></i>
                                                    </div>
                                                @elseif ($this->disapprovedByCostingApprover1($costingApprover, $ticket) && $this->isDoneCostingApproval1($ticket)  || $this->disapprovedByCostingApprover2($costingApprover, $ticket) && $this->isDoneCostingApproval2($ticket))
                                                    <div class="position-absolute d-flex align-items-center justify-content-center rounded-circle costing__approver__approved__badge"
                                                        style="background-color: red">
                                                        <i class="bi bi-x-lg"></i>
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
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label">
                                Status
                            </small>
                            @if ($this->isDoneCostingApproval1($ticket))
                                @if ($this->isCostingAmountNeedCOOApproval($ticket) && !$this->isDoneCostingApproval2($ticket))
                                    <small
                                        class="d-flex align-items-center justify-content-center gap-1 rounded-4 text-dark approved__costing__status">
                                        <i class="fa-solid fa-paper-plane me-1" style="color: orange;"></i>
                                        For approval
                                    </small>
                                @elseif ($this->disapprovedByCostingApprover1($costingApprover, $ticket) || $this->disapprovedByCostingApprover2($costingApprover, $ticket) && $this->isDoneCostingApproval2($ticket))
                                    <small class="d-flex align-items-center justify-content-center gap-1 rounded-4 disapproved__costing__status">
                                        <i class="fa-solid fa-circle-check me-1" style="color: red;"></i>
                                        Disapproved
                                    </small>
                                @else
                                    <small class="d-flex align-items-center justify-content-center gap-1 rounded-4 approved__costing__status">
                                        <i class="fa-solid fa-circle-check me-1" style="color: green;"></i>
                                        Approved
                                    </small>
                                @endif
                            @else
                                <small class="d-flex align-items-center justify-content-center gap-1 rounded-4 text-dark approved__costing__status">
                                    <i class="fa-solid fa-paper-plane me-1" style="color: orange;"></i>
                                    For approval
                                </small>
                            @endif
                        </div>
                        <div class="d-flex flex-column justify-content-between gap-2 position-relative">
                            <small class="text-muted text-sm costing__header__label">
                                Purchasing
                            </small>
                            @if (auth()->user()->isUser())
                                <small class="d-flex align-items-center justify-content-center gap-1 rounded-4 approved__costing__status">
                                    @if ($ticket->specialProjectStatus->purchasing_status)
                                        <i class="fa-solid fa-cart-arrow-down" style="color: green;"></i>
                                        {{ $ticket->specialProjectStatus->purchasing_status }}
                                    @else
                                        N/A
                                    @endif
                                </small>
                            @else
                                <small class="d-flex align-items-center justify-content-center gap-1 rounded-4 approved__costing__status">
                                    @if ($this->getPurchasingStatus() === SpecialProjectStatusEnum::ON_ORDERED->value)
                                        <i class="fa-solid fa-cart-arrow-down" style="color: green;"></i>
                                        {{ SpecialProjectStatusEnum::ON_ORDERED->value }}
                                    @elseif ($this->getPurchasingStatus() === SpecialProjectStatusEnum::DELIVERED->value)
                                        <i class="fa-solid fa-truck" style="color: green;"></i>
                                        {{ SpecialProjectStatusEnum::DELIVERED->value }}
                                    @else
                                        N/A
                                    @endif
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($this->isDoneSpecialProjectAmountApproval($ticket))
                <div class="row ticket__attach__pr__section">
                    <div class="col-12">
                        <div class="ticket__pr__content">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <span class="pr__section__label">
                                    You can now fill out and attach the PR for costing.
                                </span>
                                @if ($ticket->ticketCosting->prFileAttachments->count() > 0)
                                    <small
                                        class="mt-1 d-flex gap-1 align-items-baseline justify-content-center costing__labels btn__show__add__costing__pr__file__modal"
                                        data-bs-toggle="modal" data-bs-target="#addCostingPRFileModal">
                                        <i class="fa-solid fa-file-zipper"></i>
                                        {{ $ticket->ticketCosting->prFileAttachments->count() }}
                                        attached
                                        {{ $ticket->ticketCosting->prFileAttachments->count() > 1 ? 'files' : 'file' }}
                                    </small>
                                @else
                                    <small
                                        class="mt-1 d-flex gap-1 align-items-baseline justify-content-center costing__labels btn__show__add__costing__pr__file__modal"
                                        data-bs-toggle="modal" data-bs-target="#addCostingPRFileModal">
                                        <i class="bi bi-filetype-pdf"></i>
                                        Attach PR
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Preview Ticket Costing Files Modal -->
        <div wire:ignore.self class="modal fade ticket__costing__modal" tabindex="-1" id="costingPreviewFileAttachmentModal"
            aria-labelledby="modalFormLabel" aria-hidden="true">
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
                                    <li class="list-group-item d-flex align-items-center px-0 py-3 justify-content-between">
                                        <a href="{{ Storage::url($file->file_attachment) }}" target="_blank">
                                            <div class="d-flex align-items-center gap-2">
                                                @switch(pathinfo(basename($file->file_attachment),
                                                    PATHINFO_EXTENSION))
                                                    @case('jpeg')
                                                        <img src="{{ Storage::url($file->file_attachment) }}" class="file__preview">
                                                    @break

                                                    @case('jpg')
                                                        <img src="{{ Storage::url($file->file_attachment) }}" class="file__preview">
                                                    @break

                                                    @case('png')
                                                        <img src="{{ Storage::url($file->file_attachment) }}" class="file__preview">
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
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
