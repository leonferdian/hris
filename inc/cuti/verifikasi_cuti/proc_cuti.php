<?php 
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
?>
<?php if($_POST['act'] == "verifikasi"): ?>
<?php
$sql = "SELECT * FROM [db_hris].[dbo].[table_request_cuti] WHERE id = ".$_POST['id']."";
$stmt = $sqlsrv_hris->query($sql);
$row_data = $sqlsrv_hris->fetch_array($stmt);

$start_date = date_format($row_data['date_start'], "Y-m-d");
$end_date = date_format($row_data['date_end'], "Y-m-d");

// Create DateTime objects for the start and end dates
$start = new DateTime($start_date);
$end = new DateTime($end_date);

// Add one day to the end date to include it in the range
$end->modify('+1 day');

// Define the interval (1 day)
$interval = new DateInterval('P1D');

// Create a DatePeriod object
$date_period = new DatePeriod($start, $interval, $end);

// Iterate over each date in the period
foreach ($date_period as $date) {
    $day = $date->format('Y-m-d');

	$verify1 = $sqlsrv_hris->query(
		"INSERT INTO [db_hris].[dbo].[table_absensi_validasi] 
			(
			[depo]
			,[date_absen]
			,[pin]
			,[nik]
			,[leave_category]
			,[verifikasi_apps]
			,[keterangan]
			,[nilai]
			,[validated_by]
			,[validate_source]
      		,[dtmCreate]
			)
			values
			(
			'".$row_data['depo']."'
			,'".$day."'
			,'".$row_data['pin']."'
			,'".$row_data['nik']."'
			,'2'
			,'2'
			,'".$row_data['keterangan']."'
			,'0'
			,'".$_SESSION['id_user']."'
			,'hris'
			,getdate()
			)"
	);

	$verify2 = $sqlsrv_hris->query(
		"INSERT INTO [db_hris].[dbo].[table_absensi_validasi_hrd]
                (depo
                ,pin
                ,nik
                ,date_absen
                ,validasi_hrd
                ,nama_validator
                ,validate_date)
                VALUES
                (   '" . $row_data['depo'] . "'
                    ,'" . $row_data['pin'] . "'
                    ,'" . $row_data['nik'] . "'
                    ,'" . $day . "'
                    ,'1'
                    ,'" . trim($_SESSION['nama']) . "'
                    ,getdate()
                )"
	);

	if($day == $end_date) {
		if ($verify1 && $verify2) {
			echo "Data Saved";
		}
	}
}
?>
<?php endif; ?>