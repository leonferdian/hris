<?php
if(isset($_GET['act'])):
    include 'inc/report/absensi/report_validasi_hrd/'.$_GET['act'].'.php';
else:
$sqlsrv_hris = DB::connection('sqlsrv_hris'); 
$where_depo = $_SESSION['total_depo'] != 0 ? " WHERE depo in ".$_SESSION['akses_depo']."" : "";
$cek_task = $sqlsrv_hris->query("SELECT COUNT(*) as total FROM (
                                    SELECT absensi.[depo]
                                                    ,absensi.[pin]
                                                    ,absensi.[nik]
                                                    ,absensi.[nama]
                                                    ,absensi.date_absen
                                                    ,kadep.username
                                                    ,val.[keterangan]
                                                    ,val.[lc_name]
                                                FROM [db_hris].[dbo].[table_absensi_log] absensi
                                                LEFT JOIN [db_hris].[dbo].[table_depo_absensi] kadep
                                                ON absensi.depo = kadep.kode_depo
                                                LEFT JOIN (SELECT
                                                    a.*
                                                    ,b.leave_category AS lc_name
                                                    FROM [db_hris].[dbo].[table_absensi_validasi] a
                                                    LEFT JOIN [db_hris].[dbo].[table_absensi_leave_category] b
                                                    ON a.leave_category = b.id) val
                                                    ON absensi.depo = val.depo
                                                    AND absensi.pin = val.pin
                                                    AND absensi.nik = val.nik
                                                WHERE kadep.username = '".$_SESSION['username']."'
                                                and absensi.tanggal is null
                                                and val.lc_name is null) validasi");
$row = $sqlsrv_hris->fetch_array($cek_task);
$num_log = $row['total'];
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
            <li class="active">Report Validasi Absensi HRD</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Report Validasi Absensi HRD
            </h1>
            <div class="pull-right hide">
                <a style="text-decoration-line: none;" href="?page=log_task&category=<?php echo $_GET['sm']; ?>&status=0">New Task: <span class="badge<?php echo $num_log > 0 ? " badge-info" : " badge-grey"; ?>"><?php echo $num_log; ?></span></a>
            </div>
            <br>
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
                            <span id="span-tahun">
                                    <label for="slc_tahun">Tahun: </label>
                                    <select class="form-control input-sm select2" name="slc_tahun" id="slc_tahun" data-placeholder="Select Tahun" onchange="">
                                        <!-- <option>Select Company</option> -->
                                        <?php
                                        $sql_user = "SELECT DISTINCT datepart(year,[date_absen]) as tahun FROM [db_hris].[dbo].[table_absensi_log]";
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
                                    <button type="button" class="btn btn-info btn-sm"  onclick="view()">
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
                <span id="progress" style="display:none"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Load data... </span>
                <div id="view_report">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h2 class="modal-title blue font-weight-bold"></h2>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button class="btn btn-sm btn-danger" data-dismiss="modal">
					<i class="ace-icon fa fa-times"></i>
					Close
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_validasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title blue font-weight-bold">Validasi Kepala Depo</h2>
            </div>
            <span id="progress1" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-success" onclick="save()">
                    <i class="ace-icon fa fa-check"></i>
                    Save
                </button>
                <button class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
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

        // view();
    });

    var second = 0;
    var minute = 0;
    var hour = 0;
    var timer;

    // Function to update the counter
    function updateCounter() {
        second++;
        $("#counter").html("Executing time: <i>" + second + " second</i>");
        if (second == 60) {
            second = 0;
            minute++;
            $("#counter").html("Executing time: <i>" + minute + "minute" + second + " second</i>");
            if (minute == 60) {
                hour++;
                $("#counter").html("Executing time: <i>" + hour + " hour" + minute + "minute" + second + " second</i>");
            }
        }
            
        timer = setTimeout(updateCounter, 1000); // Update counter every second
    }

    function view() {
        second = 0;
        minute = 0;
        hour = 0;
        var slc_tahun = $("#slc_tahun").val();
        var slc_bulan = $('#slc_bulan').val();
        var queue = $('#progress');
        queue.show();
        updateCounter();

        $.ajax({
            type: "GET",
            url: "inc/report/absensi/report_validasi_hrd/view_report.php",
            traditional: true,
            data: {
                year: slc_tahun,
                month: slc_bulan
            },
            // data: "tanggal="+tanggal+"&depo="+slc_depo+"&act="+act,
            success: function (data) {
                // var regex = /sqlsrv_fetch_array\(\) expects parameter 1 to be resource/g;
                // if (regex.test(data)) {
                //     clearTimeout(timer);
                //     view();
                // } else {
                    $("#view_report").html(data);
                    queue.hide();
                    clearTimeout(timer);
                // }
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