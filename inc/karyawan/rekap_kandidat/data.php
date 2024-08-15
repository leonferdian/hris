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
$tgl1 = isset($_POST['tgl1']) ? $_POST['tgl1'] : '';
$tgl2 = isset($_POST['tgl2']) ? $_POST['tgl2'] : '';
$jabatan = isset($_POST['jabatan']) ? $_POST['jabatan'] : '';
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$orderDirection = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
$orderColumn = isset($_POST['columns'][$orderColumnIndex]['data']) ? $_POST['columns'][$orderColumnIndex]['data'] : '[tgl_screening]';
$filter = "";
// $filter .= !empty($tgl1) && !empty($tgl2) ? " AND [tgl_screening] BETWEEN '$tgl1' AND '$tgl2'" : "";
$filter .= !empty($jabatan) ? " AND a.[kode_jabatan] = '$jabatan'" : "";

// Total number of records with filtering
$filterQuery = "SELECT COUNT(*) AS total FROM [db_hris].[dbo].[table_biodata_pelamar] a
                LEFT JOIN [db_hris].[dbo].[table_rekap_kandidat] b
                ON a.id = b.id_pelamar WHERE 1=1";
if (!empty($searchValue)) {
    $filterQuery .= " AND (a.[nama] LIKE '%$searchValue%' OR b.[no_wa] LIKE '%$searchValue%')";
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
            ROW_NUMBER() OVER (ORDER BY b.[tgl_screening]) + $startRowNumber - 1 AS counter
            ,a.[id] as id_kandidat
            ,a.[date_create]
            ,a.[nama]
            ,a.[email]
            ,a.[no_hp]
            ,a.[kode_jabatan]
            ,b.[id]
            ,b.[tgl_screening]
            ,b.[tgl_interview]
            ,b.[posisi]
            ,b.[no_wa]
            ,b.[form]
            ,b.[interview1]
            ,b.[interview2]
            ,b.[interview3]
            ,b.[pertimbangkan]
            ,b.[final]
            ,b.[keterangan]
        FROM [db_hris].[dbo].[table_biodata_pelamar] a
        LEFT JOIN [db_hris].[dbo].[table_rekap_kandidat] b
        ON a.id = b.id_pelamar WHERE 1=1";

if (!empty($searchValue)) {
    $query .= " AND (a.[nama] LIKE '%$searchValue%' OR b.[no_wa] LIKE '%$searchValue%')";
}

$query .= $filter;
$query .= " ORDER BY $orderColumn $orderDirection OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
// echo $query;
$result = $sqlsrv_hris->query($query);
$counter = $start + 1;
$data = array();

while ($row = $sqlsrv_hris->fetch_array($result)) {

    $btn = '<a class="btn btn-xs btn-white btn-info" href="?sm=rekap_kandidat&act=edit&id='.$row['id'].'&id_kandidat='.$row['id_kandidat'].'"><i class="fa fa-pencil"></i></a>';
    $no_wa = 'https://wa.me/'.$row['no_wa'];
    if (!isset($row['no_wa'])) $no_wa = 'https://wa.me/'.preg_replace('/^0/', '62', $row['no_hp']);

    $data[] = array(
        'counter' => $counter,
        'tgl_screening' => isset($row['tgl_screening']) ? date_format($row['tgl_screening'],"Y-m-d") : "",
        // 'tgl_screening' => isset($row['date_create']) ? date_format($row['date_create'],"Y-m-d") : "",
        'tgl_interview' => isset($row['tgl_interview']) ? date_format($row['tgl_interview'],"Y-m-d") : "",
        'nama' => '<a href="?sm=rekap_kandidat&act=detail&id='.$row['id'].'">'.$row['nama'].'</a>',
        'kode_jabatan' => $row['kode_jabatan'],
        'posisi' => $row['posisi'],
        'no_hp' => $row['no_hp'],
        'no_wa' => '<a href="'.$no_wa.'">'.$no_wa.'</a>',
        'formY' => $row['form'] == "Y" ? "TRUE" : "FALSE",
        'formN' => $row['form'] == "N" ? "TRUE" : "FALSE",
        'interview1Y' => $row['interview1'] == "Y" ? "TRUE" : "FALSE",
        'interview1N' => $row['interview1'] == "N" ? "TRUE" : "FALSE",
        'interview2Y' => $row['interview2'] == "Y" ? "TRUE" : "FALSE",
        'interview2N' => $row['interview2'] == "N" ? "TRUE" : "FALSE",
        'interview3Y' => $row['interview3'] == "Y" ? "TRUE" : "FALSE",
        'interview3N' => $row['interview3'] == "N" ? "TRUE" : "FALSE",
        'pertimbangkanY' => $row['pertimbangkan'] == "Y" ? "TRUE" : "FALSE",
        'pertimbangkanN' => $row['pertimbangkan'] == "N" ? "TRUE" : "FALSE",
        'finalY' => $row['final'] == "Y" ? "TRUE" : "FALSE",
        'finalN' => $row['final'] == "N" ? "TRUE" : "FALSE",
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