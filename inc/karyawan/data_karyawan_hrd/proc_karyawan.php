<?php
include('../../../lib/database.php');
ob_start();
session_start();
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if ($_POST['act'] == "save_karyawan"):

	$update_fields = [];
	$update_fields2 = [];
	// Iterate over the $_POST['save'] array
	foreach ($_POST as $key => $value) {
		if ($key == 'tgl_akhir_pkwt' || $key == 'tgl_lahir' || $key == 'tgl_awal_kerja' || $key == 'tgl_akhir_kerja' || $key == 'tgl_lahir_anak1' || $key == 'tgl_lahir_anak2' || $key == 'tgl_lahir_anak3' || $key == 'tgl_lahir_pasangan') {
			$update_fields[] = "[$key] = '" . date("Y-m-d", strtotime($value)) . "'";
		} else {
			if ($key != "act" && $key != "status_data" && $key != "alasan_reject_hrd"):
				$update_fields[] = "[$key] = '" . trim($value) . "'";
			endif;
		}
	}

	// Add the common fields like update timestamp, user, status, etc.
	$update_fields[] = "[tgl_update] = getdate()";
	$update_fields[] = "[user_update] = '" . trim($_SESSION['username']) . "'";
	$update_fields2[] = "[status_data] = '" . trim($_POST['status_data']) . "'";
	$update_fields2[] = "[alasan_reject_hrd] = '" . trim($_POST['alasan_reject_hrd']) . "'";

	// Build the final SQL query
	$sql = "UPDATE [db_hris].[dbo].[table_karyawan] SET 
						" . implode(", ", $update_fields) . "
					WHERE nik = '" . trim($_POST['nik']) . "'";

	$stmt = $sqlsrv_hris->query($sql);
	if ($stmt):
		$sql2 = "UPDATE [db_hris].[dbo].[table_karyawan_draft] SET 
						" . implode(", ", $update_fields2) . "
					WHERE nik = '" . trim($_POST['nik']) . "'";
		$stmt2 = $sqlsrv_hris->query($sql2);

		if ($stmt):
			echo "Data Saved";
		else:
			echo "Error save data karyawan `$sql2`";
		endif;
	else:
		echo "Error save data karyawan `$sql`";
	endif;
elseif ($_POST['act'] == "save_foto"):
	$nama_foto = "";
	// for($x = 0; $x < $_POST['total_foto']; $x++){
	if (isset($_FILES['file_foto']['name'])) {
		$nama_foto = $_POST['nama_foto'] . "." . pathinfo($_FILES['file_foto']['name'], PATHINFO_EXTENSION);
		move_uploaded_file($_FILES['file_foto']['tmp_name'], "../../../image_upload/" . $_POST['jenis'] . "/" . $_POST['nama_foto'] . "." . pathinfo($_FILES['file_foto']['name'], PATHINFO_EXTENSION));
	}
	// }

	if ($_POST['total_foto'] > 0):
		$sql_cek = "select " . $_POST['jenis'] . " from [db_hris].[dbo].[table_karyawan_foto] where nik = '" . trim($_POST['nik']) . "'";
		$stmt_cek = $sqlsrv_hris->query($sql_cek);
		$num_row = $sqlsrv_hris->num_rows($stmt_cek);

		$sql = "";

		if ($num_row > 0):
			$sql = "update [db_hris].[dbo].[table_karyawan_foto] set " . $_POST['jenis'] . " = '" . trim($nama_foto) . "' where nik = '" . trim($_POST['nik']) . "'";
		else:
			$sql = "insert into [db_hris].[dbo].[table_karyawan_foto] 
				(
					[nik]
					," . $_POST['jenis'] . "
				) 
				values 
				(
					'" . trim($_POST['nik']) . "'
					,'" . trim($nama_foto) . "'
				)";
		endif;

		$stmt = $sqlsrv_hris->query($sql);

		if ($stmt):
			echo "Foto berhasil diupload";
		else:
			"error: foto gagal diupload `$sql`";
		endif;
	endif;
endif;