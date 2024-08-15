<script>
$(document).ready(function(){
	
});

</script>
<div class="scroll_user" >
	<?php 
		session_start();
		include('../../lib/db_sqlserver_central_input.php'); 
	    $active = "'Y'";
  	 	$sql_daily = sqlsrv_query($conn_ci,"SELECT lengkap.id_user,lengkap.username,lengkap.nama,today.kunjungan_hari_ini,bulan.kunjungan_bulan_ini,lengkap.user_lastlogin 
					FROM [USER] AS lengkap
					LEFT JOIN (
									SELECT u.id_user,u.username,u.nama,COUNT(u.nama) AS kunjungan_hari_ini 
									FROM [USER] AS u INNER JOIN tbl_visitor AS v ON u.username = v.email 
									WHERE CONVERT(DATE,v.tgl_visitor) ='".date('Y-m-d')."' 
									GROUP BY u.nama,u.id_user,u.username
					) today ON  today.id_user = lengkap.id_user
					LEFT JOIN (
					SELECT u.username, u.id_user, COUNT(u.nama) AS kunjungan_bulan_ini,u.user_lastlogin
					FROM [USER] AS u INNER JOIN tbl_visitor AS v ON u.username = v.email
					WHERE YEAR(tgl_visitor)='".date('Y')."' AND MONTH(tgl_visitor)='".date('m')."'
					GROUP BY u.username, u.id_user, u.user_lastlogin
					) bulan ON bulan.id_user = lengkap.id_user

					ORDER BY kunjungan_hari_ini DESC,user_lastlogin DESC", array(), array("Scrollable"=>SQLSRV_CURSOR_KEYSET));	
					

   	?>
			<table class="table table-hover"  id="list_emp">
				<thead>
					<!--
					<tr>
						<th colspan='4' style="text-align: center;">Data Pengunjung Tanggal <?php // echo date('Y-m-d');?></th>
					</tr>
					-->
					<tr>
						<th>#.</th>
						<th>Nama User</th>
						<th>Kunjungan Hari Ini</th>
						<th>Kunjungan Bulan <?php echo date('M');?></th>
						<th>Last Visited</th>
					</tr>
				</thead>
				<tbody style="color:#707070">
					<?php 
						$no = 1;
						while($dtgl = sqlsrv_fetch_array($sql_daily, SQLSRV_FETCH_ASSOC)){
					?>
						<tr <?php if($no%2==0){echo "class=''";}else{echo "scope='row'";}?>>
							<td><?php echo $no;?>.</td>
							<td><?php echo ucwords(strtolower($dtgl['nama']));?></td>
							<td align="center">
								<a href="#" onclick = "det_visitor('<?php echo date('Y-m-d'); ?>','<?php echo $dtgl['id_user']; ?>','<?php echo $dtgl['nama']; ?>','daily')" >
									<?php echo $dtgl['kunjungan_hari_ini'];?>
								</a>
							</td>
							<td align="center">
								<a href="#" onclick = "det_visitor2('<?php echo date('Y'); ?>','<?php echo date('m'); ?>','<?php echo $dtgl['id_user']; ?>','<?php echo $dtgl['nama']; ?>','monthly')" >
									<?php echo $dtgl['kunjungan_bulan_ini'];?>
								</a>
							</td>
							<td><?php echo $dtgl['user_lastlogin']->format("Y-m-d H:i:s");?></td>
						</tr>
					<?php $no++;}?>
				</tbody>
			</table>
</div>
<style type="text/css">
	.scroll_user{
	    border: 1px solid #ccc;
	    height: 310px;
	    padding: 10px;
	    overflow-y:scroll;
	}
</style>
<script type="text/javascript">
function det_visitor(tgl,iduser,nmuser,jns){
	$.ajax({
		type:"GET",
		url:"inc/index/view_det_visitor.php?tgl="+tgl+"&iduser="+iduser+"&nmuser="+nmuser+"&jns="+jns,
		success:function(data){		
			$("#visitor").show();									
			$("#view_history_visitor").html(data);
		},
		error:function(msg){
			alert(msg);
		}
	});
	//alert(tgl+"-"+iduser);
}
function det_visitor2(thn,bln,iduser,nmuser,jns){
	$.ajax({
		type:"GET",
		url:"inc/index/view_det_visitor.php?thn="+thn+"&bln="+bln+"&iduser="+iduser+"&nmuser="+nmuser+"&jns="+jns,
		success:function(data){		
			$("#visitor").show();									
			$("#view_history_visitor").html(data);
		},
		error:function(msg){
			alert(msg);
		}
	});
	//alert(tgl+"-"+iduser);
}
</script>