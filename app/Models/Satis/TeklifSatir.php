<?php

namespace App\Models\Satis;

use App\Models\BaseModel;

use DB;

class TeklifSatir extends BaseModel
{
    public $table = 'TeklifSatirlari';

    protected $with = ['detaylar'];

    protected $fillable = [
        'id',
        'Masterid',
        'StokKod',
        'VariantKod',
        'Miktar',
        'Fiyat',
        'FiyatKodu',
        'PaketTipi',
        'Kapan',
        'Iptal',
        'Iskonto',
        'Iskonto1',
        'Iskonto2',
        'Kdv',
        'IskTutari',
        'KdvTutari',
        'Tutar',
        'Stoktan',
        'Uretimden',
        'SevkTarihi',
        'TeslimTarihi',
        'SiparisTipi',
        'Birim',
        'FiyatTRL',
        'DovizKod',
        'DovizKuru',
        'PartiNo',
        'SonKullTarihi',
        'Ozellik_1',
        'Ozellik_2',
        'Ozellik_3',
        'Ozellik_4',
        'Ozellik_5',
        'Ozellik_6',
        'MusStokKod',
        'MusStokAdi',
        'Aciklama',
        'SatisBirimi',
        'BirimAgirlik',
        'VariantAdi',
        'Aciklama1',
        'Aciklama2',
        'Aciklama3',
        'Aciklama4',
        'OpMaliyetTipi',
        'Genislik',
        'Boy',
        'Kalinlik',
        'DisCap',
        'IcCap',
        'Hacim',
        'StockNameEN',
        'ShopNameGR',
        'KapIciMiktar',
        'KapMiktari',
        'PaletMiktari',
        'SandikMiktari',
        'KullaniciNo',
        'KayitTarihi',
        'TMSid',
        'TakimKodu',
        'ModulKodu',
        'TMStokKod',
        'TMVariantKod',
        'StokTipi',
        'TM_Miktar',
        'TakimMiktari',
        'TakimAdi',
        'Sirket_Kod',
        'ResimNo',
        'MaliyetFiyKodu1',
        'MaliyetFiyat1',
        'MaliyetDovKod1',
        'MaliyetIsk1_1',
        'MaliyetIsk1_2',
        'MaliyetIsk1_3',
        'MaliyetIskluFiyat1',
        'MaliyetFiyKodu2',
        'MaliyetFiyat2',
        'MaliyetDovKod2',
        'MaliyetIsk2_1',
        'MaliyetIsk2_2',
        'MaliyetIsk2_3',
        'MaliyetIskluFiyat2',
        'm_SirketKod',
        'DegisiklikTarihi',
        'ComputerUser1',
        'ComputerUser',
        'ComputerName1',
        'ComputerName',
        'DegKullNo',
        'StokAd',
        'RefSatId',
        'Mahal',
        'Aktar',
        'Aktarildi',
        'DepoKodu',
        'SatirNo',
        'FaturaStokIsim',
        'NetBirimFiyat',
        'ListeFiyati',
        'Bedelsiz',
        'VResimNo'
    ];

    public function teklif()
    {
        return $this->belongsTo(Teklif::class, 'id', 'Masterid');
    }

    public function detaylar()
    {
        return $this->hasMany(TeklifSatirDetay::class, 'Masterid', 'id');
    }
}
