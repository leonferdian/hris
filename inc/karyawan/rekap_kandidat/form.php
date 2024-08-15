<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<?php $where_depo = $_SESSION['total_depo'] != 0 ? " WHERE depo in ".$_SESSION['akses_depo']."" : ""; ?>
<?php
if(isset($_GET['id']) && $_GET['id'] != ""):
    $sql = "select * FROM [db_hris].[dbo].[table_rekap_kandidat] where id = '".$_GET['id']."'";
    // echo $sql;
    $stmt = $sqlsrv_hris->query($sql);
    $row_data = $sqlsrv_hris->fetch_array($stmt);
    echo '<input type="hidden" id="id" value="'.$row_data['id'].'">';
else:
    echo '<input type="hidden" id="id" value="">';
endif;
$sql_kandidat = "select * FROM [db_hris].[dbo].[table_biodata_pelamar] where id = '".$_GET['id_kandidat']."'";
// echo $sql_kandidat;
$stmt_kandidat = $sqlsrv_hris->query($sql_kandidat);
$row_kandidat = $sqlsrv_hris->fetch_array($stmt_kandidat);
echo '<input type="hidden" id="id_kandidat" value="'.$row_kandidat['id'].'">';
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Dashboard</a>
            </li>
            <li class="active">Form Rekap Kandidat</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Form Rekap Kandidat
            </h1>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title"></h4>
                        <span class="widget-toolbar">
                            <a href="#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Nama Lengkap </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control input-sm" id="nama" name="nama" value="<?php echo $row_kandidat['nama']; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Email </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control input-sm" id="email" name="email" value="<?php echo $row_kandidat['email']; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> No hp </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control input-sm" id="no_hp" name="no_hp" value="<?php echo $row_kandidat['no_hp']; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Jabatan dilamar </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control input-sm" id="kode_jabatan" name="kode_jabatan" value="<?php echo $row_kandidat['kode_jabatan']; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> tgl screening </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control input-sm datepicker1" id="tgl_screening" name="tgl_screening" value="<?php echo isset($_GET['id']) && $_GET['id'] != "" ? $row_data['tgl_screening'] : ""; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> tgl interview </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control input-sm datepicker1" id="tgl_interview" name="tgl_interview" value="<?php echo isset($_GET['id']) && $_GET['id'] != "" ? $row_data['tgl_interview'] : ""; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> posisi </label>
                                        <div class="col-sm-7">
                                            <select class="form-control input-sm select2" name="slc_posisi" id="slc_posisi" data-placeholder="Select Posisi" onchange="" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                                <?php
                                                    $sql_seksi = "SELECT * FROM [db_hris].[dbo].[table_master_seksi] ORDER BY nama_seksi asc";
                                                    $stmt_seksi = $sqlsrv_hris->query($sql_seksi);
                                                    while ($row_seksi = sqlsrv_fetch_array($stmt_seksi)):
                                                        $selected = (isset($_GET['id']) && $_GET['id'] != "") && trim($row_seksi['nama_seksi']) == trim($row_data['posisi']) ? 'selected':'';
                                                        echo '<option value="' . $row_seksi['nama_seksi'] . '" '.$selected.'>' . $row_seksi['nama_seksi'] . '</option>';
                                                    endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> No WA </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control input-sm" id="no_wa" name="no_wa" value="<?php echo isset($_GET['id']) && $_GET['id'] != "" ? $row_data['no_wa'] : ""; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> form </label>
                                        <div class="col-sm-7">
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="form" value="Y" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['form'] == "Y" ? "checked" : ""; ?> />
                                                    <span class="lbl"> Y</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="form" value="N" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['form'] == "N" ? "checked" : ""; ?> />
                                                    <span class="lbl"> N</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> interview1 </label>
                                        <div class="col-sm-7">
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="interview1" value="Y" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['interview1'] == "Y" ? "checked" : ""; ?> />
                                                    <span class="lbl"> Y</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="interview1" value="N" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['interview1'] == "N" ? "checked" : ""; ?> />
                                                    <span class="lbl"> N</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> interview2 </label>
                                        <div class="col-sm-7">
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="interview2" value="Y" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['interview2'] == "Y" ? "checked" : ""; ?> />
                                                    <span class="lbl"> Y</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="interview2" value="N" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['interview2'] == "N" ? "checked" : ""; ?> />
                                                    <span class="lbl"> N</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> interview3 </label>
                                        <div class="col-sm-7">
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="interview3" value="Y" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['interview3'] == "Y" ? "checked" : ""; ?> />
                                                    <span class="lbl"> Y</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="interview3" value="N" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['interview3'] == "N" ? "checked" : ""; ?> />
                                                    <span class="lbl"> N</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> pertimbangkan </label>
                                        <div class="col-sm-7">
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="pertimbangkan" value="Y" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['pertimbangkan'] == "Y" ? "checked" : ""; ?> />
                                                    <span class="lbl"> Y</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="pertimbangkan" value="N" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['pertimbangkan'] == "N" ? "checked" : ""; ?> />
                                                    <span class="lbl"> N</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> final </label>
                                        <div class="col-sm-7">
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="final" value="Y" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['final'] == "Y" ? "checked" : ""; ?> />
                                                    <span class="lbl"> Y</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="line-height-1 blue">
                                                    <input name="final" value="N" type="radio" class="ace" <?php echo (isset($_GET['id']) && $_GET['id'] != "") && $row_data['final'] == "N" ? "checked" : ""; ?> />
                                                    <span class="lbl"> N</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Keterangan </label>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" id="keterangan" placeholder="Default Text" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>><?php echo isset($_GET['id']) && $_GET['id'] != "" ? $row_data['keterangan'] : ""; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?php if ($_GET['act'] != "detail"): ?>
                                        <label class="col-sm-3 control-label no-padding-top">
                                            <span id="progress1" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <button class="btn btn-sm btn-success" onclick="save()">
                                                <i class="ace-icon fa fa-check"></i>
                                                Save
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        $('.select2').css('width', '200px').select2({ allowClear: true })
            .on('change', function () {
                // $(this).closest('form').validate().element($(this));
            });

        $('.datepicker1').datepicker({
            Format: 'YYYY-MM-DD',
            autoclose: true,
            todayHighlight: true

        })
        .prev().on(ace.click_event, function () {
            $(this).next().focus();
        });

        const d = new Date();
        var year = d.getFullYear();
        var month = d.getMonth();
        var startDay = new Date(year, 0, 1);
        var endDay = new Date(year, month + 1, 0);
        var now = d.getDate();

        if ($('#id').val() != "") {
            startDay = $('#startDate').val();
            endDay = $('#endDate').val();
        }

        $('input[name=date-range-picker]').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            startDate: now,
            endDate: now,
            locale: {
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                format: 'YYYY-MM-DD'
            }
        })
        .prev().on(ace.click_event, function () {
            $(this).next().focus();
        });
    });

    function save() {
        var queue = $('#progress1');
        var act = "save";
        var id = $('#id').val();
        var id_kandidat = $('#id_kandidat').val();
        var tgl_screening = $('#tgl_screening').val();
        var tgl_interview = $('#tgl_interview').val();
        var posisi = $('#slc_posisi').val();
        var no_wa = $('#no_wa').val();
        var form = $('input[name="form"]:checked').val();
        var interview1 = $('input[name="interview1"]:checked').val();
        var interview2 = $('input[name="interview2"]:checked').val();
        var interview3 = $('input[name="interview3"]:checked').val();
        var pertimbangkan = $('input[name="pertimbangkan"]:checked').val();
        var final = $('input[name="final"]:checked').val();
        var keterangan = $('#keterangan').val();
        queue.show();
        $.ajax({
            type: "POST",
            url: "inc/karyawan/rekap_kandidat/proc.php",
            traditional: true,
            data: {
                act: act,
                id: id,
                id_kandidat: id_kandidat,
                tgl_screening: tgl_screening,
                tgl_interview: tgl_interview,
                posisi: posisi,
                no_wa: no_wa,
                form: form,
                interview1: interview1,
                interview2: interview2,
                interview3: interview3,
                pertimbangkan: pertimbangkan,
                final: final,
                keterangan: keterangan
            },
            success: function (data) {
                alert(data);
                if (data.trim() == "Data Saved") {
                    location.href="?sm=rekap_kandidat";
                }
                queue.hide();
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }
</script>