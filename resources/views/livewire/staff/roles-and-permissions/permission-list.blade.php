<div>
    <div class="d-flex align-items-center justify-content-between my-3 flex-wrap-reverse">
        <div class="d-flex flex-column position-relative mx-4 flex-wrap gap-1 pt-3">
            <div class="w-100 d-flex align-items-center position-relative">
                <input wire:model.debounce.400ms="searchPermission" type="text" class="form-control table__search__field" placeholder="Search permission">
                <i wire:loading.remove wire:target="searchPermission" class="fa-solid fa-magnifying-glass table__search__icon"></i>
                <span wire:loading wire:target="searchPermission" class="spinner-border spinner-border-sm table__search__icon" role="status" aria-hidden="true">
                </span>
            </div>
            @if (!empty($searchPermission))
                <div class="w-100 d-flex align-items-center position-absolute mb-1 gap-2" style="font-size: 0.9rem; bottom: -25px;">
                    <small class="text-muted">
                        {{ $permissions->count() }} {{ $permissions->count() > 1 ? 'results' : 'result' }} found
                    </small>
                    <small wire:click="clearSearchPermission" class="fw-regular text-danger clear__search">Clear</small>
                </div>
            @endif
        </div>
        <div class="mx-4 mt-2">
            <div id="page-number-select-option" placeholder="Show" wire:ignore></div>
        </div>
    </div>
    <div class="roles__permissions__type__card">
        <div class="table-responsive custom__table">
            @if ($permissions->isNotEmpty())
                <table class="mb-0 table">
                    <thead>
                        <tr>
                            <th class="table__head__label border-0" style="padding: 17px 30px;">
                                Name
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center td__content text-start">
                                        <span>{{ $permission->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-end td__content text-start">
                                        <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal" wire:click="deletePermission({{ $permission->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="rounded-3 px-3 py-2" style="margin: 20px 29px; background-color: #f3f4f6;">
                    <small style="font-size: 0.8rem;">Empty ticket permissions</small>
                </div>
            @endif
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between mx-4 mt-3 flex-wrap">
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
        const pageNumberSelectOption = document.querySelector('#page-number-select-option');

        const pageNumberOptions = @json($pageNumberOptions).map(number => ({
            label: `${number} items`,
            value: number
        }));

        VirtualSelect.init({
            ele: pageNumberSelectOption,
            options: pageNumberOptions
        });

        pageNumberSelectOption.addEventListener('change', (event) => {
            @this.set('paginatePageNumber', event.target.value);
        });

        pageNumberSelectOption.addEventListener('reset', () => {
            @this.set('paginatePageNumber', 5)
        });
    </script>
@endpush
