<table class="table table-striped mb-0" id="table">
    <thead>
        <tr>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($serviceDepartments as $serviceDepartment)
        <tr>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $serviceDepartment->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $serviceDepartment->dateCreated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $serviceDepartment->dateUpdated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                    @include('layouts.staff.system_admin.manage.service_departments.includes.modal.edit_service_department_modal_form')
                    <button data-tooltip="Edit" data-tooltip-position="top" data-tooltip-font-size="11px" type="button"
                        class="btn action__button" data-bs-toggle="modal"
                        data-bs-target="#editServiceDepartment{{ $serviceDepartment->id }}" id="btnEdit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('staff.manage.service_department.delete', $serviceDepartment->id) }}"
                        method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm action__button mt-0">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>