<?php 

require("class.PHPMailer.php");
require("class.pop3.php");

$mail = new PHPMailer();

$pop = new POP3();

$pop->Authorise('mail.padmatirtagroup.com', 110, 30, 'dashboard@padmatirtagroup.com', 'padma123', 1);

//$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "mail.padmatirtagroup.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "dashboard@padmatirtagroup.com";  // SMTP username
$mail->Password = "padma123"; // SMTP password*/

$mail->From = "dashboard@padmatirtagroup.com";
$mail->FromName = "Padmatirta Dashboard System";

include('../lib/db_connect.php');
$get_ras = mysqli_query($dbpdc,"SELECT DISTINCT `to` FROM tbl_requirement WHERE read_req = 0");
$to = '';
//$to2 = '';

while($dras = mysqli_fetch_array($get_ras)){
	
	if (strpos($dras['to'], ',') !== false) {
		$mail_content ="";
		$to = $dras['to'];
		$tag = explode(',' , $to);
		$hitung = count($tag);
		for($i=0;$i<=$hitung-1;$i++){
			//echo "'".$tag[$i]."'"."<br>";
			$get_user = mysqli_query($dbpdc,"SELECT * FROM user WHERE nama = '".$tag[$i]."'");
			while($duser = mysqli_fetch_array($get_user)){
				//echo "To ".$duser['nama']."<br>";
				
				
				$mail->WordWrap = 50;                                 // set word wrap to 50 characters
				//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
				//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
				$mail->IsHTML(true);                                  // set email format to HTML
				$mail->Subject = "RAS Yang Perlu Direspon";
				
				$mail_content .= "Dear ".$duser['nama']."<br><br>";
				$mail_content .= "Anda Mendapatkan RAS Baru. Seperti Tabel Dibawah<br>";
				$mail_content .= "<table width='900' style='margin:0;border-collapse:collapse;background:#ecf3eb'>";
				$mail_content .="<tr>
							<th width='30' style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>No</th>
							<th style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>From</th>
							<th width='170' style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>Subject</th>
							<th width='150' style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>Tanggal</th>
							</tr>";
				$send_ras = mysqli_query($dbpdc,"SELECT * FROM tbl_requirement as r inner join user as u on r.from = u.id_user WHERE r.to like '%".$duser['nama']."%' and r.read_req = 0 order by date asc");
				$no=1;
				while($dsend_ras = mysqli_fetch_array($send_ras)){
					//echo "isi pesan : ".$dsend_ras['subject']." - ".$dsend_ras['id_requirement']."<br>";
					$mail_content .="<tr>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$no."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['nama']."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['subject']."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['date']."</td>
					</tr>";
				$no++;}
				$mail_content .= "</table><br/>";
				$mail->AddAddress($duser['username']);
				$mail->AddReplyTo("dashboard@padmatirtagroup.com", "Padmatirta Dashboard System");
				
			}
		}
		$mail->Body    = $mail_content;
				if(!$mail->Send())
				{
				   echo "Message could not be sent. <p>";
				   echo "Mailer Error: " . $mail->ErrorInfo;
				   exit;
				}else{echo "Message has been sent";}
		//echo $to."<br>";
	}else{
		$mail_content2 ="";
		$to = "'".$dras['to']."'";
		$get_user = mysqli_query($dbpdc,"SELECT * FROM user WHERE nama = '".$dras['to']."'");
		while($duser = mysqli_fetch_array($get_user)){
			//echo "To ".$duser['nama']."<br>";
				
				$mail->WordWrap = 50;                                 // set word wrap to 50 characters
				//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
				//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
				$mail->IsHTML(true);                                  // set email format to HTML
				$mail->Subject = "RAS Yang Perlu Direspon";
				
			    $mail_content2 .= "Dear ".$duser['nama']."<br><br>";
				$mail_content2 .= "RAS Yang Perlu Anda Respon. Seperti Tabel Dibawah<br>";
				$mail_content2 .= "<table width='900' style='margin:0;border-collapse:collapse;background:#ecf3eb'>";
				$mail_content2 .="<tr>
							<th width='30' style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>No</th>
							<th style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>From</th>
							<th width='170' style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>Subject</th>
							<th width='150' style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>Tanggal</th>
							</tr>";
			$send_ras = mysqli_query($dbpdc,"SELECT * FROM tbl_requirement as r inner join user as u on r.from = u.id_user WHERE r.to like '%".$duser['nama']."%' and r.read_req = 0 order by date asc");
			$no=1;
			while($dsend_ras = mysqli_fetch_array($send_ras)){
				//echo "isi pesan : ".$dsend_ras['subject']." - ".$dsend_ras['id_requirement']."<br>";
				$mail_content2 .="<tr>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$no."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['nama']."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['subject']."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['date']."</td>
					</tr>";
				$no++;}
				$mail_content2 .= "</table><br/>";
				$mail->AddAddress($duser['username']);
				$mail->AddReplyTo("dashboard@padmatirtagroup.com", "Padmatirta Dashboard System");
				
			
		}
		//echo $to."<br>";
		$mail->Body    = $mail_content2;
			if(!$mail->Send())
			{
			   echo "Message could not be sent. <p>";
			   echo "Mailer Error: " . $mail->ErrorInfo;
			   exit;
			}else{echo "Message has been sent";}
	}
	

	//$get_user = mysqli_query($dbpdc,"SELECT * FROM tbl_requirement WHERE read_req = 0")
}
//$mail->Send();
//echo $mail_content;
/*if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}else{echo "Message has been sent";}*/
?>