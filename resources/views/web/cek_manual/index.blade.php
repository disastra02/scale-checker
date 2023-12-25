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
                        <span class="text-black-50">Input Surat Jalan</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <a class="btn btn-primary btn-sm" href="{{ route('w-cek-manual.create') }}"><i class="fa-solid fa-plus"></i> &nbsp; Tambah Data</a>
                    </div>
                </div>
            </div>
            <table class="table align-middle" id="dataChecker">
                <thead>
                    <tr>
                        <th class="text-center" width="8%">No</th>
                        <th class="text-center">Pembuat</th>
                        <th class="text-center" width="16%">Nomor Kendaraan</th>
                        <th class="text-center" width="16%">Total</th>
                        <th class="text-center" width="16%">Total Berat</th>
                        <th class="text-center" width="16%">Waktu</th>
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
            // Data Checker
            var tableChecker = $('#dataChecker').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('w-cek-manual.scopeData') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center"},
                    {data: 'user', name: 'user'},
                    {data: 'no_kendaraan', name: 'no_kendaraan'},
                    {data: 'total', name: 'total', searchable: false},
                    {data: 'berat', name: 'berat', searchable: false},
                    {data: 'tanggal', name: 'tanggal', searchable: false},
                    {data: 'aksi', name: 'aksi', orderable: false, searchable: false},
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