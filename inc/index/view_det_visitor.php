<script type="text/javascript">
$(document).ready(function(){
	MergeCommonRows($('#list_empp'));
});
function MergeCommonRows(table) {
	//alert(table)
	var firstColumnBrakes = [];
	// iterate through the columns instead of passing each column as function parameter:
	for(var i=1; i<=table.find('th').length; i++){
		var previous = null, cellToExtend = null, rowspan = 1;
		//table.find("td:nth-child(" + i + ")").each(function(index, e){
		table.find(".td_l1:nth-child(" + i + ")").each(function(index, e){
			var jthis = $(this), content = jthis.text();
			//alert(content);
			// check if current row "break" exist in the array. If not, then extend rowspan:
			if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1 && typeof content === "string") {
				// hide the row instead of remove(), so the DOM index won't "move" inside loop.
				jthis.addClass('hidden');
				cellToExtend.attr("rowspan", (rowspan = rowspan+1));
			}else{
				// store row breaks only for the first column:
				if(i === 1) firstColumnBrakes.push(index);
				rowspan = 1;
				previous = content;
				cellToExtend = jthis;
			}
		});
		
	}
	// now remove hidden td's (or leave them hidden if you wish):
	$('td.hidden').remove();
}
</script>
<?php 
	$day = date ("D");
	switch ($day) {
		case 'Sun' : $hari = "Minggu"; break;
		case 'Mon' : $hari = "Senin"; break;
		case 'Tue' : $hari = "Selasa"; break;
		case 'Wed' : $hari = "Rabu"; break;
		case 'Thu' : $hari = "Kamis"; break;
		case 'Fri' : $hari = "Jum'at"; break;
		case 'Sat' : $hari = "Sabtu"; break;
		default : $hari = "Kiamat";
	}

	session_start();
	include('../../lib/db_new_nva.php');
	if($_GET['jns']=='daily'){
		$sql_det_visitor = mysqli_query($db_new_nva,"SELECT 
													a.*, b.id_user, b.nama 
												FROM tbl_visitor a
												JOIN USER b ON b.username = a.email
												WHERE DATE_FORMAT(a.tgl_visitor, '%Y-%m-%d') = '".$_GET['tgl']."' AND b.id_user = '".$_GET['iduser']."' 
												ORDER BY a.tgl_visitor desc");	
	}else if($_GET['jns']=='monthly'){
		$sql_det_visitor = mysqli_query($db_new_nva,"SELECT 
													a.*, b.id_user, b.nama 
												FROM tbl_visitor a
												JOIN USER b ON b.username = a.email
												WHERE YEAR(a.tgl_visitor) = '".$_GET['thn']."' AND MONTH(a.tgl_visitor) = '".$_GET['bln']."' AND b.id_user = '".$_GET['iduser']."' 
												ORDER BY a.tgl_visitor desc");	
	}
 	
?>
<table class="table table-hover"  id="list_empp">
	<thead>
		<tr>
			<th colspan='4' style="text-align: center;">
				History User <?php echo $_GET['nmuser'];?>, 
				<?php
					if($_GET['jns']=='daily'){
						echo $hari; 
					}else if($_GET['jns']=='monthly'){
						echo date('M');
					}
				?>
			</th>
		</tr>
		<tr>
			<th>#.</th>
			<th>Nama User</th>
			<th>Tanggal Kunjungan</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$no = 1;
			while($dtgl = mysqli_fetch_array($sql_det_visitor)){
		?>
			<tr <?php if($no%2==0){echo "class='success'";}else{echo "scope='row'";}?>>
				<td><?php echo $no;?>.</td>
				<td class='td_l1' style='vertical-align:middle; text-align:center;'><?php echo ucwords(strtolower($dtgl['nama']));?></td>
				<td><?php echo $dtgl['tgl_visitor'];?></td>
			</tr>
		<?php $no++;}?>
	</tbody>
</table>