<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;
use App\Models\BaseModel;

use DB;

class TeminTuru extends BaseModel
{
    protected $table = 'TanimTeminTurleri';

    protected $fillable = [
        'id', 'aciklama'
    ];

    protected $visible = [
    ];
}
