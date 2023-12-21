@extends('web.layouts.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('w-dashboard.index') }}" class="text-white">Dashboard</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Master User</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-title mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="fw-bold mb-0">Master User</h3>
                        <span class="text-black-50">Lorem ipsum dolor</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> &nbsp; Tambah Data</button>
                    </div>
                </div>
            </div>
            <table class="table align-middle" id="datacek">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->jenisUsers->name }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Detail</a></li>
                                        <li><a class="dropdown-item" href="#">Perbarui</a></li>
                                        @if ($item->id_jenis != 1)
                                            <li><a class="dropdown-item" href="#">Hapus</a></li>
                                        @endif
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
        });
    </script>
@endpush