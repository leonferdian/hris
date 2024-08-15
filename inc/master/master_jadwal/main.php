<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Master Jadwal Karyawan</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <div class="pull-right">
                <button class="btn btn-xs btn-success" onclick="add_dialog()">
                    <i class="ace-icon fa fa-plus bigger-120"></i> Add Jadwal
                </button>
            </div>
            <h1>
                Master Jadwal Karyawan
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-body">
                    <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
                    <div id="list_vol">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_add_master" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title font-weight-bold">Jadwal Karyawan</h2>
            </div>
                <br><span id="progress2" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="add_divisi()">
                    <i class="ace-icon fa fa-check"></i>
                    Save
                </button>
                <button class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_edit_master" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title font-weight-bold">Jadwal Karyawan</h2>
            </div>
                <br><span id="progress2" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait... </span>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" onclick="edit_divisi()">
                    <i class="ace-icon fa fa-check"></i>
                    Save
                </button>
                <button class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
                <div id="result"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        view_report();
    });

    function view_report() {
        $('#progress').show();
        $.ajax({
            type: "GET",
            url: "inc/master/master_jadwal/view_report.php",
            traditional: true,
            // data: },
            success: function(data) {
                $("#list_vol").html(data);
                $('#progress').hide();
            },
            error: function(msg) {
                alert(msg);
            }
        });
    }

    function add_dialog() {
        $('#modal_add_master').modal('show');
        $("#modal_add_master .modal-body").html("");
        $('#modal_add_master #progress2').show();
        $.ajax({
            type: "POST",
            url: "inc/master/master_jadwal/add_edit_delete.php",
            traditional: true,
            data: {
                act: 'add_dialog'
            },
            success: function(data) {
                $("#modal_add_master .modal-body").html(data);
                $('#modal_add_master #progress2').hide();
                $('.chosen-select4').chosen({ allow_single_deselect: true });
                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function () {
                        $('.chosen-select4').each(function () {
                            var $this = $(this);
                            $this.next().css({ 'width': '100%' });
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function (e, event_name, event_val) {
                    if (event_name != 'sidebar_collapsed') return;
                    $('.chosen-select4').each(function () {
                        var $this = $(this);
                        $this.next().css({ 'width': '100%' });
                    })
                });

                $('.chosen-select').chosen();
                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function () {
                        $('.chosen-select').each(function () {
                            var $this = $(this);
                            $this.next().css({
                                'width': '100%'
                            });
                        })
                    }).trigger('resize.chosen');

                $('.timepicker1').timepicker({
                    minuteStep: 1,
                    showSeconds: false,
                    showMeridian: false,
                    disableFocus: true,
                    defaultTime: 'value',
                    icons: {
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down'
                    }
                }).on('focus', function () {
                    $('#timepicker1').timepicker('showWidget');
                }).next().on(ace.click_event, function () {
                    $(this).prev().focus();
                });
            },
            error: function(msg) {
                alert(msg);
            }
        });
    }

    function add_divisi() {
        var depo = $("#slc_depo").val();
        var shift = $("#slc_shift").val();
        var jam_masuk = $("#txt_jam_masuk").val();
        var jam_keluar = $("#txt_jam_keluar").val();
        var start_scan_masuk = $("#txt_start_scan_masuk").val();
        var start_scan_keluar = $("#txt_start_scan_keluar").val();
        var end_scan_masuk = $("#txt_end_scan_masuk").val();
        var end_scan_keluar = $("#txt_end_scan_keluar").val();
        var vendor = $("#slc_shift").find(':selected').attr("data-vendor");
        var shift_name = $("#slc_shift").find(':selected').attr("data-shiftname");
        if (shift == "") {
            alert("Semua data Harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/master/master_jadwal/add_edit_delete.php",
                traditional: true,
                data: {
                    act: 'add_data',
                    depo: depo,
                    shift: shift,
                    jam_masuk: jam_masuk,
                    jam_keluar: jam_keluar,
                    start_scan_masuk: start_scan_masuk,
                    start_scan_keluar: start_scan_keluar,
                    end_scan_masuk: end_scan_masuk,
                    end_scan_keluar: end_scan_keluar,
                    vendor: vendor,
                    shift_name: shift_name
                },
                success: function(data) {
                    $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'Success',
                        // (string | mandatory) the text inside the notification
                        text: data,
                        class_name: 'gritter-success' + (!$('#gritter-light').get(0).checked ? ' gritter-light' : '')
                    });
                    $('#modal_add_master').modal('hide');
                    view_report();
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }

    function edit_dialog(kode) {
        $('#modal_edit_master').modal('show');
        $("#modal_edit_master .modal-body").html("");
        $('#modal_edit_master #progress2').show();
        $.ajax({
            type: "POST",
            url: "inc/master/master_jadwal/add_edit_delete.php",
            traditional: true,
            data: {
                act: 'edit_dialog',
                kode: kode
            },
            success: function(data) {
                $("#modal_edit_master .modal-body").html(data);
                $('#modal_edit_master #progress2').hide();
            },
            error: function(msg) {
                alert(msg);
            }
        });
    }

    function edit_divisi() {
        var depo = $("#slc_depo").val();
        var shift = $("#slc_shift").val();
        var jam_masuk = $("#txt_jam_masuk").val();
        var jam_keluar = $("#txt_jam_keluar").val();
        var start_scan_masuk = $("#txt_start_scan_masuk").val();
        var start_scan_keluar = $("#txt_start_scan_keluar").val();
        var end_scan_masuk = $("#txt_end_scan_masuk").val();
        var end_scan_keluar = $("#txt_end_scan_keluar").val();
        var id_jadwal = $("#id_jadwal").val();
        var vendor = $("#slc_shift").find(':selected').attr("data-vendor");
        var shift_name = $("#slc_shift").find(':selected').attr("data-shiftname");
        if (shift == "") {                                             
            alert("Semua data harus diisi");
        } else {
            $.ajax({
                type: "POST",
                url: "inc/master/master_jadwal/add_edit_delete.php",
                traditional: true,
                data: {
                    act: 'edit_data',
                    id_jadwal: id_jadwal,
                    depo: depo,
                    shift: shift,
                    jam_masuk: jam_masuk,
                    jam_keluar: jam_keluar,
                    start_scan_masuk: start_scan_masuk,
                    start_scan_keluar: start_scan_keluar,
                    end_scan_masuk: end_scan_masuk,
                    end_scan_keluar: end_scan_keluar,
                    vendor: vendor,
                    shift_name: shift_name
                },
                success: function(data) {
                    $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: 'Success',
                        // (string | mandatory) the text inside the notification
                        text: data,
                        class_name: 'gritter-success'
                    });
                    $('#modal_edit_master').modal('hide');
                    view_report();
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }

    function del(kode, depo) {
        if (window.confirm("Apakah Anda Yakin Ingin Menghapus Jadwal " + depo + " ?")) {
            $.ajax({
                type: "POST",
                url: "inc/master/master_jadwal/add_edit_delete.php",
                traditional: true,
                data: {
                    act: 'del',
                    kode: kode
                },
                success: function(data) {
                    alert(data);
                    location.reload();
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }
</script>