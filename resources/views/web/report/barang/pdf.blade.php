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

        .border-bottom {
            border-bottom: 0.5px solid #dee2e6;
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
    <h3 class="fw-bold mb-0 text-center mb-2">Laporan Barang {{ $tipe }}</h3>
    <p class="text-center text-black-50 mb-4">{{ $tanggal }}</p>
    
    <table class="table table-custom align-middle mb-0">
        <thead>
            <tr class="border-top border-secondary-subtle">
                <th class="text-center" width="5%">No</th>
                <th class="text-start" width="20%">Kode</th>
                <th class="text-start" width="55%">Nama Barang</th>
                <th class="text-center" width="20%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr class="border-top border-bottom border-secondary-subtle">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ getBarang($item->kode)->name ?? '-' }}</td>
                    <td class="text-center">{{ $item->total ?? 0 }}</td>
                </tr>
            @empty
                <tr class="border-top border-bottom border-secondary-subtle">
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>