<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Timbangan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function barangs(): HasOne
    {
        return $this->hasOne(Barang::class, 'kode', 'kode_barang');
    }
}
