<table class="table table-striped mb-0" id="table">
    <thead>
        <tr>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Help Topic</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Team</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">SLA</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($helpTopics as $helpTopic)
        <tr>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $helpTopic->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $helpTopic->serviceDepartment->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $helpTopic->team->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $helpTopic->sla->time_unit }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $helpTopic->dateCreated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $helpTopic->dateUpdated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                    <a href="" class="btn action__button mt-0">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form action="" method="post">
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
