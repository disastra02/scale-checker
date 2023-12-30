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
    <nav aria-label="breadcrumb">
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
                        <div class="">
                            <label for="exampleFormControlInput1" class="form-label fw-medium">Perbandingan Data Checker</label>
                            <div class="d-flex">
                                <select class="form-select select2" id="perbandingan-value">
                                    <option selected value="" disabled>Pilih Perbandingan</option>
                                    @foreach ($kendaraan as $item)
                                        <option value="{{ $item->id }}">{{ $item->no_kendaraan }} - {{ getJumlahSurat($item->id) }} Surat - {{ getJumlahBerat($item->id) }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-primary btn-cari ms-3" title="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
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
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="card bg-success-subtle text-success border-0">
                                        <div class="card-body">
                                            <h3 class="fw-bold mb-4"><i class="fa-solid fa-weight-scale"></i></h3>
                                            <h3 class="fw-bold mb-0">{{ getJumlahSurat($transport->id) }} Surat</h3>
                                            <span>Total Surat</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-danger-subtle text-danger border-0">
                                        <div class="card-body">
                                            <h3 class="fw-bold mb-4"><i class="fa-solid fa-user"></i></h3>
                                            <h3 class="fw-bold mb-0">{{ getJumlahBerat($transport->id) }}</h3>
                                            <span>Total Berat</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            @forelse ($suratJalan as $item)
                                <div class="row justify-content-center div-surat">
                                    <div class="col-md-12">
                                        <div class="card shadow-sm border-light-subtle">
                                            <div class="card-body">
                                                <h3 class="fw-bold mb-0 text-center">Surat Jalan</h3>
                                                <p class="text-center text-black-50 mb-4">Nomor : {{ $item->no_surat }}</p>

                                                <div class="row mb-3">
                                                    <div class="col-md-12">
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
                                                                    <td class="text-center">{{ $data->berat_barang }} KG</td>
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
            $(`td.active`).parent().addClass('border-top border-secondary-subtle');

            // Select 2
            $('.select2').select2();

            $('.btn-cari').click(function() {
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