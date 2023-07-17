<form action="{{ route('user.ticket.requesterStoreTicketReply', $ticket->id) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <textarea id="myeditorinstance" name="description" placeholder="Type here..."></textarea>
    @error('description', 'requesterStoreTicketReply')
    <span class="error__message">
        <i class="fa-solid fa-triangle-exclamation"></i>
        {{ $message }}
    </span>
    @enderror
    <div class="mt-1 d-flex flex-wrap align-items-center justify-content-between">
        <div class="d-flex flex-column gap-1">
            <input class="form-control ticket__file__input w-auto my-3" type="file" name="replyFiles[]" id="ticketFile"
                multiple>
            @error('replyFiles', 'requesterStoreTicketReply')
            <span class="error__message">
                <i class="fa-solid fa-triangle-exclamation"></i>
                {{ $message }}
            </span>
            @enderror
        </div>
        <button type="submit"
            class="btn my-3 d-flex align-items-center justify-content-center gap-2 btn__send__ticket__reply">
            Send
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</form>
