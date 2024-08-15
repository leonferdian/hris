<?php 
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
set_time_limit(0);
session_start();
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if($_POST['act'] == "save"):

	$sql = "INSERT INTO [db_hris].[dbo].[table_rekap_kandidat]
			(
				[id_pelamar]
				,[tgl_screening]
				,[tgl_interview]
				,[posisi]
				,[no_wa]
				,[form]
				,[interview1]
				,[interview2]
				,[interview3]
				,[pertimbangkan]
				,[final]
				,[keterangan]
				,[date_create]
				,[create_by]
				,[date_update]
				,[update_by]
			)
			values
			(
			'".$_POST['id_kandidat']."'
			,'".date("Y-m-d", strtotime($_POST['tgl_screening']))."'
			,'".date("Y-m-d", strtotime($_POST['tgl_interview']))."'
			,'".$_POST['posisi']."'
			,'".$_POST['no_wa']."'
			,'".$_POST['form']."'
			,'".$_POST['interview1']."'
			,'".$_POST['interview2']."'
			,'".$_POST['interview3']."'
			,'".$_POST['pertimbangkan']."'
			,'".$_POST['final']."'
			,'".$_POST['keterangan']."'
			,getdate()
			,'".$_SESSION['nama']."'
			,getdate()
			,'".$_SESSION['nama']."'
			)";

	if (isset($_POST['id']) && $_POST['id'] != ""):
	$sql = "UPDATE [db_hris].[dbo].[table_request_cuti]
			SET [id_pelamar]  = '".$_POST['id_kandidat']."'
				,[tgl_screening]  = '".$_POST['tgl_screening']."'
				,[tgl_interview]  = '".$_POST['tgl_interview']."'
				,[posisi]  = '".$_POST['posisi']."'
				,[no_wa]  = '".$_POST['no_wa']."'
				,[form]  = '".$_POST['form']."'
				,[interview1]  = '".$_POST['interview1']."'
				,[interview2]  = '".$_POST['interview2']."'
				,[interview3]  = '".$_POST['interview3']."'
				,[pertimbangkan]  = '".$_POST['pertimbangkan']."'
				,[final]  = '".$_POST['final']."'
				,[keterangan]  = '".$_POST['keterangan']."'
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
endif;
?>