USE [HarmonyERTAS]
GO
/****** Object:  StoredProcedure [dbo].[Web_MalzemeGrubuKontrol]    Script Date: 4.5.2020 13:13:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
   
ALTER PROCEDURE [dbo].[Web_MalzemeGrubuKontrol]
	@spSirket_Kod varchar(10),
	@spMalzemeGrb varchar(15)
	--@spMalzemeGrb2 varchar(15),
	--@spMalzemeGrb3 varchar(15),
	--@spMalzemeGrb4 varchar(15),
	--@spMalzemeGrb5 varchar(15)
--WITH ENCRYPTION
AS
BEGIN

Set nocount on
Set DateFormat dmy
Set Language Turkish

  IF EXISTS (SELECT * FROM StokKarti 
  WHERE (MalzemeGrubu1 = @spMalzemeGrb 
	  OR MalzemeGrubu2=@spMalzemeGrb 
	  OR MalzemeGrubu3=@spMalzemeGrb
	  OR MalzemeGrubu4=@spMalzemeGrb 
	  OR MalzemeGrubu5=@spMalzemeGrb)
  AND Sirket_Kod = @spSirket_Kod)
  BEGIN
	select 0 as 'status', 'Stok Kartlarında kullanılıyor' as 'message'
	RETURN
  END
	
  select 1 as 'status', 'Kullanılmıyor' as 'message'
  RETURN
end
