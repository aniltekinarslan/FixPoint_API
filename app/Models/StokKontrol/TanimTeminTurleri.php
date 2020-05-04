<?php

namespace App\Models\StokKontrol;

use App\Models\BaseModel;

use DB;

class TanimTeminTurleri extends BaseModel
{
    public $table = 'TanimTeminTurleri';

    protected $fillable = [
        'id', 'aciklama',
    ];
    protected $visible = [
    ];
}
