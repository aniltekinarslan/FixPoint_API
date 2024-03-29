USE [HarmonyERTAS]
GO
/****** Object:  StoredProcedure [dbo].[Web_BirimKontrol]    Script Date: 4.5.2020 13:11:57 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER PROCEDURE [dbo].[Web_BirimKontrol]
	@spBirim varchar(10)
--WITH ENCRYPTION
AS
BEGIN

Set nocount on
Set DateFormat dmy
Set Language Turkish

  IF EXISTS (SELECT * FROM StokKarti WHERE (Birim = @spBirim
												  OR SatinalmaSiparisBirim=@spBirim 
												  OR SatinalmaKabulBirim=@spBirim
												  OR SatisBirim=@spBirim 
												  OR ImalatSiparisBirim=@spBirim))
  BEGIN
	select 0 as 'status', 'Stok Kartlarında kullanılıyor' as 'message'
	RETURN
  END
	
  select 1 as 'status', 'Kullanılmıyor' as 'message'
  RETURN
end