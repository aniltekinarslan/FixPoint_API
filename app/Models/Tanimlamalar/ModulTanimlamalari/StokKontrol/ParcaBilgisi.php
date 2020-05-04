<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;
use App\Models\BaseModel;

use DB;

class ParcaBilgisi extends BaseModel
{
    protected $table = 'TanimParcaBilgileri';

    protected $fillable = [
        'id', 'aciklama'
    ];

    protected $visible = [
    ];
}
