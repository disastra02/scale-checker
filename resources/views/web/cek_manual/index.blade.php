@extends('web.layouts.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('w-dashboard.index') }}" class="text-white">Dashboard</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Cek Manual</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-title mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="fw-bold mb-0">Cek Manual</h3>
                        <span class="text-black-50">Title deskripsi</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <a class="btn btn-primary btn-sm" href="{{ route('w-cek-manual.create') }}"><i class="fa-solid fa-plus"></i> &nbsp; Tambah Data</a>
                    </div>
                </div>
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
                                        <li><a class="dropdown-item" href="{{ route('w-cek-manual.show', $item->id) }}">Detail</a></li>
                                        <li><a class="dropdown-item" href="{{ route('w-cek-manual.perbandingan', $item->id) }}">Perbandingan</a></li>
                                        <li>
                                            <form action="{{ route('w-cek-manual.destroy', $item->id) }}" method="POST">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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