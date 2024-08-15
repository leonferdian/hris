<!DOCTYPE html>
<html lang="en">
<?php 
ob_start();
session_start();
require('partials/config.php');

if(!isset($_SESSION['username'])) {
	header("location:login.php");
}
else{
    require('lib/database.php');

	$sql = " select * from user where id_user = '".$_SESSION['id_user']."' ";
    $cek_login = DB::connection('mysql_hris')->query($sql);  
    $dcek_login = $cek_login->fetch_array();

    if($dcek_login['password'] != $_SESSION['password'])
    {
        header("location:login.php");
    }
    else if($_SESSION['kind_app']!="dashboardd"){
        header("location:login.php");
    }
}

include('lib/class_paging.php');
include('lib/fungsi_rupiah.php');
?>
<?php if($_SESSION['url_status']=="internal" || $_SESSION['url_status']=="eksternal,internal" || $_SESSION['url_status']=="internal,eksternal" || $_SESSION['url_status']=='null' || $_SESSION['url_status']==null): ?>
	<?php include('partials/head.php'); ?>

	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default ace-save-state" style="background-image: url(images/h1.jpg);background-size: cover;background-repeat: no-repeat;border-bottom: solid 7px #1e62a6;">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="?page=dashboard" class="navbar-brand">
						<small>
							<img src="images/apple-touch-icon.png" width="50px" height="30px">
							<span style="font-family: Arial, serif;letter-spacing: 1px;text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);">Padma HRIS</span>
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation" style="margin-top: 5px;">
					<ul class="nav ace-nav">
											
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background: transparent">
								<?php $lokasi_foto = "images/employee/".$_SESSION['user_foto'];
									if($_SESSION['user_foto']!='' or $_SESSION['user_foto']!=null):
								?>
					                <img class="nav-user-photo" src="images/employee/<?php echo $_SESSION['user_foto'];?>" class="img-circle m-b" width="50" alt="logo">
								<?php else: ?>
									<img class="nav-user-photo" src="images/none.png" class="img-circle m-b" width="50" alt="logo" />
								<?php endif; ?>
								<span class="user-info">
									<small>Welcome,</small>
									<?php echo $_SESSION['nama'];?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<!-- <li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										Settings
									</a>
								</li> -->

								<li>
									<a href="?mm=daftar_user&act=edit_user&id_user=<?php echo $_SESSION['id_user'];?>" id="change_pass">
										<i class="ace-icon fa fa-user"></i>
										Change Password
									</a>
								</li>
								

								<li class="divider"></li>

								<li>
									<a href="proc/logoutsubmit.php">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<?php include('partials/footer.php') ?>
	</body>
<?php 
else:
	echo "PAGE NOT FOUND";
endif;
?>
</html>

