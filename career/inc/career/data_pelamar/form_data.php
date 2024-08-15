<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<?php if (isset($_GET['act']) && $_GET['act'] == "basic-test"): ?>
    <?php include "inc/career/data_pelamar/soal_tes.php"; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "finish"): ?>
    <?php include "inc/career/data_pelamar/finish.php"; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "home"): ?>
    <?php include "inc/career/data_pelamar/home.php"; ?>
<?php else: ?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state hide" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Form Data Pelamar</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Form Data Pelamar
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title"></h4>
                    </div>
                    <div class="widget-body">
                        <br><br>
                        <div id="list_vol">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nama Lengkap</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control input-sm" id="nama" name="nama">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-7">
                                        <input type="email" class="form-control input-sm" id="email" name="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">No hp</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control input-sm" id="no_hp" name="no_hp">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jabatan yang dilamar</label>
                                    <div class="col-sm-9">
                                        <select class="form-control input-sm chosen-select" name="kode_jabatan" id="kode_jabatan" data-placeholder="Pilih Jabatan"
                                            onchange="">
                                            <option value=""></option>
                                            <?php
                                            $sql = "SELECT * FROM [db_hris].[dbo].[table_master_jabatan] order by [nama_jabatan]";
                                            $stmt = $sqlsrv_hris->query($sql);
                                            while ($row = $sqlsrv_hris->fetch_array($stmt)):
                                                echo '<option value="' . $row['nama_jabatan'] . '">' . strtoupper($row['nama_jabatan']) . '</option>';
                                            endwhile;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">&nbsp;</label>
                                    <div class="col-sm-7">
                                        <div class="pull-right">
                                            <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
                                            <button class="btn btn-sm btn-primary" onclick="save()">
                                                <i class="ace-icon fa fa-check"></i>
                                                Submit
                                            </button>
                                        </div>
                                    </div>
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
        var nama = $("#nama").val();
        var email = $("#email").val();
        var no_hp = $("#no_hp").val();
        var kode_jabatan = $("#kode_jabatan").val();
        
        if (nama == "") {
            alert("Semua data Harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/career/data_pelamar/add_edit_delete.php",
                traditional: true,
                data:{
                    act: 'add_data',
                    nama : nama,
                    email: email,
                    no_hp: no_hp,
                    kode_jabatan: kode_jabatan
                },
                success: function(data) {
                    alert(data);
                    if (data.trim() == "Data Berhasil Ditambahkan") {
                        location.href="?page=data_pelamar&act=basic-test&kode_soal=PDC202407182&no_hp="+no_hp;
                    }
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }
</script>
<?php endif; ?>