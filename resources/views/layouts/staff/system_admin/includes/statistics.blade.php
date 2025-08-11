<div>
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="dept-tab" data-bs-toggle="tab" data-bs-target="#dept" type="button" role="tab">
                <i class="fa-solid fa-building"></i> Department
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="topic-tab" data-bs-toggle="tab" data-bs-target="#topic" type="button" role="tab">
                <i class="fa-solid fa-list"></i> Help Topic
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="agent-tab" data-bs-toggle="tab" data-bs-target="#agent" type="button" role="tab">
                <i class="fa-solid fa-user-gear"></i> Agent
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content">

        <!-- Department Stats -->
        <div class="tab-pane fade show active" id="dept" role="tabpanel">
            <div class="card card__rounded__and__no__border h-100">
                <div class="table-responsive custom__table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Opened</th>
                                <th>Assigned</th>
                                <th>Overdue</th>
                                <th>Closed</th>
                                <th>Reopened</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departmentStats as $row)
                                <tr>
                                    <td>{{ $row['name'] }}</td>
                                    <td>{{ $row['opened'] }}</td>
                                    <td>{{ $row['assigned'] }}</td>
                                    <td>{{ $row['overdue'] }}</td>
                                    <td>{{ $row['closed'] }}</td>
                                    <td>{{ $row['reopened'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Help Topic Stats -->
        <div class="tab-pane fade" id="topic" role="tabpanel">
            <div class="card card__rounded__and__no__border h-100">
                <div class="table-responsive custom__table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Help Topic</th>
                                <th>Opened</th>
                                <th>Assigned</th>
                                <th>Overdue</th>
                                <th>Closed</th>
                                <th>Reopened</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topicStats as $row)
                                <tr>
                                    <td>{{ $row['name'] }}</td>
                                    <td>{{ $row['opened'] }}</td>
                                    <td>{{ $row['assigned'] }}</td>
                                    <td>{{ $row['overdue'] }}</td>
                                    <td>{{ $row['closed'] }}</td>
                                    <td>{{ $row['reopened'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Agent Stats -->
        <div class="tab-pane fade" id="agent" role="tabpanel">
            <div class="card card__rounded__and__no__border h-100">
                <div class="table-responsive custom__table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Agent</th>
                                <th>Assigned</th>
                                <th>Overdue</th>
                                <th>Closed</th>
                                <th>Reopened</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agentStats as $row)
                                <tr>
                                    <td>{{ $row['name'] }}</td>
                                    <td>{{ $row['assigned'] }}</td>
                                    <td>{{ $row['overdue'] }}</td>
                                    <td>{{ $row['closed'] }}</td>
                                    <td>{{ $row['reopened'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
