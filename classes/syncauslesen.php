<?php
include("bootstrapfunc.php");
include("dbtool.php");
include("../config.php");
$debug=$_GET['debug'];
if ($debug<>"J") {
	$debug="N";
}	
$auto=$_POST['auto'];
$callbackurl=$_POST['callbackurl'];
$onlyshow=$_POST['onlyshow'];
$anztables=$_POST["anztables"];
$arrdbtables=json_decode($_POST["strdbtables"]);
$strdbtables=$arrdbtables[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strdbtables=$strdbtables.",".$arrdbtables[$tablecount];
}
$arrdbindex=json_decode($_POST["strdbindex"]);
$strdbindex=$arrdbindex[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strdbindex=$strdbindex.",".$arrdbindex[$tablecount];
}
$arrdbbez=json_decode($_POST["strdbbez"]);
$strdbbez=$arrdbbez[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strdbbez=$strdbbez.",".$arrdbbez[$tablecount];
}
$arrvondbtyp=json_decode($_POST["strvondbtyp"]);
$strvondbtyp=$arrvondbtyp[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strvondbtyp=$strvondbtyp.",".$arrvondbtyp[$tablecount];
}
$arrnchdbtyp=json_decode($_POST["strnchdbtyp"]);
$strnchdbtyp=$arrnchdbtyp[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strnchdbtyp=$strnchdbtyp.",".$arrnchdbtyp[$tablecount];
}
$arrvondbname=json_decode($_POST["strvondbname"]);
$strvondbname=$arrvondbname[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strvondbname=$strvondbname.",".$arrvondbname[$tablecount];
}
$arrnchdbname=json_decode($_POST["strnchdbname"]);
$strnchdbname=$arrnchdbname[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strnchdbname=$strnchdbname.",".$arrnchdbname[$tablecount];
}

$arrvondbuser=json_decode($_POST["strvondbuser"]);
$strvondbuser=$arrvondbuser[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strvondbuser=$strvondbuser.",".$arrvondbuser[$tablecount];
}
$arrnchdbuser=json_decode($_POST["strnchdbuser"]);
$strnchdbuser=$arrnchdbuser[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strnchdbuser=$strnchdbuser.",".$arrnchdbuser[$tablecount];
}

$arrvondbpassword=json_decode($_POST["strvondbpassword"]);
$strvondbpassword=$arrvondbpassword[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strvondbpassword=$strvondbpassword.",".$arrvondbpassword[$tablecount];
}
$arrnchdbpassword=json_decode($_POST["strnchdbpassword"]);
$strnchdbpassword=$arrnchdbpassword[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
	 $strnchdbpassword=$strnchdbpassword.",".$arrnchdbpassword[$tablecount];
}

$arrdbnchsyncnr=json_decode($_POST["strdbnchsyncnr"]);
$strdbnchsyncnr=$arrdbnchsyncnr[0];
for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
  $strdbnchsyncnr=$strdbnchsyncnr.",".$arrdbnchsyncnr[$tablecount];
}
$arrcolsel=json_decode($_POST["strcolsel"]);
$arrnchwebsite=json_decode($_POST["strnchwebsite"]);
$strnchwebsite=$arrnchwebsite[0];

bootstraphead();
bootstrapbegin("Datenaustausch");

echo "<a href='".$callbackurl."'  class='btn btn-primary btn-sm active' role='button'>Zurück</a> ";

if ($debug=="J") {
echo "<div class='alert alert-info'>";
echo "<table>";
echo "<tr><td>auto</td><td> : ".$auto."</td></tr>";
echo "<tr><td>callbackurl</td><td> : ".$callbackurl."</td></tr>";
echo "<tr><td>strdbtables</td><td> : ".$strdbtables."</td></tr>";
echo "<tr><td>strdbindex</td><td> : ".$strdbindex."</td></tr>";
echo "<tr><td>strdbbez</td><td> : ".$strdbbez."</td></tr>";
echo "<tr><td>strnchdbtyp</td><td> : ".$strnchdbtyp."</td></tr>";
echo "<tr><td>strnchdbname</td><td> : ".$strnchdbname."</td></tr>";
echo "<tr><td>strnchdbuser</td><td> : ".$strnchdbuser."</td></tr>";
echo "<tr><td>strnchdbpassword</td><td> : ".$strnchdbpassword."</td></tr>";
echo "<tr><td>strvondbtyp</td><td> : ".$strvondbtyp."</td></tr>";
echo "<tr><td>strvondbname</td><td> : ".$strvondbname."</td></tr>";
echo "<tr><td>strvondbuser</td><td> : ".$strvondbuser."</td></tr>";
echo "<tr><td>strvondbpassword</td><td> : ".$strvondbpassword."</td></tr>";
echo "<tr><td>strdbsyncnr</td><td> : ".$strdbnchsyncnr."</td></tr>";
echo "<tr><td>strnchwebsite</td><td> : ".$strnchwebsite."</td></tr>";
echo "</table>";
echo "</div>";
}

echo "<form class='form-horizontal' method='post' action='".$callbackurl."&onlyshow=".$onlyshow."'>";
echo "<input type='hidden' name='status' value='einspielen' />";
echo "<br>";

