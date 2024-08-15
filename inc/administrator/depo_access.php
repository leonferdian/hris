<script>
	$(document).ready(function () {
		$('.select2').css('width', '100%').select2({ allowClear: true })
		$('#select2-multiple-style .btn').on('click', function (e) {
			var target = $(this).find('input[type=radio]');
			var which = parseInt(target.val());
			if (which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
		});
	});

	function save_hak_akses() {
		var id_user = $('#id_user').val();
		var list_depo = $('#depo').val();
		$("#progress").show();
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_depo_access.php",
			data: {
				act: "save_changes",
				id_user: id_user,
				list_depo: list_depo,
			},
			success: function (responseText) {
				alert(responseText);
				if (responseText == "Data Saved") {
					location.reload();
					$("#progress").hide();
				}
			},
			error: function (data) {
				alert(data);
				$("#progress").hide();
			}
		});
	}
</script>
<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');
$sql_user = "SELECT * FROM user where id_user = '" . $_GET['user_id'] . "'";
$user = DB::connection('mysql_hris')->query($sql_user);
$duser = $user->fetch_array();

$sql_user = "SELECT * FROM tbl_hakdepo WHERE id_user = '" . $duser['id_user'] . "'";
$stmt_user = DB::connection('mysql_hris')->query($sql_user);
$arr_depo = [];
while ($row = $stmt_user->fetch_array()) {
	$arr_depo[] = $row['kode_depo'];
}
?>
<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?page=dashboard">Home</a>
			</li>
			<li>
				<a href="?mm=hak_akses">Hak Akses user</a>
			</li>
			<li class="active">Hak Akses Depo</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>
				Hak Akses Depo<small> - <?php echo $duser['nama']; ?></small>
			</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="widget-body">
				<input type="hidden" id="id_user" value="<?php echo $duser['id_user']; ?>">
				<div class="col-md-5 widget_1_box2" style="">
					<div class="form-group">
						<label for="depo" class="">Depo</label>
						<select multiple="" id="depo" name="depo" class="select2" data-placeholder="Click to Choose...">
							<?php
							$sql = "SELECT cab_name as depo from [db_ftm].[dbo].[cabang]
									union
									SELECT pembagian3_nama as depo from [db_fin_pro].[dbo].[pembagian3]";
							$stmt = $sqlsrv_hris->query($sql);
							if ($stmt):
								while ($row = $sqlsrv_hris->fetch_array($stmt)):
									$selected = in_array(trim($row['depo']), $arr_depo) ? "selected" : "";
									echo '<option value="' . $row['depo'] . '" ' . $selected . '>' . $row['depo'] . '</option>';
								endwhile;
							endif;
							?>
						</select>
					</div>
					<button type="button" class="btn btn-outline btn-primary" onclick="save_hak_akses()">Save</button>
					<span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...</span>
				</div>
			</div>
		</div>
	</div>
</div>