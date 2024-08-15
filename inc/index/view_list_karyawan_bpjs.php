<?php 
	$get_depo = pg_query($dbpostgre, "SELECT * FROM tbdepo WHERE id = '".$_GET['id_depo']."' ");
	$row_depo = pg_fetch_array($get_depo);
?>
<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?page=dashboard">Home</a>
			</li>
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?page=dashboard&act=see_detail_bpjs">See Detail Chart BPJS</a>
			</li>
			<li class="active">
				List Karyawan 
				<?php if($_GET['status']=="uncovered"){ ?> Uncovered <?php }else if($_GET['status']=="covered"){?> Covered <?php }?>
				- Depo <?php echo $row_depo['cdepo']; ?></li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>
				List Karyawan 
				<?php if($_GET['status']=="uncovered"){ ?> Uncovered <?php }else if($_GET['status']=="covered"){?> Covered <?php }?> 
				- Depo <?php echo $row_depo['cdepo']; ?>
			</h1>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#.</th>
							<th>NIK</th>
							<th>Nama Karyawan</th>
							<th>Jamsostek Number</th>
							<th>Join Date</th>
							<th>Cut Off Date</th>
							<th>Work Periode</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							if($_GET['status']=="uncovered"){
								$bpjs = pg_query($dbpostgre, "SELECT tbkaryawan.*,
																	DATE_PART('year', '".date('Y-m-d')."'::date) - DATE_PART('year', dtglawalkerja::date) as periode_kerja,
																	DATE_PART('month', '".date('Y-m-d')."'::date) - DATE_PART('month', dtglawalkerja::date) as periode_kerja_bln
																	FROM tbkaryawan 
																	WHERE (cnojamsostek ='' or cnojamsostek is null)  and cactive='Y' and iddepo = '".$_GET['id_depo']."'
																	ORDER BY cnama ASC");	
							}else if($_GET['status']=="covered"){
								$bpjs = pg_query($dbpostgre, "SELECT tbkaryawan.*,
																	DATE_PART('year', '".date('Y-m-d')."'::date) - DATE_PART('year', dtglawalkerja::date) as periode_kerja,
																	DATE_PART('month', '".date('Y-m-d')."'::date) - DATE_PART('month', dtglawalkerja::date) as periode_kerja_bln
																	FROM tbkaryawan where cnojamsostek !=''  and cactive='Y' and iddepo = '".$_GET['id_depo']."'");	
							}
							$no =1;
							while($d_bpjs = pg_fetch_array($bpjs)){
						?>
							<tr>
								<td><?php echo $no; ?>.</td>
								<td><?php echo $d_bpjs['nik']; ?></td>
								<td><?php echo $d_bpjs['cnama']; ?></td>
								<td><?php if($d_bpjs['cnojamsostek']!=""){echo $d_bpjs['cnojamsostek'];}else{echo "-";} ?></td>
								<td><?php echo date('Y-m-d', strtotime($d_bpjs['dtglawalkerja'])); ?></td>
								<td><?php if($d_bpjs['dtglakhirkerja']!=""){echo date('Y-m-d', strtotime($d_bpjs['dtglakhirkerja']));}else{echo "-";} ?></td>
								<td>
									<?php if($d_bpjs['periode_kerja']!=0){echo $d_bpjs['periode_kerja']." Year";}else{echo $d_bpjs['periode_kerja_bln']." Month";}?>
								</td>
							</tr>
						<?php $no++;}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>