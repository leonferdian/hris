<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<?php $where_depo = $_SESSION['total_depo'] != 0 ? " WHERE depo in ".$_SESSION['akses_depo']."" : ""; ?>
<?php
$sql_user = "SELECT a.pin, b.email_atasan 
            FROM [db_hris].[dbo].[table_karyawan] a
            LEFT JOIN [db_hris].[dbo].[table_email_atasan] b
            ON a.[email] = b.[email_user]
            WHERE a.[nik] = '".$_SESSION['nik']."'";
$stmt_user = $sqlsrv_hris->query($sql_user);
$row_user = $sqlsrv_hris->fetch_array($stmt_user);

if($_GET['act'] == "edit" || $_GET['act'] == "detail"):
$sql = "SELECT [id]
            ,[depo]
            ,[nama_karyawan]
            ,[pin]
            ,[nik]
            ,[keterangan]
            ,[date_start]
            ,[date_end]
            ,[status]
            ,[approval_by]
            ,[date_create]
            ,[create_by]
            ,[date_update]
            ,[updated_by]
        FROM [db_hris].[dbo].[table_request_cuti]
		WHERE id = '".$_GET['id']."'";
// echo $sql;
$no = 1;
$stmt = $sqlsrv_hris->query($sql);
$row_data = $sqlsrv_hris->fetch_array($stmt);
echo '<input type="hidden" id="id_cuti" value="'.$row_data['id'].'">';
else:
echo '<input type="hidden" id="id_cuti" value="">';
endif;
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Dashboard</a>
            </li>
            <li class="active">Form Pengajuan Cuti</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Form Pengajuan Cuti
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
                                        <label class="col-sm-3 control-label no-padding-top"> Depo </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="depo" class="form-control input-sm" value="<?php echo $_SESSION['depo']; ?>" readonly>
                                            <!-- <select class="form-control input-sm select2" name="slc_depo" id="slc_depo" data-placeholder="Select Depo" onchange="select_emp()" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                                <option value=""></option>
                                                <?php
                                                $sql = "SELECT * FROM
                                                        (
                                                        SELECT cab_name as depo from [db_ftm].[dbo].[cabang]
                                                            union
                                                        SELECT pembagian3_nama as depo from [db_fin_pro].[dbo].[pembagian3]
                                                        ) a
                                                ".$where_depo."";
                                                $stmt = $sqlsrv_hris->query($sql);
                                                while ($row = $sqlsrv_hris->fetch_array($stmt)):
                                                    $selected = ($_GET['act'] == "edit" || $_GET['act'] == "detail") && $row_data['depo'] == $row['depo'] ? 'selected':'';
                                                    echo '<option value="' . $row['depo'] . '" '.$selected.'>' . $row['depo'] . '</option>';
                                                endwhile;
                                                ?>
                                                <option value="Blank">Unknow</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Nama Karyawan </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="nama_karyawan" class="form-control input-sm" value="<?php echo $_SESSION['nama']; ?>" readonly>
                                            <!-- <select class="form-control input-sm select2" name="slc_nama_karyawan" id="slc_nama_karyawan" data-placeholder="Select Person" onchange="" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                                <?php
                                                if ($_GET['act'] == "edit" || $_GET['act'] == "detail"):
                                                $sql = "SELECT * FROM (SELECT a.[pin]
                                                            ,a.[nik]
                                                            ,a.[alias]
                                                            ,b.cab_name as depo
                                                        FROM [db_ftm].[dbo].[emp] a
                                                        LEFT JOIN [db_ftm].[dbo].[cabang] b
                                                        ON a.cab_id_auto = b.cab_id_auto
                                                        union all
                                                        SELECT [pegawai_pin] as pin
                                                            ,[pegawai_nip] as nik
                                                            ,[pegawai_nama] as alias
                                                            ,b.[pembagian3_nama] as depo
                                                        FROM [db_fin_pro].[dbo].[pegawai] a
                                                        LEFT JOIN [db_fin_pro].[dbo].[pembagian3] b
                                                        ON a.pembagian3_id = b.pembagian3_id) emp
                                                        WHERE ISNUMERIC([alias]) = 0
                                                        GROUP BY [pin], [nik], [alias], [depo]
                                                        ORDER BY alias";
                                                $stmt = $sqlsrv_hris->query($sql);
                                                while ($row = $sqlsrv_hris->fetch_array($stmt)):
                                                    $selected = trim($row_data['nama_karyawan']) == trim($row['alias']) ? 'selected':'';
                                                    echo '<option data-pin="' . $row['pin'] . '" data-nik="' . $row['nik'] . '" value="' . $row['alias'] . '" '.$selected.'>' . $row['alias'] . '</option>';
                                                endwhile;
                                                endif;
                                                ?>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> NIK </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="nik" class="form-control input-sm" value="<?php echo $_SESSION['nik']; ?>" readonly>
                                            <input type="hidden" id="pin" value="<?php echo $row_user['pin']; ?>">
                                            <input type="hidden" id="email_atasan" value="<?php echo $row_user['email_atasan']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Keterangan Cuti </label>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" id="keterangan" placeholder="Default Text" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>><?php echo $_GET['act'] == "edit" || $_GET['act'] == "detail" ? $row_data['keterangan'] : ""; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Periode Cuti </label>
                                        <div class="col-sm-7">
                                            <div class="clearfix">
                                                <div class="input-group" id="span_periode">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar bigger-110"></i>
                                                    </span>
                                                    <input class="form-control" type="text" name="date-range-picker" id="date_range" class="col-xs-12 col-sm-4" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?> />
                                                    <input type="hidden" id="startDate" value="<?php echo $_GET['act'] == "edit" || $_GET['act'] == "detail" ? date_format($row_data['date_start'], "Y-m-d") : ""; ?>">
                                                    <input type="hidden" id="endDate" value="<?php echo $_GET['act'] == "edit" || $_GET['act'] == "detail" ? date_format($row_data['date_end'], "Y-m-d") : ""; ?>">
                                                </div>
                                            </div>
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

        $('#datepicker1').datepicker({
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

        if ($('#id_cuti').val() != "") {
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
        var id = $('#id_cuti').val();
        var depo = $('#depo').val();
        var nama_karyawan = $('#nama_karyawan').val();
        var nik = $('#nik').val();
        var pin = $('#pin').val();
        var email_atasan = $('#email_atasan').val();
        // var pin = $('#slc_nama_karyawan option:selected').data('pin');
        // var nik = $('#slc_nama_karyawan option:selected').data('nik');
        var keterangan = $('#keterangan').val();
        var tgl_range = $("#date_range").val().split(' - ');
		var tgl1 = tgl_range[0];
		var tgl2 = tgl_range[1];
        queue.show();
        $.ajax({
            type: "POST",
            url: "inc/cuti/pengajuan_cuti/proc.php",
            traditional: true,
            data: {
                act: act,
                id: id,
                depo: depo,
                nama_karyawan: nama_karyawan,
                pin: pin,
                nik: nik,
                email_atasan: email_atasan,
                tgl1: tgl1,
                tgl2: tgl2,
                keterangan: keterangan
            },
            success: function (data) {
                alert(data);
                if (data.trim() == "Data Saved") {
                    location.href="?sm=pengajuan_cuti";
                }
                queue.hide();
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }

    function select_emp() {
        var act = "select_emp";
        var depo = $('#slc_depo').val();
        $.ajax({
            type: "POST",
            url: "inc/cuti/pengajuan_cuti/proc.php",
            traditional: true,
            data: {
                act: act,
                depo: depo,
            },
            success: function (data) {
                $('#slc_nama_karyawan').html(data.trim());
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }
</script>