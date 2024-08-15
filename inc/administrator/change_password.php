<script>
$(document).ready(function() {
	if(!ace.vars['touch']) {
		$('.chosen-select').chosen({allow_single_deselect:true}); 				
	}
	var field = document.querySelector('[id="pwd"]');

	field.addEventListener('keypress', function ( event ) {  
	var key = event.keyCode;
		if (key === 32) {
		event.preventDefault();
		}
	});
});
function save_pwd(id_user){
	var act = 'edit_pwd';
	var pwd = $("#pwd").val();
	if(pwd==''){alert('Please Fill Password');}
	else{
		  $("#progress_pwd").show(); 
		  $.ajax({
				 type:"POST",
				 url:"inc/administrator/proc_create_user.php",
				 data:"pwd="+pwd+"&id_user="+id_user+"&act="+act,
				 success:function(responseText){
					//$("#loading_rek").hide();
					$("#progress_pwd").hide(); 
					alert("Success To Update");
					//location.href="?mm=daftar_user";
					//detail pembinaan
					
					//
				 },
					error:function(data){
					alert(data);$("#progress_pwd").hide(); }
				});
	}
}
function save_user(id_user){
	var act = 'change_password';
	var username = $("#username").val();
	var pwd 	 = $("#pwd").val();

	if(pwd == ""){
		alert("Masukkan Password Yang Sesuai Saat Login Atau Ganti Password Sesuai Dengan Yang Diinginkan");
	}
	else {
		$("#progress").show(); 
		$.ajax({
			type:"POST",
			url:"inc/administrator/proc_change_password.php",
			data:"username="+username+"&pwd="+pwd+
			"&act="+act+"&id_user="+id_user,
			success:function(responseText){
				//$("#loading_rek").hide();
				$("#progress").hide(); 
				alert("Success To Update");
				location.href="?mm=daftar_user";
				//detail pembinaan
				
				//
			},
				error:function(data){
				alert(data);$("#progress").hide(); }
		});
	}
}
function view_depo(){
	var entity = $("#slc_entity").val();
	$("#progress_view2").show();
	$.ajax({
		type:"GET",
		url:"inc/administrator/get_depo.php?entity="+entity,
		success:function(data){											
			$("#span_depo").html(data);
			$("#progress_view2").hide();
		}
	});
}
</script>
<?php 
	$user = DB::connection('mysql_hris')->query("SELECT *,u.divisi as user_divisi FROM user as u inner join tbl_user_detail as d on u.username = d.user_name where u.id_user = '".$_SESSION['id_user']."'");	
	$duser = $user->fetch_array();
?>
<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?mm=daftar_user">List User</a>
            </li>
            <li class="active">Change Password</li>
        </ul>
    </div>
    <div class="page-content">
		<div class="page-header">
			<h1>
				Change Password<small>- <?php echo $duser['nama']; ?></small>
			</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="widget-body">
				<div class="col-md-6 widget_1_box2" style="">
					<div class="form-group">
						<label>Email : </label>
						<input type="text" id="username" value="<?php echo $duser['username'];?>" class="form-control" readonly>
					</div>
					<div class="form-group">
						<label>Password :  </label>
						<input type="password" id="pwd" value="" class="form-control">
					</div>
				</div>

				<hr><br><br>
				<div class="col-md-12 ">
					<input type="button" class="btn btn-primary" onclick="save_user('<?php echo $_SESSION['id_user'];?>')" value="Save">	
					<span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...</span>
				</div>
			</div>
		</div>
	</div>
</div>