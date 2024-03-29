USE [HarmonyERTAS]
GO
/****** Object:  StoredProcedure [dbo].[Web_CariOzelFiyatKontrol]    Script Date: 4.5.2020 13:12:48 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[Web_CariOzelFiyatKontrol]
	@spSirketKod varchar(10),
	@spStokKod varchar(25)
AS
BEGIN

	Declare @cariKod varchar(150) = ''
	Declare @cariAdi varchar(150) = ''
	
	SELECT @cariKod = MS.CariKod, @cariAdi = MC.CariAdi FROM MusteriStoklari MS 
							 INNER JOIN StokKarti S ON S.Kod=MS.StokKod AND S.Sirket_Kod=MS.Sirket_Kod
							 INNER JOIN m_CariKart MC ON MC.CariKod=MS.CariKod AND MC.SirketKod=MS.Sirket_Kod
								WHERE MS.StokKod = @spStokKod AND MS.Sirket_Kod = @spSirketKod
								
	IF @cariKod is not null and @cariKod != ''
	BEGIN
		select 0 as 'status', 'Bu Stok Cari Özel Fiyatlandırmada ' + @cariKod + ' (' + @cariAdi + ') kullanılıyor' as 'message'
		RETURN
	END	

  select 1 as 'status', 'Kullanılmıyor' as 'message'
  RETURN
end