<div>
    <div wire:ignore.self class="modal fade team__modal" id="addNewTeamModal" tabindex="-1"
        aria-labelledby="addNewTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="addNewTeamModalLabel">Add new team</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="saveTeam">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="col-12 mb-3 d-flex">
                                <input wire:model="hasSubteam" class="form-check-input check__special__project"
                                    type="checkbox" role="switch" id="checkHasChildren" wire:loading.attr="disabled">
                                <label class="form-check-label" for="checkHasChildren"
                                    style="margin-top: 0.2rem !important;">
                                    Has subteam
                                </label>
                            </div>
                            <div class="mb-2" style="z-index: 2;">
                                <label for="name" class="form-label form__field__label">Name</label>
                                <input type="text" wire:model.defer="name"
                                    class="form-control form__field @error('name') is-invalid @enderror" id="name"
                                    placeholder="Enter team name">
                                @error('name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            @if ($hasSubteam)
                                <div class="ps-4 pe-0 pt-4 mb-4 border-start border-bottom rounded-3 position-relative"
                                    style="height: 93px; width: 88%; margin-left: 40px; margin-top: -25px; z-index: 1;">
                                    <div class="d-flex mt-2 align-items-center justify-content-between gap-2">
                                        <label for="childInput" class="form-label mt-1 form__field__label">
                                            Add subteam
                                        </label>
                                        @error('subteam')
                                            <span class="error__message">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="position-relative">
                                        <input type="text" wire:model.defer="subteam"
                                            class="form-control position-relative pe-5 form__field {{ session()->has('childError') ? 'is-invalid' : '' }}"
                                            placeholder="Enter child name" style="width: 100%;" id="childInput">
                                        <button wire:click="addSubteam" type="button"
                                            class="btn btn-sm d-flex align-items-center justify-content-center outline-none rounded-3 position-absolute"
                                            style="right: 0.6rem; top: 0.5rem; height: 30px; width: 30px; background-color: #edeef0; border: 1px solid #e7e9eb;">
                                            <span wire:loading.remove wire:target="addSubteam">
                                                <i class="bi bi-save"></i>
                                            </span>
                                            <div wire:loading wire:target="addSubteam"
                                                class="spinner-border spinner-border-sm loading__spinner"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                {{-- subteam list --}}
                                @if (!empty($addedSubteam))
                                    @foreach ($this->addedSubteam as $key => $subteam)
                                        <div class="ps-4 pe-0 pt-4 mb-4 border-start border-bottom rounded-3 position-relative"
                                            style="height: 60px; width: 88%; margin-left: 40px; margin-top: -25px;">
                                            <div wire:key="{{ $key }}" class="position-relative">
                                                <input type="text" readonly value="{{ $subteam }}"
                                                    class="form-control position-relative pe-5 form__field"
                                                    style="width: 100%; margin-top: 11px; background-color: #f9fbfc;">
                                                <div class="d-flex align-items-center justify-content-center bg-white p-3 rounded-circle position-absolute"
                                                    style="right: -0.5rem; top: -0.5rem; height: 30px; width: 30px;">
                                                    <button wire:click="removeSubteam({{ $key }})"
                                                        type="button"
                                                        class="btn btn-sm d-flex align-items-center p-2 justify-content-center outline-none rounded-circle"
                                                        style="height: 27px; width: 27px; font-size: 0.75rem; color: #d32839; background-color: #F5F7F9; border: 1px solid #e7e9eb;">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif

                            <div class="mb-2">
                                <label for="department" class="form-label form__field__label">Service Department</label>
                                <div>
                                    <div id="select-service-department" wire:ignore></div>
                                </div>
                                @error('selectedServiceDepartment')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div wire:ignore class="ps-4 pe-0 pt-4 border-start border-bottom position-relative"
                                style="height: 76px; width: 88%; margin-bottom: 1.7rem; margin-left: 40px; margin-top: -8px; border-bottom-left-radius: 10px;"
                                id="selectServiceDeptChildrenContainer">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <label for="childInput" class="form-label form__field__label">
                                        Select Sub-Service Department
                                    </label>
                                    @error('selectedChild')
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="position-relative">
                                    <div>
                                        <div id="select-service-department-child-select" wire:ignore></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label form__field__label">Assign to branch</label>
                                <div>
                                    <div id="select-branch" wire:ignore></div>
                                </div>
                                @error('selectedBranches')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal__footer p-0 justify-content-between border-0 gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit"
                                class="btn m-0 d-flex align-items-center justify-content-center gap-2 btn__modal__footer btn__send">
                                <span wire:loading wire:target="saveTeam" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Add New
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" wire:click="cancel"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('livewire-select')
    <script>
        const serviceDepartmentSelect = document.querySelector('#select-service-department');
        const branchSelect = document.querySelector('#select-branch');
        const serviceDepartmentChildSelect = document.querySelector('#select-service-department-child-select');
        const selectServiceDeptChildrenContainer = document.querySelector('#selectServiceDeptChildrenContainer');

        selectServiceDeptChildrenContainer.style.display = 'none';

        if (serviceDepartmentSelect) {
            const serviceDepartmentOption = [
                @foreach ($serviceDepartments as $serviceDepartment)
                    {
                        label: "{{ $serviceDepartment->name }}",
                        value: "{{ $serviceDepartment->id }}"
                    },
                @endforeach
            ];

            VirtualSelect.init({
                ele: serviceDepartmentSelect,
                options: serviceDepartmentOption,
                search: true,
                required: true,
                markSearchResults: true,
            });

            VirtualSelect.init({
                ele: serviceDepartmentChildSelect,
                search: true,
                required: true,
                markSearchResults: true,
            });

            serviceDepartmentSelect.addEventListener('reset', () => {
                serviceDepartmentChildSelect.setOptions([]);
                selectServiceDeptChildrenContainer.style.display = 'none';
            });

            serviceDepartmentSelect.addEventListener('change', () => {
                const serviceDeptId = parseInt(serviceDepartmentSelect.value);

                if (serviceDeptId) {
                    @this.set('selectedServiceDepartment', serviceDeptId);

                    window.addEventListener('load-service-department-children', (event) => {
                        const children = event.detail.serviceDeptChildren
                        const childrenOption = [];
                        console.log(children);

                        if (children.length > 0) {
                            selectServiceDeptChildrenContainer.style.display = 'block';

                            children.forEach(function(child) {
                                childrenOption.push({
                                    label: child.name,
                                    value: child.id
                                });
                            });

                            serviceDepartmentChildSelect.setOptions(childrenOption)
                            serviceDepartmentChildSelect.addEventListener('change', () => {
                                @this.set('selectedChild', serviceDepartmentChildSelect.value)
                            });

                        } else {
                            selectServiceDeptChildrenContainer.style.display = 'none';
                            serviceDepartmentChildSelect.setOptions([]);
                        }
                    });
                }
            });
        }

        if (branchSelect) {
            const branchOption = [
                @foreach ($branches as $branch)
                    {
                        label: "{{ $branch->name }}",
                        value: "{{ $branch->id }}"
                    },
                @endforeach
            ];

            VirtualSelect.init({
                ele: branchSelect,
                options: branchOption,
                search: true,
                required: true,
                multiple: true,
                showValueAsTags: true,
                markSearchResults: true,
            });

            branchSelect.addEventListener('change', () => {
                @this.set('selectedBranches', branchSelect.value);
            });
        }

        // Clear all selected branches in the select option.
        window.addEventListener('clear-select-options', () => {
            branchSelect.reset();
            serviceDepartmentSelect.reset();
        });
    </script>
@endpush
