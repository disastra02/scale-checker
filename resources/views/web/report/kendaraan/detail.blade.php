@extends('web.layouts.app')

@push('css')
    <style>
        .div-surat {
            margin-bottom: 1rem;
        }

        .div-surat:last-child {
            margin-bottom: 0;
        }
    </style>
@endpush

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('r-kendaraan.index') }}" class="text-white">Laporan Kendaraan</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Detail Transaksi</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-title mb-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="fw-bold mb-0">Detail Transaksi</h3>
                        <span class="text-black-50">Melihat Aktivitas Kendaraan {{ $kendaraan }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <table class="table align-middle mb-0 text-center" id="dataTransaksi">
                    <thead>
                        <tr>
                            <th width="4%" class="align-middle">No</th>
                            <th width="20%" class="align-middle">Tanggal Transaksi</th>
                            <th width="30%">Jumlah Surat</th>
                            <th width="30%" class="align-middle">Total Berat</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-2 d-flex flex-column">
                    <a class="btn btn-light" href="{{ route('r-kendaraan.index') }}"><i class="fa-solid fa-arrow-left"></i> &nbsp; Kembali </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let kendaraan = `{{ $kendaraan }}`;
            let startDate = `{{ Request::get('awal') }}`;
            let endDate = `{{ Request::get('akhir') }}`;

            console.log(startDate, endDate)

            var tableTransaksi = $('#dataTransaksi').DataTable({
                processing: true,
                serverSide: true,
                ajax: `{{ route('r-kendaraan.scopeDataShow') }}?kendaraan=${kendaraan}&awal=${startDate}&akhir=${endDate}`,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center"},
                    {data: 'tanggal', name: 'tanggal', className: "text-center"},
                    {data: 'total', name: 'total', searchable: false, className: "text-center"},
                    {data: 'berat', name: 'berat', searchable: false, className: "text-center"},
                ]
            });
        });
    </script>
@endpush