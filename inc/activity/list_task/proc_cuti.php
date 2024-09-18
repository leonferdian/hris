<?php 
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
?>
<?php if($_POST['act'] == "approve_cuti"): ?>
<?php
	$sql = "UPDATE [db_hris].[dbo].[table_request_cuti]
			SET [status] = '".$_POST['status']."'
				,[alasan_reject] = '".$_POST['alasan_reject']."'
				,[approval_by] = '".$_SESSION['nama']."'
				WHERE id = ".$_POST['id']."";

	$stmt = $sqlsrv_hris->query($sql);
	if ($stmt) {
		echo "Data Saved";
	} else {
		echo "Error: ".$sql;
	}
?>
<?php endif; ?>