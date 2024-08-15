<?php
include ('../../../lib/database.php');
include ('send_email.php');
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
    $pertanyaan = $_POST['pertanyaan'];
    $score = $_POST['score'];
    $total_item = count($pertanyaan);

    $count = 1;
    foreach ($pertanyaan as $index => $pertanyaan_item) {
        $pertanyaan_item = trim(str_replace("\r", "<br>", $pertanyaan_item));
        $score_item = trim($score[$index]);

        $sql = "INSERT INTO [db_hris].[dbo].[table_penilaian_karyawan_result]
            (
                [kode_penilaian]
                ,[nama]
                ,[divisi]
                ,[tahun]
                ,[pertanyaan]
                ,[score]
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
                ,'" . validate_input($pertanyaan_item) . "'
                ,'" . validate_input(str_replace(",", ".", $score_item)) . "'
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
    $kode_penilaian = $_POST['kode_penilaian'];
    $id_hasil = $_POST['id_hasil'];
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $divisi = $_POST['divisi'];
    $tahun = $_POST['tahun'];
    $jabatan = $_POST['jabatan'];
    $masa_kerja = $_POST['masa_kerja'];
    $total_score = $_POST['total_score'];
    $total_score_atasan = $_POST['total_score_atasan'];
    $total_nilai = $_POST['total_nilai'];
    $total_nilai_atasan = $_POST['total_nilai_atasan'];
    $total_item = count($id_hasil);
    $sql_email = "SELECT a.email, b.email_atasan FROM [db_hris].[dbo].[table_karyawan] a
                LEFT JOIN [db_hris].[dbo].[table_email_atasan] b
                ON a.email = b.email_user
                WHERE a.nik = '".$nik."'";
    $stmt_email = $sqlsrv_hris->query($sql_email);
    $row_email = $sqlsrv_hris->fetch_array($stmt_email);
    $count = 1;
    foreach ($id_hasil as $index => $id_hasil_item) {

        $sql_update = "UPDATE [db_hris].[dbo].[table_penilaian_karyawan_result] SET
                    [arsip] = '1'
                    ,[date_update] = getdate()
                    ,[update_by] = '" . $_SESSION['nama'] . "'
                    WHERE id = '" . $id_hasil_item . "'";
        $result = $sqlsrv_hris->query($sql_update);

        if (!$result):
            echo "Error: \r\n" . $sql_update;
        endif;

        $data = array(
            'judul' => $nama,
            'kode_penilaian' => $kode_penilaian,
            'nama_karyawan' => $nama_karyawan,
            'nik' => $nik,
            'divisi' => $divisi,
            'tahun' => $tahun,
            'jabatan' => $jabatan,
            'masa_kerja' => $masa_kerja,
            'total_score' => $total_score,
            'total_score_atasan' => $total_score_atasan,
            'total_nilai' => $total_nilai,
            'total_nilai_atasan' => $total_nilai_atasan,
        );

        if ($count == $total_item) {
            send_email($row_email['email'], $row_email['email_atasan'],$data);
            echo "Data Berhasil Disimpan"; 
        }

        $count++;
    }
elseif (isset($_POST['act']) && $_POST['act'] == "del"):
    $sql_del = "DELETE FROM [db_hris].[dbo].[table_penilaian_karyawan_result] WHERE kode_penilaian = '" . $_POST['kode_penilaian'] . "'";
    $result = $sqlsrv_hris->query($sql_del);
    if ($result):
        echo "Data Berhasil dihapus";
    else:
        echo "Error: `$sql_del`";
    endif;
endif;
?>