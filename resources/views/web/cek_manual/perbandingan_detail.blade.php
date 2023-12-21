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
                <h3 class="fw-bold mb-0">{{ getJumlahBerat($transport->id) }} KG</h3>
                <span>Total Berat</span>
            </div>
        </div>
    </div>
</div>

@forelse ($suratJalan as $item)
<div class="card div-surat">
    <div class="card-body">
        <h3 class="fw-bold mb-0 text-center">Surat Jalan</h3>
        <p class="text-center text-black-50 mb-4">Nomor : {{ $item->no_surat }}</p>

        <table class="table table-borderless table-sm align-middle">
            <tr>
                <td class="fw-bold ps-0" width="35%">Nomor Kendaraan</td>
                <td>: <span class="text-black-50 mb-0">{{ $transport->no_kendaraan }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold ps-0" width="35%">Tanggal</td>
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
@empty
<div class="w-100 text-center">
    <div class="alert alert-danger" role="alert">
        Tidak ada data.
    </div>
</div>
@endforelse                