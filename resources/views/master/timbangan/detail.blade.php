@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-4 border-0">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-light back-page bg-transparent border-0"><i class="fa-solid fa-arrow-left"></i></button>
                        <h5 class="fw-bold mb-0">Detail Timbangan</h5>
                        <button class="btn btn-light bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="{{ route('timbangan.destroy', $transport->id) }}" method="POST">
                                    @method("DELETE")
                                    @csrf

                                    <a class="dropdown-item delete-data" type="submit">Hapus</a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-4 border-0">
                <div class="card-body p-0">
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="mb-1 text-black-50">Kendaraan</span>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0 fw-bold">{{ $transport->no_kendaraan }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="mb-1 text-black-50">Tanggal</span>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0 fw-bold">{{ $transport->created_at->format('Y-m-d'); }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="mb-1 text-black-50">Waktu</span>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0 fw-bold">{{ $transport->created_at->format('H:i'); }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="mb-1 text-black-50">Total Surat</span>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0 fw-bold">{{ getJumlahSurat($transport->id) }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="mb-1 text-black-50">Total Berat</span>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0 fw-bold">{{ getJumlahBerat($transport->id) }}</p>
                        </div>
                    </div>
                    <div class="row">
                        @forelse ($suratJalan as $item)
                            <div class="col-12">
                                <div class="card mb-3 bg-light border-0">
                                    <div class="card-header border-0 bg-secondary-subtle">
                                        <span class="mb-0 text-black-50">{{ $loop->iteration }}. Surat Jalan</span>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 text-black-50">Nomer Surat Jalan</p>
                                        <p class="mb-0 fw-bold">{{ $item->no_surat }}</p>
                                        <p class="mb-0 mt-3 text-black-50">Pelanggan</p>
                                        <p class="mb-0 fw-bold">{{ $item->customers->name }}</p>
                                        <p class="mb-0 mt-3 text-black-50">Alamat</p>
                                        <p class="mb-0 fw-bold">{{ $item->customers->address }}</p>
                                        @if ($item->timbangans)
                                            <p class="mb-1 mt-3 text-black-50">Data Barang</p>
                                            <table class="table table-borderless align-middle mb-0 table-sm">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5%">No</th>
                                                        <th class="text-start" width="40%">Barang</th>
                                                        <th class="text-center" width="30%">Berat</th>
                                                        <th class="text-center" width="30%">Total</th>
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
                        @empty
                            <div class="w-100 text-center">
                                <div class="alert alert-danger" role="alert">
                                    Tidak ada data.
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-5 d-flex flex-column">
                            <button class="btn btn-light back-page" type="button"><i class="fa-solid fa-arrow-left"></i> &nbsp; Kembali </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $(`td.active`).parent().addClass('border-top border-secondary-subtle');

            // Button back
            $('.back-page').on('click', function() {
                let url = `{{ route('home') }}`;
                window.location.href = url;
            });

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