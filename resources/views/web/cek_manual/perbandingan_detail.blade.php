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