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
    $sql_add = "INSERT INTO [db_hris].[dbo].[table_pengajuan_karyawan]
        (
            [no_pengajuan]
            ,[email_kadiv]
            ,[kode_divisi]
            ,[kode_jabatan_kandidat]
            ,[kriteria]
            ,[jenis_kelamin]
            ,[tgl_pengajuan]
            ,[tgl_deadline]
            ,[date_create]
            ,[create_by]
            ,[update_date]
            ,[update_by]
        )
        VALUES 
        (
            '" . $_POST['kode_divisi'] . $_POST['kode_jabatan_kandidat'] . rand(0, 999) . "'
            ,'" . validate_input(trim($_POST['email_kadiv'])) . "'
            ,'" . validate_input(trim($_POST['kode_divisi'])) . "'
            ,'" . validate_input(trim($_POST['kode_jabatan_kandidat'])) . "'
            ,'" . validate_input(trim($_POST['kriteria'])) . "'
            ,'" . validate_input(trim($_POST['jenis_kelamin'])) . "'
            ,'" . validate_input(trim($_POST['tgl_pengajuan'])) . "'
            ,'" . validate_input(trim($_POST['tgl_deadline'])) . "'
            ,getdate()
            ,'" . $_SESSION['nama'] . "'
            ,getdate()
            ,'" . $_SESSION['nama'] . "'
        ); SELECT SCOPE_IDENTITY()";
    $result = $sqlsrv_hris->query($sql_add);
    sqlsrv_next_result($result);
    sqlsrv_fetch($result);
    $insertedId = sqlsrv_get_field($result, 0);
    if ($result):
        $sql_status = "INSERT INTO [db_hris].[dbo].[table_approval_pengajuan_karyawan]
        (
            [id_pengajuan]
            ,[status]
        )
        VALUES 
        (
            '" . $insertedId . "'
            ,'draft'
        )";
        $result2 = $sqlsrv_hris->query($sql_status);

        if ($result2):
            echo "Data Berhasil Ditambahkan";
        else:
            echo "Error: `$sql_status`";
        endif;

    else:
        echo "Error: \r\n" . $sql_add;
    endif;
elseif (isset($_POST['act']) && $_POST['act'] == "edit_data"):
    $sql_update = "UPDATE [db_hris].[dbo].[table_pengajuan_karyawan] SET
        [email_kadiv] = '" . validate_input(trim($_POST['email_kadiv'])) . "'
        ,[kode_divisi] = '" . validate_input(trim($_POST['kode_divisi'])) . "'
        ,[kode_jabatan_kandidat] = '" . validate_input(trim($_POST['kode_jabatan_kandidat'])) . "'
        ,[kriteria] = '" . validate_input(trim($_POST['kriteria'])) . "'
        ,[jenis_kelamin] = '" . validate_input(trim($_POST['jenis_kelamin'])) . "'
        ,[tgl_pengajuan] = '" . validate_input(trim($_POST['tgl_pengajuan'])) . "'
        ,[tgl_deadline] = '" . validate_input(trim($_POST['tgl_deadline'])) . "'
        ,[update_date] = getdate()
        ,[update_by] = '" . $_SESSION['nama'] . "'
        WHERE id = '" . $_POST['id'] . "'";
    $result = $sqlsrv_hris->query($sql_update);
    if ($result):
        echo "Data Berhasil Diubah";
    else:
        echo "Error: \r\n" . $sql_update;
    endif;
elseif (isset($_POST['act']) && $_POST['act'] == "del"):
    $sql_del = "DELETE FROM [db_hris].[dbo].[table_pengajuan_karyawan] WHERE id = '" . $_POST['id'] . "'";
    $result = $sqlsrv_hris->query($sql_del);
    if ($result):
        $del_status = "DELETE FROM [db_hris].[dbo].[table_approval_pengajuan_karyawan] WHERE id_pengajuan = '" . $_POST['id'] . "'";
        $result2 = $sqlsrv_hris->query($sql_del);
        if ($result2):
            echo "Data Berhasil dihapus";
        else:
            echo "Error: `$del_status`";
        endif;
    else:
        echo "Error: `$sql_del`";
    endif;
