<?php
$sqlsrv_db = DB::connection('sqlsrv_hris');
$month = array("jan" => "01", "feb" => "02", "mar" => "03", "apr" => "04", "may" => "05", "jun" => "06", "jul" => "07", "aug" => "08", "sep" => "09", "oct" => "10", "nov" => "11", "dec" => "12");
$where_depo = $_SESSION['total_depo'] != 0 ? " WHERE depo in ".$_SESSION['akses_depo']."" : "";

$cek_task = $sqlsrv_db->query("SELECT * FROM [db_hris].[dbo].[table_log_activity] WHERE [category] = '".$_GET['sm']."' AND [status] = 0");
$num_log = $sqlsrv_db->num_rows($cek_task);
?>
<style>
    .multiselect {
        position: relative;
        height: 28px;
    }

    .multiselect .caret {
        position: absolute;
        right: 5px;
        line-height: 34px;
        display: inline-block;
        top: 50%;
        margin-top: -3px;
    }

    .dropdown-menu {
        max-height: 500px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    #sortable {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 100px;
        display: inline;
    }

    /* #sortable span {
                    margin: 3px 3px 3px 0;
                    padding: 1px;
                    float: left;
                    width: 50px;
                    height: 50px;
                    font-size: 1em;
                    text-align: center;
                } */

    #progress-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .progress-container {
        width: 200px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
        width: 0%;
        height: 20px;
        background: #3498db;
        border-radius: 4px;
        transition: width 0.3s ease;
    }
