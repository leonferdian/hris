<?php
function format_rupiah_comma($angka){
  $rupiah=number_format($angka,0,',',',');
  return $rupiah;
}
?> 
