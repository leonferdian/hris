<?php 
	session_start();
	ob_start();
	include_once('../../lib/config.php');
	include('../../lib/database.php');
	$sqlsrv_hris = DB::connection('sqlsrv_hris');

	$act = $_POST['act'];
	if($act=="save_user"):
		define('ARR_DEPO', $_POST['depo']);
		define('ARR_BRAND', $_POST['brand']);
		$user_password = md5($scrambler . md5($_POST['pwd']) . $scrambler);
		$save_user = DB::connection('mysql_hris')->query("insert into user values(0,'".$_POST['username']."','".$user_password."','".$_POST['emp_name']."','".$_POST['divisi']."','Active','".$_POST['user_level']."'
		,0,now(),'','','".$_POST['nik']."','".ARR_DEPO."','".$_POST['entity']."','".$_POST['segmen']."','".ARR_BRAND."',null,now(),'".$_SESSION['nama']."','".$_POST['leader']."',now(),'".$_SESSION['company']."',".$_POST['levelling']."
		,'".$_POST['url']."',null,null)");

		$id_user = DB::connection('mysql_hris')->insert_id;

		if ($save_user):
			$depo = explode(",",ARR_DEPO);
			save_akses_depo($depo, $id_user);

			//insert Detail User
			$det_user = DB::connection('mysql_hris')->query("insert into tbl_user_detail values (0,'".$_POST['username']."','','".$_POST['divisi']."','','')");
			//insert to stream
			$insert_stream = DB::connection('mysql_hris')->query("insert into tbl_act_stream values (0,'Add User','".addslashes($_SESSION['nama'])." Add User ".addslashes($_POST['emp_name'])."',now(),'".$_SESSION['id_user']."')");

			$email_atasan = $_POST['email_atasan'];
			$update_atasan = "INSERT INTO [db_hris].[dbo].[table_email_atasan] ([email_user] ,[nik] ,[email_atasan],[nama_atasan]) VALUES ('".$_POST['username']."','".$_POST['nik']."','".$email_atasan."','".$_POST['leader']."')";
			$sqlsrv_hris->query($update_atasan);
			echo "Data Saved";
		endif;
		
	elseif($act=="edit_user"):
		define('ARR_DEPO', $_POST['depo']);
		define('ARR_BRAND', $_POST['brand']);
		$user_password = "";
		if($_POST['password'] == "") {
			$user_password = "" ;
		} else {
			$aa = md5($scrambler . md5($_POST['password']) . $scrambler);
			$user_password = " ,password = '".$aa."' ";
		}

		//echo "string".$user_password;
		$query_edit = "update `user` set 
		username = '".$_POST['username']."'
		".$user_password."
		,nama = '".$_POST['emp_name']."'
		,divisi = '".$_POST['divisi']."'
		,user_level = '".$_POST['user_level']."'
		,nik = '".$_POST['nik']."'
		,leader = '".$_POST['leader']."'
		,date_modified = now()
		,depo='".ARR_DEPO."'
		,entity = '".$_POST['entity']."'
		,segmen = '".$_POST['segmen']."'
		,brand = '".ARR_BRAND."'
		where id_user = '".$_POST['id_user']."'";
		$edit_user = DB::connection('mysql_hris')->query($query_edit);
		if ($edit_user > 0):
			$email_atasan = $_POST['email_atasan'];
			$cek_atasan = $sqlsrv_hris->query("SELECT * FROM [db_hris].[dbo].[table_email_atasan] WHERE [email_user] = '".$_POST['username']."'");
			$num_cek = $sqlsrv_hris->num_rows($cek_atasan);

			if ($num_cek > 0):
				$sqlsrv_hris->query("UPDATE [db_hris].[dbo].[table_email_atasan] SET [email_atasan] = '".$email_atasan."',[nik] = '".$_POST['nik']."' ,[nama_atasan] = '".$_POST['leader']."' WHERE [email_user] = '".$_POST['username']."'");
			else:
				$sqlsrv_hris->query("INSERT INTO [db_hris].[dbo].[table_email_atasan] ([email_user] ,[nik] ,[email_atasan]) VALUES ('".$_POST['username']."','".$_POST['nik']."','".$email_atasan."')");
			endif;

			$depo = explode(",",ARR_DEPO);
			save_akses_depo($depo, $_POST['id_user']);

			echo "Data Saved";
		else:
			echo "error: `$query_edit`";
		endif;
	elseif($act=="edit_pwd"):
		$user_password = md5($scrambler . md5($_POST['pwd']) . $scrambler);
		$edit_pwd_user =  DB::connection('mysql_hris')->query("update `user` set password = '".$user_password."' where id_user = '".$_POST['id_user']."'");
	elseif($act=="non_aktif"):
		$nonaktif_user = DB::connection('mysql_hris')->query("update `user` set user_status = 'Non Active' where id_user = '".$_POST['id_user']."'");
		//insert to stream
		$insert_stream = DB::connection('mysql_hris')->query("insert into tbl_act_stream values (0,'Non Aktif User','".addslashes($_SESSION['nama'])." Non Aktifkan User ".addslashes($_POST['nama_user'])."',now(),'".$_SESSION['id_user']."')");
	endif;

	function save_akses_depo($list_depo, $id_user) {
		if(isset($list_depo) && $list_depo != ""):
			$arr_out_depo = [];
			$arr_in_depo = [];

			$cek_outdepo = DB::connection('mysql_hris')->query("select * from tbl_hakdepo where id_user = ".$id_user." and kode_depo not in ('".implode("','",$list_depo)."')");
			while ($out_depo = $cek_outdepo->fetch_array()) {
				$arr_out_depo[] = $out_depo['kode_depo'];
			}
			
			if (count($arr_out_depo) > 0):
				DB::connection('mysql_hris')->query("delete from tbl_hakdepo where id_user = ".$id_user." and kode_depo in ('".implode("','", $arr_out_depo)."')");
			endif;

			$total = count($list_depo);
			$count = 0;
			$cek_depo = DB::connection('mysql_hris')->query("select * from tbl_hakdepo where id_user = ".$id_user."");
			while ($indepo = $cek_depo->fetch_array()) {
				$arr_in_depo[] = $indepo['kode_depo'];
			}

			foreach($list_depo as $depo):
				if (!in_array($depo, $arr_in_depo)):
					$sql_hak_depo = "insert into tbl_hakdepo (id_user, kode_depo) values ('".$id_user."','".$depo."')";
					$stmt_hak_depo = DB::connection('mysql_hris')->query($sql_hak_depo);
					if ($stmt_hak_depo):
						if ($count == $total):
							echo "Data Saved";
						endif;
					else:
						echo "error `$sql_hak_depo`\r\n";
					endif;
				endif;
				$count++;
			endforeach;
		endif;
	}
?>
<?php DB::connection('mysql_hris')->close(); ?>