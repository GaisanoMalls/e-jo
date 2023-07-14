<!-- Preview Ticket Files Modal -->
<div class="modal ticket__actions__modal" id="replyTicketFilesModalForm{{ $reply->id }}" tabindex="-1"
    aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered custom__modal">
        <div class="modal-content custom__modal__content">
            <div class="modal__header d-flex justify-content-between align-items-center">
                <h6 class="modal__title">
                    {{ $reply->fileAttachments->count() > 1 ? 'Reply file attachments' : 'Reply file attachment'}}
                    ({{ $reply->fileAttachments->count() }})
                </h6>
                <a href="" style="font-size: 14px;">Download all</a>
            </div>
            <div class="modal__body mt-3">
                <ul class="list-group list-group-flush">
                    @foreach ($reply->fileAttachments as $replyFile)
                    <li class="list-group-item d-flex align-items-center px-0 py-3 justify-content-between">
                        <a href="{{ Storage::url($replyFile->file_attachment) }}" target="_blank">
                            <div class="d-flex align-items-center gap-2">
                                @switch(pathinfo(basename($replyFile->file_attachment), PATHINFO_EXTENSION))
                                @case('jpeg')
                                <img src="{{ Storage::url($replyFile->file_attachment) }}" class="file__preview">
                                @break
                                @case('jpg')
                                <img src="{{ Storage::url($replyFile->file_attachment) }}" class="file__preview">
                                @break
                                @case('png')
                                <img src="{{ Storage::url($replyFile->file_attachment) }}" class="file__preview">
                                @break
                                @case('pdf')
                                <i class="fa-solid fa-file-pdf" style="font-size: 35px;"></i>
                                @break
                                @case('docx')
                                <i class="fa-solid fa-file-word" style="font-size: 35px;"></i>
                                @break
                                @default
                                @endswitch
                                <p class="mb-0" style="font-size: 14px;">{{ basename($replyFile->file_attachment) }}</p>
                            </div>
                        </a>
                        <a href="{{ Storage::url($replyFile->file_attachment) }}" download target="_blank"
                            style="font-size: 20px;">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
