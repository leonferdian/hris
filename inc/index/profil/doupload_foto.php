<?php ob_start();session_start();
include('../../lib/db_nirvana.php');
$nama_foto = $_FILES['foto_user']['name'];
$lokasi_foto = $_FILES['foto_user']['tmp_name'];
$tipe_foto = substr($nama_foto,-4,4);
if($_POST['id_user']!="" && $_FILES['foto_user']['name']!="")
{
	
	
		if($tipe_foto!=".jpg"  && $tipe_foto!=".JPG")
		{
			?>
			<script language="javascript">
			parent.alert("Gagal menyimpan : format foto tidak mendukung! foto harus berekstensi .jpg");
			parent.document.getElementById("haha").style.display='';
			parent.document.getElementById("progress").style.display='none';
			</script>
			<?php

		}
		else
		{
			$nama_foto_baru = str_replace($nama_foto,$_POST['id_user'].".jpg",$nama_foto);
			$direktori = "../../../images/employee/$nama_foto_baru";
			
			if(move_uploaded_file($lokasi_foto,$direktori))
			{
				$cek_file_foto = mysqli_query($dbnva,"SELECT * FROM user where id_user='".$_POST['id_user']."'");
				//$num_file_kendaraan = mysqli_num_rows($cek_file_foto);
			
				$upload_file_foto = mysqli_query($dbnva,"UPDATE user SET user_foto = '".$nama_foto_baru."' where id_user = '".$_POST['id_user']."'");	
			
				sleep(2);
				
				?>
			<script language="javascript">
				//parent.alert("Upload Success Silahkan Logout Kemudian Login Kembali");
				parent.document.getElementById("haha").style.display='';
				parent.document.getElementById("progress").style.display='none';
				parent.sukses("lib/img_resizer.php?gambar=../images/employee/<?php echo $nama_foto_baru;?>&lebar=300&<?php echo date('s'); ?>","lib/img_resizer.php?gambar=../images/employee/<?php echo $nama_foto_baru; ?>&lebar=300&<?php echo date('s'); ?>","<?php echo $_POST['id_user']; ?>");
			</script>

		<?php
			}
		}
	
}
else
{
				?>
			<script language="javascript">
			parent.alert("Gagal menyimpan : Tidak ada file yang dipilih");
			parent.document.getElementById("haha").style.display='';
			parent.document.getElementById("progress").style.display='none';
			</script>
			<?php
}
?>
