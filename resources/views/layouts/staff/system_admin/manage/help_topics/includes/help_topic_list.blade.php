<table class="table table-striped mb-0" id="table">
    <thead>
        <tr>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Help Topic</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Service Department</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Team</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">SLA</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Approvals</th>
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
                    <span>{{ $helpTopic->levels->count() }}</span>
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
                    <button data-tooltip="Edit" data-tooltip-position="top" data-tooltip-font-size="11px"
                        onclick="window.location.href='{{ route('staff.manage.help_topic.edit_details', $helpTopic->id) }}'"
                        type="button" class="btn action__button">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('staff.manage.help_topic.delete', $helpTopic->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm action__button mt-0">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>