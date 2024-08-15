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
    $nama_soal = $_POST['nama_soal'];
    $pertanyaan = $_POST['pertanyaan'];
    $a1 = $_POST['a1'];
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a4 = $_POST['a4'];
    $a5 = $_POST['a5'];
    $nilai = $_POST['nilai'];
    $jawaban = $_POST['jawaban'];
    $kode_soal = "PDC".date("Ym").rand(9,999);
    $total_item = count($pertanyaan);

    $sql = "INSERT INTO [db_hris].[dbo].[table_tes_soal]
        (
            [kode_soal]
            ,[nama_soal]
            ,[date_create]
            ,[create_by]
            ,[date_update]
            ,[update_by]
        )
        VALUES 
        (
            '" . $kode_soal . "'
            ,'" . validate_input(trim($nama_soal)) . "'
            ,getdate()
            ,'" . $_SESSION['nama'] . "'
            ,getdate()
            ,'" . $_SESSION['nama'] . "'
        )";
    $result = $sqlsrv_hris->query($sql);
    $count = 1;
    if ($result):
        foreach ($pertanyaan as $index => $pertanyaan_item) {
            $pertanyaan_item = trim(str_replace("\r","<br>",$pertanyaan_item));
            $a1_item = trim($a1[$index]);
            $a2_item = trim($a2[$index]);
            $a3_item = trim($a3[$index]);
            $a4_item = trim($a4[$index]);
            $a5_item = trim($a5[$index]);
            $nilai_item = trim($nilai[$index]);
            $jawaban_item = trim($jawaban[$index]);

            $sql_detail = "INSERT INTO [db_hris].[dbo].[table_tes_soal_detail]
            (
                [kode_soal]
                ,[pertanyaan]
                ,[a1]
                ,[a2]
                ,[a3]
                ,[a4]
                ,[a5]
                ,[nilai]
                ,[date_create]
                ,[create_by]
                ,[date_update]
                ,[update_by]
            )
            VALUES 
            (
                '".$kode_soal."'
                ,'".validate_input($pertanyaan_item)."'
                ,'".validate_input($a1_item)."'
                ,'".validate_input($a2_item)."'
                ,'".validate_input($a3_item)."'
                ,'".validate_input($a4_item)."'
                ,'".validate_input($a5_item)."'
                ,'".validate_input(str_replace(",",".",$nilai_item))."'
                ,getdate()
                ,'" . $_SESSION['nama'] . "'
                ,getdate()
                ,'" . $_SESSION['nama'] . "'
            ); SELECT SCOPE_IDENTITY()";
            $result2 = $sqlsrv_hris->query($sql_detail);
            if ($result2):
                sqlsrv_next_result($result2);
                sqlsrv_fetch($result2);
                $insertedId = sqlsrv_get_field($result2, 0);

                $sql_kunci = "INSERT INTO [db_hris].[dbo].[table_tes_jawaban]
                (
                    [kode_soal]
                    ,[id_soal]
                    ,[jawaban]
                )
                VALUES 
                (
                    '".$kode_soal."'
                    ,'" . $insertedId . "'
                    ,'" . $jawaban_item . "'
                )";

                $result3 = $sqlsrv_hris->query($sql_kunci);

                if ($result3 && $count == $total_item):
                    echo "Data Berhasil Disimpan";
                else:
                    echo "Error: `$sql_kunci` $total_item : $count";
                endif;
            else:
                echo "Error: \r\n" . $sql_detail;
            endif;
            $count++;
        }
    else:
        echo "Error: \r\n" . $sql;
    endif;
