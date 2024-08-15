<?php 
session_start();ob_start();
include_once('../../lib/config.php');
include('../../lib/db_new_nva.php');


$act = $_POST['act'];
if($act=="save_user"){
}
else if($act=="edit_user"){

}
else if($act=="change_password"){
    $scrambler="PadmaTiRt4";
	$user_password = md5($scrambler . md5($_POST['pwd']) . $scrambler);
	$edit_pwd_user =  DB::connection('mysql_hris')->query("update `user` set password = '".$user_password."' where id_user = '".$_POST['id_user']."'");
}
else if($act=="non_aktif"){
	$nonaktif_user = DB::connection('mysql_hris')->query("update `user` set user_status = 'Non Active' where id_user = '".$_POST['id_user']."'");
	//insert to stream
	$insert_stream = DB::connection('mysql_hris')->query("insert into tbl_act_stream values (0,'Non Aktif User','".addslashes($_SESSION['nama'])." Non Aktifkan User ".addslashes($_POST['nama_user'])."',now(),'".$_SESSION['id_user']."')");
}
?>