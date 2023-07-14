<table class="table table-striped mb-0" id="table">
    <thead>
        <tr>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">BU/Department</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Branches</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($buDepartments as $department)
        <tr>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $department->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $department->getBranches() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $department->dateCreated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $department->dateUpdated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                    <a href="" class="btn action__button mt-0">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form action="{{ route('staff.manage.bu_department.delete', $department->id) }}" method="post">
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
