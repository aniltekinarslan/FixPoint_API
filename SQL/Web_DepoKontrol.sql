USE [HarmonyERTAS]
GO
/****** Object:  StoredProcedure [dbo].[Web_DepoKontrol]    Script Date: 4.5.2020 13:12:56 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[Web_DepoKontrol]
	@spSirket_Kod varchar(10),
	@spDepoKod varchar(10)
--WITH ENCRYPTION
AS
BEGIN

Set nocount on
Set DateFormat dmy
Set Language Turkish

  IF EXISTS (SELECT * FROM StokKarti 
		WHERE (OzelCikisDepoKod = @spDepoKod
			OR OzelGirisDepoKod = @spDepoKod 
			OR GenelCikisDepoKod= @spDepoKod
			OR GenelGirisDepoKod= @spDepoKod )
		AND Sirket_Kod = @spSirket_Kod)
	BEGIN
		select 0 as 'status', 'Stok Kartlarında kullanılıyor' as 'message'
		RETURN
  END

  IF EXISTS (SELECT * FROM SevkSatirlari 
		WHERE DepoKod = @spDepoKod
		AND Sirket_Kod = @spSirket_Kod)
	BEGIN
		select 0 as 'status', 'Sevk Satırlarında kullanılıyor.' as 'message'
		RETURN
  END
 
  IF EXISTS (SELECT * FROM m_FaturaDetay 
		WHERE DepoKod = @spDepoKod
		AND SirketKod = @spSirket_Kod)
	BEGIN
		select 0 as 'status', 'Fatura Satırlarında kullanılıyor.' as 'message'
		RETURN
  END

  IF EXISTS (SELECT * FROM StokHareketleri
		WHERE (GirisDepoKod = @spDepoKod
			OR CikisDepoKod = @spDepoKod)
		AND Sirket_Kod = @spSirket_Kod)
  BEGIN
		select 0 as 'status', 'Stok Hareketlerinde kullanılıyor' as 'message'
		RETURN
  END


  select 1 as 'status', 'Kullanılmıyor' as 'message'
  RETURN
end