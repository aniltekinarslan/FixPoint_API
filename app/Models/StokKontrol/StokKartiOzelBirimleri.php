<?php

namespace App\Models\StokKontrol;

use App\Models\BaseModel;

use DB;

class StokKartiOzelBirimleri extends BaseModel
{
    public $table = 'StokKartiOzelBirimleri';

    protected $fillable = [
        'id', 'StokKod', 'Birim', 'Esdegeri', 'Kaci', 'Sirket_Kod',
    ];

    protected $visible = [
    ];

    public function formul($birim)
    {
        return $this->Kaci . ' ' . $birim . ' = '. $this->Esdegeri . ' '. $this->Birim;
    }
}
