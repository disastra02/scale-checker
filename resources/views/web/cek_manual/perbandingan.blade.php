@extends('web.layouts.app')

@push('css')
    <style>
        .div-surat {
            margin-bottom: 1rem;
        }

        .div-surat:last-child {
            margin-bottom: 0;
        }

        .select2-container .select2-selection--single {
            height: 36px !important;
            border: var(--bs-border-width) solid var(--bs-border-color) !important;
            border-radius: var(--bs-border-radius) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }

        .f-12 {
            font-size: 12px !important;
        }
    </style>
@endpush

@section('content')
    <nav class="container" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('w-cek-manual.index') }}" class="text-white">Surat Jalan Manual</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Perbandingan Manual</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <div class="card-body">
            <div class="card-title mb-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="fw-bold mb-0">Perbandingan Manual</h3>
                        <span class="text-black-50">Memastikan Data Sesuai</span>
                    </div>
                    <div class="col-md-6">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="">
                                    <label for="" class="form-label fw-medium">Pembuat</label>
                                    <select class="form-select select2" id="pembuat-value">
                                        <option selected value="" disabled>Pilih Pembuat</option>
                                        @foreach ($pembuat as $item)
                                            @if (!($transport->created_by == $item->id ))
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="select-next">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3"><span class="fw-medium">Pembuat</span></div>
                                <div class="col-md-9">: <span class="fw-medium">{{ getUser($transport->created_by) ? getUser($transport->created_by)->name : '-' }}</span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><span class="fw-medium">No Polisi Kendaraan</span></div>
                                <div class="col-md-9">: <span class="fw-medium">{{ $transport->no_kendaraan }}</span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"><span class="fw-medium">Total Surat</span></div>
                                <div class="col-md-9">: <span class="fw-medium">{{ getJumlahSurat($transport->id) }} Surat</span></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3"><span class="fw-medium">Total Berat</span></div>
                                <div class="col-md-9">: <span class="fw-medium">{{ getJumlahBerat($transport->id) }}</span></div>
                            </div>
        
                            @forelse ($suratJalan as $item)
                                <div class="row justify-content-center div-surat">
                                    <div class="col-md-12">
                                        <div class="card shadow-sm border-light-subtle">
                                            <div class="card-body f-12">
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
                                                                            <div class="col-md-8">: <span class="fw-medium">{{ $item->customers->name }}</span></div>
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
                
                                                <table class="table table-sm table-bordered align-middle mb-0 text-uppercase text-center f-12">
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
                                                                            <td class="text-end">{{ $list->berat_barang }}</td>
                
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
                                                                            <td class="text-end">{{ $list->berat_barang }}</td>
                
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
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-perbandingan" style="display: none;">
                        <div class="card-body">
                            <div class="" id="perbandinganView"></div>
                        </div>
                    </div>
                </div>

            </div>
            <hr>
            <div class="row justify-content-end">
                <div class="col-md-2 d-flex flex-column">
                    <a class="btn btn-light" href="{{ route('w-cek-manual.index') }}"><i class="fa-solid fa-arrow-left"></i> &nbsp; Kembali </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(`.container-content`).removeClass('container').addClass('container-fluid');
            $(`td.active`).parent().addClass('border-top border-secondary-subtle');

            // Select 2
            $('.select2').select2();

            $('#pembuat-value').change(function() {
                let id = $(this).val(); 
                
                $.ajax({
                    type:'GET',
                    url: `{{ route('w-cek-checker.getOption') }}?id=${id}`,
                    beforeSend:function(e) {
                        let html = `<div class="text-center">
                                        <h6 class="mb-0"><i class="fa-solid fa-spinner fa-spin-pulse"></i></h6>
                                    </div>`;
                        $('.select-next').html(html);
                        $('.card-perbandingan').hide();
                    },
                    success:function(data) {
                        $('.select-next').html(data);
                        $('.select2').select2();
                    }
                });
            });

            $('body').on('click', '.btn-cari', function() {
                let perbandingan = $('#perbandingan-value').val();

                if (perbandingan) {
                    $('.card-perbandingan').show();

                    $.ajax({
                        type:'GET',
                        url: `{{ route('w-cek-manual.perbandinganDetail') }}?id=${perbandingan}`,
                        beforeSend:function(e) {
                            let html = `<div class="text-center">
                                            <h1 class="mb-0"><i class="fa-solid fa-spinner fa-spin-pulse"></i></h1>
                                        </div>`;
                            $('#perbandinganView').html(html);
                        },
                        success:function(data) {
                            $('#perbandinganView').html(data);
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Opss...",
                        text: "Pilih perbandingan terlebih dahulu.",
                        icon: "error"
                    });
                }
            });
        });
    </script>
@endpush