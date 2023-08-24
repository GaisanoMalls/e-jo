<table class="table table-striped mb-0" id="table">
    <thead>
        <tr>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Name</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Color</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Tickets</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($statuses as $status)
        <tr>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $status->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start gap-2 td__content">
                    <div class="rounded-1" style="background-color: {{ $status->color }}; height: 16px; width: 16px; box-shadow: 0 0.25rem
                        0.375rem -0.0625rem rgba(20, 20, 20, 0.12), 0 0.125rem 0.25rem -0.0625rem rgba(20, 20, 20,
                        0.07);">
                    </div>
                    <span>{{ $status->color }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start gap-2 td__content">
                    <span>{{ $status->tickets()->count() }}</span>
                    <i class="fa-solid fa-envelope" style="font-size: 13px; color: #8d94a1;"></i>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $status->dateCreated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                    @include('layouts.staff.system_admin.manage.ticket_statuses.includes.modal.edit_status_modal')
                    <button data-tooltip="Edit" data-tooltip-position="top" data-tooltip-font-size="11px" type="button"
                        class="btn action__button" data-bs-toggle="modal" data-bs-target="#editStatus{{ $status->id }}"
                        id="btnEdit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('staff.manage.ticket_statuses.delete', $status->id) }}" method="post">
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