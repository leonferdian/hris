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
        $sql_add = "INSERT INTO [db_hris].[dbo].[table_master_divisi] 
                    (
                        [kode]
                        ,[nama_divisi]
                        ,[create_by]
                        ,[date_create]
                        ,[update_by]
                        ,[date_update]
                    )
                    VALUES 
                    (
                        '".validate_input($_POST['kode'])."'
                        ,'".validate_input($_POST['nama_divisi'])."'
                        ,'".$_SESSION['nama']."'
                        ,getdate()
                        ,'".$_SESSION['nama']."'
                        ,getdate()
                    )";
        $result = $sqlsrv_hris->query($sql_add);
        if($result):
            echo "Data Berhasil Ditambahkan";
        else:
            echo "Error: \r\n".$sql_add;
        endif;   
    elseif (isset($_POST['act']) && $_POST['act']=="edit_data"):
        $sql_update = "UPDATE [db_hris].[dbo].[table_master_divisi] SET 
                    kode = '".validate_input($_POST['kode'])."'
                    ,nama_divisi = '".validate_input($_POST['nama_divisi'])."'
                    ,update_by = '".$_SESSION['nama']."'
                    ,date_update = getdate()
                    WHERE id = '".validate_input($_POST['id'])."'";
        $result = $sqlsrv_hris->query($sql_update);
        if ($result):
            echo "Data Berhasil Diubah";
        else:
            echo "Error: \r\n".$sql_update;
        endif;
    elseif(isset($_POST['act']) && $_POST['act']=="del"):
        $sql_del = "DELETE FROM [db_hris].[dbo].[table_master_divisi] WHERE id = '".validate_input($_POST['id'])."'";
        $result = sqlsrv_query($conn_ilv, $sql_del);
        if($result):
            echo "Data Berhasil dihapus";
        else:
            echo "Error: `$sql_del`";
        endif;
    elseif(isset($_POST['act']) && ($_POST['act']=="add_dialog" || $_POST['act']=="edit_dialog")):
        if($_POST['act']=="edit_dialog"):
            $sql_select = "select * from [db_hris].[dbo].[table_master_divisi] where id = '".$_POST['id']."'";
            $stmt_select = $sqlsrv_hris->query($sql_select);
            $row_select = $sqlsrv_hris->fetch_array($stmt_select);
            echo '<input type="hidden" id="id" value="'.$row_select['id'].'"/>';
        endif;
        ?>
        <div class="row" style="width:100%;height:100%; margin-left: 5px;">
            <label class="col-sm-12 control-label">Kode</label> 
            <div class="col-sm-10">
                <input type="text" class="form-control input-sm" id="kode" name = "kode" value="<?php echo $_POST['act'] == "edit_dialog" ? trim($row_select['kode']) : ""; ?>" >
            </div>
            <label class="col-sm-12 control-label">Nama Divisi</label> 
            <div class="col-sm-10">
                <input type="text" class="form-control input-sm" id="nama_divisi" name = "nama_divisi" value="<?php echo $_POST['act'] == "edit_dialog" ? trim($row_select['nama_divisi']) : ""; ?>" >
            </div>
        </div>
    <?php 
    endif;
?>