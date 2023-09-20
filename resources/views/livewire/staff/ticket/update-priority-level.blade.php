<div>
    <!-- Priority Level Modal -->
    <div wire:ignore.self class="modal ticket__actions__modal" id="changePriorityLevelModal" tabindex="-1"
        aria-labelledby="modalFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom__modal">
            <div class="modal-content d-flex flex-column custom__modal__content">
                <div class="modal__header d-flex justify-content-between align-items-center">
                    <h6 class="modal__title">Change Priority Level</h6>
                    <button class="btn d-flex align-items-center justify-content-center modal__close__button"
                        data-bs-dismiss="modal" id="btnCloseModal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal__body">
                    <form wire:submit.prevent="updatePriorityLevel">
                        <div class="my-3">
                            <div class="d-flex justify-content-between gap-2">
                                @foreach ($priorityLevels as $priorityLevel)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" wire:model="priority_level"
                                        id="rbnt{{ $priorityLevel->name }}" value="{{ $priorityLevel->id }}"
                                        style="border-color: {{ $priorityLevel->color }} !important">
                                    <label class="form-check-label radio__button__label"
                                        for="rbnt{{ $priorityLevel->name }}" style="color: {{ $priorityLevel->color }}">
                                        {{ $priorityLevel->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn mt-2 modal__footer__button modal__btnsubmit__bottom"
                            wire:click="$emit('loadPriorityLevel')">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>