<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail"):
    $sql_select = "SELECT * FROM [db_hris].[dbo].[table_penilaian_karyawan] where kode_penilaian = '" . $_GET['kode_penilaian'] . "'";
    $stmt_select = $sqlsrv_hris->query($sql_select);
    $row_select = $sqlsrv_hris->fetch_array($stmt_select);
    $stmt_detail = $sqlsrv_hris->query($sql_select);
    $no = 1;
endif;
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Form Penilaian Hasil Kinerja Karyawan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <a href="?sm=form_penilaian"> <i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <h1>
                Form Penilaian Hasil Kinerja Karyawan
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-body">
                    <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...
                    </span>
                    <div id="list_vol">
                        <form id="dataForm" class="form-horizontal">
                            <input  type="hidden" name="kode_penilaian" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? $row_select['kode_penilaian'] : ""; ?>"/>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Judul</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-sm" id="nama" name="nama" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['nama']) : ""; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Divisi</label>
                                <div class="col-sm-5">
                                    <select class="form-control input-sm select2" name="divisi" id="divisi" data-placeholder="Select Divisi" onchange="">
                                        <option></option>
                                        <?php
                                        $sql = "SELECT * FROM [db_hris].[dbo].[table_master_divisi] ORDER BY [nama_divisi]";
                                        $stmt = $sqlsrv_hris->query($sql);
                                        while ($row = $sqlsrv_hris->fetch_array($stmt)):
                                            $selected = ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail") && $row['nama_divisi'] == $row_select['divisi'] ? " selected" : "";
                                            echo '<option data-kode_divisi="' . $row['kode'] . '" value="' . $row['nama_divisi'] . '" '.$selected.'>' . strtoupper($row['nama_divisi']) . '</option>';
                                        endwhile;
                                        ?>
                                    </select>
                                    <input type="hidden" id="kode_divisi" name="kode_divisi">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Tahun</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-sm" id="tahun" name="tahun" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['tahun']) : date("Y"); ?>" readonly>
                                </div>
                            </div>
                            <hr>
                            <div id="detail_soal">
                            <h3>Detail Isi Form</h3>
                            <?php if ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail"): ?>
                                <?php while ($row_detail = $sqlsrv_hris->fetch_array($stmt_detail)): ?>
                                    <div id="isi_soal">
                                        <div class="form-group">
                                            <label class="col-sm-7 control-label">
                                            </label>
                                            <div class="col-sm-2">
                                                <?php if ($_GET['act'] != "detail"): ?>
                                                    <a style="cursor: pointer; text-decoration: none;" class="red removeRow"><i class="fa fa-times"></i></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">
                                                No.<?php echo $no; ?>
                                            </label>
                                            <div class="col-sm-5">
                                                <input type="hidden" name="id_penilaian[]" value="<?php echo $row_detail['id']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Kategori</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control input-sm" id="category" name="category[]" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_detail['category']) : ""; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">penilaian</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control input-sm" id="penilaian" name="penilaian[]" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_detail['penilaian']) : ""; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Kriteria Scoring</label>
                                            <div class="col-sm-5">
                                                <textarea type="text" name="kriteria_scoring[]" class="form-control" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>><?php echo trim($row_detail['kriteria_scoring']); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php $no++; endwhile; ?>
                            <?php else: ?>
                                <div id="isi_soal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">
                                            No.1
                                        </label>
                                        <div class="col-sm-10">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kategori</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control input-sm" id="category" name="category[]">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Penilaian</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control input-sm" id="penilaian" name="penilaian[]">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kriteria Scoring</label>
                                        <div class="col-sm-5">
                                            <textarea type="text" name="kriteria_scoring[]" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">
                                </label>
                                <div class="col-sm-2">
                                    <div class="pull-right">
                                        <?php if ($_GET['act'] != "detail"): ?>
                                            <a style="cursor: pointer; text-decoration: none;" id="addRow" class="green"><i class="fa fa-plus"></i>  New</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-7 control-label">
                                    <hr>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">
                                </label>
                                <div class="col-sm-2">
                                    <input type="hidden" name="act" value="<?php echo $_GET['act'] == "edit_dialog" ? "edit_data" : "add_data" ?>">
                                    <div class="pull-right">
                                        <?php if ($_GET['act'] != "detail"): ?>
                                            <button class="btn btn-sm btn-primary" onclick="">
                                                <i class="ace-icon fa fa-check"></i>
                                                Save
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select2').css('width', '220px').select2({ allowClear: true })
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


        $('#addRow').click(function () {
            var rowCount = $('#detail_soal #isi_soal').length + 1;
            var newRow = `<div id="isi_soal">
                                        <div class="form-group">
                                            <label class="col-sm-7 control-label">
                                            </label>
                                            <div class="col-sm-2">
                                                <a style="cursor: pointer; text-decoration: none;" class="red removeRow"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label label-number">
                                                No.`+rowCount+`
                                            </label>
                                            <div class="col-sm-5">
                                                <input  type="hidden" name="id_penilaian[]" value=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Kategori</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control input-sm" id="category" name="category[]">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Penilaian</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control input-sm" id="penilaian" name="penilaian[]">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Kriteria Scoring</label>
                                            <div class="col-sm-5">
                                                <textarea type="text" name="kriteria_scoring[]" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>`;
            $('#detail_soal').append(newRow);

            $('.chosen-select').css('width', '200px').select2({ allowClear: true })
                .on('change', function () {
                    // $(this).closest('form').validate().element($(this));
                });
        });

        $(document).on('click', '.removeRow', function () {
            $(this).closest('#isi_soal').remove();
        });

        $('#divisi').change(function() {
            var selectedOption = $(this).find('option:selected');
            var kodeDivisi = selectedOption.data('kode_divisi');
            $('#kode_divisi').val(kodeDivisi);
        });

        $('#dataForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: 'inc/karyawan/form_penilaian/add_edit_delete.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response);
                    // $('#dataForm')[0].reset();
                    if (response.trim() == "Data Berhasil Disimpan") {
                        location.href="?sm=form_penilaian";
                    }
                }
            });
        });
    });
</script>