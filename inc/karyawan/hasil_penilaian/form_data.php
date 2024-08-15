<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail"):
    $sql_select = "SELECT
                    a.[kode_penilaian]
                    ,a.[nama]
                    ,a.[divisi]
                    ,a.[tahun]
                    ,a.[penilaian]
                    ,b.[score]
                    ,b.[nilai]
                    ,b.[grade]
                    ,b.[score_atasan]
                    ,b.[grade_atasan]
                    ,b.[nilai_atasan]
                    ,b.[id] as id_result
                    ,b.[nama_karyawan]
                    ,b.[nik]
                    ,b.[hasil]
                    ,a.[category]
                    ,a.[kriteria_scoring]
                    ,c.[jabatan]
                    ,c.[lama_kerja_th]
                    ,c.[lama_kerja_bln]
                FROM [db_hris].[dbo].[table_penilaian_karyawan] a
                LEFT JOIN [db_hris].[dbo].[table_penilaian_karyawan_result] b
                    ON b.[id_penilaian] = a.id
                LEFT JOIN [db_hris].[dbo].[table_karyawan] c
                ON b.nik = c.nik
                WHERE a.kode_penilaian = '" . $_GET['kode_penilaian'] . "' 
                 AND b.nik = '" . $_GET['nik'] . "'";
    $stmt_select = $sqlsrv_hris->query($sql_select);
    $row_select = $sqlsrv_hris->fetch_array($stmt_select);
    $stmt_detail = $sqlsrv_hris->query($sql_select);
    $stmt_score = $sqlsrv_hris->query($sql_select);
    $total_score = $total_score_atasan = $total_nilai = $total_nilai_atasan = 0;
    // echo $sql_select;
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
            <li class="active">Arsip Penilaian Karyawan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <a href="?sm=hasil_penilaian"> <i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <h1>
                Arsip Penilaian Karyawan
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-2">Judul</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="nama" name="nama" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['nama']) : ""; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">Divisi</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="divisi" name="divisi" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['divisi']) : ""; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">Tahun</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="tahun" name="tahun" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['tahun']) : date("Y"); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3">Nama Karyawan</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="nama_karyawan" name="nama_karyawan" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['nama_karyawan']) : ""; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3">NIK</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="nik" name="nik" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['nik']) : ""; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3">Jabatan</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="jabatan" name="jabatan" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['jabatan']) : ""; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3">Masa Kerja</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control input-sm" id="masa_kerja" name="masa_kerja" value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? $row_select['lama_kerja_th']." tahun ".$row_select['lama_kerja_bln']." bulan" : ""; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div id="detail_jawaban" class="col-sm-12">
                            <h3>Score Detail</h3>
                            <ul>
                                <li><i>1 = Bad</i></li>
                                <li><i>2 = Average</i></li>
                                <li><i>3 = Good</i></li>
                                <li><i>4 = Very Good</i></li>
                                <li><i>5 = Excellent</i></li>
                            </ul>
                            <hr>
                            <?php if ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail"): ?>
                                <?php while ($row_detail = $sqlsrv_hris->fetch_array($stmt_detail)): ?>
                                    <input type="hidden" name="id_hasil[]" value="<?php echo $row_detail['id_result']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>/>
                                    <div id="isi_soal" class="col-xs-7">
                                        <div class="form-group">
                                            <label class="col-sm-2">
                                                <b>No.<?php echo $no; ?></b>
                                                <input type="hidden" name="id_penilaian[]" value="<?php echo $row_detail['id']; ?>" />
                                            </label>
                                            <label class="col-sm-5">
                                                <b><?php echo trim($row_detail['penilaian']); ?></b>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">Category</label>
                                            <div class="col-xs-5">
                                                <span><?php echo trim($row_detail['category']); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">Kriteria</label>
                                            <div class="col-xs-5">
                                                <span><?php echo trim($row_detail['kriteria_scoring']); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">Score dari Atasan:</label>
                                            <div class="col-xs-5">
                                                <div class="rating score-<?php echo $row_detail['id_result']; ?> inline"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">Score Karyawan:</label>
                                            <div class="col-xs-5">
                                                <div class="rating score2-<?php echo $row_detail['id_result']; ?> inline"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">Grade dari Atasan</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control input-sm" name="grade_atasan[]" value="<?php echo trim($row_detail['grade_atasan']); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">Grade Karyawan</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control input-sm" name="grade[]" value="<?php echo trim($row_detail['grade']); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">Nilai dari Atasan</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control input-sm" name="nilai_atasan[]" value="<?php echo trim($row_detail['nilai_atasan']); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">Nilai Karyawan</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control input-sm" name="nilai[]" value="<?php echo trim($row_detail['nilai']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <?php $total_score += $row_detail['score']; ?>
                                    <?php $total_score_atasan += $row_detail['score_atasan']; ?>
                                    <?php $total_nilai += $row_detail['nilai']; ?>
                                    <?php $total_nilai_atasan += $row_detail['nilai_atasan']; ?>
                                <?php $no++; endwhile; ?>
                            <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2">Pengoreksian</label>
                                <div class="col-sm-5">
                                    <div class="radio">
                                        <label>
                                            <input name="hasil" type="radio" class="ace" value="complete" <?php echo $row_select['hasil'] == "complete" ? " checked" : ""; ?>>
                                            <span class="lbl"> complete</span>
                                        </label>
                                        <label>
                                            <input name="hasil" type="radio" class="ace" value="revisi" <?php echo $row_select['hasil'] == "revisi" ? " checked" : ""; ?>>
                                            <span class="lbl"> revisi</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-7 control-label">
                                    <hr>
                                    <input type="hidden" name="total_score" value="<?php echo $total_score; ?>" >
                                    <input type="hidden" name="total_score_atasan" value="<?php echo $total_score_atasan; ?>" >
                                    <input type="hidden" name="total_nilai" value="<?php echo $total_nilai; ?>" >
                                    <input type="hidden" name="total_nilai_atasan" value="<?php echo $total_nilai_atasan; ?>" >
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">
                                </label>
                                <div class="col-sm-2">
                                    <input type="hidden" name="act" value="<?php echo $_GET['act'] == "edit_dialog" ? "edit_data" : "add_data" ?>">
                                    <div class="pull-right">
                                        <?php if ($_GET['act'] != "detail" || isset($row_select['hasil']) || $row_select['arsip'] != '1'): ?>
                                            <button class="btn btn-sm btn-primary" onclick="">
                                                <i class="ace-icon fa fa-cloud-upload"></i>
                                                Arsipkan
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

        <?php if ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail"): ?>
        <?php while ($row_score = $sqlsrv_hris->fetch_array($stmt_score)): ?>
            $('.score-<?php echo $row_score['id_result']; ?>').raty({
                'cancel' : true,
                'half': false,
                'starType' : 'i',
                'scoreName': 'score_atasan[]',
                'score': <?php echo $row_score['score_atasan']; ?>,
            })

            $('.score2-<?php echo $row_score['id_result']; ?>').raty({
                'cancel' : true,
                'half': false,
                'starType' : 'i',
                'scoreName': 'score[]',
                'score': <?php echo $row_score['score_atasan']; ?>,
            })
        <?php endwhile; ?>
        <?php endif; ?>

        $('#dataForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: 'inc/karyawan/hasil_penilaian/add_edit_delete.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response);
                    // $('#dataForm')[0].reset();
                    if (response.trim() == "Data Berhasil Disimpan") {
                        location.href="?sm=hasil_penilaian";
                    }
                }
            });
        });
    });
</script>