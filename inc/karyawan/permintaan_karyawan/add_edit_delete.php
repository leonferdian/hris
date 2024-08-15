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

if (isset($_POST['act']) && $_POST['act'] == "update_status"):
    $sql_add = "UPDATE [db_hris].[dbo].[table_approval_pengajuan_karyawan] 
    SET [status] = '" . validate_input(trim($_POST['status'])) . "' WHERE [id_pengajuan] = '" . $_POST['id'] . "'";
        $result = $sqlsrv_hris->query($sql_add);
    if ($result):
        echo "Data berhasil diupdate";
    else:
        echo "Error: \r\n" . $sql_add;
    endif;
endif;
?>