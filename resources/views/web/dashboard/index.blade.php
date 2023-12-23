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
                <span class="text-black-50">Title deskripsi</span>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4 bg-secondary-subtle text-secondary border-0 shadow-sm">
                                <div class="card-body">
                                    <h3 class="fw-bold mb-4"><i class="fa-solid fa-truck"></i></h3>
                                    <h3 class="fw-bold mb-0">{{ $totalKendaraan }}</h3>
                                    <span>Total Kendaraan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4 bg-primary-subtle text-primary border-0 shadow-sm">
                                <div class="card-body">
                                    <h3 class="fw-bold mb-4"><i class="fa-solid fa-envelope"></i></h3>
                                    <h3 class="fw-bold mb-0">{{ $totalSurat }}</h3>
                                    <span>Total Surat</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success-subtle text-success border-0 shadow-sm">
                                <div class="card-body">
                                    <h3 class="fw-bold mb-4"><i class="fa-solid fa-weight-scale"></i></h3>
                                    <h3 class="fw-bold mb-0">{{ converHasilSatuan($totalBerat) }}</h3>
                                    <span>Total Berat</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-danger-subtle text-danger border-0 shadow-sm">
                                <div class="card-body">
                                    <h3 class="fw-bold mb-4"><i class="fa-solid fa-user"></i></h3>
                                    <h3 class="fw-bold mb-0">{{ $totalChecker }}</h3>
                                    <span>Total Checker</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card h-100 border-light-subtle shadow-sm">
                        <div class="card-body">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mt-4 border-light-subtle shadow-sm">
                        <div class="card-body">
                            <div class="card-title mb-4">
                                <h3 class="fw-bold mb-0">Pengecekan Barang</h3>
                                <span class="text-black-50">Title deskripsi</span>
                            </div>
                            <table class="table align-middle" id="datacek">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Pembuat</th>
                                        <th class="text-center">Nomor Kendaraan</th>
                                        <th class="text-center">Total Surat</th>
                                        <th class="text-center">Total Berat</th>
                                        <th class="text-center">Waktu</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kendaraan as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ getUser($item->created_by) ? getUser($item->created_by)->name : '-' }}</td>
                                            <td>{{ $item->no_kendaraan }}</td>
                                            <td>{{ getJumlahSurat($item->id) }} Surat</td>
                                            <td>{{ getJumlahBerat($item->id) }}</td>
                                            <td>{{ getTanggalIndo($item->created_at->format('Y-m-d')) }}, {{ $item->created_at->format('H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('w-timbangan.show', $item->id) }}">Detail</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('w-timbangan.perbandingan', $item->id) }}">Perbandingan</a></li>
                                                        <li>
                                                            <form action="{{ route('w-timbangan.destroy', $item->id) }}" method="POST">
                                                                @method("DELETE")
                                                                @csrf
            
                                                                <a class="dropdown-item delete-data" type="submit">Hapus</a>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($tanggal),
                datasets: [{
                label: 'Jumlah',
                data: @json($jumlah),
                borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Aktivitas Checker'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
            });

            new DataTable('#datacek');
            
            // Button delete
            $('.delete-data').click(function(e){
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