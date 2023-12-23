@extends('web.layouts.app')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('w-dashboard.index') }}" class="text-white">Dashboard</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Master Pelanggan</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-title mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="fw-bold mb-0">Master Pelanggan</h3>
                        <span class="text-black-50">Title deskripsi</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> &nbsp; Tambah Data</button>
                    </div>
                </div>
            </div>
            <table class="table align-middle" id="dataPelanggan">
                <thead>
                    <tr>
                        <th class="text-center" width="8%">No</th>
                        <th class="text-center" width="22%">Nama</th>
                        <th class="text-center" width="62%">Alamat</th>
                        <th class="text-center" width="8%">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Data Pelanggan
            var tablePelanggan = $('#dataPelanggan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('m-customer.scopeData') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'address', name: 'address', searchable: false},
                    {data: 'aksi', name: 'aksi', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush