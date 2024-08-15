<?php 
include('../../../lib/database.php');
$sqlsrv_hris = DB::connection('sqlsrv_hris');
set_time_limit(0);
session_start();
function validate_input($data) {
    $data = str_replace("'", "''", $data);
    return $data;
}

if(isset($_POST['act']) && $_POST['act']=="add_data"):
    $sql_add = "INSERT INTO [db_hris].[dbo].[table_absensi_leave_category] 
    (
        leave_category
        ,keterangan
        )
    VALUES 
    (
        '".validate_input($_POST['leave_category'])."'
        ,'".validate_input($_POST['keterangan'])."'
    )";
    $result = $sqlsrv_hris->query($sql_add);
    if($result):
        echo "Data Berhasil Ditambahkan";
    else:
        echo "Error: \r\n".$sql_add;
    endif;   
elseif (isset($_POST['act']) && $_POST['act']=="edit_data"):
    $sql_update = "UPDATE [db_hris].[dbo].[table_absensi_leave_category] SET 
    leave_category = '".validate_input($_POST['leave_category'])."'
    ,keterangan = '".validate_input($_POST['keterangan'])."'
    WHERE id = '".validate_input($_POST['id'])."'";
    $result = $sqlsrv_hris->query($sql_update);
    if ($result):
        echo "Data Berhasil Diubah";
    else:
        echo "Error: \r\n".$sql_update;
    endif;
elseif(isset($_POST['act']) && $_POST['act']=="del"):
    $sql_del = "DELETE FROM [db_hris].[dbo].[table_absensi_leave_category] WHERE id = '".validate_input($_POST['id'])."'";
    $result = $sqlsrv_hris->query($sql_del);
    if($result):
        echo "Data Berhasil dihapus";
    else:
        echo "Error: `$sql_del`";
    endif;
elseif(isset($_POST['act']) && ($_POST['act']=="add_dialog" || $_POST['act']=="edit_dialog")):
    if($_POST['act']=="edit_dialog"):
        $sql_select = "select * FROM [db_hris].[dbo].[table_absensi_leave_category] where id = '".$_POST['id']."'";
        $stmt_select = $sqlsrv_hris->query($sql_select);
        $row_select = $sqlsrv_hris->fetch_array($stmt_select);
        echo '<input type="hidden" id="id" value="'.$row_select['id'].'"/>';
    endif;
    ?>
    <div class="row" style="width:100%;height:100%; margin-left: 5px;">
        <label class="col-sm-12 control-label">Kategori</label> 
        <div class="col-sm-10">
            <input type="text" class="form-control input-sm" id="leave_category" name = "leave_category" value="<?php echo $_POST['act'] == "edit_dialog" ? trim($row_select['leave_category']) : ""; ?>" >
        </div>
        <label class="col-sm-12 control-label">Keterangan</label> 
        <div class="col-sm-10">
            <input type="text" class="form-control input-sm" id="keterangan" name = "keterangan" value="<?php echo $_POST['act'] == "edit_dialog" ? trim($row_select['keterangan']) : ""; ?>" >
        </div>
    </div>
    <?php 
endif;
?>