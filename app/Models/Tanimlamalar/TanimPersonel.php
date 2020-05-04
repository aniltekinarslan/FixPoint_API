<?php

namespace App\Models\Tanimlamalar;

use App\Models\BaseModel;

use DB;

class TanimPersonel extends BaseModel
{
    public $table = 'TanimPersonel';

    protected $fillable = [
        'id', 'SicilNo', 'Gorevi', 'Isim', 'Aktif', 'Sifre', 'EPosta', 'KisaKodu', 'CalistigiBolum', 'Sirket_Kod', 'TcKimlikNo', 'SonSifreDegisikligi',
    ];
}
