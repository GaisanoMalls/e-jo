<div class="card d-flex flex-column roles__permissions__card p-0">
    <div class="roles__permissions__card__header pb-0 pt-4 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column me-3">
                <h6 class="card__title">Roles</h6>
                <p class="card__description">
                    Find all the users with their associated roles.
                </p>
            </div>
        </div>
    </div>
    <div class="roles__permissions__type__card">
        <div class="table-responsive custom__table">
            @if (!$roles->isEmpty())
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th class="border-0 table__head__label" style="padding: 17px 30px;">
                            Role
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center text-start td__content">
                                <span>{{ $role->name }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="bg-light py-3 px-4 rounded-3" style="margin: 20px 29px;">
                <small style="font-size: 14px;">Empty roles</small>
            </div>
            @endif
        </div>
    </div>
</div>