<div class="card p-3">
    <h5 class="mb-3">Open User Approvals</h5>

    @if ($userApprovals->isEmpty())
        <div class="alert alert-secondary text-center">
            No pending account approvals.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Requested On</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userApprovals as $index => $approval)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $approval->user->profile->first_name }} {{ $approval->user->profile->last_name }}</td>
                            <td>{{ $approval->user->email }}</td>
                            <td>{{ $approval->created_at->format('F d, Y h:i A') }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">Pending</span>
                            </td>
                            <td>
                                <button wire:click="approve({{ $approval->id }})" class="btn btn-sm btn-success">Approve</button>

                                <button wire:click="reject({{ $approval->id }})" class="btn btn-sm btn-danger">Reject</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
