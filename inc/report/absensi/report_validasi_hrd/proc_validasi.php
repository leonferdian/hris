<?php 
session_start();
ob_start();
include('../../../lib/database.php');
require '../../../lib/vendor/autoload.php';
$sqlsrv_hris = DB::connection('sqlsrv_hris');

use Intervention\Image\ImageManagerStatic as Image;
?>
<?php if($_POST['act'] == "add_validasi"): ?>
<div class="row">
	<div class="col-xs-12">
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-top"> Nama </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm" id="nama" name="nama" value="<?php echo $_POST['nama']; ?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-top"> Tanggal </label>
				<div class="col-sm-7">
					<input type="text" class="form-control input-sm" id="tanggal" name="tanggal" value="<?php echo $_POST['tanggal']; ?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-top"> Leave Category </label>
				<div class="col-sm-7">
					<select class="form-control input-sm chosen-select" name="slc_lc" id="slc_lc" data-placeholder="Select Person" onchange="show_upload_file()">
						<?php
						$sql = "SELECT [id], [leave_category] FROM [db_hris].[dbo].[table_absensi_leave_category]";
						$stmt = $sqlsrv_hris->query($sql);
						while ($row = $sqlsrv_hris->fetch_array($stmt)):
							echo '<option value="' . $row['id'] . '">' . $row['leave_category'] . '</option>';
						endwhile;
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-top"> Keterangan </label>
				<div class="col-sm-7">
					<textarea class="form-control" id="keterangan" placeholder="Default Text"></textarea>
				</div>
			</div>
            <div class="form-group" id="input-file" style="display: none;">
                <label class="col-sm-3 control-label no-padding-top">File Surat Dokter </label>
                <div class="col-sm-7">
                    <input multiple type="file" class="id-input-file-3" id="absen_file" name="absen_file[]" />
                </div>
            </div>
			<input type="hidden" id="pin" value="<?php echo $_POST['pin']; ?>">
			<input type="hidden" id="nik" value="<?php echo $_POST['nik']; ?>">
			<input type="hidden" id="depo" value="<?php echo $_POST['depo']; ?>">
		</form>
	</div><!-- /.col -->
</div><!-- /.row -->
<?php elseif($_POST['act'] == "save_validasi"):
	$verifikasi_apps = $_POST['leave_category'] == 1 ? 1 : 2; //0 = process verification 1 = masuk 2 = verified tidak masuk
	$nilai = $_POST['leave_category'] == 1 ? 1 : 0; //0 = tidak masuk 1 = masuk
	$sql = "INSERT INTO [db_hris].[dbo].[table_absensi_validasi] 
			(
			[depo]
			,[date_absen]
			,[pin]
			,[nik]
			,[leave_category]
			,[verifikasi_apps]
			,[keterangan]
			,[nilai]
			,[validated_by]
			,[validate_source]
      		,[dtmCreate]
			)
			values
			(
			'".$_POST['depo']."'
			,'".$_POST['tanggal']."'
			,'".$_POST['pin']."'
			,'".$_POST['nik']."'
			,'".$_POST['leave_category']."'
			,'".$verifikasi_apps."'
			,'".$_POST['keterangan']."'
			,'".$nilai."'
			,'".$_SESSION['id_user']."'
			,'centralinput'
			,getdate()
			)";
    $stmt = $sqlsrv_hris->query($sql);
	if ($stmt) {
        $sql_log = "INSERT INTO [db_hris].[dbo].[table_log_activity]
                    (
                    [tanggal]
                    ,[category]
                    ,[depo]
                    ,[note]
                    ,[status]
                    ,[date_create]
                    ,[create_by]
                    ,[date_update]
                    ,[update_by]
                    )
                    VALUES
                    (
                    '".$_POST['tanggal']."'
                    ,'validasi_absensi'
                    ,'".$_POST['depo']."'
                    ,'".$_POST['keterangan']."'
                    ,'0'
                    ,getdate()
                    ,'".$_SESSION['id_user']."'
                    ,getdate()
                    ,'".$_SESSION['id_user']."'
                    )";
        $stmt_log = $sqlsrv_hris->query($sql_log);
        if (!$stmt_log):
            echo "{'Error: $sql_log'}";
        endif;
		echo "Data Saved";
	} else {
		echo "Error: `".$sql."`";
	}
