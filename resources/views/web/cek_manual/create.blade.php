@extends('web.layouts.app')

@push('css')
    <style>
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
    </style>
@endpush

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('w-cek-manual.index') }}" class="text-white">Surat Jalan Manual</a></li>
            <li class="breadcrumb-item text-white active" aria-current="page">Tambah Data</li>
        </ol>
    </nav>

    <div class="card shadow-lg">
        <form id="formTimbangan" action="{{ route('w-cek-manual.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-body">
                <div class="card-title mb-4">
                    <h3 class="fw-bold mb-0">Pengecekan Surat Jalan</h3>
                    <span class="text-black-50">Menginput Surat Jalan</span>
                    <hr>
                </div>
                
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="nomorKendaraan" class="form-label">Nomor Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_kendaraan" class="form-control" id="nomorKendaraan" autocomplete="off" placeholder="Masukkan Nomor Kendaraan" required>
                        </div>
                        
                        <div id="sectionSuratJalan">
                            <div class="card bg-light">
                                <div class="card-header border-0 bg-secondary-subtle">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            Surat Jalan
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="suratJalan" class="form-label">No Surat Jalan <span class="text-danger">*</span></label>
                                        <input type="text" name="surat_jalan[]" class="form-control" autocomplete="off" id="suratJalan" placeholder="Masukkan No Surat Jalan" required>
                                        <input type="hidden" name="nomer_surat[]" value="1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="customer" class="form-label">Customer <span class="text-danger">*</span></label>
                                        <select class="form-select select2" name="customer[]" required>
                                            <option selected value="" disabled>Customer</option>
                                            @foreach ($customer as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <label for="scanBarcode" class="form-label">Data Barang</label>
                                        <table class="table align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-start" width="55%">Barang</th>
                                                    <th class="text-start" width="35%">Berat</th>
                                                    <th class="text-center" width="10%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody1">
                                                <tr>
                                                    <td>
                                                        <select class="form-select select2" name="kode_barang[]" required>
                                                            <option selected value="" disabled>Barang</option>
                                                            @foreach ($barang as $item)
                                                                <option value="{{ $item->kode }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step=".001" class="form-control" autocomplete="off" name="berat_barang[]" placeholder="Berat" required>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="nomer_barcode[]" value="1">
                                                        {{-- <button type="button" class="btn btn-danger bg-danger-subtle btn-remove-barang border-danger" data-id="1"><i class="text-danger fa-solid fa-trash"></i></button> --}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="card border-0 bg-success-subtle">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <p class="fw-medium mb-0">Tambah Data Barang</p>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <button type="button" class="btn btn-success bg-success-subtle btn-add-barang border-success btn-sm text-success" data-id="1"><i class="text-success fa-solid fa-plus"></i> &nbsp; Tambah</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card border-0 bg-secondary-subtle">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <p class="fw-medium mb-0">Tambah Data Surat Jalan</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" class="btn btn-secondary bg-secondary-subtle btn-add-surat border-secondary btn-sm text-secondary"><i class="text-secondary fa-solid fa-plus"></i> &nbsp; Tambah</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row justify-content-end">
                    <div class="col-md-2 d-flex flex-column">
                        <a class="btn btn-light" href="{{ route('w-cek-manual.index') }}"><i class="fa-solid fa-times"></i> &nbsp; Batal </a>
                    </div>
                    <div class="col-md-2 d-flex flex-column">
                        <button class="btn btn-primary btn-submit-data" type="button"><i class="fa-solid fa-check"></i> &nbsp; Simpan </button>
                        <button id="submitData" type="submit" class="d-none"></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let idStreamAll = 1;
            var arrayDataTimbangan = [];

            // Select 2
            $('.select2').select2();

            // Tambah Input Barang
            $('body').on('click', '.btn-add-barang', function() {
                let idStream = $(this).data('id');
                let html = `<tr>
                                <td>
                                    <select class="form-select select2" name="kode_barang[]">
                                        <option selected value="" disabled>Barang</option>
                                        @foreach ($barang as $item)
                                            <option value="{{ $item->kode }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step=".001" class="form-control" autocomplete="off" name="berat_barang[]" placeholder="Berat">
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="nomer_barcode[]" value="${idStream}">
                                    <button type="button" class="btn btn-danger bg-danger-subtle btn-remove-barang border-danger" data-id="${idStream}"><i class="text-danger fa-solid fa-trash"></i></button>
                                </td>
                            </tr>`;

                $(`#tbody${idStream}`).append(html);
                $('.select2').select2();
            });

            // Remove Input Barang
            $('body').on('click', '.btn-remove-barang', function() {
                let html = $(this).parents().eq(1);

                Swal.fire({
                    title: "Apakah anda yakin ?",
                    text: `Data barang akan dihapus !`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        html.remove();
                    }
                });
            });

            // Tambah Surat Jalan
            $('body').on('click', '.btn-add-surat', function() {
                idStreamAll++;
                let html = `<div class="card bg-light">
                                <div class="card-header border-0 bg-secondary-subtle">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            Surat Jalan
                                        </div>
                                        <div class="col-6 text-end">
                                            <button type="button" class="btn btn-danger bg-danger-subtle btn-remove-card-surat border-danger" data-id="${idStreamAll}" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"><i class="text-danger fa-solid fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="suratJalan" class="form-label">No Surat Jalan <span class="text-danger">*</span></label>
                                        <input type="text" name="surat_jalan[]" class="form-control" autocomplete="off" id="suratJalan" placeholder="Masukkan No Surat Jalan" required>
                                        <input type="hidden" name="nomer_surat[]" value="${idStreamAll}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="customer" class="form-label">Customer <span class="text-danger">*</span></label>
                                        <select class="form-select select2" name="customer[]" required>
                                            <option selected value="" disabled>Customer</option>
                                            @foreach ($customer as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <label for="scanBarcode" class="form-label">Data Barang</label>
                                        <table class="table align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-start" width="55%">Barang</th>
                                                    <th class="text-start" width="35%">Berat</th>
                                                    <th class="text-center" width="10%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody${idStreamAll}">
                                                <tr>
                                                    <td>
                                                        <select class="form-select select2" name="kode_barang[]">
                                                            <option selected value="" disabled>Barang</option>
                                                            @foreach ($barang as $item)
                                                                <option value="{{ $item->kode }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step=".001" class="form-control" autocomplete="off" name="berat_barang[]" placeholder="Berat">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="nomer_barcode[]" value="${idStreamAll}">
                                                        {{-- <button type="button" class="btn btn-danger bg-danger-subtle btn-remove-barang border-danger" data-id="${idStreamAll}"><i class="text-danger fa-solid fa-trash"></i></button> --}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="card border-0 bg-success-subtle">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <p class="fw-medium mb-0">Tambah Data Barang</p>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <button type="button" class="btn btn-success bg-success-subtle btn-add-barang border-success btn-sm text-success" data-id="${idStreamAll}"><i class="text-success fa-solid fa-plus"></i> &nbsp; Tambah</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                $(`#sectionSuratJalan`).append(html);
                $('.select2').select2();
            });

            // Remove Surat
            $('body').on('click', '.btn-remove-card-surat', function() {
                let html = $(this).parents().eq(3);

                Swal.fire({
                    title: "Apakah anda yakin ?",
                    text: `Data surat jalan akan dihapus !`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        html.remove();
                    }
                });
            });

            // Submit data
            $('.btn-submit-data').on('click', function() {
                Swal.fire({
                    title: "Apakah anda yakin ?",
                    text: `Data akan disimpan !`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Simpan",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit element
                        $('#submitData').trigger('click');
                    }
                });
            });
        });
    </script>
@endpush