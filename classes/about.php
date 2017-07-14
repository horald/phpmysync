<?php
include("bootstrapfunc.php");
include("../config.php");
bootstraphead();
bootstrapbegin($headline."<br>");
$string = file_get_contents("../version.json");
$json_a = json_decode($string, true);
$versnr=$json_a['versnr'];
$versdat=$json_a['versdat'];
echo "<a href='../index.php' class='btn btn-primary btn-sm active' role='button'>Menü</a> "; 
echo "<a href='help.php?url=about.php' class='btn btn-primary btn-sm active' role='button'>Hilfe</a> "; 
echo "<pre>";
echo "<table>";
echo "<tr><td>Stand</td>  <td>: ".$versdat."</td></tr>";
echo "<tr><td>aktuelle Version</td><td>: ".$versnr."</td></tr>";
echo "<tr><td>Sourcecode unter</td><td>: <a href='https://github.com/horald/phpmysync' target='_blank'>github:phpmysync</a></td></tr>";
echo "</table>";
echo "</pre>";
bootstrapend();
?>