elseif (isset($_POST['act']) && $_POST['act'] == "edit_data"):
    $pertanyaan = $_POST['pertanyaan'];
    $id_detail = $_POST['id_detail'];
    $a1 = $_POST['a1'];
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a4 = $_POST['a4'];
    $a5 = $_POST['a5'];
    $nilai = $_POST['nilai'];
    $jawaban = $_POST['jawaban'];
    $total_item = count($pertanyaan);

    $sql_update = "UPDATE [db_hris].[dbo].[table_tes_soal] SET
        [nama_soal] = '" . validate_input(trim($_POST['nama_soal'])) . "'
        ,[date_update] = getdate()
        ,[update_by] = '" . $_SESSION['nama'] . "'
        WHERE kode_soal = '" . $_POST['kode_soal'] . "'";
    $result = $sqlsrv_hris->query($sql_update);
    $count = 1;
    if ($result):
        foreach ($pertanyaan as $index => $pertanyaan_item) {
            $pertanyaan_item = trim(str_replace("\r","<br>",$pertanyaan_item));
            $id_detail_item = trim($id_detail[$index]);
            $a1_item = trim($a1[$index]);
            $a2_item = trim($a2[$index]);
            $a3_item = trim($a3[$index]);
            $a4_item = trim($a4[$index]);
            $a5_item = trim($a5[$index]);
            $nilai_item = trim($nilai[$index]);
            $jawaban_item = trim($jawaban[$index]);

            if($count == $total_item) {
                echo "Data Berhasil Disimpan";
            }

            // $sql_cek_detail = "SELECT * FROM [db_hris].[dbo].[table_tes_soal_detail] WHERE id=".$id_detail_item."";
            // $result_cek = $sqlsrv_hris->query($sql_cek_detail);
            // $num_cek = $sqlsrv_hris->num_rows($result_cek);
            if (isset($id_detail_item) && $id_detail_item != ""):
                $sql_update2 = "UPDATE [db_hris].[dbo].[table_tes_soal_detail] SET
                    [pertanyaan] = '" . validate_input($pertanyaan_item) . "'
                    ,[a1] = '" . validate_input($a1_item) . "'
                    ,[a2] = '" . validate_input($a2_item) . "'
                    ,[a3] = '" . validate_input($a3_item) . "'
                    ,[a4] = '" . validate_input($a4_item) . "'
                    ,[a5] = '" . validate_input($a5_item) . "'
                    ,[nilai] = '" . validate_input($nilai_item) . "'
                    ,[date_update] = getdate()
                    ,[update_by] = '" . $_SESSION['nama'] . "'
                    WHERE id = '" . $id_detail_item . "'";
                $result2 = $sqlsrv_hris->query($sql_update2);
                if ($result2):
                    $sql_update3 = "UPDATE [db_hris].[dbo].[table_tes_jawaban] SET
                                [jawaban] = '" . validate_input($jawaban_item) . "'
                                WHERE id_soal = '" . $id_detail_item . "'";

                    $result3 = $sqlsrv_hris->query($sql_update3);

                    if (!$result3):
                        echo "Error: `$sql_update3`)";
                    endif;
                else:
                    echo "Error: \r\n" . $sql_update2;
                endif;
                $count++;
            else:
                $sql_detail = "INSERT INTO [db_hris].[dbo].[table_tes_soal_detail]
                (
                    [kode_soal]
                    ,[pertanyaan]
                    ,[a1]
                    ,[a2]
                    ,[a3]
                    ,[a4]
                    ,[a5]
                    ,[nilai]
                    ,[date_create]
                    ,[create_by]
                    ,[date_update]
                    ,[update_by]
                )
                VALUES 
                (
                    '".$_POST['kode_soal']."'
                    ,'".validate_input($pertanyaan_item)."'
                    ,'".validate_input($a1_item)."'
                    ,'".validate_input($a2_item)."'
                    ,'".validate_input($a3_item)."'
                    ,'".validate_input($a4_item)."'
                    ,'".validate_input($a5_item)."'
                    ,'".validate_input(str_replace(",",".",$nilai_item))."'
                    ,getdate()
                    ,'" . $_SESSION['nama'] . "'
                    ,getdate()
                    ,'" . $_SESSION['nama'] . "'
                ); SELECT SCOPE_IDENTITY()";
                $result2 = $sqlsrv_hris->query($sql_detail);
                if ($result2):
                    sqlsrv_next_result($result2);
                    sqlsrv_fetch($result2);
                    $insertedId = sqlsrv_get_field($result2, 0);

                    $sql_kunci = "INSERT INTO [db_hris].[dbo].[table_tes_jawaban]
                    (
                        [kode_soal]
                        ,[id_soal]
                        ,[jawaban]
                    )
                    VALUES 
                    (
                        '".$_POST['kode_soal']."'
                        ,'" . $insertedId . "'
                        ,'" . $jawaban_item . "'
                    )";

                    $result3 = $sqlsrv_hris->query($sql_kunci);

                    if (!$result3):
                        echo "Error: `$sql_kunci`";
                    endif;
                else:
                    echo "Error: \r\n" . $sql_detail;
                endif;

                $count++;
            endif;
        }
    else:
        echo "Error: \r\n" . $sql_update;
    endif;
elseif (isset($_POST['act']) && $_POST['act'] == "del"):
    $sql_del = "DELETE FROM [db_hris].[dbo].[table_tes_soal] WHERE kode_soal = '" . $_POST['kode_soal'] . "'";
    $result = $sqlsrv_hris->query($sql_del);
    if ($result):
        $del_status = "DELETE FROM [db_hris].[dbo].[table_tes_soal_detail] WHERE kode_soal = '" . $_POST['kode_soal'] . "'";
        $result2 = $sqlsrv_hris->query($del_status);
        if ($result2):
            $del_jawaban = "DELETE FROM [db_hris].[dbo].[table_tes_jawaban] WHERE kode_soal = '" . $_POST['kode_soal'] . "'";
            $result3 = $sqlsrv_hris->query($del_jawaban);
            if ($result3):
                echo "Data Berhasil dihapus";
            else:
                echo "Error: `$del_jawaban`";
            endif;
        else:
            echo "Error: `$del_status`";
        endif;
    else:
        echo "Error: `$sql_del`";
    endif;
endif;
?>