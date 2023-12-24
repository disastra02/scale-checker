<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">

    <title>{{ str_replace('_', ' ', config('app.name', 'Laravel')) }}</title>
    <link rel="icon" type="image" href="{{ asset('images/scale.png') }}">

    <!-- Custom Style -->
    @include('web.layouts.css')
    <style>
        .h-90 {
            height: 95%;
        }

        .div-surat {
            margin-bottom: 1rem;
        }

        .div-surat:last-child {
            margin-bottom: 0;
        }

        h3 {
            font-size: 1.75rem;
            font-weight: 700!important;
        }
        
        .table-top {
            vertical-align: top;
        }

        .border-top {
            border-top: 0.5px solid #dee2e6;
        }

        .table-header tr th {
            padding: 0.1rem 0.1rem;
        }

        .table-header tr td {
            padding: 0.1rem 0.1rem;
        }

        .table-custom tr th {
            padding: 0.5rem 0.5rem;
        }

        .table-custom tr td {
            padding: 0.5rem 0.5rem;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body class="bg-white">
    @forelse ($suratJalan as $item)
        <div class="row justify-content-center div-surat">
            <div class="col-md-12">
                <div class="card border-0">
                    <div class="card-body">
                        <h3 class="fw-bold mb-0 text-center">Surat Jalan</h3>
                        <p class="text-center text-black-50 mb-4">Nomor : {{ $item->no_surat }}</p>

                        <div class="row mb-4">
                            <table class="table table-top table-header">
                                <tr>
                                    <td width="20%"><span class="fw-normal">Nomor Kendaraan</span></td>
                                    <td width="30$">: <span class="fw-normal text-black-50">{{ $transport->no_kendaraan }}</td>
                                    <td width="20%"><span class="fw-normal">Tanggal</span></td>
                                    <td width="30$">: <span class="fw-normal text-black-50">{{ getTanggalIndo($item->created_at->format('Y-m-d')) }}</span></td>
                                </tr>
                                <tr>
                                    <td width="20%"><span class="fw-normal">Pelanggan</span></td>
                                    <td width="30$">: <span class="fw-normal text-black-50">{{ $item->customers->name }}</span></td>
                                    <td width="20%"><span class="fw-normal">Jumlah Barang</span></td>
                                    <td width="30$">: <span class="fw-normal text-black-50">{{ getJumlahBarang($item->id) }}</span></td>
                                </tr>
                                <tr>
                                    <td width="20%"><span class="fw-normal">Alamat</span></td>
                                    <td width="30$">: <span class="fw-normal text-black-50">{{ $item->customers->address }}</span></td>
                                    <td width="20%"><span class="fw-normal">Total Barang</span></td>
                                    <td width="30$">: <span class="fw-normal text-black-50">{{ getTotalBarang($item->id) }}</span></td>
                                </tr>
                            </table>
                        </div>

                        @if ($item->timbangans)
                            <table class="table table-custom align-middle mb-0">
                                <thead>
                                    <tr class="border-top border-secondary-subtle">
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-start" width="55%">Barang</th>
                                        <th class="text-center" width="20%">Berat</th>
                                        <th class="text-center" width="20%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $last_item = null;
                                        $same_item = null;
                                    @endphp
                                    @forelse ($item->timbangans as $data)
                                        @if ($last_item == $data->kode_barang)
                                        <tr>
                                        @else
                                        <tr class="border-top border-secondary-subtle">
                                        @endif
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td class="text-center">{{ converHasilSatuan($data->berat_barang) }}</td>
                                            @if ($last_item == $data->kode_barang)
                                                @php $same_item = $data->kode_barang; @endphp
                                                <td></td>
                                            @else
                                                <td class="active text-center">{{ getJumlahBeratLetterBarang($data->id_letter, $data->kode_barang) }}</td>
                                            @endif
                                        </tr>

                                        @if ($loop->last)
                                            <tr class="border-top border-secondary-subtle">
                                                <td colspan="3" class="fw-bold">Total Berat</td>
                                                <td class="fw-bold text-center">{{ getJumlahBeratLetter($item->id) }}</td>
                                            </tr>
                                        @endif
                                        @php $last_item = $data->kode_barang; @endphp
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data</td>
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
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @empty
        <div class="w-100 text-center">
            <div class="alert alert-danger" role="alert">
                Tidak ada data.
            </div>
        </div>
    @endforelse
</body>
</html>