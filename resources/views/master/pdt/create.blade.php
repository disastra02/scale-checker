@extends('layouts.app')

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

        .input-scan::placeholder {
            color: #ffffff;
            opacity: 1;
        }
    </style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-4 border-0">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-light back-page bg-transparent border-0"><i class="fa-solid fa-arrow-left"></i></button>
                        <h5 class="fw-bold mb-0">Data Timbangan</h5>
                        <button class="btn btn-light bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Reset Formulir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <form id="formTimbangan" action="{{ route('timbangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4 border-0">
                    <div class="card-body p-2">
                        <div class="mb-3">
                            <label for="nomorKendaraan" class="form-label">No Polisi Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_kendaraan" class="form-control" tabindex="-1" id="nomorKendaraan" autocomplete="off" placeholder="Masukkan No Polisi Kendaraan" required>
                        </div>
                        
                        <div id="sectionSuratJalan">
                            <div class="card mb-3 mb-4 bg-light border-0">
                                <div class="card-header border-0 bg-secondary-subtle">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            Surat Jalan
                                        </div>
                                        <div class="col-6 text-end">
                                            {{-- <button class="btn btn-secondary btn-add-surat" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" type="button"><i class="fa-solid fa-plus"></i>&nbsp; Tambah Surat</button> --}}
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
                                        <label for="noPo" class="form-label">No PO <span class="text-danger">*</span></label>
                                        <input type="text" name="po[]" class="form-control" autocomplete="off" id="noPo" placeholder="Masukkan No PO" required>
                                        <input type="hidden" name="nomer_po[]" value="1">
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
                                    <div class="d-flex flex-column">
                                        <label for="scanBarcode" class="form-label">Kode Barang</label>
                                        <p>Hasil : <span id="hasilContoh1"></span></p>
                                        <input type="text" id="inputScan1" class="btn btn-success input-scan" data-id="1" autocomplete="off" placeholder="Scan Data">
                                        <div class="text-center" id="scanDiv1">
                                            <table class="table align-middle mb-0 mt-3">
                                                <thead>
                                                    <tr>
                                                        <th class="text-start" width="55%">Barang</th>
                                                        <th class="text-start" width="35%">Berat</th>
                                                        <th class="text-center" width="10%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody1">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="card border-0 bg-secondary-subtle">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <p class="fw-medium mb-0">Tambah Surat Jalan</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button class="btn btn-secondary btn-add-surat" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" type="button"><i class="fa-solid fa-plus"></i>&nbsp; Tambah Surat</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-5 d-flex flex-column">
                                <button class="btn btn-light back-page" type="button"><i class="fa-solid fa-xmark"></i> &nbsp; Batal </button>
                            </div>
                            <div class="col-5 d-flex flex-column">
                                <button class="btn btn-primary btn-submit-data" type="button"><i class="fa-solid fa-check"></i> &nbsp; Simpan </button>
                                <button id="submitData" type="submit" class="d-none"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/html5-qrcode-2.3.8/js/html5-qrcode.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Disbled tab & enter
            $(window).keydown(function(event){
                if(event.keyCode == 13 || event.keyCode == 9) {
                    event.preventDefault();
                    return false;
                }
            });

            // Select 2
            $('.select2').select2();

            var dataBarang = JSON.parse(`{!! json_encode($dataBarang) !!}`);
            var statusStream = false, htmlQrCodeAktif = null;
            var idStreamAll = 1, idStreamAktif = 0;
            var arrayDataTimbangan = [];

            // Hapus data scan
            $('body').on('click', '.btn-remove-qr', function() {
                let id = $(this).data('id');

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
                        // Remove array
                        let getIndex = arrayDataTimbangan.indexOf(id);
                        arrayDataTimbangan.splice(getIndex, 1);

                        // Remove element
                        $(`#qrValue${id}`).remove();
                    }
                });
            });

            // Hapus data card surat
            $('body').on('click', '.btn-remove-card-surat', function() {
                let id = $(this).data('id');

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
                        // Remove element
                        $(`#cardSuratJalan${id}`).remove();

                        // Show button tambah
                        $('.btn-add-surat').last().show();
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
            
            // Fungsi Qr PDT
            let dataQrCode = [[]];
            // Start Qr
            let valueInputScan = '';
            $('body').on('focusin', '.input-scan', function(e) {
                    $(this).attr('placeholder', "Stop Scan");
                    $(this).addClass('btn-danger');
                })
                .on('focusout', '.input-scan', function(e) {
                    $(this).attr('placeholder', "Scan Data");
                    $(this).removeClass('btn-danger');
                }
            );

            // Mengambil data
            $('body').on('keyup', '.input-scan', function(e) {
                let val = $(this).val();
                idStreamAktif = $(this).data('id');

                if (e.key == "Enter") {
                    // Jika berhasil
                    // let kodeBarangId = resultQr ? resultQr.substr(0, 7) : 0;
                    // let beratBarangId = resultQr ? resultQr.substr(7, 4) / 10 : 0;
                    // let barang = dataBarang[kodeBarangId];
                    $(`#hasilContoh${idStreamAktif}`).append(`${valueInputScan}, `);
                    let kodeBarangId = valueInputScan;
                    let beratBarangId = valueInputScan;
                    let valueQr = valueInputScan;
                    let html = `<tr id="qrValue${valueQr}">
                                    <td class="text-start">
                                        ${kodeBarangId}
                                        <input type="hidden" name="kode_barang[]" value="${kodeBarangId}">
                                    </td>
                                    <td class="text-start">
                                        ${beratBarangId} KG
                                        <input type="hidden" name="berat_barang[]" value="${beratBarangId}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger bg-danger-subtle btn-remove-qr border-danger" data-id="${valueQr}" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"><i class="text-danger fa-solid fa-trash"></i></button>
                                        <input type="hidden" name="nomer_barcode[]" value="${idStreamAktif}">
                                    </td>
                                </tr>`;
                    $(`#tbody${idStreamAktif}`).append(html);


                    valueInputScan = '';
                    $(this).val('');
                } else {
                    valueInputScan += e.key;
                }
            });

            // Add Surat Jalan
            $('body').on('click', '.btn-add-surat', function() {
                idStreamAll++;
                let html = `<div class="card mb-3 mb-4 bg-light border-0" id="cardSuratJalan${idStreamAll}">
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
                                        <label for="noPo" class="form-label">No PO <span class="text-danger">*</span></label>
                                        <input type="text" name="po[]" class="form-control" autocomplete="off" id="noPo" placeholder="Masukkan No PO" required>
                                        <input type="hidden" name="nomer_po[]" value="${idStreamAll}">
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
                                    <div class="d-flex flex-column">
                                        <label for="scanBarcode" class="form-label">Kode Barang</label>
                                        <p>Hasil : <span id="hasilContoh${idStreamAll}"></span></p>
                                        <input type="text" id="inputScan${idStreamAll}" class="btn btn-success input-scan" data-id="${idStreamAll}" autocomplete="off" placeholder="Scan Data">
                                        <div class="text-center" id="scanDiv${idStreamAll}">
                                            <table class="table align-middle mb-0 mt-3">
                                                <thead>
                                                    <tr>
                                                        <th class="text-start" width="55%">Barang</th>
                                                        <th class="text-start" width="35%">Berat</th>
                                                        <th class="text-center" width="10%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody${idStreamAll}">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                $('#sectionSuratJalan').append(html);
                $('.select2').select2();
            });

            let alertCustom = (status, title, text) => {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: status,
                    showConfirmButton: true
                });
            }

            // Button back
            $('.back-page').on('click', function() {
                let url = `{{ route('home') }}`;
                window.location.href = url;
            });
        });
    </script>
@endpush