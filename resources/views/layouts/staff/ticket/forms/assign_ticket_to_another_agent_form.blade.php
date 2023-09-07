<div class="my-2">
    <label class="ticket__actions__label mb-2">Assign to agent</label>
    <div class="input-group">
        <select class="form-select p-0 border-0 ticket__dropdown__select" id="selectAssignToAgent">
            <option value="" selected disabled>Choose a suffix</option>
            @foreach ($approvers as $approver)
            <option value="{{ $approver->id }}">
                {{ $approver->profile->getFullName() }}
            </option>
            @endforeach
        </select>
    </div>
</div>