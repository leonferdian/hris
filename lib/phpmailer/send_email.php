<?php
require("class.PHPMailer.php");
require("class.pop3.php");

$mail = new PHPMailer();

$pop = new POP3();

$pop->Authorise('mail.padmatirtagroup.com', 110, 30, 'dashboard@padmatirtagroup.com', 'padma123', 1);

//$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "mail.padmatirtagroup.com";  // specify main and backup server
$mail->SMTPAuth = false;     // turn on SMTP authentication
$mail->Username = "dashboard@padmatirtagroup.com";  // SMTP username
$mail->Password = "padma123"; // SMTP password*/

$mail->From = "dashboard@padmatirtagroup.com";
$mail->FromName = "Padmatirta Dashboard System";
//$mail->AddAddress("nopitrianto.herdiawan@padmatirtagroup.com", "Herdiawan");
$mail->AddAddress("huddinmer@gmail.com");                  // name is optional
$mail->AddReplyTo("dashboard@padmatirtagroup.com", "Padmatirta Dashboard System");

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Here is the subject";
$mail_content = "This is the HTML message body <b>in bold!</b><br>";
$mail_content .= "second <b>in bold!</b><br>";
$mail_content .= "third <b>in bold!</b><br>";
$mail->Body    = $mail_content;
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}

echo "Message has been sent";
?>