<div>
    <div class="d-flex flex-wrap-reverse align-items-center justify-content-between my-3">
        <div class="d-flex flex-column flex-wrap mx-4 pt-3 gap-1 position-relative">
            <div class="w-100 d-flex align-items-center position-relative">
                <input wire:model.debounce.400ms="searchPermission" type="text"
                    class="form-control table__search__field" placeholder="Search permission">
                <i wire:loading.remove wire:target="searchPermission"
                    class="fa-solid fa-magnifying-glass table__search__icon"></i>
                <span wire:loading wire:target="searchPermission"
                    class="spinner-border spinner-border-sm table__search__icon" role="status" aria-hidden="true">
                </span>
            </div>
            @if (!empty($searchPermission))
                <div class="w-100 d-flex align-items-center gap-2 mb-1 position-absolute"
                    style="font-size: 0.9rem; bottom: -25px;">
                    <small class="text-muted ">
                        {{ $permissions->count() }} {{ $permissions->count() > 1 ? 'results' : 'result' }} found
                    </small>
                    <small wire:click="clearSearchPermission" class="fw-regular text-danger clear__search">Clear</small>
                </div>
            @endif
        </div>
        <div class="mx-4 mt-2">
            <div id="permission-number-select-list" placeholder="Show" wire:ignore></div>
        </div>
    </div>
    <div class="roles__permissions__type__card">
        <div class="table-responsive custom__table">
            @if ($permissions->isNotEmpty())
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Name
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $permission->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start justify-content-end td__content">
                                        <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                            wire:click="deletePermission({{ $permission->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-2 px-3 rounded-3" style="margin: 20px 29px; background-color: #f3f4f6;">
                    <small style="font-size: 0.8rem;">Empty ticket permissions</small>
                </div>
            @endif
        </div>
    </div>
    <div class="mt-3 mx-4 d-flex flex-wrap align-items-center justify-content-between">
        <small class="text-muted" style="margin-bottom: 20px; font-size: 0.82rem;">
            Showing {{ $permissions->firstItem() }}
            to {{ $permissions->lastItem() }}
            of {{ $permissions->total() }} results
        </small>
        {{ $permissions->links() }}
    </div>
</div>

@push('livewire-select')
    <script>
        const permissionNumberSelectList = document.querySelector('#permission-number-select-list');

        const numberListOptions = @json($numberList).map(number => ({
            label: `${number} items`,
            value: number
        }));

        VirtualSelect.init({
            ele: permissionNumberSelectList,
            options: numberListOptions
        });

        permissionNumberSelectList.addEventListener('change', () => {
            @this.set('paginatePageNumber', permissionNumberSelectList.value);
        });

        permissionNumberSelectList.addEventListener('reset', () => {
            @this.set('paginatePageNumber', 5)
        });
    </script>
@endpush
