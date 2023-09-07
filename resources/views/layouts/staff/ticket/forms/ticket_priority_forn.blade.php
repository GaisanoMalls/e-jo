<div class="my-3">
    <label class="ticket__actions__label">Priority</label>
    <div class="d-flex gap-2">
        @foreach ($priorityLevels as $priorityLevel)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="priority_level" id="rbnt{{ $priorityLevel->name }}"
                value="1" {{ $ticket->priority_level_id === $priorityLevel->id ? 'checked' : '' }}>
            <label class="form-check-label radio__button__label" for="rbnt{{ $priorityLevel->name }}">
                {{ $priorityLevel->name }}
            </label>
        </div>
        @endforeach
    </div>
</div>