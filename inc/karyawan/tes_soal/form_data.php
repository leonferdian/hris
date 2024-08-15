<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail"):
    $sql_select = "select * FROM [db_hris].[dbo].[table_tes_soal] where kode_soal = '" . $_GET['kode_soal'] . "'";
    $stmt_select = $sqlsrv_hris->query($sql_select);
    $row_select = $sqlsrv_hris->fetch_array($stmt_select);

    $sql_detail = "SELECT a.*,b.[jawaban] FROM [db_hris].[dbo].[table_tes_soal_detail] a 
                    LEFT JOIN [db_hris].[dbo].[table_tes_jawaban] b
                    ON a.id = b.id_soal
                    WHERE a.kode_soal = '" . $_GET['kode_soal'] . "'
                    order by a.date_create";
    $stmt_detail = $sqlsrv_hris->query($sql_detail);
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
            <li class="active">Soal Tes Calon Karyawan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <a href="?sm=tes_soal"> <i class="fa fa-arrow-left"></i> Back</a>
            </div>
            <h1>
                Soal Tes Calon Karyawan
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-body">
                    <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...
                    </span>
                    <div id="list_vol">
                        <form id="dataForm" class="form-horizontal">
                            <input  type="hidden" name="kode_soal" value="<?php echo $row_select['kode_soal']; ?>"/>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Nama Soal</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control input-sm" id="nama_soal" name="nama_soal"
                                        value="<?php echo $_GET['act'] == "edit_dialog" || $_GET['act'] == "detail" ? trim($row_select['nama_soal']) : ''; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                </div>
                            </div>
                            <hr>
                            <div id="detail_soal">
                            <h3>Isi Soal</h3>
                            <?php if ($_GET['act'] == "edit_dialog" || $_GET['act'] == "detail"): ?>
                                <?php while ($row_detail = $sqlsrv_hris->fetch_array($stmt_detail)): ?>
                                    <div id="isi_soal">
                                        <div class="form-group">
                                            <label class="col-sm-9 control-label">
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
                                            <div class="col-sm-10">
                                                <input type="hidden" name="id_detail[]" value="<?php echo $row_detail['id']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Pertanyaan</label>
                                            <div class="col-sm-7">
                                                <textarea type="text" name="pertanyaan[]" class="form-control" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>><?php echo trim($row_detail['pertanyaan']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban A</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a1[]" class="form-control"
                                                    value="<?php echo $row_detail['a1']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban B</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a2[]" class="form-control"
                                                    value="<?php echo $row_detail['a2']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban C</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a3[]" class="form-control"
                                                    value="<?php echo $row_detail['a3']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban D</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a4[]" class="form-control"
                                                    value="<?php echo $row_detail['a4']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban E</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a5[]" class="form-control"
                                                    value="<?php echo $row_detail['a5']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nilai</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="nilai[]" class="form-control"
                                                    value="<?php echo $row_detail['nilai']; ?>" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Kunci Jawaban</label>
                                            <div class="col-sm-7">
                                                <select class="form-control input-sm chosen-select" name="jawaban[]" data-placeholder="Pilih Kunci" onchange="" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                                    <option value="a1" <?php echo $row_detail['jawaban'] == "a1" ? "selected" : ""; ?>>A</option>
                                                    <option value="a2" <?php echo $row_detail['jawaban'] == "a2" ? "selected" : ""; ?>>B</option>
                                                    <option value="a3" <?php echo $row_detail['jawaban'] == "a3" ? "selected" : ""; ?>>C</option>
                                                    <option value="a4" <?php echo $row_detail['jawaban'] == "a4" ? "selected" : ""; ?>>D</option>
                                                    <option value="a5" <?php echo $row_detail['jawaban'] == "a5" ? "selected" : ""; ?>>E</option>
                                                </select>
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
                                            <label class="col-sm-2 control-label">Pertanyaan</label>
                                            <div class="col-sm-7">
                                                <textarea type="text" name="pertanyaan[]" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban A</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a1[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban B</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a2[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban C</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a3[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban D</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a4[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban E</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a5[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nilai</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="nilai[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Kunci Jawaban</label>
                                            <div class="col-sm-7">
                                                <select class="form-control input-sm chosen-select" name="jawaban[]" data-placeholder="Pilih Kunci" onchange="">
                                                    <option value="a1">A</option>
                                                    <option value="a2">B</option>
                                                    <option value="a3">C</option>
                                                    <option value="a4">D</option>
                                                    <option value="a5">E</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                            <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-7 control-label">
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
                                <label class="col-sm-9 control-label">
                                    <hr>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-7 control-label">
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
        $('.chosen-select').css('width', '200px').select2({ allowClear: true })
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
                                            <label class="col-sm-9 control-label">
                                            </label>
                                            <div class="col-sm-2">
                                                <a style="cursor: pointer; text-decoration: none;" class="red removeRow"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label label-number">
                                                No.`+rowCount+`
                                            </label>
                                            <div class="col-sm-10">
                                                <input  type="hidden" name="id_detail[]" value=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Pertanyaan</label>
                                            <div class="col-sm-7">
                                                <textarea type="text" name="pertanyaan[]" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban A</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a1[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban B</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a2[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban C</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a3[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban D</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a4[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Jawaban E</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="a5[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nilai</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="nilai[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Kunci Jawaban</label>
                                            <div class="col-sm-7">
                                                <select class="form-control input-sm chosen-select" name="jawaban[]" data-placeholder="Pilih Kunci" onchange="">
                                                    <option value="a1">A</option>
                                                    <option value="a2">B</option>
                                                    <option value="a3">C</option>
                                                    <option value="a4">D</option>
                                                    <option value="a5">E</option>
                                                </select>
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

        $('#dataForm').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: 'inc/karyawan/tes_soal/add_edit_delete.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response);
                    // $('#dataForm')[0].reset();
                    if (response.trim() == "Data Berhasil Disimpan") {
                        location.href="?sm=tes_soal";
                    }
                }
            });
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
                url: "inc/karyawan/tes_soal/add_edit_delete.php",
                traditional: true,
                data: {
                    act: 'add_data',
                    email_kadiv: email_kadiv,
                    kode_divisi: kode_divisi,
                    kode_jabatan_kandidat: kode_jabatan_kandidat,
                    kriteria: kriteria,
                    jenis_kelamin: jenis_kelamin,
                    tgl_pengajuan: tgl_pengajuan,
                    tgl_deadline: tgl_deadline
                },
                success: function (data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Ditambahkan") {
                        location.reload();
                    }
                },
                error: function (msg) {
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
                url: "inc/karyawan/tes_soal/add_edit_delete.php",
                traditional: true,
                data: {
                    act: 'edit_data',
                    id: id,
                    email_kadiv: email_kadiv,
                    kode_divisi: kode_divisi,
                    kode_jabatan_kandidat: kode_jabatan_kandidat,
                    kriteria: kriteria,
                    jenis_kelamin: jenis_kelamin,
                    tgl_pengajuan: tgl_pengajuan,
                    tgl_deadline: tgl_deadline
                },
                success: function (data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Diubah") {
                        location.reload();
                    }
                },
                error: function (msg) {
                    alert(msg);
                }
            });
        }
    }
</script>