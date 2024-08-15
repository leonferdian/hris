<style>
    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }
</style>
<?php
require('partials/config.php');
if (!isset($_GET['email'])):
    header("location:login.php");
else: ?>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title><?php echo WEB_NAME; ?>: Verification Page</title>
        <meta name="description" content="User verification page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="icon" type="image/gif/png" href="images/padma_logo.gif">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
        <link rel="stylesheet" href="assets/css/ace.min.css" />
        <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
    </head>
    <body class="login-layout light-login">
        <div class="main-container"
            style="background-image: url('images/back4.jpg');background-color: #ffffff; background-repeat: no-repeat;background-attachment: fixed;background-size: cover;">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="space-30"></div>
                            <div class="space-30"></div>
                            <div class="space-30"></div>
                            <div class="space-30"></div>
                            <div class="space-30"></div>
                            <div class="space-30"></div>
                            <img src="images/apple-touch-icon.png" alt="Dashboard" class="center">
                            <br>
                            <div class="center">
                            </div>
                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border"
                                    style="background-color: #F4F9F9">
                                    <div class="widget-body">
                                        <div class="widget-main" style="background-color: #F4F9F9">
                                            <h5 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-coffee green"></i>
                                                Please Enter Your Code Verification
                                            </h5>
                                            <div class="space-6"></div>
                                            <!-- <form action="proc/loginsubmit.php" id="loginForm" method="Post"> -->
                                            <form id="loginForm">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            Code
                                                            <input type="text" title="Please enter your password"
                                                                required="" value="" name="code" id="code"
                                                                class="form-control">
                                                        </span>
                                                    </label>
                                                    <input type='hidden' id='email' name='email'
                                                        value='<?php echo $_GET['email']; ?>'>
                                                    <div class="clearfix">
                                                        <span class="help-block small" id="exp_fail">
                                                            <button class="btn btn-success btn-block"
                                                                onclick="login('proc/loginsubmit.php?act=code')">Submit</button>
                                                            <div class="space-4"></div>
                                                        </span>
                                                    </div>
                                                </fieldset>
                                            </form>
                                            <button class="btn btn-success btn-block" id='resent_code' onclick="send_code()">Resend Code</button>
                                        </div><!-- /.widget-main -->
                                    </div><!-- /.widget-body -->
                                </div><!-- /.login-box -->
                            </div><!-- /.position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script src="assets/js/jquery-2.1.4.min.js"></script>

        <!-- <![endif]-->

        <!--[if IE]>
                <script src="assets/js/jquery-1.11.3.min.js"></script>
                <![endif]-->
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
        </script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            $(document).ready(function () {
                sent_mail();
            });

            function login(action) {
                var form = document.getElementById('loginForm');
                form.action = action;
                form.method = "Post";
                form.submit();
            }

            function send_code() {
                var countdown = 60;
                document.getElementById("resent_code").disabled = true;
                document.getElementById("resent_code").innerHTML = 'Resent Code ' + '(' + countdown + ')';
                sent_mail();
                var start = setInterval(function () {
                    countdown--;
                    document.getElementById("resent_code").innerHTML = 'Resent Code ' + '(' + countdown + ')';

                    // If the count down is finished, write some text
                    if (countdown == 0) {
                        clearInterval(start);
                        document.getElementById("resent_code").innerHTML = "Resent Code";
                        document.getElementById("resent_code").disabled = false;
                    }
                }, 1000);
            }

            function sent_mail() {
                $.ajax({
                    type: "POST",
                    url: "proc/send_email.php",
                    data: {
                        act: "send_email",
                        username: $('#email').val()
                    },
                    success: function (data) {
                        alert(data);
                    },
                    error: function (msg) {
                        alert(msg);
                    }
                });
            }
        </script>
    </body>
</html>
<?php endif; ?>