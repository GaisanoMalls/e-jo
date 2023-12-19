<div>
    <div class="roles__permissions__type__card">
        <div class="table-responsive custom__table">
            @if (!$permissions->isEmpty())
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="border-0 table__head__label" style="padding: 17px 30px;">
                                Name
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center text-start td__content">
                                        <span>{{ $permission->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-start justify-content-end td__content">
                                        <button class="btn btn-sm action__button mt-0" data-bs-toggle="modal"
                                            wire:click="deletePermission({{ $permission->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                    <small style="font-size: 14px;">Empty permissions</small>
                </div>
            @endif
        </div>
    </div>
</div>
