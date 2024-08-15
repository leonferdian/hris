<?php ob_start();session_start();
// include all requirement files
include('../lib/database.php'); 


$sql1="UPDATE user SET user_session = '' WHERE username='$_SESSION[username]'";
$q_sid = DB::connection('mysql_hris')->query($sql1); 

session_unset();
session_destroy();

header("location:../login.php");

DB::connection('mysql_hris')->close();