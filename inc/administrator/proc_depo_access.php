<?php
include('../../lib/database.php');
if($_POST['act']=='save_changes'):
	define('ARR_DEPO', $_POST['list_depo']);
	if(isset($_POST['list_depo']) && $_POST['list_depo'] != ""):
		$arr_out_depo = [];
		$arr_in_depo = [];

		$cek_outdepo = DB::connection('mysql_hris')->query("select * from tbl_hakdepo where id_user = ".$_POST['id_user']." and kode_depo not in ('".implode("','",ARR_DEPO)."')");
		while ($out_depo = $cek_outdepo->fetch_array()) {
			$arr_out_depo[] = $out_depo['kode_depo'];
		}
		
		if (count($arr_out_depo) > 0):
			DB::connection('mysql_hris')->query("delete from tbl_hakdepo where id_user = ".$_POST['id_user']." and kode_depo in ('".implode("','", $arr_out_depo)."')");
		endif;

		$total = count($_POST['list_depo']);
		$count = 0;
		$cek_depo = DB::connection('mysql_hris')->query("select * from tbl_hakdepo where id_user = ".$_POST['id_user']."");
		while ($indepo = $cek_depo->fetch_array()) {
			$arr_in_depo[] = $indepo['kode_depo'];
		}

		foreach(ARR_DEPO as $depo):
			if (!in_array($depo, $arr_in_depo)):
				DB::connection('mysql_hris')->query("insert into tbl_hakdepo (id_user, kode_depo) values ('".$_POST['id_user']."','".$depo."')");
			endif;
			$count++;
			if ($count == $total):
				echo "Data Saved";
			endif;
		endforeach;
	endif;
endif;
?>
<?php DB::connection('mysql_hris')->close(); ?>