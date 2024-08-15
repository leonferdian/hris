<?php session_start();ob_start(); 
include('../lib/db_connect_task_management.php');?>

<?php
 $chat = mysqli_query($dbtask,"update tbl_user_chat set status='".$_POST['status']."' where id_user = '".$_SESSION['id_user']."'");		
 
?>


