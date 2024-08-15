<!DOCTYPE html>
<html lang="en">
<?php 
require('partials/config.php');
require('../lib/database.php');
include('../lib/class_paging.php');
include('../lib/fungsi_rupiah.php');
?>
	<?php include('partials/head.php'); ?>
	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default ace-save-state" style="background-image: url(../images/h1.jpg);background-size: cover;background-repeat: no-repeat;border-bottom: solid 7px #1e62a6;">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<small>
							<img src="../images/apple-touch-icon.png" width="50px" height="30px">
							<span style="font-family: Arial, serif;letter-spacing: 1px;text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);"><?php echo WEB_NAME ?></span>
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right hide" role="navigation" style="margin-top: 5px;">
					<ul class="nav ace-nav">
											
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background: transparent">
									<img class="nav-user-photo" src="../images/none.png" class="img-circle m-b" width="50" alt="logo" />
								<span class="user-info">
									<small>Welcome</small>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<?php include('partials/footer.php') ?>
	</body>
</html>

