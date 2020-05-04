<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;
use App\Models\BaseModel;

class CikisTuru extends BaseModel
{
    protected $table = 'TanimCikisTurleri';

    protected $fillable = [
        'id', 'aciklama'
    ];
    protected $visible = [
        'id', 'aciklama'
    ];
}
