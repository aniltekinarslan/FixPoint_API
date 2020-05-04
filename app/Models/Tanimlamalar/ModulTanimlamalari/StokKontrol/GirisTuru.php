<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;
use App\Models\BaseModel;

class GirisTuru extends BaseModel
{
    protected $table = 'TanimGirisTurleri';

    protected $fillable = [
        'id', 'aciklama', 'transfer', 'hareket'
    ];
    protected $visible = [
        'id', 'aciklama', 'transfer', 'hareket'
    ];
}
