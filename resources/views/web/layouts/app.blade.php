<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">

    <title>{{ str_replace('_', ' ', config('app.name', 'Laravel')) }}</title>
    <link rel="icon" type="image" href="{{ asset('images/scale.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/fontawesome-6.5.1/css/all.min.css') }}"/>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-5.3.2/css/bootstrap.min.css') }}">
    <!-- SweetAlert -->
    <link rel="stylesheet" href="{{ asset('assets/sweetalert2-11.10.1/dist/sweetalert2.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/select2-4.1.0/dist/css/select2.min.css') }}">
    <!-- Datatable -->
    <link rel="stylesheet" href="{{ asset('assets/datatables-1.13.7/dist/css/dataTables.bootstrap5.min.css') }}">

    <!-- Custom Style -->
    <style>
        .main-background::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 320px;
            --bs-bg-opacity: 1;
            background-color: rgba(var(--bs-success-rgb),var(--bs-bg-opacity))!important;
            z-index: -1;
        }

        .h-90 {
            height: 95%;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            --bs-text-opacity: 1;
            color: rgba(var(--bs-white-rgb),var(--bs-text-opacity))!important;
        }
    </style>
    @stack('css')
</head>
<body class="bg-white">
    @include('web.layouts.nav_bar')

    <main class="main-background">
        <div class="container py-4 px-2">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap Script -->
    <script src="{{ asset('assets/bootstrap-5.3.2/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jQuery -->
    <script src="{{ asset('assets/jquery-3.7.1/jquery.min.js') }}"></script>
    <!-- SweetAlert -->
    <script src="{{ asset('assets/sweetalert2-11.10.1/dist/sweetalert2.all.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('assets/select2-4.1.0/dist/js/select2.min.js') }}"></script>
    <!-- Chart Js -->
    <script src="{{ asset('assets/chartjs-4.4.1/dist/js/chart.min.js') }}"></script>
    <!-- Datatable Js -->
    <script src="{{ asset('assets/datatables-1.13.7/dist/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/datatables-1.13.7/dist/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Custom Js -->
    @stack('scripts')
    @if (Session::has('success'))
        <script>
            Swal.fire({
                title: "Berhasil",
                text: `{{ Session::get('success') }}`,
                icon: "success"
            });
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            Swal.fire({
                title: "Opps...",
                text: `{{ Session::get('error') }}`,
                icon: "error"
            });
        </script>
    @endif
</body>
</html>
