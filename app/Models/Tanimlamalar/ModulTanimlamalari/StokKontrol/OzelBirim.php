<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;

use App\Models\BaseModel;

use DB;

class OzelBirim extends BaseModel
{
    protected $table = 'StokKartiOzelBirimleri';

    protected $fillable = [
        'id', 'StokKod', 'Birim', 'Esdegeri', 'Kaci', 'Sirket_Kod'
    ];
}
