<?php

namespace App\Models\StokKontrol;

use App\Models\BaseModel;

use DB;

class StokKarti extends BaseModel
{
    public $table = 'StokKarti';

    protected $fillable = [
        'id', 'Kod', 'Isim', 'Isim2', 'Aktif', 'Hayalet', 'BarkodNo', 'ResimNo', 'Genislik', 'Kalinlik', 'Boy', 'DisCap', 'IcCap', 'Birim', 'SatinalmaSiparisBirim', 'SatinalmaKabulBirim',
        'RaporBirimi', 'GenelGirisDepoKod', 'OzelGirisDepoKod', 'EskiGirisDepoKod', 'GeciciGirisDepoKod', 'GenelGirisDepoKod2', 'OzelGirisDepoKod2', 'EskiGirisDepoKod2', 'GeciciGirisDepoKod2',
        'GenelCikisDepoKod', 'OzelCikisDepoKod', 'EskiCikisDepoKod', 'GeciciCikisDepoKod', 'ParcaBilgisi', 'TeminTuru', 'SatinalmaTeminTuru', 'MalzemeGrubu1', 'MalzemeGrubu2', 'MalzemeGrubu3',
        'MalzemeGrubu4', 'MalzemeGrubu5', 'MamulAnaGrubu', 'MamulAltGrubu', 'FireStokKod', 'EmniyetStogu', 'SiparisSeviye', 'KdvOrani', 'SatisKdvOrani', 'DepoAdresi', 'Aciklama',
        'MailStokSeviyesi', 'imalatTedSure', 'MinStokSeviyesi', 'MaxStokSeviyesi', 'OptSipMiktari', 'MinSipMiktari', 'SipKatlari', 'IskartaOrani', 'OzelKod1', 'OzelKod2', 'OzelKod3',
        'AltStokKod1', 'AltStokKod2', 'AltStokKod3', 'BirimFiyatTarihi', 'BirimFiyati', 'DovizKod', 'BirimFiyati2', 'DovizKod2', 'BirimFiyati3', 'DovizKod3', 'BirimFiyati4', 'DovizKod4',
        'BirimFiyati5', 'DovizKod5', 'EkFiyat1', 'EkFiyat2', 'EkFiyat3', 'MaliyetGrubu', 'Iskonto1', 'Iskonto2', 'Iskonto3', 'MpsKalemi', 'MontajOnceligi', 'MontajOnceligiGiris', 'KarOrani',
        'SatisYap', 'SeriNoZorunlu', 'PartiNoZorunlu', 'SonKulTarihiZorunlu', 'Ozellik1_Zorunlu', 'Ozellik2_Zorunlu', 'Ozellik3_Zorunlu', 'Ozellik4_Zorunlu', 'Ozellik5_Zorunlu',
        'Ozellik6_Zorunlu', 'KaliteZorunlu', 'AmbalajTakibiZorunlu', 'KisaTanim', 'VariantOzelligi', 'Yukseklik', 'Yogunluk', 'AgirlikBrut', 'AgirlikNet', 'AgacBazMiktar', 'StandartKod',
        'KutuIciAdet', 'KoliIciAdet', 'PaletIciAdet', 'MusteriUrunKodu', 'ImalatSiparisBirim', 'Denye', 'FaturaStokIsim', 'Birim2Zorunlu', 'Birim2', 'SatisBirim', 'GenelSatisDepoKod',
        'OzelSatisDepoKod', 'EskiSatisDepoKod', 'GeciciSatisDepoKod', 'UretimKatsayisi', 'HurdaStokKod', 'Marka', 'KullaniciNo', 'KayitTarihi', 'KutuEn', 'KutuBoy', 'KutuYukseklik', 'KoliEn',
        'KoliBoy', 'KoliYukseklik', 'PaletEn', 'PaletBoy', 'PaletYukseklik', 'StockNameEN', 'ShopNameGR', 'MRPdeYenSipSevGore', 'MRPdeMinMaksGore', 'GTipNo', 'MPStekiStokKod',
        'BilesenIrsaliyeli', 'Hacim', 'FireEkYuzde', 'EKFiyatYuzde', 'KutuBarkodNo', 'KutuAgirlikKG', 'KutuToleransYuzde', 'KutuToleransKG', 'KoliBarkodNo', 'KoliAgirlikKG', 'KoliToleransYuzde',
        'KoliToleransKG', 'StockNameArapca', 'Sirket_Kod', 'CokluEtiketBasabilir', 'Kod2', 'YuzeyAlaniM2', 'SatinalmaPktlmOkutulsun', 'NetKesimBoy', 'NetKesimEn', 'KabaKesimBoy', 'KabaKesimEn',
        'AdresKapasitesi', 'AnaGrup', 'AltGrup', 'DetayGrup', 'SiparisteBilesenleriniKaydet', 'SevkiyattaUrunAgaciCekiListKaydet', 'OzelKod4', 'KoliAmbalajKG', 'EskiKod', 'BarkodZorunlu',
        'KoliIcAdetKadarSatisZorunlu', 'KnfgSistemi', 'KnfgSirket_Kod', 'KnfgMamul', 'KnfgHerZamanYeniKodUret', 'DinamikPaketleme', 'MaxSiparisMiktari', 'Prototip', 'DegTarihi', 'DegKullNo', 'DiibId', 'Endirekt',
    ];
    protected $visible = [
    ];

    public function ozel_birimler()
    {
        // Sirket_Kod eklenecek?
        return $this->hasMany(StokKartiOzelBirimleri::class, 'StokKod', 'Kod');
    }

    public function variantlar()
    {
        // Sirket_Kod eklenecek?
        return $this->hasMany(Variant::class, 'StokKod', 'Kod');
    }
}
