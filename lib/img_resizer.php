<?php 
$fl = $_GET['gambar'];
$width = $_GET['lebar'];
list($w, $h) = getimagesize($fl);
if ($w > $h) 
{
	$persen = $width / $w;
} 
else 
{
	$persen = $width / $h;
}

$nw = round($w * $persen);
$nh = round($h * $persen);

$gambar_asli = imagecreatefromjpeg($fl);
$gambar_kecil = imagecreatetruecolor($nw, $nh);
imagecopyresampled($gambar_kecil, $gambar_asli, 0, 0, 0, 0,
$nw, $nh, $w, $h);
imagejpeg($gambar_kecil);
?>