$db = new SQLite3('../data/'.$database);
echo "<table class='table table-hover'>";
echo "<tr><th>Table</th><th>Index</th><th>Bezeichnung</th><th>NOSYNC</th></tr>";
$arranzds = array();
$arrtblstatus = array();
$arridxds = array();
$arrdaten = array();
for($tablecount = 0; $tablecount < $anztables; $tablecount++) {
  $dbnchopen=dbopentyp($arrnchdbtyp[$tablecount],$arrnchdbname[$tablecount],$arrnchdbuser[$tablecount],$arrnchdbpassword[$tablecount]);
  $column=getdbcolumn($arrnchdbtyp[$tablecount],$arrnchdbname[$tablecount],$arrdbtables[$tablecount],$arrnchdbuser[$tablecount],$arrnchdbpassword[$tablecount]);   
  if ($arrcolsel[$tablecount]==$column) {
    array_push($arrtblstatus,"OK");
    $query="SELECT * FROM tblsyncstatus WHERE fldtable='".$arrdbtables[$tablecount]."' AND flddbsyncnr=".$arrdbnchsyncnr[$tablecount];
    echo "<div class='alert alert-info'>";
    echo $query."<br>";
    echo "</div>";
    $resst = $db->query($query);
    if ($linst = $resst->fetchArray()) {
      $timestamp=$linst['fldtimestamp'];
    } else {
      $timestamp='2015-01-01 00:00:00'; 
    }
 
    $qryval = "SELECT ".$arrcolsel[$tablecount]." FROM ".$arrdbtables[$tablecount]." WHERE flddbsyncstatus='SYNC' AND fldtimestamp>'".$timestamp."'";
    echo "<div class='alert alert-info'>";
    echo $qryval."<br>";
    echo "</div>";
    $resval = dbquerytyp($arrnchdbtyp[$tablecount],$dbnchopen,$qryval);
	//echo $arrnchdbtyp[$tablecount]."<br>";
	$datcnt=0;
    while ($linval = dbfetchtyp($arrnchdbtyp[$tablecount],$resval)) {
	  $datcnt=$datcnt+1;
      $arrcolumn = explode(",", $arrcolsel[$tablecount]);
      for($colcnt = 0; $colcnt < count($arrcolumn); $colcnt++) {
        //$inh="#".$linval[$arrcolumn[$colcnt]]."#";	
		  $inh=$linval[$arrcolumn[$colcnt]];
		  $inh=str_replace(" ","#",$inh);
		  $inh=str_replace("'","",$inh);
		  $inh=utf8_encode($inh);
  	     //echo "<tr><td>".$arrvontables[$tablecount]."</td><td>".$arrcolumn[$colcnt]."</td><td>".$inh."</td><td>_</td></tr>";
        array_push($arrdaten,$inh);
      }
      echo "<tr>";
      echo "<td>".$arrdbtables[$tablecount]."</td>";
      echo "<td>".$linval[$arrdbindex[$tablecount]]."</td>";
      echo "<td>".utf8_encode($linval[$arrdbbez[$tablecount]])."</td>";
      echo "<td><a href='nosync.php?menu=".$menu."&dbindex=".$linval[$arrdbindex[$tablecount]]."' class='btn btn-primary btn-sm active' role='button'>NOSYNC</a></td> ";
      echo "</tr>";
      array_push($arridxds,$linval[$arrdbindex[$tablecount]]);
	}
	array_push($arranzds,$datcnt);
  } else {
    array_push($arrtblstatus,"FEHLER");
    echo "<div class='alert alert-info'>";
    echo "nach:".$column."<br>";
    echo "von :".$arrcolsel[$tablecount]."<br>";
    echo $arrdbtables[$tablecount]." no ok.";
    echo "</div>";
  }  
}
echo "</table>";

echo "<input type='hidden' name='anztables' value=".$anztables." />";
echo "<input type='hidden' name='strdbtables' value=".json_encode($arrdbtables)." />";
echo "<input type='hidden' name='strdbindex' value=".json_encode($arrdbindex)." />";
echo "<input type='hidden' name='strvondbtyp' value=".json_encode($arrvondbtyp)." />";
echo "<input type='hidden' name='strvondbname' value=".json_encode($arrvondbname)." />";
echo "<input type='hidden' name='strvondbuser' value=".json_encode($arrvondbuser)." />";
echo "<input type='hidden' name='strvondbpassword' value=".json_encode($arrvondbpassword)." />";
echo "<input type='hidden' name='strtblstatus' value=".json_encode($arrtblstatus)." />";
echo "<input type='hidden' name='strdbnchsyncnr' value=".json_encode($arrdbnchsyncnr)." />";
echo "<input type='hidden' name='stranzds' value=".json_encode($arranzds)." />";
echo "<input type='hidden' name='stridxds' value=".json_encode($arridxds)." />";
echo "<input type='hidden' name='strdaten' value=".json_encode($arrdaten)." />";
echo "<input type='hidden' name='strnchwebsite' value=".json_encode($arrnchwebsite)." />";
if ($auto=="J") {
  echo "<input type='hidden' name='auto' value=".$auto." />";
  echo "<input type='submit' name='submit' id='btnsubmit' value='Submit' />";
} else {
  echo "<input type='submit' value='Daten einspielen' />";
}
echo "</form>";


bootstrapend();
?>