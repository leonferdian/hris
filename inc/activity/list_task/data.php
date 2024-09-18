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
// $filter .= !empty($tahun) ? " AND datepart(year,[date_start]) = '$tahun'" : "";
// $filter .= !empty($bulan) ? " AND datepart(month,[date_start]) = '$bulan'" : "";
$filter .= " AND [category] = '".$_POST['category']."'";
$filter .= " AND [status] = 0";

// Total number of records with filtering
$filterQuery = "SELECT COUNT(*) AS total FROM [db_hris].[dbo].[table_log_task] WHERE 1=1";
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
            ROW_NUMBER() OVER (ORDER BY [depo]) + $startRowNumber - 1 AS counter
            ,[id]
            ,[tanggal]
            ,[category]
            ,[depo]
            ,[id_task]
            ,[description]
            ,[status]
            ,[date_create]
            ,[create_by]
            ,[date_update]
            ,[update_by]
        FROM [db_hris].[dbo].[table_log_task]
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
    if ($row['status'] == "diajukan"):
        $btn = '<button class="btn btn-xs btn-white btn-success" onclick="approval('.$row['id'].', 1)"><i class="fa fa-check"></i> approve</button>
                            <button class="btn btn-xs btn-white btn-danger" onclick="approval('.$row['id'].', 2)"><i class="fa fa-times"></i> reject</button>';
    endif;

    $data[] = array(
        'counter' => $counter,
        'depo' => $row['depo'],
        'tanggal' => date_format($row['tanggal'],"Y-m-d"),
        'category' => $row['category'],
        'description' => $row['description'],
        // 'btn' => $btn
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