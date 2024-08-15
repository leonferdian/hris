<?php 
if(isset($_GET['page']))
{
	$webpages =  DB::connection('mysql_hris')->query("SELECT * FROM tbl_webpages WHERE web_page_case='".$_GET['page']."'");
	$dwebpages = $webpages->fetch_array();
	include "$dwebpages[webpage_include]";
	
}
else if(isset($_GET['mm'])){
	$main_menu =  DB::connection('mysql_hris')->query("SELECT * FROM tbl_mainmenu WHERE mainmenu_case='".$_GET['mm']."'");
	$dmain_menu = $main_menu->fetch_array();
	include "$dmain_menu[mainmenu_include]";
}
else if(isset($_GET['sm'])){
	$sub_menu =  DB::connection('mysql_hris')->query("SELECT * FROM tbl_submenu WHERE submenu_case='".$_GET['sm']."'");
	$dsubmenu = $sub_menu->fetch_array();
	include "$dsubmenu[submenu_include]";
}
?>