elseif (isset($_POST['act']) && ($_POST['act'] == "add_dialog" || $_POST['act'] == "edit_dialog" || $_POST['act'] == "detail")):
    if ($_POST['act'] == "edit_dialog" || $_POST['act'] == "detail"):
        $sql_select = "select * FROM [db_hris].[dbo].[table_pengajuan_karyawan] where id = '" . $_POST['id'] . "'";
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

            if (typeof jQuery.ui !== 'undefined' && ace.vars['webkit']) {

                var lastResizableImg = null;
                function destroyResizable() {
                    if (lastResizableImg == null) return;
                    lastResizableImg.resizable("destroy");
                    lastResizableImg.removeData('resizable');
                    lastResizableImg = null;
                }

                var enableImageResize = function () {
                    $('.wysiwyg-editor')
                        .on('mousedown', function (e) {
                            var target = $(e.target);
                            if (e.target instanceof HTMLImageElement) {
                                if (!target.data('resizable')) {
                                    target.resizable({
                                        aspectRatio: e.target.width / e.target.height,
                                    });
                                    target.data('resizable', true);

                                    if (lastResizableImg != null) {
                                        //disable previous resizable image
                                        lastResizableImg.resizable("destroy");
                                        lastResizableImg.removeData('resizable');
                                    }
                                    lastResizableImg = target;
                                }
                            }
                        })
                        .on('click', function (e) {
                            if (lastResizableImg != null && !(e.target instanceof HTMLImageElement)) {
                                destroyResizable();
                            }
                        })
                        .on('keydown', function () {
                            destroyResizable();
                        });
                }

                enableImageResize();

                /**
                //or we can load the jQuery UI dynamically only if needed
                if (typeof jQuery.ui !== 'undefined') enableImageResize();
                else {//load jQuery UI if not loaded
                    //in Ace demo ./components will be replaced by correct components path
                    $.getScript("assets/js/jquery-ui.custom.min.js", function(data, textStatus, jqxhr) {
                        enableImageResize()
                    });
                }
                */
            }

            $('#editor2').css({ 'height': '200px' }).ace_wysiwyg({
                toolbar_place: function (toolbar) {
                    return $(this).closest('.widget-box')
                        .find('.widget-header').prepend(toolbar)
                        .find('.wysiwyg-toolbar').addClass('inline');
                },
                toolbar:
                    [
                        'bold',
                        { name: 'italic', title: 'Change Title!', icon: 'ace-icon fa fa-leaf' },
                        'strikethrough',
                        null,
                        'insertunorderedlist',
                        'insertorderedlist',
                        null,
                        'justifyleft',
                        'justifycenter',
                        'justifyright'
                    ],
                speech_button: false
            });
        });
    </script>
    <div class="row" style="width:100%;height:100%; margin-left: 5px;">

        <label class="col-sm-12 control-label">email kadiv</label>
        <div class="col-sm-10">
            <input type="email" class="form-control input-sm" id="email_kadiv" name="email_kadiv"
                value="<?php echo $_POST['act'] == "edit_dialog" || $_POST['act'] == "detail" ? trim($row_select['email_kadiv']) : ''; ?>">
        </div>

        <label class="col-sm-12 control-label">divisi</label>
        <div class="col-sm-10">
            <select class="form-control input-sm chosen-select" name="kode_divisi" id="kode_divisi"
                data-placeholder="Pilih Divisi" onchange="">
                <option value=""></option>
                <?php
                $sql = "SELECT * FROM [db_hris].[dbo].[table_master_divisi] order by [nama_divisi]";
                $stmt = $sqlsrv_hris->query($sql);
                while ($row = $sqlsrv_hris->fetch_array($stmt)):
                    $selected = $_POST['act'] == "edit_dialog" || $_POST['act'] == "detail" && $row['kode'] == $row_select['kode_divisi'] ? " selected" : "";
                    echo '<option value="' . $row['kode'] . '" ' . $selected . '>' . strtoupper($row['nama_divisi']) . '</option>';
                endwhile;
                ?>
            </select>
        </div>

        <label class="col-sm-12 control-label">jabatan kandidat</label>
        <div class="col-sm-10">
            <select class="form-control input-sm chosen-select" name="kode_jabatan_kandidat" id="kode_jabatan_kandidat"
                data-placeholder="Pilih Jabatan" onchange="">
                <option value=""></option>
                <?php
                $sql = "SELECT * FROM [db_hris].[dbo].[table_master_jabatan] order by [nama_jabatan]";
                $stmt = $sqlsrv_hris->query($sql);
                while ($row = $sqlsrv_hris->fetch_array($stmt)):
                    $selected = $_POST['act'] == "edit_dialog" || $_POST['act'] == "detail" && $row['kode'] == $row_select['kode_jabatan_kandidat'] ? " selected" : "";
                    echo '<option value="' . $row['kode'] . '" ' . $selected . '>' . strtoupper($row['nama_jabatan']) . '</option>';
                endwhile;
                ?>
            </select>
        </div>

        <label class="col-sm-12 control-label">Jenis Kelamin</label>
        <div class="col-sm-10">
            <select class="form-control input-sm chosen-select" name="jenis_kelamin" id="jenis_kelamin"
                data-placeholder="Pilih Jenis Kelamin" onchange="">
                <option value="PRIA">PRIA</option>
                <option value="WANITA">WANITA</option>
            </select>
        </div>

        <label class="col-sm-12 control-label">Kriteria</label>
        <div class="col-sm-10">
            <div class="widget-box widget-color-green">
                <div class="widget-header widget-header-small"> </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">
                        <div class="wysiwyg-editor" id="editor2">
                            <?php echo $_POST['act'] == "edit_dialog" || $_POST['act'] == "detail" ? trim($row_select['kriteria']) : ''; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <label class="col-sm-12 control-label">tgl pengajuan</label>
        <div class="col-sm-10">
            <input type="text" class="form-control input-sm datepicker" id="tgl_pengajuan" name="tgl_pengajuan"
                value="<?php echo $_POST['act'] == "edit_dialog" || $_POST['act'] == "detail" ? date_format($row_select['tgl_pengajuan'], "Y-m-d") : ''; ?>">
        </div>

        <label class="col-sm-12 control-label">tgl deadline</label>
        <div class="col-sm-10">
            <input type="text" class="form-control input-sm datepicker" id="tgl_deadline" name="tgl_deadline"
                value="<?php echo $_POST['act'] == "edit_dialog" || $_POST['act'] == "detail" ? date_format($row_select['tgl_deadline'], "Y-m-d") : ''; ?>">
        </div>
    </div>
    <?php
endif;
?>