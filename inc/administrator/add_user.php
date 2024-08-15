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

function save_user(){
	var act = 'save_user';
	var username = $("#username").val();
	var pwd = $("#pwd").val();
	var emp_name = $("#emp_name").val();
	var divisi = $("#divisi").val();
	var user_level = $("#user_level").val();
	var nik = $("#nik").val();
	var leader = $("#slc_leader").val();
	var email_atasan = $('#slc_leader option:selected').data('email');
	var levelling = null;
	var entity = $("#slc_entity").val();
	var depo = $("#slc_depo").val();
	var segmen = $("#slc_segmen").val();
	var brand = $("#slc_brand").val();
	var url="";
	$("#progress").show(); 
	$.ajax({
		type:"POST",
		url:"inc/administrator/proc_create_user.php",
		data:"username="+username+"&pwd="+pwd+"&emp_name="+emp_name+"&divisi="+divisi+"&user_level="+user_level+"&nik="+nik+
		"&act="+act+"&leader="+leader+"&email_atasan="+email_atasan+"&levelling="+levelling+"&entity="+entity+"&depo="+depo+"&segmen="+segmen+"&brand="+brand+"&url="+url,
		success:function(responseText) {
			$("#progress").hide(); 
			alert(responseText);
			location.href="?mm=daftar_user";

		},
		error:function(data){alert(data);$("#progress").hide(); }
	});
}
</script>
<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?mm=daftar_user">List User</a>
			</li>
			<li class="active">Add user</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>
				Add User
			</h1>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="widget-body">
					<div class="col-md-6 widget_1_box2" style="">
						<div class="form-group">
							<label>Email : </label>
							<input type="text" id="username" value="" class="form-control">
						</div>
						<div class="form-group">
							<label>Password :  </label>
							<input type="password" id="pwd" value="" class="form-control">
						</div>
						<div class="form-group">
							<label>Employee Name : </label>
					        <input type="text" id="emp_name" value="" class="form-control">
						</div>
						<div class="form-group">
							<label>Employee Leader : </label>
					        <?php 
								$leader = DB::connection('mysql_hris')->query("SELECT * FROM user order by nama asc");
							?>
							<select class="select2 form-control" required="" id="slc_leader">
								<?php while($dleader = $leader->fetch_array()): ?>
									<option value="<?php echo $dleader['nama'];?>" data-email="<?php echo $dleader['username'];?>"><?php echo $dleader['nama'];?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label>Divisi : </label>
							<?php 
								$sql_divisi =  "SELECT * FROM [db_hris].[dbo].[table_master_divisi] order by nama_divisi";
								$stmt_divisi = $sqlsrv_hris->query($sql_divisi);
							?>
							<select class="select2 form-control" required="" id="divisi">
								<?php while($ddivisi = $sqlsrv_hris->fetch_array($stmt_divisi)): ?>
									<option value="<?php echo $ddivisi['nama_divisi'];?>"><?php echo $ddivisi['nama_divisi'];?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label>User Level : </label>
							<select class="select2 form-control" required="" id="user_level">
								<option value="Administrator">Administrator</option>
								<option value="Staff">Staff</option>
								<option value="Leader">Leader</option>								
							  </select>
						</div>
					</div>
					<div class="col-md-6 widget_1_box2" style="">
						<div class="form-group">
							<label>NIK : </label>
							<input type="text" id="nik" value="" class="form-control">
						</div>
						<div class="form-group">
							<label>Entity : </label>
							<select class="select2 form-control" id="slc_entity" data-placeholder="Choose a Entity..." >
								<?php
									$sql_entity = "SELECT * FROM [db_hris].[dbo].[table_master_entity] order by nama_entity";
									$stmt_entity = $sqlsrv_hris->query($sql_entity);
									while($row_entity = $sqlsrv_hris->fetch_array($stmt_entity)):
								?>
									<option value="<?php echo $row_entity['nama_entity']; ?>"><?php echo $row_entity['nama_entity']; ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label>Depo : </label>
							<select multiple="" id="slc_depo" name="slc_depo" class="select2 form-control" data-placeholder="Click to Choose...">
								<?php
								$sql_depo = "SELECT cab_name as depo from [db_ftm].[dbo].[cabang]
											union
											SELECT pembagian3_nama as depo from [db_fin_pro].[dbo].[pembagian3]";
								$stmt_depo = $sqlsrv_hris->query($sql_depo);
								if ($stmt_depo):
									while ($row = $sqlsrv_hris->fetch_array($stmt_depo)):
										echo '<option value="' . $row['depo'] . '">' . $row['depo'] . '</option>';
									endwhile;
								endif;
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Brand : </label>
							<select multiple="" class="select2 form-control" id="slc_brand" data-placeholder="Choose a Brand..." >
								<?php
									$sql_brand = "SELECT * FROM [db_hris].[dbo].[table_master_brand] order by nama_brand";
									$stmt_brand = $sqlsrv_hris->query($sql_brand);
									while($row_brand = $sqlsrv_hris->fetch_array($stmt_brand)):
								?>
									<option value="<?php echo $row_brand['nama_brand']; ?>"><?php echo $row_brand['nama_brand']; ?></option>
								<?php endwhile; ?>
								<option value=""></option>
							</select>
						</div>
						<div class="form-group">
							<label>Segmen : </label>
							<input type="text" id="slc_segmen" value="" class="form-control">
						</div>
						<div class="form-group">
							<label>Akses URL : </label>
							<?php 
								$sql_url = DB::connection('mysql_hris')->query("SELECT * FROM tbl_url_akses");
							?>
							<select class="select2 form-control" id="slc_url" data-placeholder="Choose a URL akses..." >
								<?php while($row_url = $sql_url->fetch_array()): ?>
									<option value="<?php echo $row_url['url_akses'];?>"><?php echo $row_url['akses'];?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>		
							
					<hr><br><br>
					<div class="col-md-12 ">
						<input type="button" class="btn btn-primary" onclick="save_user()" value="Save">	
						<span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
