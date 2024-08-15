<?php
    require('../lib/database.php');
    include_once('../lib/config.php');
    require("../lib/phpmailer/class.phpmailer.php");
    require("../lib/phpmailer/class.pop3.php");

    $username = addslashes($_POST['username']);
    $result = "";
    $random = rand(100001,999999);
    $sql_user_email = "select * from user where username ='".$username."'";
    $stmt_user = DB::connection('mysql_hris')->query($sql_user_email);
    $num_user2 = $stmt_user->num_rows;

    if($num_user2 == 1){
        $sql_update = "update user set user_authentication = '".$random."', expired_code=now()+ INTERVAL 5 MINUTE, date_modified=NOW() where username = '".$username."'";
        $stmt_code = DB::connection('mysql_hris')->query($sql_update);
    } else {
        $result = "User Tidak Ditemukan!";
    }

    if($stmt_code === false){
        $success = 0;
    }
    else{
        $success = 1;
    }

    if($success==1){
        $mail = new PHPMailer();
        $pop = new POP3();
        $pop->Authorise('10.100.100.19', 110, 30, 'handheld@xeise.com', 'hh.45', 1);
        //$mail = new PHPMailer();
        $mail->IsSMTP();                                      // set mailer to use SMTP
        $mail->Host = "10.100.100.19";  // specify main and backup server
        $mail->SMTPAuth = true;     // turn on SMTP authentication
        $mail->Username = "handheld@xeise.com";  // SMTP username
        $mail->Password = "hh.45"; // SMTP password*/

        $mail->From = "handheld@xeise.com";
        $mail->FromName = "Dashboard HRIS";
        $mail->AddAddress($username,'');
        $mail->AddReplyTo("handheld@xeise.com", "Dashboard HRIS");
        $mail->WordWrap = 50;                                 
        // set word wrap to 50 characters
        $mail->IsHTML(true);                                  
        // set email format to HTML

        $mail->Subject = "Kode Login Website Dashboard HRIS";
        $mail_content = " Hi, <b>".$username."</b><br><br>";
        $mail_content .= "Gunakan kode ini setiap kali login pada <u>Website Dashboard HRIS</u>.<br>";
        $mail_content .= "Ini kode login rahasia anda : ".$random."<br>";
        // $mail_content .= "Jangan bagikan kode login kamu dengan yang lain. <br><br>";
        $mail_content .= "Thanks & regards<br>";
        $mail_content .= "<img src='http://www.padmatirtagroup.com/tms/images/padma_logo.gif'/><br>";
        $mail_content .= "<br><br><br>*Please do not reply to this email, thank you for you cooperation.";
        $mail_content .= "<br>*Tolong untuk tidak membalas email ini, terima kasih untuk kerjasamanya.";
        $mail->Body    = $mail_content;
        $mail->AltBody = "This is the body in plain text for non-HTML mail clients";
        if(!$mail->Send())
        {
            //$result = array("success"=>0,"status"=>"E","message"=>"Sent Data Error. Please try Again","other_message"=>$mail->ErrorInfo);
            $result = $mail->ErrorInfo;
        }
        else{
            //$result = array("success"=>1,"status"=>"S","message"=>"Data Sent. Please Check Your Email","other_message"=>""); 
            $result = "Silahkan Cek Email Anda";
        }
    }
    else{
        //$result = array("success"=>0,"status"=>"E","message"=>"Email anda belum terdaftar","other_message"=>"");
        $result = "Ada Kesalahan Sistem! Silahkan Hubungi Tim IT";
    }
    echo $result;
    DB::connection('mysql_hris')->close();
?>