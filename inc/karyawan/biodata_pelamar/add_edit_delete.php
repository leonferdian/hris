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
elseif (isset($_POST['act']) && $_POST['act'] == "edit_data"):
    $sql_update = "UPDATE [db_hris].[dbo].[table_biodata_pelamar] SET
        [nama] = '" . validate_input(trim($_POST['nama'])) . "'
        ,[kode_divisi] = '" . validate_input(trim($_POST['email'])) . "'
        ,[kode_divisi] = '" . validate_input(trim($_POST['no_hp'])) . "'
        ,[kode_divisi] = '" . validate_input(trim($_POST['kode_jabatan'])) . "'
        WHERE id = '" . $_POST['id'] . "'";
    $result = $sqlsrv_hris->query($sql_update);
    if ($result):
        echo "Data Berhasil Diubah";
    else:
        echo "Error: \r\n" . $sql_update;
    endif;
elseif (isset($_POST['act']) && $_POST['act'] == "del"):
    $sql_del = "DELETE FROM [db_hris].[dbo].[table_biodata_pelamar] WHERE id = '" . $_POST['id'] . "'";
    $result = $sqlsrv_hris->query($sql_del);
    if ($result):
        echo "Data Berhasil dihapus";
    else:
        echo "Error: `$sql_del`";
    endif;
elseif (isset($_POST['act']) && ($_POST['act'] == "add_dialog" || $_POST['act'] == "edit_dialog")):
    if ($_POST['act'] == "edit_dialog"):
        $sql_select = "select * FROM [db_hris].[dbo].[table_biodata_pelamar] where id = '" . $_POST['id'] . "'";
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

            $('.datepicker').datepicker({
                Format: 'YYYY-MM-DD',
                autoclose: true,
                todayHighlight: true
            })
            .prev().on(ace.click_event, function () {
                $(this).next().focus();
            });
        });
    </script>
    <div class="row" style="width:100%;height:100%; margin-left: 5px;">
        <label class="col-sm-12 control-label">Nama Lengkap</label>
        <div class="col-sm-10">
            <input type="text" class="form-control input-sm" id="nama" name="nama" value="<?php echo $_POST['act'] == 'edit_dialog' ? trim($row_select['nama']) : ''; ?>">
        </div>

        <label class="col-sm-12 control-label">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control input-sm" id="email" name="email" value="<?php echo $_POST['act'] == 'edit_dialog' ? trim($row_select['email']) : ''; ?>">
        </div>

        <label class="col-sm-12 control-label">No hp</label>
        <div class="col-sm-10">
            <input type="text" class="form-control input-sm" id="no_hp" name="no_hp" value="<?php echo $_POST['act'] == 'edit_dialog' ? trim($row_select['no_hp']) : ''; ?>">
        </div>

        <label class="col-sm-12 control-label">Jabatan yang dilamar</label>
        <div class="col-sm-10">
            <select class="form-control input-sm chosen-select" name="kode_jabatan" id="kode_jabatan" data-placeholder="Pilih Jabatan"
                onchange="">
                <option value=""></option>
                <?php
                $sql = "SELECT * FROM [db_hris].[dbo].[table_master_jabatan] order by [nama_jabatan]";
                $stmt = $sqlsrv_hris->query($sql);
                while ($row = $sqlsrv_hris->fetch_array($stmt)):
                    $selected = $_POST['act'] == "edit_dialog" && $row['kode'] == $row_select['kode_jabatan'] ? " selected" : "";
                    echo '<option value="' . $row['kode'] . '" '.$selected.'>' . strtoupper($row['nama_jabatan']) . '</option>';
                endwhile;
                ?>
            </select>
        </div>
    </div>
<?php
endif;
?>