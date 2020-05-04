<?php

namespace App\Models\Muhasebe;

use App\Models\BaseModel;

use DB;

class HesapPlani extends BaseModel
{
    public $table = 'm_HesapPlani';

    protected $fillable = [
        'id',
        'SirketKod',
        'HesapKod',
        'HesapAdi1',
        'HesapAdi2',
        'UstHesapKod',
        'HesapTipi',
        'CalismaSekli',
        'OzelKod1',
        'OzelKod2',
        'EnfHesapTipi',
        'EnfFarkHesapKod',
        'KarMerkezi',
        'DagitimKodu',
        'DovizKod',
        'SonOnayTarihi',
        'AnalizKoduIleCalis',
        'KonsolidiyeDahil',
        'KarMerkeziIleCalis',
        'EnflasyonaGoreArtis',
        'EnfFarkFisleriniYokSa',
        'Aktif',
        'TorbaHesap',
        'DagilimTablosuKalemi',
        'KullaniciNo',
        'KayitTarihi',
        'KurFarkiHesapla',
        'HesapTuru',
        'Kaynakid',
        'KaynakProg',
        'UstTanim',
        'AltTanim',
        'RaporSiraNo'
    ];
}
