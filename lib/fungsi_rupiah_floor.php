<?php
function floor_rupiah($angka){
  $rupiah=number_format(floor($angka),0,',','.');
  return $rupiah;
}
?> 
