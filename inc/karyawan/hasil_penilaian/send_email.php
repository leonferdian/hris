<?php
    // require('../../../lib/database.php');
    include_once('../../../lib/config.php');
    require("../../../lib/phpmailer/class.phpmailer.php");
    require("../../../lib/phpmailer/class.pop3.php");

    function send_email($email = "", $cc = "", $data = array()){
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
        $mail->AddAddress($email,'');
        $mail->AddReplyTo("handheld@xeise.com", "Dashboard HRIS");
        $mail->addCC($cc,'');  // Add a CC
        $mail->WordWrap = 50;                                 
        // set word wrap to 50 characters
        $mail->IsHTML(true);                                  
        // set email format to HTML

        $mail->Subject = "Dokumentasi Penilaian Kinerja Karyawan";
        $mail_content = " Hi, <b>".$email."</b><br><br>";
        $mail_content .= "Aplikasi Form penilaian kinerja karyawan telah diverifikasi dengan detail berikut: <br>";
        $mail_content .= "Kode Form: ".$data['kode_penilaian']." <br>";
        $mail_content .= "Judul: ".$data['judul']." <br>";
        $mail_content .= "Nama: ".$data['nama_karyawan']." <br>";
        $mail_content .= "NIK: ".$data['nik']." <br>";
        $mail_content .= "Divisi: ".$data['divisi']." <br>";
        $mail_content .= "Tahun: ".$data['tahun']." <br>";
        $mail_content .= "Jabatan: ".$data['jabatan']." <br>";
        $mail_content .= "Masa Kerja: ".$data['masa_kerja']." <br>";
        $mail_content .= 'Total Score: '.$data['total_score'].' <i class="fa fa-star"><br><br>';
        $mail_content .= 'Total Score dari Atasan: '.$data['total_score_atasan'].' <i class="fa fa-star"></i><br><br>';
        $mail_content .= 'Total Nilai: '.$data['total_nilai'].' <i class="fa fa-star"></i><br><br>';
        $mail_content .= 'Total Nilai dari Atasan: '.$data['total_nilai_atasan'].' <i class="fa fa-star"></i><br><br>';
        $mail_content .= "Thanks & regards<br>";
        $mail_content .= "<img src='http://hris.int.padmatirtagroup.com/absensi/images/apple-touch-icon.png'/><br>";
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
?>