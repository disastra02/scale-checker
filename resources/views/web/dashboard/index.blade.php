@extends('web.layouts.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active text-white" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    <div class="card shadow-lg border-light-subtle">
        <div class="card-body">
            <div class="card-title mb-4">
                <h3 class="fw-bold mb-0">Summary Dashboard</h3>
                <span class="text-black-50">Aktivitas Scan Timbangan</span>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card mb-4 bg-secondary-subtle text-secondary border-0 shadow-sm">
                                <div class="card-body row align-items-center">
                                    <div class="col-md-9">
                                        <h3 class="fw-bold mb-0">{{ $totalKendaraan }}</h3>
                                        <span>Total Kendaraan</span>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <h3 class="fw-bold mb-0"><i class="fa-solid fa-truck"></i></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mb-4 bg-primary-subtle text-primary border-0 shadow-sm">
                                <div class="card-body row align-items-center">
                                    <div class="col-md-9">
                                        <h3 class="fw-bold mb-0">{{ $totalSurat }}</h3>
                                        <span>Total Surat</span>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <h3 class="fw-bold mb-0"><i class="fa-solid fa-envelope"></i></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mb-4 bg-warning-subtle text-secondary border-0 shadow-sm">
                                <div class="card-body row align-items-center">
                                    <div class="col-md-9">
                                        <h3 class="fw-bold mb-0">{{ $totalPelanggan }}</h3>
                                        <span>Total Pelanggan</span>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <h3 class="fw-bold mb-0"><i class="fa-solid fa-user"></i></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success-subtle text-success border-0 shadow-sm">
                                <div class="card-body row align-items-center">
                                    <div class="col-md-9">
                                        <h3 class="fw-bold mb-0">{{ converHasilSatuan($totalBerat) }}</h3>
                                        <span>Total Berat</span>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <h3 class="fw-bold mb-0"><i class="fa-solid fa-weight-scale"></i></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-8">
                    <div class="card h-100 border-light-subtle shadow-sm">
                        <div class="card-body">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div> --}}
                <div class="col-md-12">
                    <div class="card mt-4 border-light-subtle shadow-sm">
                        <div class="card-body">
                            <div class="card-title mb-4">
                                <h3 class="fw-bold mb-0">Pengecekan Barang</h3>
                                <span class="text-black-50">Scan Timbangan</span>
                            </div>
                            <table class="table align-middle text-center" id="dataChecker">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center" width="10%">Pembuat</th>
                                        <th class="text-center" width="10%">Kendaraan</th>
                                        <th class="text-center" width="15%">Total</th>
                                        <th class="text-center" width="10%">Total Berat</th>
                                        <th class="text-center" width="15%">Waktu</th>
                                        <th class="text-center" width="30%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // const ctx = document.getElementById('myChart');

            // new Chart(ctx, {
            // type: 'bar',
            // data: {
            //     labels: @json($tanggal),
            //     datasets: [{
            //     label: 'Jumlah',
            //     data: @json($jumlah),
            //     borderWidth: 1
            //     }]
            // },
            // options: {
            //     responsive: true,
            //     maintainAspectRatio: false,
            //     plugins: {
            //         legend: {
            //             position: 'top',
            //         },
            //         title: {
            //             display: true,
            //             text: 'Aktivitas Scan Timbangan'
            //         }
            //     },
            //     scales: {
            //         y: {
            //             beginAtZero: true
            //         }
            //     }
            // }
            // });

            // Data Checker
            var tableChecker = $('#dataChecker').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('w-cek-checker.scopeData') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center"},
                    {data: 'user', name: 'user'},
                    {data: 'no_kendaraan', name: 'no_kendaraan'},
                    {data: 'total', name: 'total', searchable: false},
                    {data: 'berat', name: 'berat', searchable: false},
                    {data: 'tanggal', name: 'tanggal', searchable: false},
                    {data: 'aksi', name: 'aksi', width:'100%', orderable: false, searchable: false},
                ]
            });
            
            // Button delete
            $('body').on('click', '.delete-data', function(e){
                e.preventDefault();
                Swal.fire({
                    title: "Apakah anda yakin ?",
                    text: `Data akan dihapus !`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(e.target).closest('form').submit();
                    }
                });
            });
        });
    </script>
@endpush