<label for="" class="form-label fw-medium">Perbandingan Data</label>
<div class="d-flex">
    <select class="form-select select2" id="perbandingan-value">
        <option selected value="" disabled>Pilih Perbandingan</option>
        @foreach ($kendaraan as $item)
            <option value="{{ $item->id }}">{{ $item->no_kendaraan }} - {{ getJumlahSurat($item->id) }} Surat - {{ getJumlahBerat($item->id) }}</option>
        @endforeach
    </select>
    <button type="button" class="btn btn-primary btn-cari ms-3" title="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
</div>