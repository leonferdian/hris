<?php
function format_rupiah_ch($angka){
  if($angka != null){
    $rupiah=number_format($angka,0,',','.');
  }
  else{
    $rupiah = 0;
  }
  return $rupiah;
}
?> 
