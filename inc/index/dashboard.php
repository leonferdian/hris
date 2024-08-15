<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="#">Home</a>
			</li>
			<li class="active">Dashboard</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>
				Dashboard
			</h1>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="widget-box" id="widget-box-1">
					<div class="widget-header hide" style="padding: 10px;">
						<div class="widget-body">
							<div class="widget-main no-padding">
								<div id="view_report">
									<span id="progress" style="display:none"><img src="images/loading.gif" width="20" />
										Please Wait... </span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-content" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header text-center hide">
				<div class="pull-right">
					<button class="btn btn-minier btn-danger" data-dismiss="modal">
						<i class="ace-icon fa fa-times"></i>
					</button>
				</div>
				<h2 class="modal-title font-weight-bold"></h2>
			</div>
			<span id="progress_view2" style="display:none"><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Load data... </span>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {

	});

	function load_table() {
		// var tgl_range = $("#date_range").val().split(' - ');
		var tgl1 = "<?php $dt = date("Y-m-d");
		echo date("Y-m-d", strtotime("$dt -7 day")); ?>";
		var tgl2 = "<?php echo date("Y-m-d"); ?>";
		var company = "";
		var comp_name = "";
		var queue = $('#progress_view2');
		queue.show();

		$.ajax({
			type: "GET",
			url: "inc/post_everything/post/view_timeline_dashboard.php",
			data: "id_company=" + company + "&tgl1=" + tgl1 + "&tgl2=" + tgl2,
			success: function (data) {

				$("#view_report").html(data);
				$('.timeline-title').html(comp_name.toUpperCase());
				$('#btn-timeline-write').show();
				$('#btn-load').show();

				// $(".tahun").html('Report Periode ' + tgl1 + ' sd ' +tgl2);
				queue.hide();
			},
			error: function (msg) {
				alert(msg);
			}
		});
	}
</script>