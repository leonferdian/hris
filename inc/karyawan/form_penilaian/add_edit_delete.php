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
    $category = $_POST['category'];
    $penilaian = $_POST['penilaian'];
    $kriteria_scoring = $_POST['kriteria_scoring'];
    $total_item = count($penilaian);

    $count = 1;
    foreach ($penilaian as $index => $penilaian_item) {
        $category_item = trim($category[$index]);
        $penilaian_item = trim(str_replace("\r", "<br>", $penilaian_item));
        $kriteria_scoring_item = trim($kriteria_scoring[$index]);

        $sql = "INSERT INTO [db_hris].[dbo].[table_penilaian_karyawan]
            (
                [kode_penilaian]
                ,[nama]
                ,[divisi]
                ,[tahun]
                ,[category]
                ,[penilaian]      
                ,[kriteria_scoring]
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
                ,'" . validate_input($category_item) . "'
                ,'" . validate_input($penilaian_item) . "'
                ,'" . validate_input($kriteria_scoring_item) . "'
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
    $id_penilaian = $_POST['id_penilaian'];
    $nama = $_POST['nama'];
    $divisi = $_POST['divisi'];
    $tahun = $_POST['tahun'];
    $category = $_POST['category'];
    $penilaian = $_POST['penilaian'];
    $kriteria_scoring = $_POST['kriteria_scoring'];
    $total_item = count($penilaian);

    $count = 1;
    foreach ($penilaian as $index => $penilaian_item) {
        $category_item = trim($category[$index]);
        $penilaian_item = trim(str_replace("\r", "<br>", $penilaian_item));
        $id_penilaian_item = trim($id_penilaian[$index]);
        $kriteria_scoring_item = trim($kriteria_scoring[$index]);

        if (isset($id_penilaian_item) && $id_penilaian_item != ""):
            $sql_update = "UPDATE [db_hris].[dbo].[table_penilaian_karyawan] SET
                    [nama] = '" . validate_input($nama) . "'
                    ,[category] = '" . validate_input($category_item) . "'
                    ,[penilaian] = '" . validate_input($penilaian_item) . "'
                    ,[kriteria_scoring] = '" . validate_input($kriteria_scoring_item) . "'
                    ,[date_update] = getdate()
                    ,[update_by] = '" . $_SESSION['nama'] . "'
                    WHERE id = '" . $id_penilaian_item . "'";
            $result = $sqlsrv_hris->query($sql_update);
            if (!$result):
            endif;
        else:
            $sql = "INSERT INTO [db_hris].[dbo].[table_penilaian_karyawan]
            (
                [kode_penilaian]
                ,[nama]
                ,[divisi]
                ,[tahun]
                ,[category]
                ,[penilaian]      
                ,[kriteria_scoring]
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
                ,'" . validate_input($category_item) . "'
                ,'" . validate_input($penilaian_item) . "'
                ,'" . validate_input($kriteria_scoring_item) . "'
                ,getdate()
                ,'" . $_SESSION['nama'] . "'
                ,getdate()
                ,'" . $_SESSION['nama'] . "'
            )";
            $result = $sqlsrv_hris->query($sql);
            if (!$result):
                echo "Error: \r\n" . $sql;
            endif;
        endif;
        if ($count == $total_item) echo "Data Berhasil Disimpan"; 
        $count++;
    }
elseif (isset($_POST['act']) && $_POST['act'] == "del"):
    $sql_del = "DELETE FROM [db_hris].[dbo].[table_penilaian_karyawan] WHERE kode_penilaian = '" . $_POST['kode_penilaian'] . "'";
    $result = $sqlsrv_hris->query($sql_del);
    if ($result):
        echo "Data Berhasil dihapus";
    else:
        echo "Error: `$sql_del`";
    endif;
endif;
?>