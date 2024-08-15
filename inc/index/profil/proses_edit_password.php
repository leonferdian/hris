<?php
	session_start();
	include_once('../../../lib/config.php');
	include('../../lib/db_nirvana.php');

	$user_password_new = addslashes(md5($scrambler . md5($_POST['pswd_new']) . $scrambler));

	

	$get_old_pass = mysqli_query($dbnva, "SELECT * FROM user WHERE id_user = '".$_POST['id_user']."' ");
	$row_old_pass = mysqli_fetch_array($get_old_pass);

	if($_POST['pswd_new'] == $_POST['re_pswd_new']){
		$save_edit_pass = mysqli_query($dbnva, "UPDATE user SET password = '".$user_password_new."' WHERE id_user = '".$_POST['id_user']."' ");
		if($save_edit_pass){echo "Data Tersimpan";}
	}else{
		echo "Re-Entry Password Harus sama";
	}
	
	
?>