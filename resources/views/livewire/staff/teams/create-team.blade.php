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
                            <div class="mb-2">
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
                            <div class="mb-2" style="z-index: 2;">
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
                            <div wire:ignore
                                class="ps-4 pe-0 pt-4 mb-4 border-start border-bottom rounded-3 position-relative"
                                style="height: 93px; width: 88%; margin-left: 40px; margin-top: -35px; z-index: 1;"
                                id="selectServiceDeptChildrenContainer">
                                <div class="d-flex mt-2 align-items-center justify-content-between gap-2">
                                    <label for="childInput" class="form-label mt-1 form__field__label">
                                        Select a child
                                    </label>
                                    @if (session()->has('childError'))
                                        <span class="error__message">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            {{ session('childError') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="position-relative">
                                    <div>
                                        <div id="select-service-department-child-select"></div>
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
                showValueAsTags: true,
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
