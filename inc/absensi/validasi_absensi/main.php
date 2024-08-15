<?php 
$sqlsrv_db = DB::connection('sqlsrv_hris'); 
$where_depo = $_SESSION['total_depo'] != 0 ? " WHERE depo in ".$_SESSION['akses_depo']."" : "";
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Dashboard</a>
            </li>
            <li class="active">Validasi Absensi</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                <?php echo strtoupper(str_replace("_"," ",$_GET['act'])); ?>
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
                                <span id="span-company">
                                    <label for="slc_depo">Depo: &nbsp;&nbsp;&nbsp;</label>
                                    <select class="form-control input-sm select2" name="slc_depo" id="slc_depo"
                                        data-placeholder="Select Company" onchange="">
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
                                                echo '<option value="'.$row['depo'].'">'.strtoupper($row['depo']).'</option>';
                                            endwhile; 
                                            ?>
                                        <option value="Blank">Unknow</option>
                                    </select>
                                </span>
                                <br><br>
                                <label for="datepicker1">Tanggal: </label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" id="datepicker1" name="datepicker1" value="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd" placeholder="">
                                </div>
                                <input type="hidden" id="act" value="<?php echo $_GET['act']; ?>" />
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
            <div class="widget-main">
            <span id="progress" style="display:none"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Load data... </span>
            <div class="pull-right" >
                <div id="counter"></div>
            </div>
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
        var tanggal = $("#datepicker1").val();
        var queue = $('#progress');
        var slc_depo = $('#slc_depo').val();
        var act = $('#act').val();
        queue.show();
        updateCounter();

        $.ajax({
            type: "GET",
            url: "inc/absensi/validasi_absensi/view_report.php",
            traditional: true,
            data: {
                tanggal: tanggal,
                depo: slc_depo,
                act: act
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