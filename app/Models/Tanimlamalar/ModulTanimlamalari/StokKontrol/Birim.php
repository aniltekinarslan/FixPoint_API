<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;
use App\Models\BaseModel;

use DB;

class Birim extends BaseModel
{
    protected $table = 'TanimBirimler';

    protected $fillable = [
        'id', 'aciklama'
    ];
    protected $visible = [
        'id', 'aciklama',
        'is_used'
    ];

    public function getIsUsedAttribute()
    {
        $response = DB::select("EXEC Web_BirimKontrol @spBirim = ?", [$this->aciklama])[0];
        return $response->status == 0;
    }
}
