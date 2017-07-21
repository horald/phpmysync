<?php
echo "Updates<br>";

$pfad=$_SERVER['PHP_SELF'];
$pfad=substr($pfad,0,-17);
$pfad="http://".$_SERVER['SERVER_NAME'].$pfad;
$string = file_get_contents("../version.json");
$json_a = json_decode($string, true);
$versnr=$json_a['versnr'];
//echo $versnr."=versnr<br>";
$pfad=$pfad."/sites/help/de-DE/updates/updateprot_".$versnr.".html";
//echo $pfad."=path<br>";

echo "<iframe src='".$pfad."' width='800' height='400' frameborder='0'></iframe>";
?>