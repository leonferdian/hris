<?php
include ('../../../lib/database.php');
require "../../../lib/fungsi_rupiah.php";
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();

// Initialize variables
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 50;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$orderDirection = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
$orderColumn = isset($_POST['columns'][$orderColumnIndex]['data']) ? $_POST['columns'][$orderColumnIndex]['data'] : 'absensi.depo, absensi.date_absen, absensi.nama';
$filter = "";
// $filter .= !empty($tahun) ? " AND datepart(year,[date_start]) = '$tahun'" : "";
// $filter .= !empty($bulan) ? " AND datepart(month,[date_start]) = '$bulan'" : "";
// $filter .= " AND [email_atasan] = '".$_SESSION['username']."'";
$filter .= " AND kadep.username = '".$_SESSION['username']."'";
$filter .= " AND absensi.tanggal IS NULL";
$filter .= " AND DATENAME(WEEKDAY, absensi.date_absen) != 'Sunday'";
            
// Total number of records with filtering
$filterQuery = "SELECT COUNT(*) AS total 
        FROM [db_hris].[dbo].[table_absensi_log] absensi
        LEFT JOIN [db_hris].[dbo].[table_depo_absensi] kadep 
        ON absensi.depo = kadep.kode_depo
        LEFT JOIN (
            SELECT a.*, b.leave_category AS lc_name
            FROM [db_hris].[dbo].[table_absensi_validasi] a
            LEFT JOIN [db_hris].[dbo].[table_absensi_leave_category] b 
            ON a.leave_category = b.id
        ) val 
        ON absensi.depo = val.depo 
        AND absensi.pin = val.pin 
        AND absensi.nik = val.nik
        AND absensi.date_absen = val.date_absen
        WHERE 1=1";
if (!empty($searchValue)) {
    $filterQuery .= " AND (a.[nama_karyawan] LIKE '%$searchValue%' OR a.[depo] LIKE '%$searchValue%')";
}
$filterQuery .= $filter;
// echo $filterQuery;
$result = $sqlsrv_hris->query($filterQuery);
$row = $sqlsrv_hris->fetch_array($result);
$totalRecordWithFilter = $row['total'];
$startRowNumber = $start + 1;

$total_soal = 40;

// Fetch records
$query = "SELECT 
            ROW_NUMBER() OVER (ORDER BY absensi.[depo]) + $startRowNumber - 1 AS counter
            ,absensi.[depo]
            ,absensi.date_absen
            ,absensi.[pin]
            ,absensi.[nik]
            ,absensi.[nama]
            ,kadep.username
            ,val.[keterangan]
            ,val.[lc_name]
        FROM [db_hris].[dbo].[table_absensi_log] absensi
        LEFT JOIN [db_hris].[dbo].[table_depo_absensi] kadep 
        ON absensi.depo = kadep.kode_depo
        LEFT JOIN (
            SELECT a.*, b.leave_category AS lc_name
            FROM [db_hris].[dbo].[table_absensi_validasi] a
            LEFT JOIN [db_hris].[dbo].[table_absensi_leave_category] b 
            ON a.leave_category = b.id
        ) val 
        ON absensi.depo = val.depo 
        AND absensi.pin = val.pin 
        AND absensi.nik = val.nik
        AND absensi.date_absen = val.date_absen
        WHERE 1=1";

if (!empty($searchValue)) {
    $query .= " AND (no_do LIKE '%$searchValue%' OR nama_outlet_by_system LIKE '%$searchValue%')";
}

$query .= $filter;
$query .= " ORDER BY $orderColumn $orderDirection OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
// echo $query;
$result = $sqlsrv_hris->query($query);
$counter = $start + 1;
$data = array();

while ($row = $sqlsrv_hris->fetch_array($result)) {
    $btn = '';

    if ($row['lc_name'] != ""):
        '<i class="ace-icon fa fa-check bigger-120"></i> Validated';
    else:
        $btn = '<button class="btn btn-xs btn-white btn-info" onclick="validasi("'.$row['pin'].'", "'.$row['nik'].'", "'.$row['date_absen'].'", "'.$row['nama'].'", "'.$row['depo'].')""><i class="fa fa-pencil"></i></button>';
    endif;

    $data[] = array(
        'counter' => $counter,
        'depo' => $row['depo'],
        'date_absen' => $row['date_absen'],
        'pin' => $row['pin'],
        'nik' => $row['nik'],
        'nama' => $row['nama'],
        'lc_name' => $row['lc_name'],
        'keterangan' => $row['keterangan'],
        'btn' => $btn
    );

    $counter++;
}

// Prepare response
$response = array(
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecordWithFilter),
    "recordsFiltered" => intval($totalRecordWithFilter),
    "data" => $data
);

// Debug output
error_log(print_r($response, true)); // Log the response for debugging
echo json_encode($response);
?>