</style>
<script>
    $(document).ready(function () {
        // $('.chosen-select').chosen();
        // $(window)
        //     .off('resize.chosen')
        //     .on('resize.chosen', function() {
        //         $('.chosen-select').each(function() {
        //             // var $this = $(this);
        //             // $this.next().css({
        //             //     'width': '100%'
        //             // });
        //         })
        //     }).trigger('resize.chosen');

        $('.chosen-select').css('width', '200px').select2({ allowClear: true })
            .on('change', function () {
                // $(this).closest('form').validate().element($(this));
            });

        $('.multiselect').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableHTML: true,
            buttonClass: 'btn btn-white btn-primary',
            templates: {
                button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-selected-text"></span> &nbsp;<b class="fa fa-caret-down"></b></button>',
                ul: '<ul class="multiselect-container dropdown-menu"></ul>',
                filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
                filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
                li: '<li><a tabindex="0"><label></label></a></li>',
                divider: '<li class="multiselect-item divider"></li>',
                liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>'
            }
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
    });

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
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Dashboard</a>
            </li>
            <li>HRIS</li>
            <li class="active">Absensi Checkin Checkout</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Absensi Checkin Checkout
            </h1>
            <div class="pull-right hide">
                <a style="text-decoration-line: none;" href="?page=log_task&category=absensi&status=0">New Task: <span class="badge<?php echo $num_log > 0 ? " badge-info" : " badge-grey"; ?>"><?php echo $num_log; ?></span></a>
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
                                <span id="span-company">
                                    <label for="slc_depo">Depo: &nbsp;&nbsp;&nbsp;</label>
                                    <select class="form-control input-sm chosen-select" name="slc_depo" id="slc_depo"
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
                                        $stmt = $sqlsrv_db->query($sql);
                                        while ($row = $sqlsrv_db->fetch_array($stmt)):
                                            echo '<option value="' . $row['depo'] . '">' . strtoupper($row['depo']) . '</option>';
                                        endwhile;
                                        ?>
                                        <option value="Blank">Unknow</option>
                                    </select>
                                </span>
                                <?php #echo $sql; ?>
                                <br><br>
                                <label for="datepicker1">Tanggal: </label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" id="datepicker1" name="datepicker1"
                                        value="<?php echo date('m/d/Y'); ?>" placeholder="">
                                </div>
                                <!-- <br><br> -->
                                <span id="span-cabang" class="hide">
                                    <label for="slc_absen">Absensi: </label>
                                    <select class="form-control input-sm chosen-select" name="slc_absen" id="slc_absen"
                                        data-placeholder="Select Absensi" onchange="">
                                        <option value="1">Absensi Mesin</option>
                                        <option value="2">Absensi ILP</option>
                                    </select>
                                </span>
                                <span class="pull-right">
                                    <button type="button" class="btn btn-info btn-sm" onclick="select_view()">
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
            <div class="widget-main">
                <span id="progress" style="display:none"> <i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Load data... </span>
                <div id="ProgressDialog" style="display:none">
                    <h4 class="header smaller lighter blue">
                        <i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Syncronize data...
                    </h4>
                </div>
                <div class="pull-right">
                    <?php if ($_SESSION['user_level']): ?>
                        <a href="#" class="btn btn-sm btn-white btn-info" onclick="sinkron_absen()"><i class="ace-icon glyphicon glyphicon-refresh"></i> Sinkron Absensi</a>
                    <?php endif; ?>
                    <div id="counter"></div>
                </div>
                <br>
                <br>
                <br>
                <div class="table-responsive" id="view_report">

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
<script>
    $(document).ready(function () {
        // PDialog();
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

    function PDialog() {
        $("#ProgressDialog").dialog({
            autoOpen: true,
            closeOnEscape: false,
            draggable: false,
            resizable: false,
            modal: true,
            buttons: []
        });

        $(".ui-dialog-titlebar-close").hide();
    }

    function select_view() {
        var jenis_lap = $('#slc_absen').val();

        if (jenis_lap == 1) {
            view();
        } else {
            load_table();
        }
    }

    function view() {
        second = 0;
        minute = 0;
        hour = 0;
        var tgl = $("#datepicker1").val();
        var queue = $('#progress');
        var slc_depo = $('#slc_depo').val();
        // var slc_cabang = $('#slc_cabang').val();
        queue.show();
        updateCounter();

        $.ajax({
            type: "GET",
            url: "inc/absensi/absensi_karyawan/view_report.php",
            traditional: true,
            data: {
                tgl: tgl,
                depo: slc_depo
                // cabang: slc_cabang
            },
            success: function (data) {
                // var regex = /sqlsrv_fetch_array\(\) expects parameter 1 to be resource/g;
                // if (regex.test(data)) {
                //     clearTimeout(timer);
                //     view();
                // } else {
                $("#view_report").html(data);
                $('#modal-content .modal-footer').html("");
                queue.hide();
                clearTimeout(timer);
                // }
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }

    function load_table() {
        second = 0;
        minute = 0;
        hour = 0;
        var tgl = $("#datepicker1").val();
        // var comp_name = $('#slc_company').find(':selected').attr('data-namecompany');
        var comp_name = 'Padmatirtagroup';
        var queue = $('#progress');
        var company = 7;
        var slc_depo = $('#slc_depo').val();
        queue.show();
        updateCounter();

        $.ajax({
            type: "GET",
            url: "inc/absensi/absensi_karyawan/view_report_ilp.php",
            data: "tgl=" + tgl + "&depo=" + slc_depo,
            success: function (data) {
                $("#view_report").html(data);
                queue.hide();
                clearTimeout(timer);
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }

    function view_iamge(filename) {
        $('#modal-view').modal('show');
        $('#modal-view .modal-title').html('View Image');
        $('#modal-view .modal-body').html('<center><img class="media-object" src="http://ilove.padmatirtagroup.com:8000/image_upload/list_foto_absen/' + filename + '" width="400" height="620" /></center>');
    }

    function sinkron_absen() {
        var tgl = $("#datepicker1").val();
        // var slc_depo = $('#slc_depo').val();
        PDialog();

        $.ajax({
            type: "POST",
            url: "inc/absensi/absensi_karyawan/proc_absensi.php",
            traditional: true,
            data: {
                act: "sinkron_absen",
                tgl: tgl,
                // depo: slc_depo,
            },
            success: function(data) {
                if (data.trim() == 'Data Berhasil Disinkron') {
					$.gritter.add({
						// (string | mandatory) the heading of the notification
						title: data.trim(),
						// (string | mandatory) the text inside the notification
						// text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="orange">magnis dis parturient</a> montes, nascetur ridiculus mus.',
						class_name: 'gritter-info' + ' gritter-light'
					});

                    $("#ProgressDialog").dialog("close");
			
                    view();
                    setTimeout(function() {
                        $.gritter.removeAll();
                    }, 5000);
                }
                
            },
            error: function(msg) {
                alert(msg);
            }
        });
    }

    function save_absensi() {
        var tgl = $("#datepicker1").val();
        var slc_depo = $('#slc_depo').val();
        PDialog();

        if (confirm("Apakah anda yakin?")) {
            $.ajax({
                type: "POST",
                url: "inc/absensi/validasi_absensi/proc_absensi.php",
                traditional: true,
                data: {
                    act: "close_absensi",
                    tgl: tgl,
                    depo: slc_depo
                },
                success: function (data) {
                    alert(data);
                    $("#ProgressDialog").dialog("close");
                    view();
                },
                error: function (msg) {
                    alert(msg);
                }
            });
        }
    }
</script>