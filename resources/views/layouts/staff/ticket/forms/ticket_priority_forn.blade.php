<form action="" id="ticketPrioritySection">
    @csrf
    <div class="my-3">
        <label class="ticket__actions__label">Priority</label>
        <div class="input-group">
            <select class="form-select p-0 border-0 ticket__dropdown__select">
                <option selected>{{ $ticket->priorityLevel->name }}</option>
                <option value="1">Low Priority</option>
            </select>
            <button class="btn modal__footer__button modal__btnsubmit__bottom" type="submit">Save</button>
        </div>
    </div>
</form>
