<?php if (isset($_GET['act']) && $_GET['act'] == "add"):  ?>
    <?php include 'inc/cuti/email_atasan/form.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "edit"):  ?>
    <?php include 'inc/cuti/email_atasan/form.php'; ?>
<?php elseif (isset($_GET['act']) && $_GET['act'] == "detail"):  ?>
    <?php include 'inc/cuti/email_atasan/form.php'; ?>
<?php else: ?>
<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<?php $where_depo = $_SESSION['total_depo'] != 0 ? " WHERE depo in ".$_SESSION['akses_depo']."" : ""; ?>
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
            <li class="active">Email Atasan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Email Atasan
            </h1>
        </div>
        <div class="row hide">
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
                                <label for="datepicker1">Tanggal: </label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" id="datepicker1" name="datepicker1"
                                        value="<?php echo date('m/d/Y'); ?>" placeholder="">
                                </div>
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
						<a class="btn btn-white btn-xs btn-success" href="?sm=email_atasan&act=add"><i class="fa fa-plus"></i> Create New</a>
						&nbsp;
					</div>
                    <br>
				</div>
                <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
                <div class="table-responsive" id="view_report">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="data_master">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Depo</th>
                                <th>Nama Karyawan</th>
                                <th>Pin</th>
                                <th>Nik</th>
                                <th>Keterangan Izin</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                                <th>Tanggal Diajukan</th>
                                <th>Update By</th>
                                <th>Status</th>
                                <th><i class="fa fa-cog"></i></th>
                            </tr>
                        </thead>
                    </table>
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
        var tanggal = $("#datepicker1").val();
        var slc_depo = $('#slc_depo').val();

        $.ajax({
            type: "GET",
            url: "inc/cuti/email_atasan/view_report.php",
            traditional: true,
            data: {
                tanggal: tanggal,
                slc_depo: slc_depo
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