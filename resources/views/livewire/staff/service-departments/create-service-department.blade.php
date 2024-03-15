<div>
    <div wire:ignore.self class="modal fade department__modal" id="addNewServiceDepartmentModal" tabindex="-1"
        aria-labelledby="addNewServiceDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewDepartmentModalLabel">Add new service department</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveServiceDepartment">
                    <div class="modal-body modal__body">
                        <div class="row position-relative">
                            <div class="col-12 mb-3 d-flex">
                                <input wire:model="hasChildren" class="form-check-input check__special__project"
                                    type="checkbox" role="switch" id="checkHasChildren" wire:loading.attr="disabled">
                                <label class="form-check-label" for="checkHasChildren"
                                    style="margin-top: 0.2rem !important;">
                                    Service department has child
                                </label>
                            </div>
                            <div class="mb-0" style="z-index: 2;">
                                <label for="name" class="form-label form__field__label">Name</label>
                                <input type="text" wire:model="name"
                                    class="form-control form__field @error('name') is-invalid @enderror" id="name"
                                    placeholder="Enter service department name">
                                @error('name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            @if ($hasChildren)
                                <div class="ps-4 pe-0 pt-4 mb-4 border-start border-bottom rounded-3 position-relative"
                                    style="height: 93px; width: 88%; margin-left: 40px; margin-top: -25px; z-index: 1;">
                                    <div class="d-flex mt-2 align-items-center justify-content-between gap-2">
                                        <label for="childInput" class="form-label mt-1 form__field__label">
                                            Add child
                                        </label>
                                        @if (session()->has('childError'))
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ session('childError') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="position-relative">
                                        <input type="text" wire:model="children"
                                            class="form-control position-relative pe-5 form__field {{ session()->has('childError') ? 'is-invalid' : '' }}"
                                            placeholder="Enter child name" style="width: 100%;" id="childInput">
                                        <button wire:click="addChildren" type="button"
                                            class="btn btn-sm d-flex align-items-center justify-content-center btn-secondary outline-none rounded-3 shadow-sm position-absolute"
                                            style="right: 0.6rem; top: 0.5rem; height: 30px; width: 30px;">
                                            <i class="fa-regular fa-floppy-disk"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            {{-- Child list --}}
                            @if (!empty($addedChildren))
                                @foreach (collect($this->addedChildren) as $key => $children)
                                    <div class="ps-4 pe-0 pt-4 mb-4 border-start border-bottom rounded-3 position-relative"
                                        style="height: 60px; width: 88%; margin-left: 40px; margin-top: -25px; z-index: 0;">
                                        <div class="position-relative">
                                            <input wire:key="{{ $key }}" type="text" disabled readonly
                                                value="{{ $children }}"
                                                class="form-control position-relative pe-5 form__field"
                                                style="width: 100%; margin-top: 11px">
                                            <button wire:click="removeChild({{ $key }})" type="button"
                                                class="btn btn-sm d-flex align-items-center p-2 justify-content-center outline-none rounded-circle text-white position-absolute"
                                                style="right: -0.5rem; top: -0.5rem; height: 18px; width: 18px; font-size: 0.65rem; background-color: #9DA85C; border: 0.19rem solid white;">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="modal-footer modal__footer p-0 mt-3 justify-content-between border-0 gap-2"
                            id="modalFooter">
                            <div class="d-flex align-items-center gap-2">
                                <button type="submit"
                                    class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                    <span wire:loading wire:target="saveServiceDepartment"
                                        class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                                    </span>
                                    Save
                                </button>
                                <button type="button" class="btn m-0 btn__modal__footer btn__cancel"
                                    data-bs-dismiss="modal" wire:click="clearFormField">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('extra')
    <script></script>
@endpush
