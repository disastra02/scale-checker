@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-4 border-0">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h1 class="fw-bold">{{ $user->name }}</h2>
                            <h6 class="mb-0 text-black-50">Summary Dashboard</h5>
                        </div>
                        <div class="col-4">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <img src="{{ asset('images/dummy.jpg') }}" class="rounded float-end" width="50" alt="...">
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col-6 mb-4">
                    <div class="card bg-primary-subtle text-primary border-0">
                        <div class="card-body row align-items-center">
                            <div class="col-8">
                                <span>Mobil</span>
                                <h6 class="mb-0 fw-bold">{{ $totalKendaraan }}</h6>
                            </div>
                            <div class="col-4 text-end">
                                <h2 class="fw-bold mb-0"><i class="fa-solid fa-truck"></i></h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 mb-4">
                    <div class="card bg-warning-subtle text-secondary border-0">
                        <div class="card-body row align-items-center">
                            <div class="col-8">
                                <span>Pelanggan</span>
                                <h6 class="mb-0 fw-bold">{{ $totalPelanggan }}</h6>
                            </div>
                            <div class="col-4 text-end">
                                <h2 class="fw-bold mb-0"><i class="fa-solid fa-user"></i></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card bg-secondary-subtle text-secondary border-0">
                        <div class="card-body row align-items-center">
                            <div class="col-8">
                                <span>Surat</span>
                                <h6 class="mb-0 fw-bold">{{ $totalSurat }}</h6>
                            </div>
                            <div class="col-4 text-end">
                                <h2 class="fw-bold mb-0"><i class="fa-solid fa-envelope"></i></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card bg-success-subtle text-success border-0">
                        <div class="card-body row align-items-center">
                            <div class="col-8">
                                <span>Berat</span>
                                <h6 class="mb-0 fw-bold">{{ converHasilSatuan($totalBerat) }}</h6>
                            </div>
                            <div class="col-4 text-end">
                                <h2 class="fw-bold mb-0"><i class="fa-solid fa-weight-scale"></i></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4 border-0">
                <div class="card-body p-2">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="fw-bold mb-0">Data Timbangan</h5>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ route('timbangan.create') }}" class="btn btn-primary" type="button"><i class="fa-solid fa-plus"></i> &nbsp; Tambah</a> &nbsp; &nbsp;
                            <button class="btn btn-secondary"><i class="fa-solid fa-filter"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12" style="display: none;">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="fw-bold mb-0">Filter</h5>
                        </div>
                        <div class="col-4">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Hari Ini</option>
                                <option value="1">Minggu Ini</option>
                                <option value="2">Bulan Ini</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            @forelse ($kendaraan as $item)
                <div class="col-12">
                    <div class="card mb-4 bg-light border-0">
                        <div class="card-header border-0 bg-secondary-subtle">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <span class="mb-1 text-black-50">Nomor Kendaraan</span>
                                    <p class="mb-0 fw-bold">{{ $item->no_kendaraan }}</p>
                                </div>
                                <div class="col-3 d-flex justify-content-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('timbangan.show', $item->id) }}">Detail</a></li>
                                            <li>
                                                <form action="{{ route('timbangan.destroy', $item->id) }}" method="POST">
                                                    @method("DELETE")
                                                    @csrf

                                                    <a class="dropdown-item delete-data" type="submit">Hapus</a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6 mb-3">
                                    <span class="mb-1 text-black-50">Surat Jalan</span>
                                    <p class="mb-0 fw-bold">{{ getJumlahSurat($item->id) }}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <span class="mb-1 text-black-50">Pelanggan</span>
                                    <p class="mb-0 fw-bold">{{ getJumlahPelanggan($item->id) }}</p>
                                </div>
                                <div class="col-6">
                                    <span class="mb-1 text-black-50">Total Berat</span>
                                    <p class="mb-0 fw-bold">{{ getJumlahBerat($item->id) }}</p>
                                </div>
                                <div class="col-6">
                                    <span class="mb-1 text-black-50">Waktu</span>
                                    <p class="mb-0 fw-bold">{{ $item->created_at->format('Y-m-d') }}, {{ $item->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-100 text-center">
                    <div class="alert alert-danger" role="alert">
                        Tidak ada data hari ini.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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
