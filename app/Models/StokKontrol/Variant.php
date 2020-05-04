<?php

namespace App\Models\StokKontrol;
use App\Models\BaseModel;

class Variant extends BaseModel
{
    protected $table = 'Variantlar';

    protected $fillable = [
        'id', 'VariantKod', 'StokKod', 'Tanim', 'vResimNo', 'Genislik', 'Boy', 'Yukseklik', 'Aktif', 'StoktakiKadarSipGir', 'FaturaStokIsim', 'SiparisGirilebilir'
    ];
    protected $visible = [
        'id', 'VariantKod', 'StokKod', 'Tanim', 'vResimNo', 'Genislik', 'Boy', 'Yukseklik', 'Aktif', 'StoktakiKadarSipGir', 'FaturaStokIsim', 'SiparisGirilebilir',
        'stok'
    ];

    public function stok()
    {
        return $this->hasOne(StokKarti::class, 'Kod', 'StokKod');
    }
}
