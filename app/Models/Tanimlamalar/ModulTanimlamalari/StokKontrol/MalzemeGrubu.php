<?php

namespace App\Models\Tanimlamalar\ModulTanimlamalari\StokKontrol;
use App\Models\BaseModel;

use DB;

class MalzemeGrubu extends BaseModel
{
    protected $table = 'TanimMlzGruplari';

    protected $fillable = [
        'id', 'GrupKod', 'Aciklama','Grup1' ,'Grup2' ,'Grup3' ,'Grup4' ,'Grup5', 'Grup2UstKod', 'Grup3UstKod', 'Grup4UstKod', 'Grup5UstKod', 'Sirket_Kod'
    ];
    protected $visible = [
        'id', 'GrupKod', 'Aciklama','Grup1' ,'Grup2' ,'Grup3' ,'Grup4' ,'Grup5', 'Grup2UstKod', 'Grup3UstKod', 'Grup4UstKod', 'Grup5UstKod', 'Sirket_Kod',
        'is_used'
    ];

    public function getIsUsedAttribute()
    {
        $response = DB::select("EXEC Web_MalzemeGrubuKontrol @spSirket_Kod = ?, @spMalzemeGrb = ?", [auth('api')->user()->Sirket_Kod, $this->GrupKod])[0];
        return $response->status == 0;
    }
}
