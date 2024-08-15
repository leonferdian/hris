<?php 
	$user = DB::connection('mysql_hris')->query("SELECT * FROM user where id_user = '".$_GET['id_user']."'");
	$duser = $user->fetch_array();
?>
<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?mm=daftar_user">List User</a>
			</li>
			<li class="active">Detail user</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>
				Detail User<small>- <?php echo $duser['nama']; ?></small>
			</h1>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="widget-body">
					<div class="col-md-6 widget_1_box2" style="">
						<div class="form-group has-error">
							<label>Email : </label>
							<input type="text" id="username" value="<?php echo $duser['username'];?>" disabled class="form-control">
						</div>
						<div class="form-group has-error">
							<label>Password :  </label>
							<input type="password" id="pwd" value="<?php echo $duser['password'];?>" disabled class="form-control">
						</div>
						<div class="form-group has-error">
							<label>Employee Name : </label>
					        <input type="text" id="emp_name" value="<?php echo $duser['nama'];?>" disabled class="form-control">
						</div>
						<div class="form-group has-error">
							<label>Employee Leader : </label>
					        	<?php 
							$leader = DB::connection('mysql_hris')->query("SELECT * FROM user ");
							?>
							<select class="form-control has-error" ng-model="model.select" disabled required="" id="slc_leader">
								<option value="">--Select Leader--</option>
								<?php while($dleader = $leader->fetch_array()){?>
									<option value="<?php echo $dleader['nama'];?>" <?php echo ($dleader['nama']==$duser['leader'])?'selected':''; ?>><?php echo $dleader['nama'];?></option>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="col-md-6 widget_1_box2" style="">
						<div class="form-group">
							<label>Divisi : </label>
							<?php 
								$sql_divisi = DB::connection('mysql_hris')->query("SELECT * FROM tbdivisi");
							?>
							<select class="form-control has-error" ng-model="model.select" required="" disabled id="divisi">
								<option value="">--Select Divisi--</option>
								<?php while($ddivisi = $sql_divisi->fetch_array()){?>
									<option value="<?php echo $ddivisi['id'];?>" <?php echo ($ddivisi['id']==$duser['divisi'])?'selected':''; ?>><?php echo $ddivisi['cdivisi'];?></option>
								<?php }?>
							</select>
						</div>
						<div class="form-group has-error">
							<label>User Level : </label>
							<select class="form-control" ng-model="model.select" required="" disabled id="user_level">
								<option value="Administrator" <?php echo ($duser['user_level']=='Administrator')?'selected':''; ?>>Administrator</option>
								<option value="Staff" <?php echo ($duser['user_level']=='Staff')?'selected':''; ?>>Staff</option>
								<option value="Leader" <?php echo ($duser['user_level']=='Leader')?'selected':''; ?>>Leader</option>
								
							</select>
						</div>
						<div class="form-group has-error">
							<label>NIK : </label>
							<input type="text" id="nik" value="<?php echo $duser['nik'];?>" disabled class="form-control">
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>