<div class="modal fade modal__confirm__delete__user__account"
    id="confirmDeleteServiceDeptAdmin{{ $serviceDepartmentAdmin->id }}" tabindex="-1"
    aria-labelledby="confirmDeleteDeptAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal__content">
            <form
                action="{{ route('staff.manage.user_account.service_department_admin.delete', $serviceDepartmentAdmin->id) }}"
                method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body border-0 text-center pt-4 pb-1">
                    <h5 class="fw-bold mb-4" style="text-transform: uppercase; letter-spacing: 1px; color: #696f77;">
                        Confirm Delete
                    </h5>
                    <p class="mb-1" style="font-weight: 500; font-size: 15px;">
                        Service Dept. Admin -
                        {{ $serviceDepartmentAdmin->profile->getFullName() ?? '' }}
                    </p>
                </div>
                <hr>
                <div class="d-flex align-items-center justify-content-center gap-3 pb-4 px-4">
                    <button type="button" class="btn w-50 btn__cancel__delete btn__confirm__modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn w-50 btn__confirm__delete btn__confirm__modal">
                        Yes, delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>