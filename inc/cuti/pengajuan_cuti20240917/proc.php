<?php 
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
set_time_limit(0);
session_start();
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if($_POST['act'] == "save"):
	$sql = "INSERT INTO [db_hris].[dbo].[table_request_cuti]
			(
				[depo]
				,[nama_karyawan]
				,[pin]
				,[nik]
				,[keterangan]
				,[date_start]
				,[date_end]
				,[status]
				,[email_atasan]
				,[date_create]
				,[create_by]
				,[date_update]
				,[updated_by]
			)
			values
			(
			'".$_POST['depo']."'
			,'".$_POST['nama_karyawan']."'
			,'".$_POST['pin']."'
			,'".$_POST['nik']."'
			,'".$_POST['keterangan']."'
			,'".date("Y-m-d", strtotime($_POST['tgl1']))."'
			,'".date("Y-m-d", strtotime($_POST['tgl2']))."'
			,0
			,'".$_POST['email_atasan']."'
			,getdate()
			,'".$_SESSION['nama']."'
			,getdate()
			,'".$_SESSION['nama']."'
			)";

	if (isset($_POST['id']) && $_POST['id'] != ""):
	$sql = "UPDATE [db_hris].[dbo].[table_request_cuti]
			SET [depo] = '".$_POST['depo']."'
				,[nama_karyawan] = '".$_POST['nama_karyawan']."'
				,[pin] = '".$_POST['pin']."'
				,[nik] = '".$_POST['nik']."'
				,[keterangan] = '".$_POST['keterangan']."'
				,[date_start] = '".date("Y-m-d", strtotime($_POST['tgl1']))."'
				,[date_end] = '".date("Y-m-d", strtotime($_POST['tgl2']))."'
				,[date_update] = getdate()
				,[updated_by] = '".$_SESSION['nama']."' 
				WHERE id = ".$_POST['id']."";
	endif;
	$stmt = $sqlsrv_hris->query($sql);
	if ($stmt) {
		echo "Data Saved";
	} else {
		echo "Error: ".$sql;
	}
elseif($_POST['act'] == "select_emp"):
	$sql = "SELECT * FROM (SELECT a.[pin]
            ,a.[nik]
            ,a.[alias]
            ,b.cab_name as depo
        FROM [db_ftm].[dbo].[emp] a
        LEFT JOIN [db_ftm].[dbo].[cabang] b
        ON a.cab_id_auto = b.cab_id_auto
        union all
        SELECT [pegawai_pin] as pin
            ,[pegawai_nip] as nik
            ,[pegawai_nama] as alias
            ,b.[pembagian3_nama] as depo
        FROM [db_fin_pro].[dbo].[pegawai] a
        LEFT JOIN [db_fin_pro].[dbo].[pembagian3] b
        ON a.pembagian3_id = b.pembagian3_id) emp
        WHERE ISNUMERIC([alias]) = 0 AND [depo] = '".$_POST['depo']."'
        GROUP BY [pin], [nik], [alias], [depo]
        ORDER BY alias";
	$stmt = $sqlsrv_hris->query($sql);
	while ($row = $sqlsrv_hris->fetch_array($stmt)):
		echo '<option data-pin="' . $row['pin'] . '" data-nik="' . $row['nik'] . '" value="' . $row['alias'] . '">' . $row['alias'] . '</option>';
	endwhile;
endif;
?>