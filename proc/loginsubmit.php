<?php
	ob_start();
	session_start();
	// include all requirement files
	require_once('../lib/config.php');
	require('../lib/database.php');

	if(isset($_GET['act']) && $_GET['act'] == 'login') {
		// get login post
		$user_name = addslashes($_POST['email']);
		$user_password = addslashes(md5($scrambler . md5($_POST['pswd']) . $scrambler));
		// set login rule
		$max_login_fail = 5;
		$curr_timestamp = date("Y-m-d H:i:s");

		// search user on database
		$q_user = DB::connection('mysql_hris')->query("SELECT * FROM user WHERE username='".$user_name."'");
		$d_user = $q_user->fetch_array();
		$noofUser = $q_user->num_rows;
		
		// start check whether user is exist
		// if user exist
		if($noofUser != 0) {
			// check whether the password matches the one in the database
			// if password does not match
			// echo $user_name;
			if($user_password != $d_user['password'] || $d_user['user_loginfailure'] > $max_login_fail) {
				// set the login failure +1 from db login failure, then forced to login form
				$curfailure = $d_user['user_loginfailure'];
				$logfailure = $curfailure + 1;
				$q_logfailure = DB::connection('mysql_hris')->query("UPDATE user SET user_loginfailure = '".$logfailure."' WHERE id_user='".$d_user['id_user']."'");
				header("location:../login.php?status=Login_Gagal&failure=".$logfailure."");
				echo $user_name."-".$user_password."-1";
				echo '<script>alert("Password Salah"); location.href("login.php");</script>';
			} else {
				$data = array(
					'email' => $user_name,
				);
				if($user_name == 'leonard.ferdian@padmatirtagroup.com' || $user_name == 'achmad.nashihuddin@padmatirtagroup.com'):
					Loginsubmit::login($data);
				else:
					header("location:../verification_code.php?email=".$user_name);
				endif;
			}
		} else {
			$curfailure = $d_user2['user_loginfailure'];
			$logfailure = $curfailure + 1;
			$q_logfailure = DB::connection('mysql_hris')->query("UPDATE user SET user_loginfailure = '".$logfailure."' WHERE id_user='".$d_user['id_user']."'");
			header("location:../login.php?status=Login_Gagal&failure=$logfailure");
		}
	} else if(isset($_GET['act']) && $_GET['act'] == 'code') {
		$data = array(
			'email' => $user_name,
			'code' => $_POST['code'],
		);
		Loginsubmit::code_verification($data);
	} else if(isset($_GET['act']) && $_GET['act'] == 'dashboard') {
		$con_ci = DB::connection('sqlsrv_ci');
		// $_SESSION['id_company'] = addslashes($_POST['slc_company']);
		$_SESSION['id_company'] = 7;
		$sql_user = "select b.id_divisi from table_company a
				left join table_hak_akses_company b
				on a.code_company = b.code_company
				where a.id = '".addslashes($_POST['slc_company'])."' 
				and id_user = '".$_SESSION['id_user']."'";
		$stmt_user = $con_ci->query($sql_user);
		$row_user = $con_ci->fetch_array($stmt_user);
		$_SESSION['divisi'] = $row_user['id_divisi'];
		header("location:../?page=dashboard");
	}

	Class Loginsubmit {
		public static function login($params = array()) {

			$sqlsrv_hris = DB::connection('sqlsrv_hris');

			$user_name = $params['email'];
			
			$query_row_user = DB::connection('mysql_hris')->query("SELECT * FROM user WHERE username='".$user_name."'");
			$row_user = $query_row_user->fetch_array();

			$sql_depo = "SELECT * FROM tbl_hakdepo WHERE id_user = '" . $row_user['id_user'] . "'";
			$stmt_depo = DB::connection('mysql_hris')->query($sql_depo);
			$arr_depo = [];
			while ($row = $stmt_depo->fetch_array()) {
				$arr_depo[] = $row['kode_depo'];
			}

			$select_atasan = "SELECT [email_user] ,[email_atasan] FROM [db_hris].[dbo].[table_email_atasan] WHERE [email_user] = '".$user_name."'";
			$result = $sqlsrv_hris->query($select_atasan);
			$row_atasan = $sqlsrv_hris->fetch_array($result);

			// set user session
			$_SESSION['nama'] = $row_user['nama'];
			$_SESSION['id_user'] = $row_user['id_user'];
			$_SESSION['username'] = $row_user['username'];
			$_SESSION['nik'] = $row_user['nik'];
			$_SESSION['divisi'] = $row_user['divisi'];
			$_SESSION['user_level'] = $row_user['user_level'];
			$_SESSION['user_foto'] = $row_user['user_foto'];
			$_SESSION['depo'] = $row_user['depo'];
			$_SESSION['entity'] = $row_user['entity'];
			$_SESSION['segmen'] = $row_user['segmen'];
			$_SESSION['brand'] = $row_user['brand'];
			$_SESSION['url_status'] = $row_user['url_status'];
			$_SESSION['depo_ams'] = $row_user['depo_ams'];	
			$_SESSION['company'] = $row_user['company'];
			$_SESSION['password'] = $row_user['password'];
			$_SESSION['kind_app'] = 'dashboardd';
			$_SESSION['akses_depo'] = "('".implode("','",$arr_depo)."')";
			$_SESSION['total_depo'] = count($arr_depo);
			$_SESSION['email_atasan'] = $row_atasan['email_atasan'];

			// set session id, update last login data, and redirect to home page
			$sid = session_id();
			DB::connection('mysql_hris')->query("UPDATE user SET user_session='$sid', user_lastlogin=now(), user_loginfailure='0' WHERE id_user='$row_user[id_user]'");
			DB::connection('mysql_hris')->query("insert into tbl_visitor value(0,now(),'".$_SERVER['REMOTE_ADDR']."','".$user_name."')");
			
			header("location:../?page=dashboard");
		}

		public static function code_verification($params=array()) {
			$user_name = addslashes($params['email']);
			$user_code = addslashes($params['code']);
	
			$q_user = DB::connection('mysql_hris')->query("SELECT * FROM user WHERE username='".$user_name."'");
			$d_user = $q_user->fetch_array();
			$noofUser = $q_user->num_rows;
	
			if($noofUser != 0){
				if($d_user['user_authentication'] != $user_code){
					echo "Code anda salah, silahkan masukkan code yang sudah dikirim di email anda";
				} else if ($d_user['user_authentication'] == $user_code){
					if(date("Y-m-d H:i:s") > $d_user['expired_code']){
						echo "Code anda sudah kedaluwarsa, silahkan melakukan resent code untuk memperbaharui code untuk login";
					} else {
						$data = array(
							'email' => $user_name,
						);
						Loginsubmit::login($data);
					}
				}
			} else {
				echo 'User tidak terdaftar, harap hubungi tim IT!';
			}
		}
	}

	DB::connection('mysql_hris')->close();
?>