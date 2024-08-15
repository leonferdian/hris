<div class="scroll_user">
	<?php 
		session_start();
		include('../../lib/db_postgre.php');
	    $active = "'Y'";
	    $sql_ultah = 'SELECT k.dtgllahir, extract(doy from k.dtgllahir), k.cnama, k.*, d.cdepo 
	                    FROM tbkaryawan as k 
	                    inner join tbdepo as d on k.iddepo = d.id 
	                    where extract(doy from k.dtgllahir)+1 >=  extract(doy from now()) and k.cactive ='.$active.' 
	                    order by EXTRACT(month FROM "dtgllahir") asc, EXTRACT(day FROM "dtgllahir") asc';
	   
	    $ultah = pg_query($dbpostgre,$sql_ultah);
	    $ultah_now = '';
	    $bsk = date('m-d', strtotime("+1 days"));
	    while ($dultah = pg_fetch_array($ultah)) {
	        if(date("m-d", strtotime($dultah['dtgllahir']))==date('m-d', strtotime("+1 days"))){
	            $ultah_now='Besok';
	        }else if(date("m-d", strtotime($dultah['dtgllahir']))==date('m-d', strtotime("-1 days"))){
	            $ultah_now='Kemarin';
	        }else if(date("m-d", strtotime($dultah['dtgllahir']))==date('m-d')){
	           $ultah_now='Hari Ini';
	        }else{
	          $ultah_now = date("d-M", strtotime($dultah['dtgllahir']));
	        }
	?>
			<div id="profile-feed-1" class="profile-feed">
				<div class="profile-activity clearfix">
					<?php 
	                    $lokasi_foto = "images/employee/".$dultah['nik'].".jpg";
	                    if(file_exists($lokasi_foto)){
	                ?>
							<img class="pull-left" src="http://www.padmatirtagroup.com/dashboard_old/images/employee/<?php echo $dultah['nik'].".jpg";?>" />
					<?php }else{?>
	                   		<img class="pull-left" src="inc/index/photos/none.png">
	                <?php }?>
					<a class="user" href="#"> <?php echo $dultah['cnama'];?> </a>
					
					<div class="time">
						<i class="ace-icon fa fa-clock-o bigger-110"></i>
						<?php echo $dultah['cdepo']." ( ".$ultah_now." ) ";?>
					</div>
				</div>
			</div>
	<?php }?> 
</div>
<style type="text/css">
	.scroll_user{
	    border: 1px solid #ccc;
	    height: 310px;
	    padding: 10px;
	    overflow-y:scroll;
	}
</style>