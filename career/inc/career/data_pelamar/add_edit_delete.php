<?php
include ('../../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
function validate_input($data)
{
    $data = str_replace("'", "''", $data);
    return $data;
}

if (isset($_POST['act']) && $_POST['act'] == "add_data"):
    $sql_add = "INSERT INTO [db_hris].[dbo].[table_biodata_pelamar]
        (
            [nama]
            ,[email]
            ,[no_hp]
            ,[kode_jabatan]
            ,[date_create]
        )
        VALUES 
        (
            '" . validate_input(trim($_POST['nama'])) . "'
            ,'" . validate_input(trim($_POST['email'])) . "'
            ,'" . validate_input(trim($_POST['no_hp'])) . "'
            ,'" . validate_input(trim($_POST['kode_jabatan'])) . "'
            ,getdate()
        );";
        $result = $sqlsrv_hris->query($sql_add);
    if ($result):
        echo "Data Berhasil Ditambahkan";
    else:
        echo "Error: \r\n" . $sql_add;
    endif;
elseif (isset($_POST['act']) && $_POST['act'] == "save_tes"):
    $id_soal = explode(",",$_POST['id_soal']);
    $jawaban = explode(",",$_POST['jawaban']);
    $nilai = explode(",",$_POST['nilai']);
    $id_peserta = $_POST['id_peserta'];
    $kode_soal = $_POST['kode_soal'];
    $total_jawaban_benar = 0;
    $total_score = 0;
    $hasil = 0;

    // print_r($id_soal);

    $sql_cek = "SELECT * FROM [db_hris].[dbo].[table_tes_hasil] WHERE [id_peserta] = '".$id_peserta."'";
    $result_cek = $sqlsrv_hris->query($sql_cek);
    $num_cek = $sqlsrv_hris->num_rows($result_cek);

    if ($num_cek == 0):
    $total_item = count($id_soal);

    foreach ($id_soal as $index => $id_soal_item):
        $jawaban_item = trim($jawaban[$index]);
        $nilai_item = trim($nilai[$index]);

        $sql_cek_jawaban = "SELECT * FROM [db_hris].[dbo].[table_tes_jawaban] WHERE kode_soal = '".$kode_soal."' AND id_soal = '".$id_soal_item."'";
        $result_jawaban = $sqlsrv_hris->query($sql_cek_jawaban);
        $row_jawaban = $sqlsrv_hris->fetch_array($result_jawaban);
        if ($row_jawaban['jawaban'] == $jawaban_item):
            $hasil = 1;
            $total_score += $nilai_item;
            $total_jawaban_benar++;
        endif;

        $sql_add = "INSERT INTO [db_hris].[dbo].[table_tes_record_hasil]
            (
                [id_peserta]
                ,[kode_soal]
                ,[id_soal]
                ,[jawaban_peserta]
                ,[hasil]
                ,[score]
                ,[time_stamp]
            )
            VALUES 
            (
                '" . $_POST['id_peserta'] . "'
                ,'" . $_POST['kode_soal'] . "'
                ,'" . $id_soal_item . "'
                ,'" . $jawaban_item . "'
                ,'" . $hasil . "'
                ,'" . $nilai_item . "'
                ,getdate()
            );";
            $result = $sqlsrv_hris->query($sql_add);
        if ($result):
            // echo "Data Berhasil Disimpan";
        else:
            echo "Error: \r\n" . $sql_add;
        endif;

    endforeach;

    $sql = "INSERT INTO [db_hris].[dbo].[table_tes_hasil]
        (
            [id_peserta]
            ,[total_soal]
            ,[total_jawaban_benar]
            ,[total_score]
            ,[date_create]
        )
        VALUES 
        (
            '" . $_POST['id_peserta'] . "'
            ,'" . $total_item . "'
            ,'" . $total_jawaban_benar . "'
            ,'" . $total_score . "'
            ,getdate()
        );";
    $result = $sqlsrv_hris->query($sql);
    if ($result):
        echo "Data Berhasil Disimpan";
    else:
        echo "Error: \r\n" . $sql_add;
    endif;

    else:
        echo "Anda sudah mengikuti tes sebelumnya, hubungi personalia jika ada kendala";
    endif;

endif;
?>