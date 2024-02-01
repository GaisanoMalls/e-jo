<div>
    @if (!is_null($ticket->ticketCosting))
        <div class="card border-0 p-0 card__ticket__details">
            <div class="row p- gap-2 justify-content-center ticket__costing__container">
                <div class="col-md-7">
                    <div class="d-flex gap-3 flex-wrap justify-content-between">
                        <div class="d-flex flex-column justify-content-between gap-2">
                            <small class="text-muted text-sm costing__header__label" @style(['color: #d32839 !important;' => $isCostingGreaterOrEqual])>
                                Actual Cost
                            </small>
                            <div class="d-flex align-items-center gap-1">
                                <span class="currency text-muted" @style(['color: #d32839 !important;' => $isCostingGreaterOrEqual])>₱</span>
                                @if ($editingFieldId === $ticket->ticketCosting->id)
                                    <div class="d-flex flex-column gap-1">
                                        <input type="text" wire:model.defer="amount"
                                            class="form-control p-0 rounded-0 border-0 border-2 border-bottom fw-bold form__field ticket__actual__cost"
                                            id="amount" placeholder="{{ $ticket->ticketCosting->amount }}"
                                            @style(['color: #d32839;' => $isCostingGreaterOrEqual])>
                                        @error('amount')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                @else
                                    <span class="amount" @style(['color: #d32839;' => $isCostingGreaterOrEqual])>
                                        {{ $ticket->ticketCosting?->getAmount() }}
                                    </span>
                                @endif
                                <div class="d-flex align-items-center">
                                    @if ($editingFieldId === $ticket->ticketCosting->id)
                                        <button wire:click="updateTicketCosting"
                                            class="btn btn-sm d-flex ms-2 rounded-circle align-items-center justify-content-center btn__update__costing">
                                            <i class="bi bi-check-lg" wire:loading.class="d-none"
                                                wire:target="updateTicketCosting" style="font-size: 18px;"></i>
                                            <div wire:loading wire:target="updateTicketCosting"
                                                class="spinner-border spinner-border-sm loading__spinner"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    @endif
                                    <button wire:click="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"
                                        class="btn btn-sm d-flex ms-2 rounded-circle align-items-center justify-content-center btn__edit__costing">
                                        @if ($editingFieldId === $ticket->ticketCosting->id)
                                            <i class="bi bi-x-lg" wire:loading.class="d-none"
                                                wire:target="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"></i>
                                            <div wire:loading
                                                wire:target="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"
                                                class="spinner-border spinner-border-sm loading__spinner"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        @else
                                            <i class="bi bi-pencil" wire:loading.class="d-none"
                                                wire:target="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"></i>
                                            <div wire:loading
                                                wire:target="toggleEditCostingAmount({{ $ticket->ticketCosting->id }})"
                                                class="spinner-border spinner-border-sm loading__spinner"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                        @if ($editingFieldId !== $ticket->ticketCosting->id)
                            <div class="d-flex flex-column justify-content-between gap-2">
                                <small class="text-muted text-sm costing__header__label">Attachment</small>
                                <small class="mb-1 mt-2 costing__labels show__costing__attachments"
                                    data-bs-toggle="modal" data-bs-target="#costingFileAttachmentModal">
                                    @if ($ticket->ticketCosting->fileAttachments->count() > 0)
                                        <i class="fa-solid fa-file-zipper"></i>
                                        {{ $ticket->ticketCosting->fileAttachments->count() }}
                                        {{ $ticket->ticketCosting->fileAttachments->count() > 1 ? 'files' : 'file' }}
                                        attached
                                    @else
                                        N/A
                                    @endif
                                </small>
                            </div>
                            <div class="d-flex flex-column justify-content-between gap-2">
                                <small class="text-muted text-sm costing__header__label">Date created</small>
                                <small
                                    class="mb-1 mt-2 costing__labels mt-2">{{ $ticket->ticketCosting?->dateCreated() }}</small>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-1 d-flex justify-content-center">
                    <div class="separator"></div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex flex-column justify-content-between gap-2">
                        <small class="text-muted text-sm costing__header__label">Special Project Cost</small>
                        <div class="d-flex gap-1">
                            <span class="currency" style="color: #d32839;">₱</span>
                            <span class="amount" style="color: #d32839;">
                                {{ $ticket->helpTopic->specialProject?->getAmount() }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Preview Ticket Costing Files Modal -->
                <div class="modal fade ticket__actions__modal" id="costingFileAttachmentModal" tabindex="-1"
                    aria-labelledby="modalFormLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered custom__modal">
                        <div class="modal-content custom__modal__content">
                            <div class="modal__header d-flex justify-content-between align-items-center">
                                <h6 class="modal__title">
                                    {{ $ticket->ticketCosting->fileAttachments->count() > 1 ? 'File attachments' : 'File attachment' }}
                                    ({{ $ticket->ticketCosting->fileAttachments->count() }})
                                </h6>
                            </div>
                            <div class="modal__body mt-3">
                                <ul class="list-group list-group-flush">
                                    @foreach ($ticket->ticketCosting->fileAttachments as $file)
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
                                                <a type="button" class="file__attachment__action__button"
                                                    wire:click="deleteCostingAttachent({{ $file->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </a>
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
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
