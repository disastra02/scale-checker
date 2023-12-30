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
            /* background-color: #A11C1F !important; */
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
        <div class="container-content container py-4 px-2">
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
    <script>
        'use strict';

        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        (function(global) {
            var MONTHS = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];
            var COLORS = [
                '#4dc9f6',
                '#f67019',
                '#f53794',
                '#537bc4',
                '#acc236',
                '#166a8f',
                '#00a950',
                '#58595b',
                '#8549ba'
            ];
            var Samples = global.Samples || (global.Samples = {});
            var Color = global.Color;
            Samples.utils = {
                // Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
                srand: function(seed) {
                    this._seed = seed;
                },

                rand: function(min, max) {
                    var seed = this._seed;
                    min = min === undefined ? 0 : min;
                    max = max === undefined ? 1 : max;
                    this._seed = (seed * 9301 + 49297) % 233280;
                    return min + (this._seed / 233280) * (max - min);
                },
                numbers: function(config) {
                    var cfg = config || {};
                    var min = cfg.min || 0;
                    var max = cfg.max || 1;
                    var from = cfg.from || [];
                    var count = cfg.count || 8;
                    var decimals = cfg.decimals || 8;
                    var continuity = cfg.continuity || 1;
                    var dfactor = Math.pow(10, decimals) || 0;
                    var data = [];
                    var i, value;

                    for (i = 0; i < count; ++i) {
                        value = (from[i] || 0) + this.rand(min, max);
                        if (this.rand() <= continuity) {
                            data.push(Math.round(dfactor * value) / dfactor);
                        } else {
                            data.push(null);
                        }
                    }

                    return data;
                },
                labels: function(config) {
                    var cfg = config || {};
                    var min = cfg.min || 0;
                    var max = cfg.max || 100;
                    var count = cfg.count || 8;
                    var step = (max - min) / count;
                    var decimals = cfg.decimals || 8;
                    var dfactor = Math.pow(10, decimals) || 0;
                    var prefix = cfg.prefix || '';
                    var values = [];
                    var i;

                    for (i = min; i < max; i += step) {
                        values.push(prefix + Math.round(dfactor * i) / dfactor);
                    }

                    return values;
                },
                months: function(config) {
                    var cfg = config || {};
                    var count = cfg.count || 12;
                    var section = cfg.section;
                    var values = [];
                    var i, value;

                    for (i = 0; i < count; ++i) {
                        value = MONTHS[Math.ceil(i) % 12];
                        values.push(value.substring(0, section));
                    }

                    return values;
                },
                color: function(index) {
                    return COLORS[index % COLORS.length];
                },

                //transparentize: function(color, opacity) {
                //  var alpha = opacity === undefined ? 0.5 : 1 - opacity;
                //  return ColorO(color).alpha(alpha).rgbString();
                //}
                transparentize: function (r, g, b, alpha) {
                    const a = (1 - alpha) * 255;
                    const calc = x => Math.round((x - a)/alpha);

                    return `rgba(${calc(r)}, ${calc(g)}, ${calc(b)}, ${alpha})`;
                    }
            

            };
            // DEPRECATED
            window.randomScalingFactor = function() {
                return Math.round(Samples.utils.rand(-100, 100));
            };
            // INITIALIZATION
            Samples.utils.srand(Date.now());

        }(this));
    </script>
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
