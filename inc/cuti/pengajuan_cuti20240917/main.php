<?php if (isset($_GET['act']) && $_GET['act'] == "add"):  ?>
    <?php include 'inc/cuti/pengajuan_cuti/form.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "edit"):  ?>
    <?php include 'inc/cuti/pengajuan_cuti/form.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "detail"):  ?>
    <?php include 'inc/cuti/pengajuan_cuti/form.php'; ?>
<?php else: ?>
<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<?php $where_depo = $_SESSION['total_depo'] != 0 ? " WHERE depo in ".$_SESSION['akses_depo']."" : ""; ?>
<?php
$arr_bulan = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'Sepetember',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember',
);
?>
<style>
    /* Center align data in the DataTable */
    .dataTables_wrapper .dataTable td, 
    .dataTables_wrapper .dataTable th {
        text-align: center;
        vertical-align: middle;
    }

    table.dataTable {
        width: 100% !important;
    }
</style>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Dashboard</a>
            </li>
            <li class="active">Request Cuti</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Request Cuti
            </h1>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">Filter</h4>
                        <span class="widget-toolbar">
                            <a href="#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="form-inline">
                                <span id="span-company" class="hide" >
                                    <label for="slc_depo">Depo: &nbsp;&nbsp;&nbsp;</label>
                                    <select class="form-control input-sm select2" name="slc_depo" id="slc_depo"
                                        data-placeholder="Select Depo" onchange="">
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
                                            echo '<option value="' . $row['depo'] . '">' . strtoupper($row['depo']) . '</option>';
                                        endwhile;
                                        ?>
                                        <option value="Blank">Unknow</option>
                                    </select>
                                </span>
                                <!-- <br><br> -->
                                <span id="span-tahun">
                                    <label for="slc_tahun">Tahun: </label>
                                    <select class="form-control input-sm select2" name="slc_tahun" id="slc_tahun" data-placeholder="Select Tahun" onchange="">
                                        <!-- <option>Select Company</option> -->
                                        <?php
                                        $sql_user = "SELECT DISTINCT datepart(year,[date_start]) as tahun FROM [db_hris].[dbo].[table_request_cuti]";
                                        $stmt_user = $sqlsrv_hris->query($sql_user);
                                        while ($row_user = $sqlsrv_hris->fetch_array($stmt_user)) {
                                            ?>
                                            <option value="<?php echo $row_user['tahun']; ?>" <?php echo $row_user['tahun'] == date("Y") ? "selected" : ""; ?>>
                                                <?php echo $row_user['tahun']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </span>
                                <span id="span-bulan">
                                    <label for="slc_bulan">Bulan: </label>
                                    <select class="form-control input-sm select2" name="slc_bulan" id="slc_bulan" data-placeholder="Select Bulan" onchange="">
                                        <!-- <option>Select Company</option> -->
                                        <?php foreach($arr_bulan as $k => $v): ?>
                                            <option value="<?php echo $k; ?>" <?php echo $k == date("m") ? "selected" : ""; ?>><?php echo $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </span>
                                <span class="pull-right">
                                    <button type="button" class="btn btn-info btn-sm" id="view_entity2"
                                        onclick="view()">
                                        <i class="ace-icon fa fa-search bigger-110"></i>View
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-header">
					<div class="pull-right">
						<a class="btn btn-white btn-xs btn-success" href="?sm=pengajuan_cuti&act=add"><i class="fa fa-plus"></i> Create New</a>
						&nbsp;
					</div>
                    <br>
				</div>
                <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
                <div class="table-responsive" id="view_report">
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

        view();
    });

    function view() {
        var slc_tahun = $('#slc_tahun').val();
        var slc_bulan = $('#slc_bulan').val();
        // var slc_depo = $('#slc_depo').val();

        $.ajax({
            type: "GET",
            url: "inc/cuti/pengajuan_cuti/view_report.php",
            traditional: true,
            data: {
                tahun: slc_tahun,
                bulan: slc_bulan,
                // slc_depo: slc_depo
            },
            success: function (data) {
                $("#view_report").html(data);
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }

    function export_excel(id, name) {
        var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
        tab_text = tab_text + '<x:Name>Test Sheet</x:Name>';
        tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
        tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
        tab_text = tab_text + "<table border='1px'>";
        var exportTable = $('#' + id).clone();
        exportTable.find('input').each(function (index, elem) {
            $(elem).remove();
        });
        tab_text = tab_text + exportTable.html();
        tab_text = tab_text + '</table></body></html>';
        var data_type = 'data:application/vnd.ms-excel';
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        var fileName = name + '.xls';
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            if (window.navigator.msSaveBlob) {
                var blob = new Blob([tab_text], {
                    type: "application/csv;charset=utf-8;"
                });
                navigator.msSaveBlob(blob, fileName);
            }
        } else {
            var blob2 = new Blob([tab_text], {
                type: "application/csv;charset=utf-8;"
            });
            var filename = fileName;
            var elem = window.document.createElement('a');
            elem.href = window.URL.createObjectURL(blob2);
            elem.download = filename;
            document.body.appendChild(elem);
            elem.click();
            document.body.removeChild(elem);
        }
    }
</script>
<?php endif; ?>