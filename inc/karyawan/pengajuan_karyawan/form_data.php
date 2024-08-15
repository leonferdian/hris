<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail"):
    $sql_select = "select * FROM [db_hris].[dbo].[table_pengajuan_karyawan] where id = '" . $_GET['id'] . "'";
    $stmt_select = $sqlsrv_hris->query($sql_select);
    $row_select = $sqlsrv_hris->fetch_array($stmt_select);
    echo '<input  type="hidden" id="id" value="' . $row_select['id'] . '"/>';
endif;
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Form Pengajuan Karyawan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <a href="?sm=pengajuan_karyawan"> <i class="fa fa-arrow-left"></i> Back</a>
            </div>
        <h1>
                Form Pengajuan Karyawan
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-body">
                    <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
                    <div id="list_vol">
                        <div class="form-horizontal">
                            <div class="form-group" >
                                <label class="col-sm-2 control-label">email kadiv</label>
                                <div class="col-sm-7">
                                    <input <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?> type="email" class="form-control input-sm" id="email_kadiv" name="email_kadiv"
                                        value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['email_kadiv']) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label">divisi</label>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm chosen-select" name="kode_divisi" id="kode_divisi"
                                        data-placeholder="Pilih Divisi" onchange="" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                        <option value=""></option>
                                        <?php
                                        $sql = "SELECT * FROM [db_hris].[dbo].[table_master_divisi] order by [nama_divisi]";
                                        $stmt = $sqlsrv_hris->query($sql);
                                        while ($row = $sqlsrv_hris->fetch_array($stmt)):
                                            $selected = $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" && $row['kode'] == $row_select['kode_divisi'] ? " selected" : "";
                                            echo '<option value="' . $row['kode'] . '" ' . $selected . '>' . strtoupper($row['nama_divisi']) . '</option>';
                                        endwhile;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label">jabatan kandidat</label>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm chosen-select" name="kode_jabatan_kandidat" id="kode_jabatan_kandidat"
                                        data-placeholder="Pilih Jabatan" onchange="" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                        <option value=""></option>
                                        <?php
                                        $sql = "SELECT * FROM [db_hris].[dbo].[table_master_jabatan] order by [nama_jabatan]";
                                        $stmt = $sqlsrv_hris->query($sql);
                                        while ($row = $sqlsrv_hris->fetch_array($stmt)):
                                            $selected = $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" && $row['kode'] == $row_select['kode_jabatan_kandidat'] ? " selected" : "";
                                            echo '<option value="' . $row['kode'] . '" ' . $selected . '>' . strtoupper($row['nama_jabatan']) . '</option>';
                                        endwhile;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label">Jenis Kelamin</label>
                                <div class="col-sm-7">
                                    <select class="form-control input-sm chosen-select" name="jenis_kelamin" id="jenis_kelamin"
                                        data-placeholder="Pilih Jenis Kelamin" onchange="" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                        <option <?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" && $row_select['jenis_kelamin'] == "PRIA" ? " selected" : ""; ?> value="PRIA">PRIA</option>
                                        <option <?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" && $row_select['jenis_kelamin'] == "WANITA" ? " selected" : ""; ?> value="WANITA">WANITA</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label">Kriteria</label>
                                <div class="col-sm-7">
                                    <div class="widget-box widget-color-green">
                                        <div class="widget-header widget-header-small"> </div>

                                        <div class="widget-body">
                                            <div class="widget-main no-padding">
                                                <div class="wysiwyg-editor" id="<?php echo $_GET['act'] == "detail" ? "" : "editor2"; ?>">
                                                    <?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['kriteria']) : ''; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label">tgl pengajuan</label>
                                <div class="col-sm-7">
                                    <input <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?> type="text" class="form-control input-sm datepicker" id="tgl_pengajuan" name="tgl_pengajuan"
                                        value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? date_format($row_select['tgl_pengajuan'], "Y-m-d") : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group" >
                                <label class="col-sm-2 control-label">tgl deadline</label>
                                <div class="col-sm-7">
                                    <input <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?> type="text" class="form-control input-sm datepicker" id="tgl_deadline" name="tgl_deadline"
                                        value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? date_format($row_select['tgl_deadline'], "Y-m-d") : ''; ?>">
                                </div>
                            </div>
                            <label class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-7">
                                <div class="pull-right">
                                    <?php if ($_GET['act'] != "detail"): ?>
                                    <button class="btn btn-sm btn-primary" onclick="<?php echo $_GET['act'] == "edit_dialog" ? "edit()" : "save()" ?>">
                                        <i class="ace-icon fa fa-check"></i>
                                        Save
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

    function save() {
        var email_kadiv = $("#email_kadiv").val();
        var kode_divisi = $("#kode_divisi").val();
        var kode_jabatan_kandidat = $("#kode_jabatan_kandidat").val();
        // var kriteria = $("#kriteria").val();
        var kriteria = $("#editor2").html();
        var jenis_kelamin = $("#jenis_kelamin").val();
        var tgl_pengajuan = $("#tgl_pengajuan").val();
        var tgl_deadline = $("#tgl_deadline").val();
        
        if (email_kadiv == "") {
            alert("Semua data Harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/karyawan/pengajuan_karyawan/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'add_data',
                    email_kadiv : email_kadiv,
                    kode_divisi: kode_divisi,
                    kode_jabatan_kandidat: kode_jabatan_kandidat,
                    kriteria: kriteria,
                    jenis_kelamin: jenis_kelamin,
                    tgl_pengajuan: tgl_pengajuan,
                    tgl_deadline: tgl_deadline
                },
                success: function(data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Ditambahkan") {
                        location.reload();
                    }
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }

    function edit() {
        var id = $("#id").val();
        var email_kadiv = $("#email_kadiv").val();
        var kode_divisi = $("#kode_divisi").val();
        var kode_jabatan_kandidat = $("#kode_jabatan_kandidat").val();
        // var kriteria = $("#kriteria").val();
        var kriteria = $("#editor2").html();
        var jenis_kelamin = $("#jenis_kelamin").val();
        var tgl_pengajuan = $("#tgl_pengajuan").val();
        var tgl_deadline = $("#tgl_deadline").val();

        if (email_kadiv == "") {
            alert("Semua data harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/karyawan/pengajuan_karyawan/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'edit_data',
                    id: id,
                    email_kadiv : email_kadiv,
                    kode_divisi: kode_divisi,
                    kode_jabatan_kandidat: kode_jabatan_kandidat,
                    kriteria: kriteria,
                    jenis_kelamin: jenis_kelamin,
                    tgl_pengajuan: tgl_pengajuan,
                    tgl_deadline: tgl_deadline
                },
                success: function(data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Diubah") {
                        location.reload();
                    }
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }
</script>