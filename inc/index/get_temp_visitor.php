<?php 
	session_start();
	include('../../lib/db_new_nva.php'); 
	$active = "'Y'";
 	$sql_daily = mysqli_query($db_new_nva,"SELECT u.nama,u.user_lastlogin,COUNT(u.nama) AS jml 
 											FROM USER AS u INNER JOIN tbl_visitor AS v ON u.username = v.email 
										WHERE DATE_FORMAT(v.tgl_visitor,'%Y-%m-%d') ='".date('Y-m-d')."' 
										GROUP BY u.nama order by jml desc");	
?>
<table class="table table-hover"  id="list_emp">
	<thead>
		<tr>
			<th colspan='4' style="text-align: center;">Data Pengunjung Tanggal <?php echo date('Y-m-d');?></th>
		</tr>
		<tr>
			<th>#.</th>
			<th>Nama User</th>
			<th>Jumlah Kunjungan</th>
			<th>Time</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$no = 1;
			while($dtgl = mysqli_fetch_array($sql_daily)){
		?>
			<tr <?php if($no%2==0){echo "class='success'";}else{echo "scope='row'";}?>>
				<td><?php echo $no;?>.</td>
				<td><?php echo ucwords(strtolower($dtgl['nama']));?></td>
				<td><?php echo $dtgl['jml'];?></td>
				<td><?php echo $dtgl['user_lastlogin'];?></td>
			</tr>
		<?php $no++;}?>
	</tbody>
</table>