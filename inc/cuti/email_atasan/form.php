<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<?php $where_depo = $_SESSION['total_depo'] != 0 ? " WHERE depo in ".$_SESSION['akses_depo']."" : ""; ?>
<?php
if($_GET['act'] == "edit" || $_GET['act'] == "detail"):
$sql = "SELECT [email_user]
            ,[nik]
            ,[email_atasan]
        FROM [db_hris].[dbo].[table_email_atasan]
		WHERE [email_user] = '".$_GET['email_user']."' OR [nik] = '".$_GET['nik']."'";
// echo $sql;
$no = 1;
$stmt = $sqlsrv_hris->query($sql);
$row_data = $sqlsrv_hris->fetch_array($stmt);
endif;
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Dashboard</a>
            </li>
            <li class="active">Form Email Atasan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Form Email Atasan
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
                                        <label class="col-sm-3 control-label no-padding-top"> Nama Karyawan </label>
                                        <div class="col-sm-7">
                                            <select class="form-control input-sm select2" name="slc_nik_karyawan" id="slc_nik_karyawan" data-placeholder="Select Person" onchange="" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                                <?php
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
                                                    $selected = ($_GET['act'] == "edit" || $_GET['act'] == "detail") && trim($row_data['nik']) == trim($row['nik']) ? 'selected':'';
                                                    echo '<option value="' . $row['nik'] . '" '.$selected.'>' . $row['alias'] . '</option>';
                                                endwhile;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Email Karyawan </label>
                                        <div class="col-sm-7">
                                            <input type="text" id="email_user" value="<?php echo $_GET['act'] == "edit" || $_GET['act'] == "detail" ? $row_data['email_user'] : ""; ?>" class="form-control input-sm">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-top"> Nama Atasan </label>
                                        <div class="col-sm-7">
                                            <select class="form-control input-sm select2" name="slc_email_atasan" id="slc_email_atasan" data-placeholder="Select Person" onchange="" <?php echo $_GET['act'] == "detail" ? "disabled" : ""; ?>>
                                                <?php
                                                    $user = DB::connection('mysql_hris')->query("SELECT * FROM user order by nama asc");
                                                while ($row = $user->fetch_array()):
                                                    $selected = ($_GET['act'] == "edit" || $_GET['act'] == "detail") && trim($row_data['email_atasan']) == trim($row['username']) ? 'selected':'';
                                                    echo '<option data-nama="'.$row['nama'].'" value="' . $row['username'] . '" '.$selected.'>' . $row['nama'] . '</option>';
                                                endwhile;
                                                ?>
                                            </select>
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

        if ($('#id_cuti').val() != "") {
            startDay = $('#startDate').val();
            endDay = $('#endDate').val();
        }

        $('input[name=date-range-picker]').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            startDate: startDay,
            endDate: endDay,
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
        var email_user = $('#email_user').val();
        var slc_nik_karyawan = $('#slc_nik_karyawan').val();
        var email_atasan = $('#slc_email_atasan').val();
        var nama_atasan = $('#slc_email_atasan option:selected').data('nama');
        queue.show();
        $.ajax({
            type: "POST",
            url: "inc/cuti/email_atasan/proc.php",
            traditional: true,
            data: {
                act: act,
                email_user: email_user,
                nik: slc_nik_karyawan,
                email_atasan: email_atasan,
                nama_atasan: nama_atasan,
            },
            success: function (data) {
                alert(data);
                if (data.trim() == "Data Saved") {
                    location.href="?sm=email_atasan";
                }
                queue.hide();
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }
</script>