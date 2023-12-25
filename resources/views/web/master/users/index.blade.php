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
                        <span class="text-black-50">Data User Keseluruhan</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> &nbsp; Tambah Data</button>
                    </div>
                </div>
            </div>
            <table class="table align-middle" id="dataUser">
                <thead>
                    <tr>
                        <th class="text-center" width="8%">No</th>
                        <th class="text-center" width="42%">Nama</th>
                        <th class="text-center" width="28%">Email</th>
                        <th class="text-center" width="16%">Jenis</th>
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
            // Data User
            var tableUser = $('#dataUser').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('m-users.scopeData') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center"},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'jenis', name: 'jenis'},
                    {data: 'aksi', name: 'aksi', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush