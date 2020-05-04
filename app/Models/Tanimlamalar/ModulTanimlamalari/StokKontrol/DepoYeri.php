<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;
use App\Models\BaseModel;

class DepoYeri extends BaseModel
{
    protected $table = 'TanimDepolar';

    protected $fillable = [
        'id', 'aciklama'
    ];
    protected $visible = [
        'id', 'aciklama'
    ];
}