?>
<?php elseif ($_POST['act'] == "validasi_absensi"): ?>
<?php	
    $sql_cek = "select * from [db_hris].[dbo].[table_absensi_validasi_hrd] where depo = '" . $_POST['depo'] . "' and pin = '" . $_POST['pin'] . "' and nik = '" . $_POST['nik'] . "' and date_absen = '" . $_POST['tanggal'] . "'";
    $stmt_cek = $sqlsrv_hris->query($sql_cek);
    $num_row = $sqlsrv_hris->num_rows($stmt_cek);

    $validasi = $_POST['validasi'] == "valid" ? 1 : 0;

    if ($num_row == 0) {
        $sql = "insert into [db_hris].[dbo].[table_absensi_validasi_hrd]
                (depo
                ,pin
                ,nik
                ,date_absen
                ,validasi_hrd
                ,nama_validator
                ,validate_date)
                values
                (   '" . $_POST['depo'] . "'
                    ,'" . $_POST['pin'] . "'
                    ,'" . $_POST['nik'] . "'
                    ,'" . $_POST['tanggal'] . "'
                    ,'" . trim($validasi). "'
                    ,'" . trim($_SESSION['nama']) . "'
                    ,getdate()
                )";

        $stmt = $sqlsrv_hris->query($sql);

        if ($stmt) {
            echo "Data saved";
        } else {
            echo "error: `".$sql."`";
        }
    } else {
        $sql = "update [db_hris].[dbo].[table_absensi_validasi_hrd]
                set validasi_hrd = '" . trim($validasi). "'
                ,nama_validator = '" . trim($_SESSION['nama']) . "'
                ,validate_date = getdate()
                where depo = '" . $_POST['depo'] . "' and date_absen = '" . $_POST['tanggal'] . "'  and nik = '" . $_POST['nik'] . "'";
        
        $stmt = $sqlsrv_hris->query($sql);

        if ($stmt) {
            echo "Data saved";
        } else {
            echo "error: `".$sql."`";
        }
    }
?>
<?php elseif ($_POST['act'] == "upload_image"): ?>
    <?php
        if ($_POST["total_file"] > 0) {
            // Path to the uploaded image
            $uploadedImage = $_FILES['absen_file']['tmp_name'];

            // Set the new dimensions
            $newWidth = 224;
            $newHeight = 340;

            // Open the uploaded image
            $img = Image::make($uploadedImage);

            $img->save('../../../images/absensi/' . "preview_" . $_POST['filename']);

            // Resize the image
            $img->resize($newWidth, $newHeight);

            // Save the resized image
            $img->save('../../../images/absensi/' . $_POST['filename']);

            // Check if the image was successfully saved
            if (file_exists('../../../images/absensi/' . $_POST['filename'])) {
                // echo "The image has been resized and saved.";
            } else {
                echo "File gagal disimpan di direktori";
            }
        }

        $sql ="insert into [db_hris].[dbo].[table_file_ssd]
                        (
                            [depo]
                            ,[nik]
                            ,[tanggal]
                            ,[filename]
                        )
                        values
                        (
                            '".$_POST['depo']."'
                            ,'".$_POST['nik']."'
                            ,'".date("Y-m-d", strtotime($_POST['tanggal']))."'
                            ,'".$_POST["filename"]."'
                        )";
                        
        $stmt = $sqlsrv_hris->query($sql);

        if ($stmt) {
            echo "Data saved";
        } else {
            echo "error: `".$sql."`";
        }
    ?>
<?php endif; ?>