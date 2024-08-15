<?php 
require('../../lib/database.php');

$id = isset($_GET['id']) ? $_GET['id'] : '';
$jenis_id = isset($_GET['jenis_id']) ? $_GET['jenis_id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';

if (count($_GET)) {
	execute($act, $id, $jenis_id);
}

function execute($act, $id, $jenis_id) {
	$display = isset($_POST['display']) ? $_POST['display'] : '';
	$type = isset($_POST['type']) ? $_POST['type'] : '';
	$id_webpage = isset($_POST['webpage_parent']) ? $_POST['webpage_parent'] : '';
	$id_mainmenu = isset($_POST['mainmenu_parent']) ? $_POST['mainmenu_parent'] : '';
	$link = isset($_POST['link']) ? $_POST['link'] : '';
	$include = isset($_POST['include']) ? $_POST['include'] : '';
	$order = isset($_POST['order']) ? $_POST['order'] : '';
	$icon = isset($_POST['icon']) ? $_POST['icon'] : '';
	$case = isset($_POST['case']) ? $_POST['case'] : '';
	$access = isset($_POST['access']) ? $_POST['access'] : 0;

	if ($jenis_id == "id_webpages" || $type == "webpage") {
		$table = 'tbl_webpages';
		$data = [
			'webpage_display' => $display,
			'webpage_link' => $link,
			'webpage_include' => $include,
			'web_page_order' => $order,
			'webpage_acces' => $access,
			'webpage_icon' => $icon,
			'web_page_case' => $case
		];
	}
	elseif ($jenis_id == "idmain_menu" || $type == "mainmenu") {
		$table = 'tbl_mainmenu';
		$data = [
			'id_webpage' => $id_webpage,
			'mainmenu_display' => $display,
			'mainmenu_link' => $link,
			'mainmenu_include' => $include,
			'mainmenu_order' => $order,
			'mainmenu_acces' => $access,
			'mainmenu_case' => $case
		];
	}
	else {
		$table = 'tbl_submenu';
		$data = [
			'id_mainmenu' => $id_mainmenu,
			'submenu_display' => $display,
			'submenu_link' => $link,
			'submenu_include' => $include,
			'submenu_order' => $order,
			'submenu_access' => $access,
			'submenu_case' => $case
		];
	}

	$sql = "";

	if (!empty($id)) {
		if ($act=="edit") {
			$arUpdate = array();
			foreach($data as $k=>$v) $arUpdate[] = " $k='$v'";
			$data_update = implode(',', $arUpdate);
			$sql = "UPDATE $table SET $data_update WHERE $jenis_id = $id LIMIT 1";
		} elseif ($act=="del") {
			$sql ="DELETE FROM $table WHERE $jenis_id = $id";
		}
	} else {
		$sql ="INSERT INTO $table (".implode(',', array_keys($data)).") VALUES ('".implode("','", array_values($data))."')";
	}

	#echo $sql."<br>";

	DB::connection('mysql_hris')->query($sql) or die(DB::connection('mysql_hris')->error);
}
?>