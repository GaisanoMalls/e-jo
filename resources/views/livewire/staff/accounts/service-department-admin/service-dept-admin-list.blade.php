<div>
    <div class="row mb-3 mt-2">
        <div class="col-lg-6 col-md-12">
            <div class="d-flex flex-column flex-wrap mx-4 gap-1 position-relative">
                <div class="w-100 d-flex align-items-center position-relative">
                    <input wire:model.debounce.400ms="searchServiceDeptAdmin" type="text" id="search-service-dept-admin"
                        class="form-control table__search__field" placeholder="Search name, service department, branch, business unit (BU)">
                    <label for="search-service-dept-admin" class="table__search__icon">
                        <i wire:loading.remove wire:target="searchServiceDeptAdmin" class="fa-solid fa-magnifying-glass"></i>
                    </label>
                    <span wire:loading wire:target="searchServiceDeptAdmin" class="spinner-border spinner-border-sm table__search__icon"
                        role="status" aria-hidden="true">
                    </span>
                </div>
                @if (!empty($searchServiceDeptAdmin))
                    <div class="w-100 d-flex align-items-center gap-2 mb-1 position-absolute" style="font-size: 0.9rem; bottom: -25px;">
                        <small class="text-muted">
                            {{ $serviceDepartmentAdmins->count() }}
                            {{ $serviceDepartmentAdmins->count() > 1 ? 'results' : 'result' }} found
                        </small>
                        <small wire:click="clearServiceDeptAdminSearch" class="fw-regular text-danger clear__search">Clear</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if ($serviceDepartmentAdmins->isNotEmpty())
        <div
            class="card account__type__card {{ Route::is('staff.manage.user_account.service_department_admins') ? 'card__rounded__and__no__border' : '' }}">
            <div class="table-responsive custom__table">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Name
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Service Department
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Branch
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                BU/Department
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Status
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Permissions
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Date
                                Added
                            </th>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Date Updated
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceDepartmentAdmins as $serviceDepartmentAdmin)
                            <tr wire:key="dept-admin-{{ $serviceDepartmentAdmin->id }}">
                                <td>
                                    <a
                                        href="{{ route('staff.manage.user_account.service_department_admin.view_details', $serviceDepartmentAdmin->id) }}">
                                        <div class="media d-flex align-items-center user__account__media">
                                            <div class="flex-shrink-0">
                                                @if ($serviceDepartmentAdmin->profile->picture)
                                                    <img src="{{ Storage::url($serviceDepartmentAdmin->profile->picture) }}" alt=""
                                                        class="image-fluid user__picture">
                                                @else
                                                    <div class="user__name__initial" style="background-color: #9DA85C;">
                                                        {{ $serviceDepartmentAdmin->profile->getNameInitial() }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column gap-1 ms-3 w-100">
                                                <span class="user__name">{{ $serviceDepartmentAdmin->profile->getFullName }}</span>
                                                <small>{{ $serviceDepartmentAdmin->email }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ Str::limit($serviceDepartmentAdmin->getServiceDepartments(), 30) }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $serviceDepartmentAdmin->getBranches() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $serviceDepartmentAdmin->getBUDepartments() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $serviceDepartmentAdmin->isActive() ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span><i class="bi bi-person-lock text-muted"></i></span>
                                        <span>{{ $serviceDepartmentAdmin->getDirectPermissions()->count() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $serviceDepartmentAdmin->dateCreated() }}</span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>
                                            @if ($serviceDepartmentAdmin->dateUpdated() > $serviceDepartmentAdmin->profile->dateUpdated())
                                                {{ $serviceDepartmentAdmin->dateUpdated() }}
                                            @else
                                                {{ $serviceDepartmentAdmin->profile->dateUpdated() }}
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td style="padding: 17px 30px;">
                                    <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                                        <button data-tooltip="Edit" data-tooltip-position="top" data-tooltip-font-size="11px"
                                            onclick="window.location.href='{{ route('staff.manage.user_account.service_department_admin.edit_details', $serviceDepartmentAdmin->id) }}'"
                                            type="button" class="btn action__button">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button data-tooltip="Delete" data-tooltip-position="top" data-tooltip-font-size="11px" type="button"
                                            class="btn action__button" data-bs-toggle="modal" data-bs-target="#confirmDeleteServiceDeptAdminModal"
                                            wire:click="deleteServiceDepartmentAdmin({{ $serviceDepartmentAdmin->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert text-center d-flex align-items-center justify-content-center gap-2 mt-5" role="alert"
            style="background-color: #F5F7F9; font-size: 14px;">
            <i class="fa-solid fa-circle-info"></i>
            Empty records for service department administrators.
        </div>
    @endif
    <div class="mt-3 mx-4 d-flex flex-wrap align-items-center justify-content-between">
        <small class="text-muted" style="margin-bottom: 20px; font-size: 0.82rem;">
            Showing {{ $serviceDepartmentAdmins->firstItem() }}
            to {{ $serviceDepartmentAdmins->lastItem() }}
            of {{ $serviceDepartmentAdmins->total() }} results
        </small>
        {{ $serviceDepartmentAdmins->links() }}
    </div>

    {{-- Delete Service Department Admin Modal --}}
    <div wire:ignore.self class="modal fade modal__confirm__delete__user__account" id="confirmDeleteServiceDeptAdminModal" tabindex="-1"
        aria-labelledby="confirmDeleteDeptAdminLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal__content">
                <form wire:submit.prevent="delete">
                    <div class="modal-body border-0 text-center pt-4 pb-1">
                        <h5 class="fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                            Confirm Delete
                        </h5>
                        <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                            Are you sure you want to delete this service department admin?
                        </p>
                        <strong>{{ $serviceDeptAdminFullName }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                        <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn w-50 d-flex align-items-center justify-content-center gap-2 btn__confirm__delete btn__confirm__modal">
                            <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                            </span>
                            Yes, delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Scripts --}}
@push('livewire-modal')
    <script>
        window.addEventListener('close-modal', () => {
            $('#confirmDeleteServiceDeptAdminModal').modal('hide');
        });

        window.addEventListener('show-delete-service-dept-admin-modal', () => {
            $('#confirmDeleteServiceDeptAdminModal').modal('show');
        });
    </script>
@endpush
