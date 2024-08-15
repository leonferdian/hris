<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?page=dashboard">Home</a>
			</li>
			<li class="active">See Detail Dashboard BPJS</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>
				See Detail Dashboard BPJS
			</h1>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#.</th>
							<th>Depo</th>
							<th>Uncovered</th>
							<th>Covered</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$tot_uncovered = '';
							$tot_covered = '';
							$tot_all = '';
							$total = '';

							$bpjs = "SELECT d.cdepo,d.id,
										(SELECT count(*) FROM tbkaryawan WHERE cnojamsostek ='' and cactive='Y' and iddepo = d.id) as uncover,
										(SELECT count(*) FROM tbkaryawan WHERE cnojamsostek !='' and cactive='Y' and iddepo = d.id) as covered,
										(SELECT count(*) FROM tbkaryawan WHERE cnojamsostek is null and cactive='Y' and iddepo = d.id) as uncover2,
										(SELECT count(*) FROM tbkaryawan WHERE cactive='Y' and iddepo = d.id) as tot_karyawan
										FROM tbdepo as d GROUP BY d.cdepo,d.id ORDER BY d.cdepo ASC";

							$bpjs_sql = pg_query($dbpostgre, $bpjs);
							$no = 1;
							while($dbpjs = pg_fetch_array($bpjs_sql)){
								$uncover = $dbpjs['uncover']+$dbpjs['uncover2'];
								$tot_uncovered += $uncover;
								$tot_covered += $dbpjs['covered'];
								$total = $uncover + $dbpjs['covered'];
								$tot_all += $total;
						?>
							<tr>
								<td style="vertical-align: middle;"><?php echo $no;?>.</td>
								<td style="vertical-align: middle;"><?php echo $dbpjs['cdepo'];?></td>
								<td style="vertical-align: middle;">
									<a href="?page=dashboard&act=see_detail_bpjs&act=list_peg_uncovered&status=uncovered&id_depo=<?php echo $dbpjs['id']; ?>"> 
										<?php echo $uncover;?>
									</a>
								</td>
								<td style="vertical-align: middle;">
									<a href="?page=dashboard&act=see_detail_bpjs&act=list_peg_covered&status=covered&id_depo=<?php echo $dbpjs['id']; ?>"> 
										<?php echo $dbpjs['covered'];?>
								</td>
								<td><?php echo $total;?></td>
							</tr>
						<?php $no++;}?>
						<tr>
							<th colspan="2">Total</th>
							<td><b><?php echo $tot_uncovered;?></b></td>
							<td><b><?php echo $tot_covered;?></b></td>
							<td><b><?php echo $tot_all;?></b></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>