<?php 
	$get_user = mysqli_query($dbnva, "SELECT * FROM user WHERE id_user = '".$_SESSION['id_user']."' ");
	$row_user = mysqli_fetch_array($get_user);
?>
<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?page=dashboard">Dashboard HOME</a>
			</li>
			<li class="active">User Profile - <?php echo $row_user['nama']; ?></li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>
				User Profile - <?php echo $row_user['nama']; ?>
			</h1>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="widget-body">
					<div id="user-profile-1" class="user-profile row">
						<form id="haha" action="inc/index/profil/doupload_foto.php" method="post" enctype="multipart/form-data" target="myiframe" onsubmit="return uploadfile()" class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern" novalidate="novalidate" ng-submit="submit()">
							<div class="col-xs-12 col-sm-3 center">
								<span class="profile-picture">
									<?php $lokasi_foto = "images/employee/".$row_user['user_foto'];
										if($row_user['user_foto']!='' or $row_user['user_foto']!=null){
									?>
						                <img id="avatar" class="editable img-responsive" src="images/employee/<?php echo $row_user['user_foto'];?>" width="180" >
									<?php }else{?>
										<img id="avatar" class="editable img-responsive"src="images/unknown_photo.png" width="180" />
									<?php }?>
								</span>
								<div class="space-4"></div>
								<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
									<div class="inline position-relative">
										<a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
											<i class="ace-icon fa fa-circle light-green"></i>
											<span class="white"><?php echo $row_user['nama']; ?></span>
										</a>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6">
								<div class="profile-user-info profile-user-info-striped">
									<div class="profile-info-row">
										<div class="profile-info-name"> Username </div>
										<div class="profile-info-value">
											<span class="editable" id="username"><?php echo $row_user['username']; ?></span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> Last Login </div>

										<div class="profile-info-value">
											<span class="editable" id="last_login"><?php echo $row_user['user_lastlogin']; ?></span>
										</div>
									</div>
									<div class="profile-info-row">
										<div class="profile-info-name"> Leader </div>
										<div class="profile-info-value">
											<span class="editable" id="leader"><?php echo $row_user['leader']; ?></span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"> Upload Foto </div>
										<div class="profile-info-value">
											<span class="editable" id="upload_foto">
												<input type="file" name="foto_user" id="foto_user"> 
												<input type="hidden" name="id_user" id="id_user" value="<?php echo $row_user['id_user'];?>"/>	
												Format file must be jpg or jpeg.
											</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"></div>
										<div class="profile-info-value">
											<span class="editable" id="foto">
												<button type="submit" class="btn-minier btn-success btn" style="">Upload Photo</button>
											</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"> New Password </div>
										<div class="profile-info-value">
											<span class="editable" id="new_pass">
												<input type="hidden" id="id_user" name="id_user" value="<?php echo $row_user['id_user']; ?>" class="form-control">
												<input type="password" title="Please enter your password" name="pswd_new" id="pswd_new" class="form-control">
											</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name" style="width:200px;"> Re-Entry New Password </div>
										<div class="profile-info-value">
											<span class="editable" id="re_entry_new_pass">
												<input type="password" title="Please enter your password" required="" name="re_pswd_new" id="re_pswd_new" class="form-control">
											</span>
										</div>
									</div>

									<div class="profile-info-row">
										<div class="profile-info-name"></div>
										<div class="profile-info-value">
											<span class="editable" id="foto">
												<button type="button" class="btn-minier btn-success btn" onclick="edit_pass()">Change Password</button>
											</span>
										</div>
									</div>
									<div id="progress" style="display:none;"><img src="images/loading.gif" id="loader"/><br />Please Wait...</div> 
								</div>
							</div>
						</form>
						<iframe id="myiframe" name="myiframe" src="#" style="height:0px; width:0px" frameborder="0"></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
function sukses(url,url2){
	$('#avatar').attr('src',url2);
	document.getElementById("haha").reset();	
	location.reload();	
}
function uploadfile(){
	//document.getElementById("haha").style.display='none';
	document.getElementById("progress").style.display='';
	document.getElementById("haha").submit();
	return false;
}
function edit_pass(){
	//var act = "cek_pass_old";
	var pswd_new = $("#pswd_new").val();
	var re_pswd_new = $("#re_pswd_new").val();
	var id_user = $("#id_user").val();
	$.ajax({
		type:"POST",
		url:"inc/index/profil/proses_edit_password.php", 
	  	data :"pswd_new="+pswd_new+"&id_user="+id_user+"&re_pswd_new="+re_pswd_new,
	  	success:function(responseText){
			alert(responseText);
		},
		error:function(msg){
			alert("Error : "+msg);	
		}
	});
}
</script>