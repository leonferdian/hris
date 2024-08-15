<?php
include ('../../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
function validate_input($data)
{
    $data = str_replace("'", "''", $data);
    return $data;
}

if (isset($_POST['act']) && $_POST['act'] == "add_data"):
    $kode_penilaian = $_POST['kode_penilaian'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $nik = $_POST['nik'];
    $id_penilaian = $_POST['id_penilaian'];
    $score = $_POST['score'];
    $grade = $_POST['grade'];
    $nilai = $_POST['nilai'];
    $total_item = count($id_penilaian);

    $sql_cek = "SELECT * FROM [db_hris].[dbo].[table_penilaian_karyawan_result] WHERE [kode_penilaian] = '".$kode_penilaian."' AND [nik] = '".$nik."'";
    $result_cek = $sqlsrv_hris->query($sql_cek);
    $num_cek = $sqlsrv_hris->num_rows($result_cek);
        $count = 1;
        foreach ($id_penilaian as $index => $id_penilaian_item) {
            $score_item = trim($score[$index]);
            $grade_item = trim($grade[$index]);
            $nilai_item = trim($nilai[$index]);

            $sql = "INSERT INTO [db_hris].[dbo].[table_penilaian_karyawan_result]
                (
                    [kode_penilaian]
                    ,[id_penilaian]
                    ,[nama_karyawan]
                    ,[nik]
                    ,[score]
                    ,[grade]
                    ,[nilai]
                    ,[date_create]
                    ,[create_by]
                    ,[date_update]
                    ,[update_by]
                )
                VALUES 
                (
                    '" . $kode_penilaian . "'
                    ,'" . $id_penilaian_item . "'
                    ,'" . $nama_karyawan . "'
                    ,'" . $nik . "'
                    ,'" . validate_input($score_item) . "'
                    ,'" . validate_input($grade_item) . "'
                    ,'" . validate_input($nilai_item) . "'
                    ,getdate()
                    ,'" . $_SESSION['nama'] . "'
                    ,getdate()
                    ,'" . $_SESSION['nama'] . "'
                )";

            if ($num_cek > 0):
                $sql = "UPDATE [db_hris].[dbo].[table_penilaian_karyawan_result] 
                    SET     
                    [score] = '" . validate_input($score_item) . "'
                    ,[grade] = '" . validate_input($grade_item) . "'
                    ,[nilai] = '" . validate_input($nilai_item) . "'
                    ,[date_update] = getdate()
                    ,[update_by] = '" . $_SESSION['nama'] . "'
                    WHERE [kode_penilaian] = '" . $kode_penilaian . "' AND [nik] = '" . $nik . "'";
            endif;

            $result = $sqlsrv_hris->query($sql);
            if ($result) {
                if ($count == $total_item) echo "Data Berhasil Disimpan";
            }
            else {
                echo "Error: \r\n" . $sql;
            }
            $count++;
        }
endif;
?>