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
$orderColumn = isset($_POST['columns'][$orderColumnIndex]['data']) ? $_POST['columns'][$orderColumnIndex]['data'] : 'a.[date_create]';
$filter = "";
$filter .= !empty($tahun) ? " AND datepart(year,[date_start]) = '$tahun'" : "";
$filter .= !empty($bulan) ? " AND datepart(month,[date_start]) = '$bulan'" : "";

// Total number of records with filtering
$filterQuery = "SELECT COUNT(*) AS total FROM [db_hris].[dbo].[table_request_cuti] WHERE 1=1";
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
            ROW_NUMBER() OVER (ORDER BY a.[date_start]) + $startRowNumber - 1 AS counter
            ,a.[id]
            ,a.[depo]
            ,a.[nama_karyawan]
            ,a.[pin]
            ,a.[nik]
            ,a.[keterangan]
            ,a.[alasan_reject]
            ,a.[email_atasan]
            ,a.[date_start]
            ,a.[date_end]
            ,a.[date_create]
            ,a.[date_update]
            ,a.[updated_by]
            ,b.status
            ,c.[nama_atasan]
        FROM [db_hris].[dbo].[table_request_cuti] a
        LEFT JOIN [db_hris].[dbo].[table_request_cuti_status] b
        ON a.status = b.id 
        LEFT JOIN [db_hris].[dbo].[table_email_atasan] c
        ON a.[email_atasan] = c.[email_atasan]
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

    $sql_verified = "SELECT * FROM [db_hris].[dbo].[table_absensi_validasi] WHERE [depo] = '".$row['depo']."' AND [pin] = '".$row['pin']."' AND [nik] = '".$row['nik']."' AND [date_absen] = '".date_format($row['date_start'],"Y-m-d")."'";
    $stmt_verified = $sqlsrv_hris->query($sql_verified);
    $num_verified = $sqlsrv_hris->num_rows($stmt_verified);
    $verified = $num_verified > 0 ? 'verified' : 'pending';

    $btn = '';
    if ($row['status'] == "disetujui" && $num_verified == 0):
        $btn = '<button class="btn btn-xs btn-white btn-success" onclick="validasi('.$row['id'].')"><i class="fa fa-check"></i> Validasi</button>';
    endif;

    $data[] = array(
        'counter' => $counter,
        'depo' => $row['depo'],
        'nama_karyawan' => '<a href="?sm=pengajuan_cuti&act=detail&id='.$row['id'].'">'.$row['nama_karyawan'].'</a>',
        'pin' => $row['pin'],
        'nik' => $row['nik'],
        'keterangan' => $row['keterangan'],
        'alasan_reject' => $row['alasan_reject'],
        'nama_atasan' => $row['nama_atasan'],
        'date_start' => date_format($row['date_start'],"Y-m-d"),
        'date_end' => date_format($row['date_end'],"Y-m-d"),
        'date_create' => date_format($row['date_create'],"Y-m-d"),
        'updated_by' => $row['updated_by'],
        'status' => $row['status'],
        'verified' => $verified,
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