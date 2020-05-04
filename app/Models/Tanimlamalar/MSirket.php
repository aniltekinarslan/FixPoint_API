<?php

namespace App\Models\Tanimlamalar;

use App\Models\BaseModel;

use DB;

class MSirket extends BaseModel
{
    public $table = 'm_Sirket';

    protected $fillable = [
        'id',
        'SirketKod',
        'SirketAdi',
        'KisaAdi',
        'BaslTarih',
        'BitisTarih',
        'Tel1',
        'Tel2',
        'Fax',
        'Adres1',
        'Adres2',
        'Adres3',
        'EPosta',
        'InternetAdresi',
        'VergiDairesi',
        'VergiNo',
        'SicilKod',
        'KurulusTarihi',
        'Aktif',
        'KullaniciNo',
        'KayitTarihi',
        'SistemDovizKod',
        'DovizliCalis',
        'DovizKod',
        'FaturaDetayindaDoviz',
        'KarMerkezliCalis',
        'KarMerkeziUstHesabaGirebilsin',
        'KarMerkeziIleGelirGiderTakibi',
        'AnalizKartlariCalisma',
        'KapanisAcilmasi',
        'AcilisAciklamasi',
        'RaporYolu',
        'CalismaGunu',
        'Kdv',
        'SubeKodu',
        'SubeAdi',
        'BinaNo',
        'PostaKodu',
        'Ilce',
        'IL',
        'Ulke',
        'TICARETSICILNO',
        'MERSISNO',
        'MailHost',
        'MailPort',
        'MailUserName',
        'MailPassword',
        'm_AnaParaBirimi',
        'Guncelle',
        'VergiDairesiKodu',
        'YmmVergiNo',
        'YmmAdi',
        'YmmSoyadi',
        'YmmTCKimlikNo',
        'YmmEposta',
        'YmmTel',
        'MailSenderUserName',
        'WebteGoster',
        'MailTLSKodu',
        'IleriTarihliIslemYapilabilir',
        'FaturaFiFoBaslamaTarihi',
    ];


}
