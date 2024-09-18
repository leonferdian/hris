<?php header('Access-Control-Allow-Origin: *');   ?>
<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');

if (isset($_GET['act']) and $_GET['act'] == "add"):
    include 'inc/cuti/approval_atasan/form.php';
elseif (isset($_GET['act']) and $_GET['act'] == "edit"):
	include 'inc/cuti/approval_atasan/form.php';
elseif (isset($_GET['act']) and $_GET['act'] == "detail"):
	include 'inc/cuti/approval_atasan/form_detail.php';
else:
?>
	<script>
		$(document).ready(function() {

			$('.chosen-select').chosen();
			$(window)
				.off('resize.chosen')
				.on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						var $this = $(this);
						$this.next().css({
							'width': '100%'
						});
					})
				}).trigger('resize.chosen');

		});

		/*
		var map;
		function initMap() {
			var myLatLng = {lat: -7.2483487621394245, lng: 112.78146597452997};
				map = new google.maps.Map(document.getElementById('map'), {
					zoom: 15,
					center: myLatLng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				
		}
		*/
		var header_used = [];
		var header_not_used = [
			"entity",
			"asal2",
			"depo_name",
			"tanggal",
			"week",
			"bulan",
			"from_hh",
			"nama_sales",
			"hot",
			"hos",
			"sm",
			"satuan",
			"brand",
			"jenis_item2",
			"jenis_item3",
			"channel",
			"segment",
			"sub_segment",
			"rute",
			"masking_segment",
			"masking_divisi"
		];

		function add_header() {
			var header = $('#header').val();
			if (header_used.includes(header)) {
				alert("Data sudah dimasukkan dalam urutan");
			} else {
				header_used.push(header);
				var index = header_not_used.indexOf(header);
				header_not_used.splice(index, 1);
				// console.log(header_used);
				// console.log(header_not_used);
				addbutton("button", header);
			}
		}

		function addbutton(type, header) {
			//Create an input type dynamically.   
			var element = document.createElement("button");
			//Assign different attributes to the element. 
			element.id = header;
			//element.style = "pointer-events: none;";
			element.ondblclick = function() { // Note this is a function
				var elem = document.getElementById(header);
				elem.parentNode.removeChild(elem);
				var index = header_used.indexOf(header);
				header_used.splice(index, 1);
				header_not_used.push(header);

				// console.log(header_used);
				// console.log(header_not_used);
				return false;
			};
			element.onclick = function() {
				return false;
			};

			var foo = document.getElementById("sortable");
			foo.appendChild(element);

			var text = document.createTextNode(header + " ");
			element.appendChild(text);
			var icon = document.createElement("i");
			icon.className = "ace-icon fa fa-times red2";
			element.appendChild(icon);
		}
	</script>
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
					<a href="?page=dashboard">Home</a>
				</li>
				<li class="active"> <a href="?page=aktifitas"> Cuti </a></li>
				<li class=""> <a href="?page=aktifitas"> Approval Cuti </a></li>
			</ul>
		</div>
		<div class="page-content">
			<div class="page-header">
				<div class="pull-right hide">
					<a class="btn btn-white btn-xs btn-success"onclick="add_event()"><i class="fa fa-plus"></i> Create New</a>
				</div>
				<h1>
					Approval Cuti <?php #echo $company_name; ?>
				</h1>
			</div>
			<div class="row" style="">
				<div class="col-xs-12">
					<div class="widget-body">
						<div id="ProgressDialog" style="display:none">
							<h4 class="header smaller lighter blue">
								<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Please Wait...
							</h4>
						</div>
						<span id="progress_view1" style="display:none"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Please Wait... </span>
						<div id="view_report">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<!-- <div class="modal-header text-center hide">
					<div class="pull-right">
						<button class="btn btn-minier btn-danger" data-dismiss="modal">
							<i class="ace-icon fa fa-times"></i>
						</button>
					</div>
					<h2 class="modal-title font-weight-bold"></h2>
				</div>
				<div class="modal-body">

				</div>
				<div class="modal-footer hide">
				</div> -->
			</div>
		</div>
	</div>
	<div class="modal fade" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header text-center">
					<h2 class="modal-title blue font-weight-bold"></h2>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
	<!-- <link rel="stylesheet" href="assets/css/jquery.gritter.min.css" />
	<script src="assets/js/jquery.gritter.min.js"></script> -->
	<script>
		$(document).ready(function() {
			$('#tbl_report').DataTable({
				fixedHeader: {
					header: true,
					footer: true
				},
				responsive: true
			});

			view();
		});

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

		function view() {
			// var tgl_range = $("#date_range").val().split(' - ');
			// var tgl1 = tgl_range[0];
			// var tgl2 = tgl_range[1];
			$("#progress_view1").show();
			$.ajax({
				type: "GET",
				url: "inc/cuti/approval_atasan/view_report.php",
				// traditional: true,
				// data: {
				// 	destination: destination,
				// 	status: status,
				// 	tgl1: tgl1,
				// 	tgl2: tgl2
				// },
				success: function(data) {
					$("#view_report").html(data);
					$("#progress_view1").hide();
				},
				error: function(msg) {
					alert(msg);
				}
			});
		}

		function edit_event(id) {
			PDialog();
			$.ajax({
				type: "GET",
				url: "inc/cuti/approval_atasan/form.php",
				// traditional: true,
				data: {
					act: "edit",
					id: id
				},
				success: function(data) {
                    $("#ProgressDialog").dialog("close");
					$('#modal-form').modal('show');
					$("#modal-form .modal-content").html(data);
				},
				error: function(msg) {
					alert(msg);
				}
			});
		}

		function list_event(tanggal) {
			PDialog();
			$.ajax({
				type: "GET",
				url: "inc/cuti/approval_atasan/list_event.php",
				// traditional: true,
				data: {
					tanggal: tanggal
				},
				success: function(data) {
					$("#ProgressDialog").dialog("close");
					$('#modal_view').modal('show');
					$("#modal_view .modal-body").html(data);
				},
				error: function(msg) {
					alert(msg);
				}
			});
		}

        function approval(id, status) {
            if (status == 1) {
                if (confirm('Apakah anda yakin?')) {
                    save_approval(id, status, '');
                }
            } else {
                bootbox.prompt("Alasan Reject:", function(result) {
                    if (result === null) {
                        alert('keterangan reject harus diisi');
                    } else {
                        save_approval(id, status, result);
                    }
                });
            }
        }

        function save_approval(id, status, result) {
            var act = "approve_cuti";
            PDialog();

            $.ajax({
                type: "POST",
                url: "inc/cuti/approval_atasan/proc_cuti.php",
                traditional: true,
                data: {
                    act: act,
                    id: id,
                    status: status,
                    alasan_reject: result
                },
                success: function (data) {
                    view();
                    $("#ProgressDialog").dialog("close");
                    $('#modal-form').modal('hide');
                    $.gritter.add({
							title: '',
							text: data,
							class_name: 'gritter-info gritter-center'
					});
					return false;
                },
                error: function (msg) {
                    alert(msg);
                }
            });
        }
	</script>
<?php endif; ?>