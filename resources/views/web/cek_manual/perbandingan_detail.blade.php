<div class="row mb-3">
    <div class="col-md-6">
        <div class="card bg-secondary-subtle text-secondary border-0">
            <div class="card-body">
                <h3 class="fw-bold mb-4"><i class="fa-solid fa-weight-scale"></i></h3>
                <h3 class="fw-bold mb-0">{{ getJumlahSurat($transport->id) }} Surat</h3>
                <span>Total Surat</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-primary-subtle text-primary border-0">
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