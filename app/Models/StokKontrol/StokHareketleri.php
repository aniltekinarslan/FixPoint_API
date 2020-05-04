<?php

namespace App\Models\StokKontrol;

use App\Models\BaseModel;

class StokHareketleri extends BaseModel
{
    protected $table = 'StokHareketleri';
    protected $appends = ['stok_tanim', 'variant_tanim'];

    protected $fillable = [
        'id', 'StokKod', 'StokTanimi', 'VariantKod', 'VariantTanimi', 'GirisMiktari', 'CikisMiktari', 'StokBirim', 'KabulGirisMiktari', 'KabulCikisMiktari', 'Sirket_Kod',
        'GirisYeri', 'GirisTuru', 'CikisYeri', 'CikisTuru', 'EvrakNo', 'KaynakProgram', 'MusteriSiparisNo', 'SatinalmaSipId', 'ImalatSiparisid', 'KullaniciNo', 'KayitTarihi',
        'KullaniciNo_1', 'KayitTarihi_1', 'Tarih', 'Aciklama' // Müşteri Firma Kodu  cekilecek,    firma adı  cekilecek

    ];
    protected $visible = [
        'id', 'StokKod', 'StokTanimi', 'VariantKod', 'VariantTanimi', 'GirisMiktari', 'CikisMiktari', 'StokBirim', 'KabulGirisMiktari', 'KabulCikisMiktari', 'Sirket_Kod',
        'GirisYeri', 'GirisTuru', 'CikisYeri', 'CikisTuru', 'EvrakNo', 'KaynakProgram', 'MusteriSiparisNo', 'SatinalmaSipId', 'ImalatSiparisid', 'KullaniciNo', 'KayitTarihi',
        'KullaniciNo_1', 'KayitTarihi_1', 'Tarih', 'Aciklama',
        'stok_tanim', 'variant_tanim'
    ];

    public function getStokTanimAttribute()
    {
        if ($this->StokTanimi != '')
            return $this->StokTanimi;

        $stok = StokKarti::where('Kod', $this->StokKod)->where('Sirket_Kod', $this->Sirket_Kod)->first();
        if ($stok == null)
            return '';

        return $stok->Isim;
    }


    public function getVariantTanimAttribute()
    {
        if ($this->VariantTanimi != '')
            return $this->VariantTanimi;

        $variant = Variant::where('VariantKod', $this->VariantKod)->where('Sirket_Kod', $this->Sirket_Kod)->first();
        if ($variant == null)
            return '';

        return $variant->Tanim;
    }
}
