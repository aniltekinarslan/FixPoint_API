<?php

namespace App\Models\Muhasebe;

use App\Models\BaseModel;

use DB;
use App\Models\StokKontrol\StokKarti;
use App\Models\StokKontrol\Variant;

class MCariOzelFiyat extends BaseModel
{
    public $table = 'MusteriStoklari';
    protected $with = ['stok', 'variant'];

    protected $fillable = [
        'id',
        'CariKod',
        'StokKod',
        'VariantKod',
        'VariantKod2',
        'CariStokKod',
        'CariStokAdi',
        'Aciklama',
        'StkIsk1',
        'StkIsk2',
        'StkIsk3',
        'BrFiyat',
        'DovizKod',
        'FiyatKodu',
        'TumVrtlardaGecerli',
        'KayitTarihi',
        'KullNo',
        'DegTarihi',
        'DegKullNo',
        'Sirket_Kod',
        'BarkodDizaynAdi'
    ];

    protected $visible = [
    ];

    public function stok()
    {
        return $this->hasOne(StokKarti::class, 'Kod', 'StokKod');
    }

    public function variant()
    {
        return $this->hasOne(Variant::class, 'VariantKod', 'VariantKod');
    }
}
