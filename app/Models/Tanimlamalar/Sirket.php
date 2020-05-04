<?php

namespace App\Models\Tanimlamalar;

use App\Models\BaseModel;

use DB;

class Sirket extends BaseModel
{
    public $table = 'Sirket';

    protected $fillable = [
        'id',
        'Sirket_Kod',
        'Sirket_Adi',
        'Kisa_Adi',
        'Aktif',
        'FiFoBaslamaTarihi',
        'KayitTarihi',
        'AnaParaBirimi',
        'MusteriCariKodAlgoritması',
        'TedarikciCariKodAlgoritması',
        'MusteriCariKodSayanUzunlugu',
    ];
}
