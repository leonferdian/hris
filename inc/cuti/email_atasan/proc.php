<?php 
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
set_time_limit(0);
session_start();
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if($_POST['act'] == "save"):

	$select_atasan = "SELECT * FROM [db_hris].[dbo].[table_email_atasan] WHERE [email_user] = '".$_POST['email_user']."' OR [nik] = '".$_POST['nik']."'";
	$result = $sqlsrv_hris->query($select_atasan);
	$num_cek = $sqlsrv_hris->num_rows($result);

	$sql = "INSERT INTO [db_hris].[dbo].[table_email_atasan]
			(
				[email_user]
				,[nik]
				,[email_atasan]
				,[nama_atasan]
			)
			values
			(
			'".$_POST['email_user']."'
			,'".$_POST['nik']."'
			,'".$_POST['email_atasan']."'
			,'".$_POST['nama_atasan']."'
			)";

	if ($num_cek > 0):
	$sql = "UPDATE [db_hris].[dbo].[table_email_atasan]
			SET [email_user] = '".$_POST['email_user']."'
				,[nik] = '".$_POST['nik']."'
				,[email_atasan] = '".$_POST['email_atasan']."'
				,[nama_atasan] = '".$_POST['nama_atasan']."'
				WHERE email_user = '".$_POST['email_user']."' OR nik = '".$_POST['nik']."'";
	endif;
	$stmt = $sqlsrv_hris->query($sql);
	if ($stmt) {
		echo "Data Saved";
	} else {
		echo "Error: ".$sql;
	}
endif;
?>