USE [HarmonyERTAS]
GO
/****** Object:  StoredProcedure [dbo].[Web_OzelBirimSilKontrol]    Script Date: 4.5.2020 13:13:09 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[Web_OzelBirimSilKontrol]
	@spSirketKod varchar(10),
	@spStokKod varchar(25),
	@spBirim varchar(10) = ''
--WITH ENCRYPTION
AS
BEGIN

	IF @spBirim = ''
	BEGIN
		IF EXISTS (SELECT * FROM StokKartiOzelBirimleri SOB 
							 INNER JOIN StokKarti S ON S.Kod=SOB.StokKod AND S.Sirket_Kod=Sob.Sirket_Kod
								WHERE SOB.StokKod = @spStokKod AND SOB.Sirket_Kod = @spSirketKod
												AND (SOB.Birim = S.SatinalmaSiparisBirim
												  OR SOB.Birim = S.SatinalmaKabulBirim
												  OR SOB.Birim = S.SatisBirim
													OR SOB.Birim = S.ImalatSiparisBirim))
		BEGIN
			select 0 as 'status', 'Özel Birimlerde kullanılıyor' as 'message'
			RETURN
		END	
													
	END
	ELSE IF EXISTS (SELECT * FROM StokKarti WHERE Kod = @spStokKod AND Sirket_Kod = @spSirketKod
												AND (Birim = @spBirim
												  OR SatinalmaSiparisBirim=@spBirim 
												  OR SatinalmaKabulBirim=@spBirim
												  OR SatisBirim=@spBirim
													OR ImalatSiparisBirim=@spBirim))
  BEGIN
		select 0 as 'status', 'Stok Kartında kullanılıyor' as 'message'
		RETURN
  END
	
  IF EXISTS (SELECT * FROM MamulAgaci WHERE (Sirket_Kod = @spSirketKod AND AltKod = @spStokKod AND Birim = @spBirim))
  BEGIN
		select 0 as 'status', 'Mamül Ağacında kullanılıyor' as 'message'
		RETURN
  END
	
  select 1 as 'status', 'Kullanılmıyor' as 'message'
  RETURN
end