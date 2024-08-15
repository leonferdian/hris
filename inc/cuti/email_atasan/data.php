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
// $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
// $depo = isset($_POST['depo']) ? $_POST['depo'] : '';
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$orderDirection = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
$orderColumn = isset($_POST['columns'][$orderColumnIndex]['data']) ? $_POST['columns'][$orderColumnIndex]['data'] : '[email_user]';
$filter = "";
// $filter .= !empty($tanggal) ? " AND [date_start] = '$tanggal'" : "";
// $filter .= !empty($depo) ? " AND [depo] = '$depo'" : "";

// Total number of records with filtering
$filterQuery = "SELECT COUNT(*) AS total FROM [db_hris].[dbo].[table_email_atasan] WHERE 1=1";
if (!empty($searchValue)) {
    $filterQuery .= " AND (email_user LIKE '%$searchValue%' OR email_atasan LIKE '%$searchValue%')";
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
            ROW_NUMBER() OVER (ORDER BY [email_user]) + $startRowNumber - 1 AS counter
            ,[email_user]
            ,[nik]
            ,[email_atasan]
        FROM [db_hris].[dbo].[table_email_atasan] WHERE 1=1";

if (!empty($searchValue)) {
    $query .= " AND (email_user LIKE '%$searchValue%' OR email_atasan LIKE '%$searchValue%')";
}

$query .= $filter;
$query .= " ORDER BY $orderColumn $orderDirection OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
// echo $query;
$result = $sqlsrv_hris->query($query);
$counter = $start + 1;
$data = array();

while ($row = $sqlsrv_hris->fetch_array($result)) {

    $btn = '<a class="btn btn-xs btn-white btn-info" href="?sm=email_atasan&act=edit&email_user='.$row['email_user'].'&nik='.$row['nik'].'"><i class="fa fa-pencil"></i></a>';

    $data[] = array(
        'counter' => $counter,
        'email_user' => $row['email_user'],
        'nik' => $row['nik'],
        'email_atasan' => $row['email_atasan'],
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