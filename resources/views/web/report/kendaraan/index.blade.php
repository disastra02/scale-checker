@extends('web.layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .range {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--bs-body-color);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: var(--bs-body-bg);
            background-image: var(--bs-form-select-bg-img),var(--bs-form-select-bg-icon,none);
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: var(--bs-border-radius);
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    </style>
@endpush

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('w-dashboard.index') }}" class="text-white">Dashboard</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Laporan Kendaraan</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-title mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="fw-bold mb-0">Laporan Kendaraan</h3>
                        <span class="text-black-50">Melihat Aktivitas Kendaraan</span>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <div class="">
                            <div class="range" id="rangeDate">
                                <i class="fa fa-calendar"></i>&nbsp;&nbsp;<span></span>&nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                        {{-- <div class="ms-3">
                            <select class="form-select" id="tipeData">
                                <option selected value="1">Terjual</option>
                                <option value="2">Tersedia</option>
                                <option value="3">Semua</option>
                            </select>
                        </div> --}}
                        <button type="button" class="btn btn-primary ms-3 btn-pencarian"><i class="fa-solid fa-magnifying-glass"></i> &nbsp; Cari</button>
                        <button class="btn btn-success btn-print ms-3" title="Print"><i class="fa-solid fa-file-pdf"></i> &nbsp; Print</button>
                    </div>
                </div>
            </div>

            {{-- <div class="row justify-content-center mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body px-3 py-2">
                            <h5 class="fw-bold mb-0" id="jumlahKendaraan">0</h5>
                            <span>Total Kendaraan Terjual</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body px-3 py-2">
                            <h5 class="fw-bold mb-0" id="jumlahKendaraanTersimpan">0</h5>
                            <span>Total Kendaraan Tersedia</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body px-3 py-2">
                            <h5 class="fw-bold mb-0" id="totalKendaraan">0</h5>
                            <span>Total Kendaraan Semua</span>
                        </div>
                    </div>
                </div>
            </div> --}}
            
            <table class="table align-middle" id="dataReportKendaraan">
                <thead>
                    <tr>
                        <th class="text-center" width="8%">No</th>
                        <th class="text-center" width="42%">Nomor Kendaraan</th>
                        <th class="text-center" width="8%">Transaksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            // Date Range
            var start = moment().subtract(6, 'days');
            var end = moment();

            function cb(start, end) {
                $('#rangeDate span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            }

            $('#rangeDate').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            // Filter Data
            var tableKendaraan;
            let startDate = start.format('YYYY-MM-DD');
            let endDate = end.format('YYYY-MM-DD');
            // let tipeData = $('#tipeData').val();

            $('#rangeDate').on('apply.daterangepicker', function(ev, picker) {
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate= picker.endDate.format('YYYY-MM-DD');
            });

            $('.btn-pencarian').click(function(){
                // tipeData = $('#tipeData').val();
                getAll(startDate, endDate);
            });

            function getAll(start, end) {
                // getJumlah(start, end);
                getTable(start, end);
            }

            // function getJumlah(start, end) {
            //     $.ajax({
            //         method: "GET",
            //         url: `r-kendaraan.getJumlah?startdate=${start}&enddate=${end}&tipe=${tipeData}`,
            //         beforeSend: function(res) {
            //             Swal.showLoading();
            //         },
            //         success: function(res) {
            //             Swal.close();
            //             $('#jumlahKendaraan').html(res.jumlah ?? 0);
            //             $('#totalKendaraan').html(res.total ?? 0);
            //             $('#jumlahKendaraanTersimpan').html(res.tersimpan ?? 0);
            //         }
            //     });
            // }

            function getTable(start, end) {
                // Data Kendaraan
                tableKendaraan = $('#dataReportKendaraan').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: `{{ route('r-kendaraan.scopeData') }}?startdate=${start}&enddate=${end}`,
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center"},
                        {data: 'no_kendaraan', name: 'no_kendaraan'},
                        {data: 'total', name: 'total', searchable: false, className: "text-center"},
                    ]
                });
            }

            getAll(startDate, endDate);

            // Print Data
            $('.btn-print').click(function() {
                window.location.href = `{{ route('r-kendaraan.printToPdf') }}?startdate=${startDate}&enddate=${endDate}`;
            });
        });
    </script>
@endpush