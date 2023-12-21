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
            <li class="breadcrumb-item"><a href="{{ route('w-cek-manual.index') }}" class="text-white">Cek Manual</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Detail Manual</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-title mb-0">
                <h3 class="fw-bold mb-0">Detail Manual</h3>
                <span class="text-black-50">Lorem ipsum dolor</span>
                <hr>
            </div>
            
            @forelse ($suratJalan as $item)
                <div class="row justify-content-center div-surat">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="fw-bold mb-0 text-center">Surat Jalan</h3>
                                <p class="text-center text-black-50 mb-4">Nomor : {{ $item->no_surat }}</p>

                                <table class="table table-borderless table-sm align-middle">
                                    <tr>
                                        <td class="fw-bold ps-0" width="25%">Nomor Kendaraan</td>
                                        <td>: <span class="text-black-50 mb-0">{{ $transport->no_kendaraan }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold ps-0" width="25%">Tanggal</td>
                                        <td>: <span class="text-black-50 mb-0">{{ getTanggalIndo($item->created_at->format('Y-m-d')) }}</span></td>
                                    </tr>
                                </table>

                                @if ($item->timbangans)
                                    <table class="table table-bordered align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-start" width="5%">No</th>
                                                <th class="text-start" width="60%">Barang</th>
                                                <th class="text-start" width="35%">Berat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($item->timbangans as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $data->name }}</td>
                                                    <td>{{ $data->berat_barang }} KG</td>
                                                </tr>

                                                @if ($loop->last)
                                                    <tr>
                                                        <td colspan="2" class="fw-bold">Total Berat</td>
                                                        <td class="fw-bold">{{ getJumlahBeratLetter($item->id) }} KG</td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                @else
                                    <div class="w-100 text-center">
                                        <div class="alert alert-danger" role="alert">
                                            Tidak ada data.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-100 text-center">
                    <div class="alert alert-danger" role="alert">
                        Tidak ada data.
                    </div>
                </div>
            @endforelse

            <hr>
            <div class="row justify-content-end">
                <div class="col-md-2 d-flex flex-column">
                    <a class="btn btn-primary btn-light bg-danger-subtle text-danger border-danger" href="{{ route('w-cek-manual.index') }}"><i class="fa-solid fa-arrow-left"></i> &nbsp; Kembali </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush