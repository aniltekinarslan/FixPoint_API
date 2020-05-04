<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;
use App\Models\BaseModel;

use DB;

class Depo extends BaseModel
{
    protected $table = 'm_Depolar';

    protected $fillable = [
        'id', 'DepoKod', 'DepoAdi', 'DepoYeri', 'BakiyedeVarYok', 'Sirket_Kod', 'EksiBakiyeKontroluYap'
    ];
    protected $visible = [
    ];

    public function getIsUsedAttribute()
    {
        $response = DB::select("EXEC Web_DepoKontrol @spSirket_Kod = ?, @spDepoKod = ?", [auth('api')->user()->Sirket_Kod, $this->DepoKod])[0];
        return $response->status == 0;
    }
}
