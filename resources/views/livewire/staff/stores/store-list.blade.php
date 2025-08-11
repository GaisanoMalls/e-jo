<div>
    <div class="table-responsive custom__table">
        <table class="table mb-0">
            @if ($stores->isNotEmpty())
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Store Code</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Store Name</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Store Group</th>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stores as $store)
                        <tr wire:key="store-{{ $store->id }}">
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $store->store_code }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $store->store_name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $store->storeGroup->name ?? 'No Group' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-start td__content">
                                    <span>{{ $store->dateCreated() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                    <button data-tooltip="Edit" data-tooltip-position="top"
                                        data-tooltip-font-size="11px" type="button" class="btn action__button"
                                        data-bs-toggle="modal" data-bs-target="#editStoreModal"
                                        wire:click="editStore({{ $store->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                        data-bs-target="#deleteStoreModal"
                                        wire:click="deleteStore({{ $store->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @else
                <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                    <small style="font-size: 14px;">No records for stores.</small>
                </div>
            @endif
        </table>
    </div>

    {{-- Edit Store Modal --}}
    <div wire:ignore.self class="modal fade department__modal" id="editStoreModal" tabindex="-1"
        aria-labelledby="editStoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-header modal__header p-0 border-0">
                    <h1 class="modal-title modal__title" id="editStoreModalLabel">Edit Store</h1>
                    <button class="btn btn-sm btn__x" data-bs-dismiss="modal">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body modal__body">
                        <div class="row mb-2">
                            <div class="mb-2">
                                <label for="store_code" class="form-label form__field__label">Store Code</label>
                                <input type="text" wire:model="store_code"
                                    class="form-control form__field @error('store_code') is-invalid @enderror" id="store_code"
                                    placeholder="Enter store code">
                                @error('store_code')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="store_name" class="form-label form__field__label">Store Name</label>
                                <input type="text" wire:model="store_name"
                                    class="form-control form__field @error('store_name') is-invalid @enderror" id="store_name"
                                    placeholder="Enter store name">
                                @error('store_name')
                                    <span class="error__message">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="store_group_id" class="form-label form__field__label">Store Group</label>
                                <select wire:model="store_group_id" class="form-control form__field @error('store_group_id') is-invalid @enderror" id="store_group_id">
                                    <option value="">Select Store Group</option>
                                    @foreach($storeGroups as $storeGroup)
                                        <option value="{{ $storeGroup->id }}">{{ $storeGroup->name }}</option>
                                    @endforeach
                                </select>
                                @error('store_group_id')
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
                                <span wire:loading wire:target="update" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true">
                                </span>
                                Update
                            </button>
                            <button type="button" class="btn m-0 btn__modal__footer btn__cancel" id="btnCloseModal"
                                data-bs-dismiss="modal" wire:click="cancel">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Store Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__bu__department" id="deleteStoreModal" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <div class="modal-body border-0 text-center pt-4 pb-1">
                    <h6 class="fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                        Confirm Delete
                    </h6>
                    <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                        Are you sure you want to delete this store?
                    </p>
                    <strong>{{ $store_name }}</strong>
                </div>
                <hr>
                <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                    <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit"
                        class="btn d-flex align-items-center justify-content-center gap-2 w-50 btn__confirm__delete btn__confirm__modal"
                        wire:click="delete">
                        <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true">
                        </span>
                        Yes, delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#editStoreModal').modal('hide');
            $('#deleteStoreModal').modal('hide');
        });

        window.addEventListener('show-edit-store-modal', () => {
            $('#editStoreModal').modal('show');
        });

        window.addEventListener('show-delete-store-modal', () => {
            $('#deleteStoreModal').modal('show');
        });
    </script>
@endpush
