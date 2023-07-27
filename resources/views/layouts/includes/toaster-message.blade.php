@if (session('success') || session('info') || session('error'))
@push('toastr-message-js')
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if (session('success'))
    <div id="toasterMessage" class="toast text-white show fade" role="alert" aria-live="assertive" aria-atomic="true"
        style="background-color: #9DA85C;">
        <div class="toast-header bg-white justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i>
                <strong class="me-auto text-dark">
                    Success
                </strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if (session('info'))
    <div id="toasterMessage" class="toast text-white show fade" role="alert" aria-live="assertive" aria-atomic="true"
        style="background-color: #3B4053;">
        <div class="toast-header bg-white justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill"></i>
                <strong class="me-auto text-dark">
                    Info
                </strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('info') }}
        </div>
    </div>
    @endif
    @if (session('error'))
    <div id="toasterMessage" class="toast text-white show fade" role="alert" aria-live="assertive" aria-atomic="true"
        style="background-color: #3B4053;">
        <div class="toast-header bg-white justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill"></i>
                <strong class="me-auto text-dark">
                    Error
                </strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
    @endif
</div>
@endpush
@endif