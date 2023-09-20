<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/gmall.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/tooltip.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/virtual-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select/custom-virtual-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/roles/staff.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vanilla-dataTables.css') }}">
    <title>{{ $title }}</title>
    @livewireStyles
</head>

<body>
    <div class="page__wrapper compact__wrapper">
        @include('layouts.staff.includes.header')
        <div class="page__body__wrapper">
            @include('layouts.staff.includes.sidebar')
            <div class="page__body">
                @livewire('offline')
                <div class="container-fluid">
                    <div class="page__header">
                        <div class="row">
                            @yield('page-header')
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    @yield('main-content')
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/select/virtual-select.min.js') }}"></script>
    <script src="{{ asset('js/init/virtual-select-init.js') }}"></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/tooltip.min.js') }}"></script>
    <script src="{{ asset('js/roles/staff/staff.js') }}"></script>
    <script src="{{ asset('js/toaster-message.js') }}"></script>
    <script src="{{ asset('js/ticket-jquery.js') }}"></script>
    <script src="{{ asset('js/init/tinymce-init.js') }}"></script>
    <script src="{{ asset('js/vanilla-dataTables.js') }}"></script>
    <script src="{{ asset('js/roles/staff/dependent-dropdown.js') }}"></script>
    @stack('sample')
    @stack('livewire-modal')
    @stack('modal-with-error')
    @stack('toastr-message-js')
    <script>
        var table = document.getElementById('table');
        var options = {
            // how many rows per page
            perPage: 10,
            perPageSelect: [5, 10, 15, 20, 25, 50, 100],
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