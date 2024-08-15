<?php 
	//$dbpostgre = pg_connect("host=vpn.padmatirta.info port=5432 dbname=dbpadma_hris user=login_hris password=l0g1nHr15");
	//$dbpostgre = pg_connect("host=10.50.1.230 port=5432 dbname=postgres user=postgres password=i021172s");
	$dbpostgre = pg_connect("host=10.100.100.230 port=5432 dbname=hris_dashboard_production user=postgres password=i021172s");
	/*if($dbpostgre){
		echo "ok";
	}else{
		echo "not ok";
	}
	*/
?>