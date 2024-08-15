<?php 
session_start();
ob_start();
require('partials/config.php');
?>
<style>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 30%;
}
</style>

<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<link rel="icon" type="image/gif/png" href="<?php echo site_url('images/padma_logo.gif'); ?>">
		<title><?php echo WEB_NAME ?>: LOGIN</title>
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo site_url('assets/font-awesome/4.5.0/css/font-awesome.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo site_url('assets/css/fonts.googleapis.com.css'); ?>" />
		<link rel="stylesheet" href="<?php echo site_url('assets/css/ace.min.css'); ?>" />
		<link rel="stylesheet" href="<?php echo site_url('assets/css/ace-rtl.min.css'); ?>" />		
	</head>

	<body class="login-layout">
		<div class="main-container" style="background-image: url('<?php echo site_url('images/back4.jpg'); ?>');background-color: #ffffff; background-repeat: no-repeat;background-attachment: fixed;background-size: cover;">
		<!-- <div class="main-container" style="background-image: linear-gradient(45deg,green, white);"> -->
			<div class="main-content" >
				<div class="row" >
					<div class="col-sm-10 col-sm-offset-1">
						<img src="<?php echo site_url('images/padma.png'); ?>" style="height: 150px;width: 250px;margin-top:3%" alt="Dashboard" class="center">
						<div class="login-container">
							<div class="center">
								<h1>
									<span class="" > </span>
									<!-- <span class="white" id="id-text2">UNA</span> -->
								</h1>
								<!-- <h4 class="blue" id="id-company-text">&copy; Unichell Nirvana Amerta</h4> -->
							</div>
							<div class="space-6"></div>
							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border" style="background-color: #F4F9F9;border: 2px solid white;border-radius: 10px;" >
									<div class="widget-body" >
										<div class="widget-main" style="background-color: #ebeff2;">
											<h4 class="header blue lighter bigger" style="text-align: center">
												<!-- <i class="ace-icon fa fa-coffee green"></i> -->
												LOGIN <?php echo WEB_NAME; ?>
											</h4>
											<div class="space-6"></div>
											<!-- <form action="proc/loginsubmit.php" id="loginForm" method="Post"> -->
											<form id="loginForm">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															Email
															<input type="text" placeholder="example@padmatirtagroup.com" title="Please enter you username" required="" value="" name="email" id="email" class="form-control">
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															Password
															<input type="password" title="Please enter your password" placeholder="******" required="" value="" name="pswd" id="pswd" class="form-control">													
														</span>
													</label>

													<div class="clearfix">
							                            <span class="help-block small" id="exp_fail">
															<button class="btn btn-primary btn-block" onclick="login('proc/loginsubmit.php?act=login')"  style="border-radius: 10px;">Login User</button>
															<div class="space-4"></div>
															<font color="Red">
																<?php 
																	if(isset($_GET['status'])):
																		$failure = $_GET['failure'];
																		if($failure<5){
																			echo $_GET['status']." Anda Sudah ".$failure." Kali Login. Jika Gagal 5 kali Silahkan Hubungi Administrator";
																		} else { 
																			echo "Anda Sudah 5 Kali Login. Hubungi Administrator Untuk Mereset Account Anda";
																		}
																	endif;
																?>
															</font>
														</span>
													</div>
												</fieldset>
											</form>
											<!-- <button class="btn btn-success btn-block" onclick="send_mail()">Request Code</button> -->
										</div><!-- /.widget-main -->
										
										<div class="toolbar clearfix" style="display:none;">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->
								
								<!-- <button style="position: relative;left:220px; <?php if(isset($_GET['status'])){echo "top:-200px";}else{echo "top:-163px";} ?>" class="btn btn-success" onclick="send_mail()">Req Code</button> -->								
								
								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>
											<p>
												Enter your email and to receive instructions
											</p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<div class="clearfix">
														<button type="button" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Send Me!</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<div id="signup-box" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												New User Registration
											</h4>

											<div class="space-6"></div>
											<p> Enter your details to begin: </p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="Username" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>

													<label class="block">
														<input type="checkbox" class="ace" />
														<span class="lbl">
															I accept the
															<a href="#">User Agreement</a>
														</span>
													</label>

													<div class="space-24"></div>

													<div class="clearfix">
														<button type="reset" class="width-30 pull-left btn btn-sm">
															<i class="ace-icon fa fa-refresh"></i>
															<span class="bigger-110">Reset</span>
														</button>

														<button type="button" class="width-65 pull-right btn btn-sm btn-success">
															<span class="bigger-110">Register</span>

															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												<i class="ace-icon fa fa-arrow-left"></i>
												Back to login
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->
<!-- 
							<div class="navbar-fixed-top align-right">
								<br />
								&nbsp;
								<a id="btn-login-dark" href="#">Dark</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-blur" href="#">Blur</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-light" href="#">Light</a>
								&nbsp; &nbsp; &nbsp;
							</div> -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="<?php echo site_url('assets/js/jquery-2.1.4.min.js'); ?>"></script>

		<!-- <![endif]-->
		<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.min.css'); ?>" />
		<script src="<?php echo site_url('assets/js/chosen.jquery.min.js'); ?>"></script>
		<!--[if IE]>
<script src="../<?php echo site_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../<?php echo site_url('assets/js/jquery.mobile.custom.min.js'); ?>'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });

			});


			jQuery(function($) {
				chosen_select();
			});

			function chosen_select(){
				$('.chosen-select').chosen({search_contains: true}); 
				$(window)
				.off('resize.chosen')
				.on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						var $this = $(this);
						$this.next().css({'width': '100%'});
					})
				}).trigger('resize.chosen');
			}
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});

			function login(action) {
				var email = $("#email").val();
				if(email=="") {
					alert("Email Harus Diisi");
					return false;
				}else{
					var form = document.getElementById('loginForm');
					form.action = action;
					form.method="Post";
					form.submit();
				}
			}


			// function send_mail(){				
			// 	$.ajax({
			// 		type:"POST",
			// 		url:"proc/send_email.php",
			// 		data: {
			// 			act: "send_email",
			// 			username:  $('#email').val(),
			// 			password :  $('#pswd').val()                          
			// 		},                        
			// 		success:function(data){
			// 			alert(data);
			// 		},
			// 		error:function(msg){                            
			// 			alert(msg);
			// 		}
			// 	});
			// }

			// function login(){				
			// 	$.ajax({
            //             type:"POST",
            //             url:"proc/loginsubmit.php",
            //             data: {
			// 				username:  $('#email').val(),
			// 				password :  $('#pswd').val(),
			// 				code: $('#code').val()
            //             },                        
            //             success:function(data){
                            
            //             },
            //             error:function(msg){                            
            //                 alert(msg);
            //             }
            //         });
			// }
		</script>
	</body>
</html>
