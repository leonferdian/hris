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
    $sql_cek = "SELECT * FROM [db_hris].[dbo].[table_depo_absensi] WHERE [username] = '" . $_POST['email'] . "' and [kode_depo] = '" . $_POST['kode_depo'] . "'";
    $result = $sqlsrv_hris->query($sql_cek);
    $exist = $sqlsrv_hris->num_rows($result);
    if ($exist == 0):
        $sql_add = "INSERT INTO [db_hris].[dbo].[table_depo_absensi]
        (
            [username]
            ,[kode_depo]
        )
        VALUES 
        (
            '" . validate_input(trim($_POST['email'])) . "'
            ,'" . validate_input($_POST['kode_depo']) . "'
        )";
        $result = $sqlsrv_hris->query($sql_add);
        if ($result):
            echo "Data Berhasil Ditambahkan";
        else:
            echo "Error: \r\n" . $sql_add;
        endif;
    else:
        echo "Data sudah ada";
    endif;
elseif (isset($_POST['act']) && $_POST['act'] == "edit_data"):
    $sql_cek = "SELECT * FROM [db_hris].[dbo].[table_depo_absensi] WHERE [username] = '" . $_POST['email'] . "' and [kode_depo] = '" . $_POST['kode_depo'] . "'";
    $result = $sqlsrv_hris->query($sql_cek);
    $exist = $sqlsrv_hris->num_rows($result);
    if ($exist == 0):
        $sql_update = "UPDATE [db_hris].[dbo].[table_depo_absensi] SET 
        [username] = '" . validate_input(trim($_POST['email'])) . "'
        ,[kode_depo] = '" . validate_input($_POST['kode_depo']) . "'
        WHERE id = '" . $_POST['id'] . "'";
        $result = $sqlsrv_hris->query($sql_update);
        if ($result):
            echo "Data Berhasil Diubah";
        else:
            echo "Error: \r\n" . $sql_update;
        endif;
    else:
        echo "Data sudah ada";
    endif;
elseif (isset($_POST['act']) && $_POST['act'] == "del"):
    $sql_del = "DELETE FROM [db_hris].[dbo].[table_depo_absensi] WHERE id = '" . $_POST['id'] . "'";
    $result = $sqlsrv_hris->query($sql_del);
    if ($result):
        echo "Data Berhasil dihapus";
    else:
        echo "Error: `$sql_del`";
    endif;
elseif (isset($_POST['act']) && ($_POST['act'] == "add_dialog" || $_POST['act'] == "edit_dialog")):
    if ($_POST['act'] == "edit_dialog"):
        $sql_select = "select * FROM [db_hris].[dbo].[table_depo_absensi] where id = '" . $_POST['id'] . "'";
        $stmt_select = $sqlsrv_hris->query($sql_select);
        $row_select = $sqlsrv_hris->fetch_array($stmt_select);
        echo '<input type="hidden" id="id" value="' . $row_select['id'] . '"/>';
    endif;
    ?>
    <script>
        $(document).ready(function () {
            $('.chosen-select').css('width', '450px').select2({ allowClear: true })
            .on('change', function () {
                // $(this).closest('form').validate().element($(this));
            });
        });
    </script>
    <div class="row" style="width:100%;height:100%; margin-left: 5px;">
        <label class="col-sm-12 control-label">Email</label>
        <div class="col-sm-10">
            <input type="text" class="form-control input-sm" id="email" name="email" value="<?php echo $_POST['act'] == "edit_dialog" ? trim($row_select['username']) : ""; ?>">
        </div>
        <label class="col-sm-12 control-label">Kode Depo</label>
        <div class="col-sm-10">
            <select class="form-control input-sm chosen-select" name="slc_depo" id="slc_depo" data-placeholder="Select Depo"
                onchange="">
                <option value=""></option>
                <?php
                $sql = "SELECT * FROM
                        (
                            SELECT cab_name as depo from [db_ftm].[dbo].[cabang]
                            union
                            SELECT pembagian3_nama as depo from [db_fin_pro].[dbo].[pembagian3]
                        ) a
                        " . $where_depo . "";
                $stmt = $sqlsrv_hris->query($sql);
                while ($row = $sqlsrv_hris->fetch_array($stmt)):
                    $selected = $_POST['act'] == "edit_dialog" && $row['depo'] == $row_select['kode_depo'] ? " selected" : "";
                    echo '<option value="' . $row['depo'] . '" '.$selected.'>' . strtoupper($row['depo']) . '</option>';
                endwhile;
                ?>
            </select>
        </div>
    </div>
<?php
endif;
?>