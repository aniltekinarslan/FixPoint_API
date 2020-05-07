<?php

namespace App\Models\Satis;

use App\Models\BaseModel;

use DB;

class TeklifSatirDetay extends BaseModel
{
    public $table = 'SatisTeklifSatirDetay';

    protected $fillable = [
        'id',
        'Sirket_Kod',
        'Masterid',
        'Satirid',
        'SevkFirmaAdi',
        'SevkAdresi',
        'SevkUlke',
        'SevkIl',
        'SevkIlce'
    ];

    public function satir()
    {
        return $this->belongsTo(TeklifSatir::class, 'id', 'Masterid');
    }
}
