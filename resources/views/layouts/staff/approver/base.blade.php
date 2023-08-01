<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/icons/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/virtual-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/custom-virtual-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/roles/approver.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vanilla-dataTables.css') }}">
    <title>{{ $title ?? '' }}</title>
</head>

<body>
    @include('layouts.staff.approver.includes.navbar')
    @include('layouts.includes.toaster-message')
    @include('layouts.staff.approver.includes.modal.confirm_logout')
    <div class="container mb-5 approver__section">
        @if (Route::is('approver.tickets.*'))
        @include('layouts.staff.approver.includes.ticket_tab')
        @endif
        @yield('main-content')
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/select/virtual-select.min.js') }}"></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/roles/staff/approver.js') }}"></script>
    <script src="{{ asset('js/roles/staff/approver.js') }}"></script>
    <script src="{{ asset('js/toaster-message.js') }}"></script>
    <script src="{{ asset('js/init/tinymce-init.js') }}"></script>
    <script src="{{ asset('js/init/virtual-select-init.js') }}"></script>
    <script src="{{ asset('js/vanilla-dataTables.js') }}"></script>

    @stack('offcanvas-error')
    @stack('modal-with-error')
    @stack('toastr-message-js')
    <script>
        var table = document.getElementById('approverTable');
        var options = {
            // how many rows per page
            perPage: 10,
            perPageSelect: [5, 10, 15, 20, 25],
            fixedColumns: true,
            fixedHeight: false,
            // Pagination
            nextPrev: true,
            firstLast: false,
            prevText: "&lsaquo;",
            nextText: "&rsaquo;",
            firstText: "&laquo;",
            lastText: "&raquo;",
            ellipsisText: "&hellip;",
            ascText: "▴",
            descText: "▾",
            truncatePager: true,
            pagerDelta: 3,
            // enables sorting
            sortable: true,
            // enables live search
            searchable: true,
            header: true,
            footer: false,
            // Customise the display text
            labels: {
                placeholder: "Search...", // The search input placeholder
                perPage: "{select}", // per-page dropdown label
                noRows: "No entries found", // Message shown when there are no search results
                info: "Showing {start} to {end} of {rows} entries" //
            },
            // Customise the layout
            layout: {
                top: "{select}{search}",
                bottom: "{info}{pager}"
            }

        };
        var dataTable = new DataTable(table, options);

    </script>
</body>

</html>