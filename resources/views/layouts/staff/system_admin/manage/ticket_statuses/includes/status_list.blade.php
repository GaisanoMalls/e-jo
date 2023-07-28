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