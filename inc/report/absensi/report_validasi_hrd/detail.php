<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');
$tanggal = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d');     // default to current year
$depo = isset($_GET['depo']) ? " AND absensi.depo = '".$_GET['depo']."'" : "";     // default to current year
$validated = isset($_GET['validate']) ? " AND [validasi_hrd] = ".$_GET['validate']."" : "";
if ($_GET['validate'] == "") {
    $validated = " AND [validasi_hrd] IS NULL";
}
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Detail Report Validasi Absensi HRD</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1 style="text-align: left;font-weight: bold; color:#646464">
                Detail Report Validasi Absensi HRD
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 col-sm-12 pricing-box">
                    <div class="widget-box widget-color-blue">
                        <div class="widget-header">
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="table-responsive" id="list_core">
                                            <table class="table table-bordered table-hover datatable" id="table_absensi_report">
                                                <thead>
                                                    <tr>
                                                        <th class="center">#</th>
                                                        <th class="center">Depo</th>
                                                        <th class="center">Pin</th>
                                                        <th class="center">Nik</th>
                                                        <th class="center">Nama</th>
                                                        <th class="center">Leave Category</th>
                                                        <th class="center">Keterangan</th>
                                                        <th class="center"><i class="fa fa-cog"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body_table_strata_segment">
                                                    <?php
                                                    $sql_mesin = "SELECT
                                                        absensi.[depo]
                                                        ,absensi.[pin]
                                                        ,absensi.[nik]
                                                        ,absensi.[nama]
                                                        ,absensi.date_absen
                                                        ,kadep.username
                                                        ,val.[keterangan]
                                                        ,val.[lc_name]
                                                        ,val_hrd.[validasi_hrd]
                                                        ,val_hrd.[nama_validator]
                                                        ,val_hrd.[validate_date]
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
                                                        AND absensi.date_absen = val.date_absen
                                                    LEFT JOIN [db_hris].[dbo].[table_absensi_validasi_hrd] val_hrd
                                                        ON absensi.depo = val_hrd.depo
                                                        AND absensi.nik = val_hrd.nik
                                                        AND absensi.pin = val_hrd.pin
                                                        AND val.date_absen = val_hrd.date_absen
                                                    WHERE kadep.username = '".$_SESSION['username']."'
                                                    AND absensi.tanggal IS NULL
                                                    AND val.[date_absen] IS NOT NULL
                                                    ".$validated.$depo."
                                                    AND absensi.date_absen = '" . $tanggal . "'
                                                    ORDER BY absensi.depo, absensi.nama, absensi.date_absen, absensi.time_scan_in, absensi.time_scan_out";
                                                    // echo $sql_mesin;
                                                    $no = 1;
                                                    $stmt_mesin = $sqlsrv_hris->query($sql_mesin);
                                                    if ($stmt_mesin):
                                                    while ($row_validasi = $sqlsrv_hris->fetch_array($stmt_mesin)):
                                                    ?>
                                                        <tr>
                                                            <td class="center"><?php echo $no; ?></td>
                                                            <td class="center"><?php echo $row_validasi['depo']; ?></td>
                                                            <td class="center"><?php echo $row_validasi['pin']; ?></td>
                                                            <td class="center"><?php echo $row_validasi['nik']; ?></td>
                                                            <td class="center"><?php echo $row_validasi['nama']; ?></td>
                                                            <td class="center"><?php echo $row_validasi['lc_name']; ?></td>
                                                            <td class="center"><?php echo $row_validasi['keterangan']; ?></td>
                                                            <td style="vertical-align:middle; text-align:center;">
                                                                <input type="hidden" id="depo_val" value="<?php echo $row_validasi['depo']; ?>">
                                                                <input type="hidden" id="pin_val" value="<?php echo $row_validasi['pin']; ?>">
                                                                <input type="hidden" id="nik_val" value="<?php echo $row_validasi['nik']; ?>">
                                                                <input type="hidden" id="tanggal_val" value="<?php echo date_format($row_validasi['date_absen'],"Y-m-d"); ?>">
                                                                <?php if (!isset($row_validasi['validate_date'])): ?>
                                                                <button class="btn btn-xs btn-success" onclick="validasi_hrd('valid')">
                                                                    <i class="ace-icon fa fa-check bigger-120"></i> Validate
                                                                </button>
                                                                <button class="btn btn-xs btn-danger" onclick="validasi_hrd('reject')">
                                                                    <i class="ace-icon fa fa-times bigger-120"></i> Reject
                                                                </button>
                                                                <?php else: ?>
                                                                    <?php echo $hasil_validasi; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        $no++;
                                                    endwhile;
                                                    else:
                                                        echo $sql_mesin;
                                                    endif;
                                                    ?>
                                                </tbody>
                                            </table>
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

    function show_upload_file() {
        var keterangan = $('#modal_validasi #slc_lc').val();
        if (keterangan == 3) {
            $('#input-file').show();
        } else {
            $('#input-file').hide();
        }
    }

    function view_iamge(filename) {
		$('#modal-view').modal('show');
		$('#modal-view .modal-title').html('View Image');
		$('#modal-view .modal-body').html('<center><img class="media-object" src="images/absensi/'+filename+'" width="400" height="620" /></center>');
	}

    function validasi(pin, nik, tanggal, nama, depo) {
        $('#modal_validasi').modal('show');
        var queue = $('#progress1');
        var act = "add_validasi";
        queue.show();
        $.ajax({
            type: "POST",
            url: "inc/absensi/validasi_absensi/proc_validasi.php",
            traditional: true,
            data: {
                tanggal: tanggal,
                pin: pin,
                nik: nik,
                nama: nama,
                depo: depo,
                act: act
            },
            success: function (data) {
                $("#modal_validasi .modal-body").html(data+'<div id="warning"></div>');
                
                if(!ace.vars['touch']) {
					$('.chosen-select').chosen({allow_single_deselect:true}); 
					//resize the chosen on window resize
			
					$(window)
					.off('resize.chosen')
					.on('resize.chosen', function() {
						$('.chosen-select').each(function() {
							 var $this = $(this);
							 $this.next().css({'width': 100});
						})
					}).trigger('resize.chosen');
					//resize chosen on sidebar collapse/expand
					$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
						if(event_name != 'sidebar_collapsed') return;
						$('.chosen-select').each(function() {
							 var $this = $(this);
							 $this.next().css({'width': $this.parent().width()});
						})
					});
				}

                $('.id-input-file-3').ace_file_input({
                    style: 'well',
                    btn_choose: 'Drop files here or click to choose',
                    btn_change: null,
                    no_icon: 'ace-icon fa fa-cloud-upload',
                    droppable: true,
                    thumbnail: 'small' //large | fit
                        //,icon_remove:null//set null, to hide remove/reset button
                        /**,before_change:function(files, dropped) {
                            //Check an example below
                            //or examples/file-upload.html
                            return true;
                        }*/
                        /**,before_remove : function() {
                            return true;
                        }*/
                        ,
                    preview_error: function(filename, error_code) {
                        //name of the file that failed
                        //error_code values
                        //1 = 'FILE_LOAD_FAILED',
                        //2 = 'IMAGE_LOAD_FAILED',
                        //3 = 'THUMBNAIL_FAILED'
                        //alert(error_code);
                    }

                }).on('change', function() {
                    //console.log($(this).data('ace_input_files'));
                    //console.log($(this).data('ace_input_method'));
                });

                queue.hide();
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }

    function validasi_hrd(validasi) {
        var queue = $('#progress1');
        var depo = $('#depo_val').val();
        var pin = $('#pin_val').val();
        var nik = $('#nik_val').val();
        var tanggal = $('#tanggal_val').val();
        queue.show();
        
        if (confirm("Apakah anda yakin?")) {
            $.ajax({
                type: "POST",
                url: "inc/absensi/validasi_absensi/proc_validasi.php",
                traditional: true,
                data: {
                    act: "validasi_absensi",
                    depo: depo, 
                    pin: pin, 
                    nik: nik, 
                    tanggal: tanggal,
                    validasi: validasi
                },
                success: function(data) {
                    alert(data)
                    view();
                    queue.hide();
                },
                error: function(msg) {
                    alert(msg);
                }
            });
        }
    }

    function save() {
        $('#modal_validasi').modal('show');
        var queue = $('#progress1');
        var act = "save_validasi";
        var pin = $('#modal_validasi #pin').val();
        var nik = $('#modal_validasi #nik').val();
        var depo = $('#modal_validasi #depo').val();
        var nama = $('#modal_validasi #nama').val();
        var tanggal = $('#modal_validasi #tanggal').val();
        var leave_category = $('#modal_validasi #slc_lc').val();
        var keterangan = $('#modal_validasi #keterangan').val();
        queue.show();
        $.ajax({
            type: "POST",
            url: "inc/absensi/validasi_absensi/proc_validasi.php",
            traditional: true,
            data: {
                act: act,
                depo: depo,
                nama: nama,
                pin: pin,
                nik: nik,
                tanggal: tanggal,
                leave_category: leave_category,
                keterangan: keterangan
            },
            success: function (data) {
                if (leave_category == 3) {
                    upload_image(depo,nik,tanggal);
                } else {
                    alert(data);
                    // location.reload();
                    queue.hide();
                    view();
                }
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }

    function upload_image(depo, nik, tanggal) {
		var fd = new FormData();
		var act = "upload_image";
		var total_file = $('#modal_validasi #absen_file').get(0).files.length;
		var absen_file = $('#modal_validasi #absen_file').get(0).files[0];
		var absen_file_name = "";
		if (total_file > 0) {
			var absen_file_name = absen_file.name;
		}
		var absen_file_ext = absen_file_name.split('.').pop();
		var rand_code = Math.floor(Math.random() * (999999 - 100000)) + 100000;
		var d = new Date();
		var filename = 'absensi' + '_' + d.getFullYear() + d.getMonth() + d.getDate() + '_' + rand_code + '.' + absen_file_ext;

		fd.append('act', act);
		fd.append('absen_file', absen_file);
		fd.append('filename', filename);
        fd.append('depo', depo);
        fd.append('nik', nik);
        fd.append('tanggal', tanggal);
		fd.append('total_file', total_file);

		if (total_file > 0) {
			$('#progress1').show();
			$.ajax({
				type: "POST",
				url: "inc/absensi/validasi_absensi/proc_validasi.php",
				processData: false, //important
				contentType: false, //important
				data: fd,
				success: function(responseText) {
					$('#modal_validasi #warning')
					.html('<span class="label label-success">\
						<i class="ace-icon fa fa-check bigger-120"></i>\
						'+responseText+'\
					</span>');
					$('#progress1').hide();
					setTimeout(function() {
						// location.reload();
                        view();
					}, 3000);
				},
				error: function(data) {
					alert(data);
				}
			});
		} else {
			$('#modal_validasi #warning')
			.html('<span class="label label-warning">\
				<i class="ace-icon fa fa-exclamation-triangle bigger-120"></i>\
				All data cannot be empty\
			</span>');
		}
	}
</script>