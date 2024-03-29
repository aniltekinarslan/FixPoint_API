USE [HarmonyERTAS]
GO
/****** Object:  StoredProcedure [dbo].[Web_StokKartiKontrol]    Script Date: 4.5.2020 13:13:17 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[Web_StokKartiKontrol]
	@spSirket_Kod varchar(10),
	@spStok_Kod varchar(25)
--WITH ENCRYPTION
AS
BEGIN

Set nocount on
Set DateFormat dmy
Set Language Turkish

  IF EXISTS (SELECT * FROM StokHareketleri 
  WHERE StokHareketleri.StokKod = @spStok_Kod and StokHareketleri.Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Stok Hareketlerinde Kullanılıyor' as 'message'
		RETURN
  END

  IF EXISTS (SELECT * FROM SiparisSatirlari 
			WHERE SiparisSatirlari.StokKod = @spStok_Kod and SiparisSatirlari.Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Müşteri Siparişlerinde kullanılıyor.' as 'message'
		RETURN
  END
  
  IF EXISTS (SELECT * FROM TeklifSatirlari 
			WHERE TeklifSatirlari.StokKod = @spStok_Kod and TeklifSatirlari.Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Müşteri Tekliflerinde kullanılıyor.' as 'message'
		RETURN
  END
	
  IF (SELECT count(*) FROM Variantlar 
			WHERE Variantlar.StokKod = @spStok_Kod and Variantlar.Sirket_Kod = @spSirket_Kod) > 1
  BEGIN
		select 0 as 'status', 'Stoğun Variantı var.' as 'message'
		RETURN
  END
	
  IF EXISTS (SELECT * FROM SatinalmaSiparisleri 
			WHERE SatinalmaSiparisleri.StokKodu = @spStok_Kod and SatinalmaSiparisleri.Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Satınalma Siparişlerinde kullanılıyor.' as 'message'
		RETURN
  END
  
  IF EXISTS (SELECT * FROM SatinalmaTeklifleri
			WHERE SatinalmaTeklifleri.StokKodu = @spStok_Kod and SatinalmaTeklifleri.Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Satınalma Tekliflerinde kullanılıyor.' as 'message'
		RETURN
  END
	
  IF EXISTS (SELECT * FROM SaticiMalzemeIliskisi 
			WHERE SaticiMalzemeIliskisi.StokKodu = @spStok_Kod and SaticiMalzemeIliskisi.Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Teradikçi Malzeme İlişkisi tablosunda kullanılıyor. ' as 'message'
		RETURN
  END 
  
  IF EXISTS (SELECT * FROM SevkEmriSatirlari 
			WHERE SevkEmriSatirlari.StokKod = @spStok_Kod and SevkEmriSatirlari.Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Sevk Emrinde kullanılıyor.' as 'message'
		RETURN
  END 
  
  IF EXISTS (SELECT * FROM SevkSatirlari 
			WHERE SevkSatirlari.StokKod = @spStok_Kod and SevkSatirlari.Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Sevkiyat İrsaliyesinde kullanılıyor.' as 'message'
		RETURN
  END 
  
  IF EXISTS (SELECT * FROM m_FaturaDetay 
			WHERE m_FaturaDetay.HesapKod = @spStok_Kod and m_FaturaDetay.SirketKod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Fatura Satırlarında kullanılıyor.' as 'message'
		RETURN
  END 
	
  IF (SELECT count(*) FROM MamulAgaci 
			WHERE (MamulAgaci.Ustkod = @spStok_Kod OR MamulAgaci.Altkod = @spStok_Kod) and MamulAgaci.Sirket_Kod = @spSirket_Kod) > 1
  BEGIN
		select 0 as 'status', 'Ürün ağacında kullanılıyor.' as 'message'
		RETURN
  END
	
	IF (SELECT count(*) FROM KonfigAgac 
			WHERE (KonfigAgac.Ustkod = @spStok_Kod OR KonfigAgac.Altkod = @spStok_Kod) and KonfigAgac.Sirket_Kod = @spSirket_Kod) > 1
  BEGIN
		select 0 as 'status', 'Konfigürasyon ağacında kullanılıyor.' as 'message'
		RETURN
  END
	
	
  select 1 as 'status', 'Kullanılmıyor' as 'message' 
  RETURN
end