<table class="table table-striped mb-0" id="table">
    <thead>
        <tr>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Assigned Branch</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($serviceDepartmentBranches as $sdb)
        <tr>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $sdb->serviceDepartment->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $sdb->branch->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $sdb->dateCreated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $sdb->dateUpdated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                    <a href="" class="btn action__button mt-0">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form action="{{ route('staff.manage.service_department.assign_branch.delete', $sdb->id) }}"
                        method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm action__button mt-0"><i class="bi bi-trash-fill"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
