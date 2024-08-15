<?php
// header('Content-Type: application/json');
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
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$orderDirection = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
$orderColumn = isset($_POST['columns'][$orderColumnIndex]['data']) ? $_POST['columns'][$orderColumnIndex]['data'] : 'a.[nama]';
$filter = "";

// Total number of records with filtering
$filterQuery = "SELECT COUNT(*) AS total FROM [db_hris].[dbo].[table_biodata_pelamar] WHERE 1=1";
if (!empty($searchValue)) {
    $filterQuery .= " AND ([nama] LIKE '%$searchValue%' OR nama_outlet_by_system LIKE '%$searchValue%')";
}
$filterQuery .= $filter;

$result = $sqlsrv_hris->query($filterQuery);
$row = $sqlsrv_hris->fetch_array($result);
$totalRecordWithFilter = $row['total'];
$startRowNumber = $start + 1;

$total_soal = 40;

// Fetch records
$query = "SELECT
            ROW_NUMBER() OVER (ORDER BY a.[nama]) + $startRowNumber - 1 AS counter
            ,a.[id]
            ,a.[date_create]
            ,b.[total_jawaban_benar]
            ,a.[nama]
            ,a.[no_hp]
            ,a.[kode_jabatan]
            FROM [db_hris].[dbo].[table_biodata_pelamar] a
            LEFT JOIN [db_hris].[dbo].[table_tes_hasil] b
            ON a.id = b.id_peserta WHERE 1=1";

if (!empty($searchValue)) {
    $query .= " AND (no_do LIKE '%$searchValue%' OR nama_outlet_by_system LIKE '%$searchValue%')";
}

$query .= $filter;
$query .= " ORDER BY $orderColumn $orderDirection OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";

$result = $sqlsrv_hris->query($query);
$counter = $start + 1;
$data = array();

while ($row = $sqlsrv_hris->fetch_array($result)) {
    // $row['counter'] = $counter;
    // $row['date_create'] = date_format($row['date_create'],"Y-m-d H:i:s");
    // $row['btn'] = '<button class="btn btn-xs btn-info" onclick="edit_dialog(\'' . $row['id'] . '\')">
    //                     <i class="ace-icon fa fa-pencil bigger-120"></i>
    //                 </button>
    //                 <button class="btn btn-xs btn-danger" onclick="del(\'' . $row['id'] . '\')">
    //                     <i class="ace-icon fa fa-trash bigger-120"></i>
    //                 </button>';

    $btn = '<button class="btn btn-xs btn-info" onclick="edit_dialog(\'' . $row['id'] . '\')">
                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                    </button>';
    $data[] = array(
        'counter' => $counter,
        'date_create' => date_format($row['date_create'],"Y-m-d H:i:s"),
        'total_jawaban_benar' => $row['total_jawaban_benar']."/".$total_soal,
        'nama' => $row['nama'],
        'no_hp' => $row['no_hp'],
        'kode_jabatan' => $row['kode_jabatan'],
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