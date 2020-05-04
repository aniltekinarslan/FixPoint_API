<?php

namespace App\Models\Muhasebe;
use App\Models\BaseModel;

use DB;

class Doviz extends BaseModel
{
    protected $table = 'm_Doviz';

    protected $fillable = [
        'id',
        'SirketKod',
        'DovizKod',
        'Simge',
        'DovizAciklama',
        'KullaniciNo',
        'KayitTarihi',
        'Sira'
    ];
}
