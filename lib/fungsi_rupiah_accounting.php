<?php

function format_rupiah_acc($angka){
	
	$hasil_rupiah = "Rp " . number_format($angka,2,'.',',');
	return $hasil_rupiah;
 
}
 
?> 
