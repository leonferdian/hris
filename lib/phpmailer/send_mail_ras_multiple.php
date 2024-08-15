<?php 

require("class.PHPMailer.php");
require("class.pop3.php");

/*$mail = new PHPMailer();

$pop = new POP3();

$pop->Authorise('mail.padmatirtagroup.com', 110, 30, 'dashboard@padmatirtagroup.com', 'padma123', 1);

//$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "mail.padmatirtagroup.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "dashboard@padmatirtagroup.com";  // SMTP username
$mail->Password = "padma123"; // SMTP password*/
/*
$mail->From = "dashboard@padmatirtagroup.com";
$mail->FromName = "Padmatirta Dashboard System";*/

//


include('../lib/db_connect.php');
$get_ras = mysqli_query($dbpdc,"SELECT DISTINCT `to`,`from`,`subject`,`date`,u.nama FROM tbl_requirement as r inner join user as u on r.from = u.id_user  WHERE read_req = 0 and `to` like '%,%'");
$to = '';
//$to2 = '';
$no=1;
while($dras = mysqli_fetch_array($get_ras)){
	
				${'mail'.$dras['to']} = new PHPMailer();
				${'pop'.$dras['to']} = new POP3();

				${'pop'.$dras['to']}->Authorise('mail.padmatirtagroup.com', 110, 30, 'dashboard@padmatirtagroup.com', 'padma123', 1);

				//$mail = new PHPMailer();

				${'mail'.$dras['to']}->IsSMTP();                                      // set mailer to use SMTP
				${'mail'.$dras['to']}->Host = "mail.padmatirtagroup.com";  // specify main and backup server
				${'mail'.$dras['to']}->SMTPAuth = true;     // turn on SMTP authentication
				${'mail'.$dras['to']}->Username = "dashboard@padmatirtagroup.com";  // SMTP username
				${'mail'.$dras['to']}->Password = "padma123"; // SMTP password*/

				${'mail'.$dras['to']}->From = "dashboard@padmatirtagroup.com";
				${'mail'.$dras['to']}->FromName = "Padmatirta Dashboard System";
				
				${'mail'.$dras['to']}->WordWrap = 50;                                 // set word wrap to 50 characters
				//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
				//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
				${'mail'.$dras['to']}->IsHTML(true);                                  // set email format to HTML
				${'mail'.$dras['to']}->Subject = "RAS Yang Perlu Direspon";
				${'mail_content'.$dras['to']} = "Dear ".$dras['to']."<br><br>";
				${'mail_content'.$dras['to']} .= "Anda Mendapatkan RAS Baru. Seperti Tabel Dibawah<br>";
				${'mail_content'.$dras['to']} .= "<table width='900' style='margin:0;border-collapse:collapse;background:#ecf3eb'>";
				${'mail_content'.$dras['to']} .="<tr>
							<th width='30' style='display:none;border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>No</th>
							<th style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>From</th>
							<th width='170' style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>Subject</th>
							<th width='150' style='border:1px solid #999;padding:5px;background:#e81849;color:#f6f3f4'>Tanggal</th>
							</tr>";
				${'mail_content'.$dras['to']} .="<tr>
					<td align='center' style='display:none;border:1px solid #999;padding:5px;'>".$no."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dras['nama']."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dras['subject']."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dras['date']."</td>
					</tr>";
	
	
		//if (strpos($dras['to'], ',') !== false) {

		//$mail_content ="";
		$to = $dras['to'];
		$tag = explode(',' , $to);
		$hitung = count($tag);
		for($i=0;$i<=$hitung-1;$i++){
			//echo "'".$tag[$i]."'"."<br>";
			$get_user = mysqli_query($dbpdc,"SELECT * FROM user WHERE nama = '".$tag[$i]."'");
			while($duser = mysqli_fetch_array($get_user)){
				//echo "To ".$duser['nama']."<br>";
				
				
				
				/*$send_ras = mysqli_query($dbpdc,"SELECT * FROM tbl_requirement as r inner join user as u on r.from = u.id_user WHERE r.to like '%".$duser['nama']."%' and r.read_req = 0 order by date asc");
				$no=1;
				while($dsend_ras = mysqli_fetch_array($send_ras)){
					//echo "isi pesan : ".$dsend_ras['subject']." - ".$dsend_ras['id_requirement']."<br>";
					${'mail_content'.$dras['to']} .="<tr>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$no."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['nama']."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['subject']."</td>
					<td align='center' style='border:1px solid #999;padding:5px;'>".$dsend_ras['date']."</td>
					</tr>";
				$no++;}*/
				${'mail_content'.$dras['to']} .= "</table><br/>";
				${'mail'.$dras['to']}->AddAddress($duser['username']);
				${'mail'.$dras['to']}->AddReplyTo("dashboard@padmatirtagroup.com", "Padmatirta Dashboard System");
				
				
				
			}
		}
		${'mail'.$dras['to']}->Body    = ${'mail_content'.$dras['to']};
		if(!${'mail'.$dras['to']}->Send())
				{
				   echo "Message could not be sent. <p>";
				   echo "Mailer Error: " . ${'mail'.$dras['to']}->ErrorInfo;
				   exit;
				}else{echo "Message has been sent";${'mail_content'.$dras['to']}='';}
		
				
		//echo $to."<br>";
	
	

	//$get_user = mysqli_query($dbpdc,"SELECT * FROM tbl_requirement WHERE read_req = 0")
$no++;}
//$mail->Send();
//echo $mail_content;
/*if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}else{echo "Message has been sent";}*/
?>