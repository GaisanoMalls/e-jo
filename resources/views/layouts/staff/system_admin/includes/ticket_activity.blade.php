<div class="card card__rounded__and__no__border mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between table__header mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-chart-line"></i>
                <h6 class="mb-0">Ticket Activity</h6>
            </div>
            <small class="text-muted">Last 30 days</small>
        </div>
        <canvas id="ticket-activity-chart" height="50"></canvas>
    </div>
</div>

@push('extra')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const ctx = document.getElementById('ticket-activity-chart').getContext('2d');
        const labels = @json($ticketActivity['labels']);
        const series = @json($ticketActivity['series']);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {label: 'Opened', data: series.created, borderColor: '#1F75CC', backgroundColor: 'rgba(31,117,204,0.1)', tension: 0.3},
                    {label: 'Closed', data: series.closed, borderColor: '#4E4392', backgroundColor: 'rgba(78,67,146,0.1)', tension: 0.3},
                    {label: 'Reopened', data: series.reopened, borderColor: '#7ba504', backgroundColor: 'rgba(123,165,4,0.1)', tension: 0.3},
                    {label: 'Assigned', data: series.assigned, borderColor: '#BEB34E', backgroundColor: 'rgba(190,179,78,0.1)', tension: 0.3},
                    {label: 'Disapproved', data: series.disapproved, borderColor: '#FF8B8B', backgroundColor: 'rgba(255,139,139,0.1)', tension: 0.3},
                    {label: 'Overdue', data: series.overdue, borderColor: '#EA001C', backgroundColor: 'rgba(234,0,28,0.1)', tension: 0.3},
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    })();
</script>
@endpush

