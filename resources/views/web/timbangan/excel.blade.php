{{-- @foreach ($suratJalan as $item) --}}
    <table>
        <tr>
            <td colspan="13"></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" style="height: 40px;"></td>
            <td colspan="2" style="text-align: left; vertical-align: bottom;">No Surat Jalan</td>
            <td colspan="3" style="text-align: left; vertical-align: bottom;">: {{ Str::upper($suratJalan->no_surat) }}</td>
            <td></td>
            <td colspan="2" style="text-align: left; vertical-align: bottom;">Tgl</td>
            <td colspan="3" style="text-align: left; vertical-align: bottom;">: {{ getTanggalIndo($suratJalan->created_at->format('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 40px; text-align: left; vertical-align: top;">Customer</td>
            <td colspan="3" style="text-align: left; vertical-align: top;">: {{ Str::upper($suratJalan->customers->name) }}</td>
            <td></td>
            <td colspan="2" style="text-align: left; vertical-align: top;">No PO</td>
            <td colspan="3" style="text-align: left; vertical-align: top;">: {{ Str::upper($suratJalan->no_po) }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th rowspan="2" width="4%" style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid black;">NO</th>
                <th rowspan="2" width="16%" style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid black;">NAMA BARANG</th>
                <th colspan="10" width="70%" style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid black;">JUMLAH</th>
                <th rowspan="2" width="10%" style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid black;">TOTAL</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 10; $i++)
                    <th width="6%" style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid black;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @forelse (getTimbanganGroup($suratJalan->id) as $data)
                @php
                    $dataTimbanganList = getTimbanganList($suratJalan->id, $data->kode_barang);
                    $jumlahTimbanganList = $dataTimbanganList->count(); 
                    $totalLoopTimbanganList = ceil($jumlahTimbanganList / 10) + 1;
                @endphp

                @for ($j = 1; $j <= $totalLoopTimbanganList; $j++)
                    @if ($j == 1)
                        <tr>
                            <td style="text-align: center; vertical-align: middle; border: 1px solid black;">{{ $loop->iteration }}</td>
                            <td style="text-align: center; vertical-align: middle; border: 1px solid black;">{{ $data->barangs->name ?? '-' }}</td>
                            @foreach ($dataTimbanganList as $key => $list)
                                <td style="text-align: right; vertical-align: middle; border: 1px solid black;">{{ converDecimal($list->berat_barang) }}</td>

                                @php $dataTimbanganList->forget($key)@endphp
                                @if ($loop->iteration == 10) @break @endif
                                @if($loop->last)
                                    @for ($k = $loop->iteration + 1; $k <= 10; $k++)
                                        <td style="border: 1px solid black;"></td>
                                    @endfor
                                @endif
                            @endforeach
                            <td style="text-align: right; vertical-align: middle; border: 1px solid black;">{{ getJumlahBeratLetterBarang($suratJalan->id, $list->kode_barang, false) }}</td>
                        </tr>
                    @elseif ($j == $totalLoopTimbanganList)
                        <tr>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            @for ($m = 1; $m <= 10; $m++)
                                <td style="border: 1px solid black;"></td>
                            @endfor
                            <td style="text-align: right; vertical-align: middle; border: 1px solid black;">-</td>
                        </tr>
                    @else
                        <tr>
                            <td style="border: 1px solid black;"></td>
                            <td style="border: 1px solid black;"></td>
                            @foreach ($dataTimbanganList as $key => $list)
                                <td style="text-align: right; vertical-align: middle; border: 1px solid black;">{{ converDecimal($list->berat_barang) }}</td>

                                @php $dataTimbanganList->forget($key)@endphp
                                @if ($loop->iteration == 10) @break @endif
                                @if($loop->last)
                                    @for ($n = $loop->iteration + 1; $n <= 10; $n++)
                                        <td style="border: 1px solid black;"></td>
                                    @endfor
                                @endif
                            @endforeach
                            <td style="border: 1px solid black;"></td>
                        </tr>
                    @endif
                @endfor

                @if ($loop->last)
                    <tr>
                        <td colspan="12" style="text-align: left; font-weight: bold; vertical-align: middle; border: 1px solid black;">TOTAL</td>
                        <td style="text-align: right; font-weight: bold; vertical-align: middle; border: 1px solid black;">{{ getJumlahBeratLetter($suratJalan->id, false) }}</td>
                    </tr>
                    <tr>
                        <td colspan="13"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center; vertical-align: middle;">Yang Menyerahkan</td>
                        <td colspan="7"></td>
                        <td colspan="3" style="text-align: center; vertical-align: middle;">Yang Menerima</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="height: 60px; text-align: right; vertical-align: middle;">(</td>
                        <td style="text-align: center; vertical-align: middle;"></td>
                        <td style="text-align: left; vertical-align: middle;">)</td>
                        <td colspan="6"></td>
                        <td style="text-align: left; vertical-align: middle;">(</td>
                        <td style="text-align: center; vertical-align: middle;"></td>
                        <td style="text-align: right; vertical-align: middle;">)</td>
                        <td></td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="13" style="text-align: center; vertical-align: middle; border: 1px solid black;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
{{-- @endforeach --}}