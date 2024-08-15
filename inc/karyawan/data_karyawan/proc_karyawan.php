<?php
include ('../../../lib/database.php');
ob_start();
session_start();
$sqlsrv_hris = DB::connection('sqlsrv_hris');
if ($_POST['act'] == "save_karyawan"):
	$sql = "";
	if (isset($_POST['id']) && $_POST['id'] != ""):
		$sql = "update [db_hris].[dbo].[table_karyawan] set 
					nama_pt = '" . trim($_POST['nama_pt']) . "'
					,[entity] = '" . trim($_POST['entity']) . "'
					,[depo] = '" . trim($_POST['depo']) . "'
					,[pin] = '" . trim($_POST['pin']) . "'
					,[nik] = '" . trim($_POST['nik']) . "'
					,[kategori_karyawan] = '" . trim($_POST['kategori_karyawan']) . "'
					,[nama_outsourcing] = '" . trim($_POST['nama_outsourcing']) . "'
					,[nama] = '" . trim($_POST['nama']) . "'
					,[lama_kerja_th] = '" . trim($_POST['lama_kerja_th']) . "'
					,[lama_kerja_bln] = '" . trim($_POST['lama_kerja_bln']) . "'
					,[status_pkwt] = '" . trim($_POST['status_pkwt']) . "'
					,[status_kontrak_kerja] = '" . trim($_POST['status_kontrak_kerja']) . "'
					,[tgl_akhir_pkwt] = '" . date("Y-m-d", strtotime($_POST['tgl_akhir_pkwt'])) . "'
					,[nama_grade] = '" . trim($_POST['nama_grade']) . "'
					,[sub_grade] = '" . trim($_POST['sub_grade']) . "'
					,[tahun] = '" . trim($_POST['tahun']) . "'
					,[grade_level] = '" . trim($_POST['grade_level']) . "'
					,[skema_insentif] = '" . trim($_POST['skema_insentif']) . "'
					,[THP] = '" . trim($_POST['THP']) . "'
					,[potongan_bpjs_tk] = '" . trim($_POST['potongan_bpjs_tk']) . "'
					,[potongan_bpjs_kes] = '" . trim($_POST['potongan_bpjs_kes']) . "'
					,[bagian] = '" . trim($_POST['bagian']) . "'
					,[sub_bagian] = '" . trim($_POST['sub_bagian']) . "'
					,[seksi] = '" . trim($_POST['seksi']) . "'
					,[jabatan] = '" . trim($_POST['jabatan']) . "'
					,[divisi] = '" . trim($_POST['divisi']) . "'
					,[brand] = '" . trim($_POST['brand']) . "'
					,[active] = '" . trim($_POST['active']) . "'
					,[kode_finger] = '" . trim($_POST['kode_finger']) . "'
					,[tempat_lahir] = '" . trim($_POST['tempat_lahir']) . "'
					,[tgl_lahir] = '" . date("Y-m-d", strtotime($_POST['tgl_lahir'])) . "'
					,[j_pengenal] = '" . trim($_POST['j_pengenal']) . "'
					,[no_pengenal] = '" . trim($_POST['no_pengenal']) . "'
					,[no_kk] = '" . trim($_POST['no_kk']) . "'
					,[alamat_ktp] = '" . trim($_POST['alamat_ktp']) . "'
					,[kota] = '" . trim($_POST['kota']) . "'
					,[alamat_domisili] = '" . trim($_POST['alamat_domisili']) . "'
					,[kota_domisili] = '" . trim($_POST['kota_domisili']) . "'
					,[telepon] = '" . trim($_POST['telepon']) . "'
					,[handphone] = '" . trim($_POST['handphone']) . "'
					,[email] = '" . trim($_POST['email']) . "'
					,[agama] = '" . trim($_POST['agama']) . "'
					,[pendidikan] = '" . trim($_POST['pendidikan']) . "'
					,[institusi] = '" . trim($_POST['institusi']) . "'
					,[tgl_awal_kerja] = '" . date("Y-m-d", strtotime($_POST['tgl_awal_kerja'])) . "'
					,[tgl_akhir_kerja] = '" . date("Y-m-d", strtotime($_POST['tgl_akhir_kerja'])) . "'
					,[status] = '" . trim($_POST['status']) . "'
					,[jenis_kelamin] = '" . trim($_POST['jenis_kelamin']) . "'
					,[bank] = '" . trim($_POST['bank']) . "'
					,[no_rekening] = '" . trim($_POST['no_rekening']) . "'
					,[status_pph_21] = '" . trim($_POST['status_pph_21']) . "'
					,[no_npwp] = '" . trim($_POST['no_npwp']) . "'
					,[no_bpjs_kes] = '" . trim($_POST['no_bpjs_kes']) . "'
					,[no_bpjs_tk] = '" . trim($_POST['no_bpjs_tk']) . "'
					,[nama_pasangan] = '" . trim($_POST['nama_pasangan']) . "'
					,[telp_pasangan] = '" . trim($_POST['telp_pasangan']) . "'
					,[tgl_lahir_pasangan] = '" . date("Y-m-d", strtotime($_POST['tgl_lahir_pasangan'])) . "'
					,[nama_kerja_pasangan] = '" . trim($_POST['nama_kerja_pasangan']) . "'
					,[lokasi_kerja_pasangan] = '" . trim($_POST['lokasi_kerja_pasangan']) . "'
					,[nama_anak1] = '" . trim($_POST['nama_anak1']) . "'
					,[tgl_lahir_anak1] = '" . date("Y-m-d", strtotime($_POST['tgl_lahir_anak1'])) . "'
					,[gender_anak1] = '" . trim($_POST['gender_anak1']) . "'
					,[nama_anak2] = '" . trim($_POST['nama_anak2']) . "'
					,[tgl_lahir_anak2] = '" . date("Y-m-d", strtotime($_POST['tgl_lahir_anak2'])) . "'
					,[gender_anak2] = '" . trim($_POST['gender_anak2']) . "'
					,[nama_anak3] = '" . trim($_POST['nama_anak3']) . "'
					,[tgl_lahir_anak3] = '" . date("Y-m-d", strtotime($_POST['tgl_lahir_anak3'])) . "'
					,[gender_anak3] = '" . trim($_POST['gender_anak3']) . "'
					,[wajib_jaminan] = '" . trim($_POST['wajib_jaminan']) . "'
					,[nama_jaminan] = '" . trim($_POST['nama_jaminan']) . "'
					,[no_jaminan] = '" . trim($_POST['no_jaminan']) . "'
					,[foto_profile] = '" . trim($_POST['foto_profile']) . "'
					,[foto_ktp] = '" . trim($_POST['foto_ktp']) . "'
					,[foto_sim] = '" . trim($_POST['foto_sim']) . "'
					,[terdaftar_bpjs_kes] = '" . trim($_POST['terdaftar_bpjs_kes']) . "'
					,[terdaftar_bpjs_tk] = '" . trim($_POST['terdaftar_bpjs_tk']) . "'
					,[tgl_update] = getdate()
					,[user_update] = '" . trim($_SESSION['username']) . "'
					where nik = '" . trim($_POST['nik']) . "'";
	else:
		$sql = "INSERT INTO [db_hris].[dbo].[table_karyawan]
				(
					[nama_pt]
					,[entity]
					,[depo]
					,[pin]
					,[nik]
					,[kategori_karyawan]
					,[nama_outsourcing]
					,[nama]
					,[lama_kerja_th]
					,[lama_kerja_bln]
					,[status_pkwt]
					,[status_kontrak_kerja]
					,[tgl_akhir_pkwt]
					,[nama_grade]
					,[sub_grade]
					,[tahun]
					,[grade_level]
					,[skema_insentif]
					,[THP]
					,[potongan_bpjs_tk]
					,[potongan_bpjs_kes]
					,[bagian]
					,[sub_bagian]
					,[seksi]
					,[jabatan]
					,[divisi]
					,[brand]
					,[active]
					,[kode_finger]
					,[tempat_lahir]
					,[tgl_lahir]
					,[j_pengenal]
					,[no_pengenal]
					,[no_kk]
					,[alamat_ktp]
					,[kota]
					,[alamat_domisili]
					,[kota_domisili]
					,[telepon]
					,[handphone]
					,[email]
					,[agama]
					,[pendidikan]
					,[institusi]
					,[tgl_awal_kerja]
					,[tgl_akhir_kerja]
					,[status]
					,[jenis_kelamin]
					,[bank]
					,[no_rekening]
					,[status_pph_21]
					,[no_npwp]
					,[no_bpjs_kes]
					,[no_bpjs_tk]
					,[nama_pasangan]
					,[telp_pasangan]
					,[tgl_lahir_pasangan]
					,[nama_kerja_pasangan]
					,[lokasi_kerja_pasangan]
					,[nama_anak1]
					,[tgl_lahir_anak1]
					,[gender_anak1]
					,[nama_anak2]
					,[tgl_lahir_anak2]
					,[gender_anak2]
					,[nama_anak3]
					,[tgl_lahir_anak3]
					,[gender_anak3]
					,[wajib_jaminan]
					,[nama_jaminan]
					,[no_jaminan]
					,[foto_profile]
					,[foto_ktp]
					,[foto_sim]
					,[terdaftar_bpjs_kes]
					,[terdaftar_bpjs_tk]
					,[tgl_update]
					,[user_update]
					,[create_by]
      				,[date_create]
				) 
				VALUES 
				(
					'" . trim($_POST['nama_pt']) . "'
					,'" . trim($_POST['entity']) . "'
					,'" . trim($_POST['depo']) . "'
					,'" . trim($_POST['pin']) . "'
					,'" . trim($_POST['nik']) . "'
					,'" . trim($_POST['kategori_karyawan']) . "'
					,'" . trim($_POST['nama_outsourcing']) . "'
					,'" . trim($_POST['nama']) . "'
					,'" . trim($_POST['lama_kerja_th']) . "'
					,'" . trim($_POST['lama_kerja_bln']) . "'
					,'" . trim($_POST['status_pkwt']) . "'
					,'" . trim($_POST['status_kontrak_kerja']) . "'
					,'" . date("Y-m-d", strtotime($_POST['tgl_akhir_pkwt'])) . "'
					,'" . trim($_POST['nama_grade']) . "'
					,'" . trim($_POST['sub_grade']) . "'
					,'" . trim($_POST['tahun']) . "'
					,'" . trim($_POST['grade_level']) . "'
					,'" . trim($_POST['skema_insentif']) . "'
					,'" . trim($_POST['THP']) . "'
					,'" . trim($_POST['potongan_bpjs_tk']) . "'
					,'" . trim($_POST['potongan_bpjs_kes']) . "'
					,'" . trim($_POST['bagian']) . "'
					,'" . trim($_POST['sub_bagian']) . "'
					,'" . trim($_POST['seksi']) . "'
					,'" . trim($_POST['jabatan']) . "'
					,'" . trim($_POST['divisi']) . "'
					,'" . trim($_POST['brand']) . "'
					,'" . trim($_POST['active']) . "'
					,'" . trim($_POST['kode_finger']) . "'
					,'" . trim($_POST['tempat_lahir']) . "'
					,'" . date("Y-m-d", strtotime($_POST['tgl_lahir'])) . "'
					,'" . trim($_POST['j_pengenal']) . "'
					,'" . trim($_POST['no_pengenal']) . "'
					,'" . trim($_POST['no_kk']) . "'
					,'" . trim($_POST['alamat_ktp']) . "'
					,'" . trim($_POST['kota']) . "'
					,'" . trim($_POST['alamat_domisili']) . "'
					,'" . trim($_POST['kota_domisili']) . "'
					,'" . trim($_POST['telepon']) . "'
					,'" . trim($_POST['handphone']) . "'
					,'" . trim($_POST['email']) . "'
					,'" . trim($_POST['agama']) . "'
					,'" . trim($_POST['pendidikan']) . "'
					,'" . trim($_POST['institusi']) . "'
					,'" . date("Y-m-d", strtotime($_POST['tgl_awal_kerja'])) . "'
					,'" . date("Y-m-d", strtotime($_POST['tgl_akhir_kerja'])) . "'
					,'" . trim($_POST['status']) . "'
					,'" . trim($_POST['jenis_kelamin']) . "'
					,'" . trim($_POST['bank']) . "'
					,'" . trim($_POST['no_rekening']) . "'
					,'" . trim($_POST['status_pph_21']) . "'
					,'" . trim($_POST['no_npwp']) . "'
					,'" . trim($_POST['no_bpjs_kes']) . "'
					,'" . trim($_POST['no_bpjs_tk']) . "'
					,'" . trim($_POST['nama_pasangan']) . "'
					,'" . trim($_POST['telp_pasangan']) . "'
					,'" . date("Y-m-d", strtotime($_POST['tgl_lahir_pasangan'])) . "'
					,'" . trim($_POST['nama_kerja_pasangan']) . "'
					,'" . trim($_POST['lokasi_kerja_pasangan']) . "'
					,'" . trim($_POST['nama_anak1']) . "'
					,'" . date("Y-m-d", strtotime($_POST['tgl_lahir_anak1'])) . "'
					,'" . trim($_POST['gender_anak1']) . "'
					,'" . trim($_POST['nama_anak2']) . "'
					,'" . date("Y-m-d", strtotime($_POST['tgl_lahir_anak2'])) . "'
					,'" . trim($_POST['gender_anak2']) . "'
					,'" . trim($_POST['nama_anak3']) . "'
					,'" . date("Y-m-d", strtotime($_POST['tgl_lahir_anak3'])) . "'
					,'" . trim($_POST['gender_anak3']) . "'
					,'" . trim($_POST['wajib_jaminan']) . "'
					,'" . trim($_POST['nama_jaminan']) . "'
					,'" . trim($_POST['no_jaminan']) . "'
					,'" . trim($_POST['foto_profile']) . "'
					,'" . trim($_POST['foto_ktp']) . "'
					,'" . trim($_POST['foto_sim']) . "'
					,'" . trim($_POST['terdaftar_bpjs_kes']) . "'
					,'" . trim($_POST['terdaftar_bpjs_tk']) . "'
					,getdate()
					,'" . trim($_SESSION['username']) . "'
					,'" . trim($_SESSION['nama']) . "'
      				,getdate()
				)";
	endif;

	$stmt = $sqlsrv_hris->query($sql);
	if ($stmt):
		echo "Data Saved";
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