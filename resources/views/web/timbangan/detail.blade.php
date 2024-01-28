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
            <li class="breadcrumb-item"><a href="{{ route('w-dashboard.index') }}" class="text-white">Dashboard</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Detail Pengecekan</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-title mb-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="fw-bold mb-2">Detail Pengecekan</h3>
                        <div class="">
                            <span class="badge text-bg-primary">Total {{ getJumlahSurat($transport->id) }} Surat</span> &nbsp; <span class="badge text-bg-secondary">Total Berat {{ getJumlahBerat($transport->id) }}</span> &nbsp; <span class="badge text-bg-success">No Polisi Kendaraan {{ $transport->no_kendaraan }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <a class="btn btn-light btn-sm" href="{{ route('w-dashboard.index') }}" title="Kembali"><i class="fa-solid fa-arrow-left"></i></a>
                        <a class="btn btn-warning btn-sm ms-2" href="{{ route('w-cek-checker.perbandingan', $transport->id) }}" title="Perbandingan"><i class="fa-solid fa-arrows-left-right"></i> &nbsp; Perbandingan</a>
                        <a class="btn btn-success btn-sm ms-2" href="{{ route('w-cek-checker.printToExcel', $transport->id) }}" title="Tallysheet"><i class="fa-solid fa-file-excel"></i> &nbsp; Tallysheet</a>

                        <form action="{{ route('w-cek-checker.destroy', $transport->id) }}" method="POST">
                            @method("DELETE")
                            @csrf

                            <a class="btn btn-danger btn-sm ms-2 delete-data" type="submit" title="Hapus"><i class="fa-solid fa-trash"></i> &nbsp; Hapus</a>
                        </form>
                    </div>
                </div>
            </div>

            @forelse ($suratJalan as $item)
                <div class="row justify-content-center div-surat">
                    <div class="col-md-12">
                        <div class="card shadow-sm border-light-subtle">
                            <div class="card-body">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-2 text-center">
                                        <img src="{{ asset('images/logo.png') }}" width="80" alt="logo">
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-4"><span class="fw-medium">No Surat Jalan</span></div>
                                                            <div class="col-md-8">: <span class="fw-medium">{{ $item->no_surat }}</span></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"><span class="fw-medium">Customer</span></div>
                                                            <div class="col-md-8">: <span class="fw-medium">{{ Str::upper($item->customers->name) }}</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-4"><span class="fw-medium">Tgl</span></div>
                                                            <div class="col-md-8">: <span class="fw-medium">{{ getTanggalIndo($item->created_at->format('Y-m-d')) }}</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-4"><span class="fw-medium">No PO</span></div>
                                                            <div class="col-md-8">: <span class="fw-medium">{{ $item->no_po }}</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-sm table-bordered align-middle mb-0 text-uppercase text-center">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" width="4%" class="align-middle">No</th>
                                            <th rowspan="2" width="16%" class="align-middle">Nama Barang</th>
                                            <th colspan="10" width="70%">Jumlah</th>
                                            <th rowspan="2" width="10%" class="align-middle">Total</th>
                                        </tr>
                                        <tr>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <th width="6%">{{ $i }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse (getTimbanganGroup($item->id) as $data)
                                            @php
                                                $dataTimbanganList = getTimbanganList($item->id, $data->kode_barang);
                                                $jumlahTimbanganList = $dataTimbanganList->count(); 
                                                $totalLoopTimbanganList = ceil($jumlahTimbanganList / 10) + 1;
                                            @endphp

                                            @for ($j = 1; $j <= $totalLoopTimbanganList; $j++)
                                                @if ($j == 1)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $data->barangs->name ?? '-' }}</td>
                                                        @foreach ($dataTimbanganList as $key => $list)
                                                            <td class="text-end">{{ converDecimal($list->berat_barang) }}</td>

                                                            @php $dataTimbanganList->forget($key)@endphp
                                                            @if ($loop->iteration == 10) @break @endif
                                                            @if($loop->last)
                                                                @for ($k = $loop->iteration + 1; $k <= 10; $k++)
                                                                    <td></td>
                                                                @endfor
                                                            @endif
                                                        @endforeach
                                                        <td class="text-end">{{ getJumlahBeratLetterBarang($item->id, $list->kode_barang, false) }}</td>
                                                    </tr>
                                                @elseif ($j == $totalLoopTimbanganList)
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        @for ($m = 1; $m <= 10; $m++)
                                                            <td></td>
                                                        @endfor
                                                        <td>-</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        @foreach ($dataTimbanganList as $key => $list)
                                                            <td class="text-end">{{ converDecimal($list->berat_barang) }}</td>

                                                            @php $dataTimbanganList->forget($key)@endphp
                                                            @if ($loop->iteration == 10) @break @endif
                                                            @if($loop->last)
                                                                @for ($n = $loop->iteration + 1; $n <= 10; $n++)
                                                                    <td></td>
                                                                @endfor
                                                            @endif
                                                        @endforeach
                                                        <td></td>
                                                    </tr>
                                                @endif
                                            @endfor

                                            @if ($loop->last)
                                                <tr>
                                                    <td colspan="12" class="text-start fw-bold">Total</td>
                                                    {{-- <td></td>
                                                    @for ($p = 1; $p <= 10; $p++)
                                                        <td></td>
                                                    @endfor --}}
                                                    <td class="text-end fw-bold">{{ getJumlahBeratLetter($item->id, false) }}</td>
                                                </tr>
                                                
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="13" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
            
            {{-- @forelse ($suratJalan as $item)
                <div class="row justify-content-center div-surat">
                    <div class="col-md-12">
                        <div class="card shadow-sm border-light-subtle">
                            <div class="card-body">
                                <h3 class="fw-bold mb-0 text-center">Surat Jalan</h3>
                                <p class="text-center text-black-50 mb-4">Nomor : {{ $item->no_surat }}</p>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4"><span class="fw-medium">Nomor Kendaraan</span></div>
                                                    <div class="col-md-8">: <span class="text-black-50">{{ $transport->no_kendaraan }}</span></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><span class="fw-medium">Pelanggan</span></div>
                                                    <div class="col-md-8">: <span class="text-black-50">{{ $item->customers->name }}</span></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><span class="fw-medium">Alamat</span></div>
                                                    <div class="col-md-8">: <span class="text-black-50">{{ $item->customers->address }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4"><span class="fw-medium">Tanggal</span></div>
                                                    <div class="col-md-8">: <span class="text-black-50">{{ getTanggalIndo($item->created_at->format('Y-m-d')) }}</span></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><span class="fw-medium">Jumlah Barang</span></div>
                                                    <div class="col-md-8">: <span class="text-black-50">{{ getJumlahBarang($item->id) }}</span></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><span class="fw-medium">Total Barang</span></div>
                                                    <div class="col-md-8">: <span class="text-black-50">{{ getTotalBarang($item->id) }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($item->timbangans)
                                    <table class="table table-borderless align-middle mb-0">
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
                                                <tr>
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
                                                    <tr class="border-top border-bottom border-secondary-subtle">
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
            @empty
                <div class="w-100 text-center">
                    <div class="alert alert-danger" role="alert">
                        Tidak ada data.
                    </div>
                </div>
            @endforelse --}}

            {{-- <div class="row justify-content-end">
                <div class="col-md-2 d-flex flex-column">
                    <a class="btn btn-primary btn-light bg-danger-subtle text-danger border-danger" href="{{ route('w-dashboard.index') }}"><i class="fa-solid fa-arrow-left"></i> &nbsp; Kembali </a>
                </div>
            </div> --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(`td.active`).parent().addClass('border-top border-secondary-subtle');

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