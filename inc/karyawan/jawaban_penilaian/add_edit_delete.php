<?php
include ('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
function validate_input($data)
{
    $data = str_replace("'", "''", $data);
    return $data;
}

if (isset($_POST['act']) && $_POST['act'] == "add_data"):
    $kode_divisi = $_POST['kode_divisi'];
    $kode_penilaian = $kode_divisi . date("Y") . rand(9, 9999);
    $nama = $_POST['nama'];
    $divisi = $_POST['divisi'];
    $tahun = $_POST['tahun'];
    $penilaian = $_POST['penilaian'];
    $score = $_POST['score'];
    $grade = $_POST['grade'];
    $nilai = $_POST['nilai'];
    $total_item = count($penilaian);

    $count = 1;
    foreach ($penilaian as $index => $penilaian_item) {
        $penilaian_item = trim(str_replace("\r", "<br>", $penilaian_item));
        $score_item = trim($score[$index]);
        $grade_item = trim($grade[$index]);
        $nilai_item = trim($nilai[$index]);

        $sql = "INSERT INTO [db_hris].[dbo].[table_penilaian_karyawan_result]
            (
                [kode_penilaian]
                ,[nama]
                ,[divisi]
                ,[tahun]
                ,[penilaian]
                ,[score_atasan]
                ,[grade_atasan]
                ,[nilai_atasan]
                ,[date_create]
                ,[create_by]
                ,[date_update]
                ,[update_by]
            )
            VALUES 
            (
                '" . $kode_penilaian . "'
                ,'" . $nama . "'
                ,'" . $divisi . "'
                ,'" . $tahun . "'
                ,'" . validate_input($penilaian_item) . "'
                ,'" . validate_input(str_replace(",", ".", $score_item)) . "'
                ,'" . validate_input(str_replace(",", ".", $grade_item)) . "'
                ,'" . validate_input(str_replace(",", ".", $nilai_item)) . "'
                ,getdate()
                ,'" . $_SESSION['nama'] . "'
                ,getdate()
                ,'" . $_SESSION['nama'] . "'
            )";
        $result = $sqlsrv_hris->query($sql);
        if ($result && $count == $total_item):
            echo "Data Berhasil Disimpan";
        else:
            // echo "Error: \r\n" . $sql;
        endif;
        $count++;
    }
elseif (isset($_POST['act']) && $_POST['act'] == "edit_data"):
    $id_hasil = $_POST['id_hasil'];
    $hasil = $_POST['hasil'];
    $score = $_POST['score'];
    $grade = $_POST['grade'];
    $nilai = $_POST['nilai'];
    $total_item = count($id_hasil);

    $count = 1;
    foreach ($id_hasil as $index => $id_hasil_item) {
        $score_item = trim($score[$index]);
        $grade_item = trim($grade[$index]);
        $nilai_item = trim($nilai[$index]);

        if (isset($id_hasil_item) && $id_hasil_item != ""):
            $sql_update = "UPDATE [db_hris].[dbo].[table_penilaian_karyawan_result] SET
                    [score_atasan] = '" . validate_input(str_replace(",", ".", $score_item)) . "'
                    ,[grade_atasan] = '" . validate_input($grade_item) . "'
                    ,[nilai_atasan] = '" . validate_input($nilai_item) . "'
                    ,[hasil] = '" . validate_input($hasil) . "'
                    ,[date_update] = getdate()
                    ,[update_by] = '" . $_SESSION['nama'] . "'
                    WHERE id = '" . $id_hasil_item . "'";
            $result = $sqlsrv_hris->query($sql_update);
            if (!$result):
                echo "Error: \r\n" . $sql_update;
            endif;
        endif;
        if ($count == $total_item) echo "Data Berhasil Disimpan"; 
        $count++;
    }
endif;
?>