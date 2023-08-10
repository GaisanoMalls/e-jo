<table class="table table-striped mb-0" id="table">
    <thead>
        <tr>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Name</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Tickets</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Created</th>
            <th class="border-0 table__head__label" style="padding: 17px 30px;">Date Updated</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tags as $tag)
        <tr>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $tag->name }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    {{-- <span>{{ $tag->tickets->count() }}</span> --}}
                    <span>----</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $tag->dateCreated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center text-start td__content">
                    <span>{{ $tag->dateUpdated() }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center justify-content-end pe-2 gap-1">
                    @include('layouts.staff.system_admin.manage.tags.includes.modal.edit_tag_modal_form')
                    <button data-tooltip="Edit" data-tooltip-position="top" data-tooltip-font-size="11px" type="button"
                        class="btn action__button" data-bs-toggle="modal" data-bs-target="#editTag{{ $tag->id }}"
                        id="btnEdit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('staff.manage.tag.delete', $tag->id) }}" method="post">
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