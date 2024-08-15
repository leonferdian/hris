<?php
include('../../lib/database.php');
if($_POST['act']=='view_webpage'){
	if($_POST['id_webpage'] == 0){
		$del_webpages = DB::connection('mysql_hris')->query("delete from tbl_hakmenu_webpage where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_webpage = DB::connection('mysql_hris')->query("select * from tbl_webpages where id_webpages not in (SELECT id_webpage from tbl_hakmenu_webpage where id_webpage in (".$_POST['id_webpage'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_webpage = $cek_webpage->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_webpages = DB::connection('mysql_hris')->query("delete from tbl_hakmenu_webpage where id_webpage ='".$dcek_webpage['id_webpages']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_webpage']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_webpage where id_webpage ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek==0){
				$insert = DB::connection('mysql_hris')->query("insert into tbl_hakmenu_webpage values(0,'".$_POST['id_user']."','".$tag[$i]."','0','0','0')");				
			}
		}
	}
}
else if($_POST['act']=='add_webpage'){ //do nothing
	if($_POST['id_webpage'] == 0){
		$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `add`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_webpage = DB::connection('mysql_hris')->query("select * from tbl_webpages where id_webpages not in (SELECT id_webpage from tbl_hakmenu_webpage where id_webpage in (".$_POST['id_webpage'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_webpage = $cek_webpage->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `add`=0 where id_webpage ='".$dcek_webpage['id_webpages']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_webpage']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_webpage where id_webpage ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `add`=1 where id_webpage ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}
else if($_POST['act']=='edit_webpage'){ // do nothing
	if($_POST['id_webpage'] == 0){
		$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `update`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_webpage = DB::connection('mysql_hris')->query("select * from tbl_webpages where id_webpages not in (SELECT id_webpage from tbl_hakmenu_webpage where id_webpage in (".$_POST['id_webpage'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_webpage = $cek_webpage->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `update`=0 where id_webpage ='".$dcek_webpage['id_webpages']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_webpage']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_webpage where id_webpage ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `update`=1 where id_webpage ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}
else if($_POST['act']=='del_webpage'){ // do nothing
	if($_POST['id_webpage'] == 0){
		$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `delete`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_webpage = DB::connection('mysql_hris')->query("select * from tbl_webpages where id_webpages not in (SELECT id_webpage from tbl_hakmenu_webpage where id_webpage in (".$_POST['id_webpage'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_webpage = $cek_webpage->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `delete`=0 where id_webpage ='".$dcek_webpage['id_webpages']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_webpage']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_webpage where id_webpage ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_webpage set `delete`=1 where id_webpage ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}
//mainmenu
else if($_POST['act']=='view_mainmenu'){
	if($_POST['id_mainmenu']==0){
		$del_mainmenu = DB::connection('mysql_hris')->query("delete from tbl_hakmenu_mainmenu where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_mainmenu = DB::connection('mysql_hris')->query("select * from tbl_mainmenu where idmain_menu not in (SELECT id_mainmenu from tbl_hakmenu_mainmenu where id_mainmenu in (".$_POST['id_mainmenu'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_mainmenu = $cek_mainmenu->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_webpages = DB::connection('mysql_hris')->query("delete from tbl_hakmenu_mainmenu where id_mainmenu ='".$dcek_mainmenu['idmain_menu']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_mainmenu']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_mainmenu where id_mainmenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek==0){
				$insert = DB::connection('mysql_hris')->query("insert into tbl_hakmenu_mainmenu values(0,'".$_POST['id_user']."','".$tag[$i]."','0','0','0')");
			}
		}
	}
}
else if($_POST['act']=='add_mainmenu'){ // do nothing
	if($_POST['id_mainmenu']==0){
		$del_mainmenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `add`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_mainmenu = DB::connection('mysql_hris')->query("select * from tbl_mainmenu where idmain_menu not in (SELECT id_mainmenu from tbl_hakmenu_mainmenu where id_mainmenu in (".$_POST['id_mainmenu'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_mainmenu = $cek_mainmenu->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `add`=0 where id_mainmenu ='".$dcek_mainmenu['idmain_menu']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_mainmenu']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_mainmenu where id_mainmenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `add`=1 where id_mainmenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}
else if($_POST['act']=='update_mainmenu'){ // do nothing
	if($_POST['id_mainmenu']==0){
		$del_mainmenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `update`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_mainmenu = DB::connection('mysql_hris')->query("select * from tbl_mainmenu where idmain_menu not in (SELECT id_mainmenu from tbl_hakmenu_mainmenu where id_mainmenu in (".$_POST['id_mainmenu'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_mainmenu = $cek_mainmenu->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `update`=0 where id_mainmenu ='".$dcek_mainmenu['idmain_menu']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_mainmenu']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_mainmenu where id_mainmenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `update`=1 where id_mainmenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}
else if($_POST['act']=='del_mainmenu'){ // do nothing
	if($_POST['id_mainmenu']==0){
		$del_mainmenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `delete`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_mainmenu = DB::connection('mysql_hris')->query("select * from tbl_mainmenu where idmain_menu not in (SELECT id_mainmenu from tbl_hakmenu_mainmenu where id_mainmenu in (".$_POST['id_mainmenu'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_mainmenu = $cek_mainmenu->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_webpages = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `delete`=0 where id_mainmenu ='".$dcek_mainmenu['idmain_menu']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_mainmenu']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_mainmenu where id_mainmenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_mainmenu set `delete`=1 where id_mainmenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}
//submenu
else if($_POST['act']=='view_submenu'){
	if($_POST['id_submenu']==0){
		$del_submenu = DB::connection('mysql_hris')->query("delete from tbl_hakmenu_submenu where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_submenu = DB::connection('mysql_hris')->query("select * from tbl_submenu where id_submenu not in (SELECT id_submenu from tbl_hakmenu_submenu where id_submenu in (".$_POST['id_submenu'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_submenu = $cek_submenu->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_submenu = DB::connection('mysql_hris')->query("delete from tbl_hakmenu_submenu where id_submenu ='".$dcek_submenu['id_submenu']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_submenu']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_submenu where id_submenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek==0){
				$insert = DB::connection('mysql_hris')->query("insert into tbl_hakmenu_submenu values(0,'".$_POST['id_user']."','".$tag[$i]."','0','0','0')");
			}
		}
	}
}
else if($_POST['act']=='add_submenu'){ // do nothing
	if($_POST['id_submenu']==0){
		$del_submenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `add`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_submenu = DB::connection('mysql_hris')->query("select * from tbl_submenu where id_submenu not in (SELECT id_submenu from tbl_hakmenu_submenu where id_submenu in (".$_POST['id_submenu'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_submenu = $cek_submenu->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_submenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `add`=0 where id_submenu ='".$dcek_submenu['id_submenu']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_submenu']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_submenu where id_submenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `add`=1 where id_submenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}
else if($_POST['act']=='update_submenu'){ // do nothing
	if($_POST['id_submenu']==0){
		$del_submenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `update`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_submenu = DB::connection('mysql_hris')->query("select * from tbl_submenu where id_submenu not in (SELECT id_submenu from tbl_hakmenu_submenu where id_submenu in (".$_POST['id_submenu'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_submenu = $cek_submenu->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_submenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `update`=0 where id_submenu ='".$dcek_submenu['id_submenu']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_submenu']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_submenu where id_submenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `update`=1 where id_submenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}
else if($_POST['act']=='del_submenu'){ // do nothing
	if($_POST['id_submenu']==0){
		$del_submenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `delete`=0 where id_user = '".$_POST['id_user']."'");
	}
	else{
		$cek_submenu = DB::connection('mysql_hris')->query("select * from tbl_submenu where id_submenu not in (SELECT id_submenu from tbl_hakmenu_submenu where id_submenu in (".$_POST['id_submenu'].") and id_user = '".$_POST['id_user']."')");
		while($dcek_submenu = $cek_submenu->fetch_array()){
			//echo $dcek_webpage['id_webpages']."<br>";
			$del_submenu = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `delete`=0 where id_submenu ='".$dcek_submenu['id_submenu']."' and id_user = '".$_POST['id_user']."'");
		}
		$tag = explode(',' , $_POST['id_submenu']);
		$hitung = count($tag);
		//echo $hitung;
		for($i=0;$i<=$hitung-1;$i++){
			
			$cek_id = DB::connection('mysql_hris')->query("select * from tbl_hakmenu_submenu where id_submenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			$num_cek = $cek_id->num_rows;
			if($num_cek!=0){
				$insert = DB::connection('mysql_hris')->query("update tbl_hakmenu_submenu set `delete`=1 where id_submenu ='".$tag[$i]."' and id_user = '".$_POST['id_user']."'");
			}
		}
	}
}

?>
<?php mysqli_close($dbit_dashboard); ?>