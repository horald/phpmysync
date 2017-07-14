<?php
header("content-type: text/html; charset=utf-8");

function showauswahl($menu,$database,$onlyshow,$debug) {
  $db = new SQLite3('../data/'.$database);
  $qry = "SELECT * FROM tbltable";
  $results = $db->query($qry);
  $anztables=0;
  $arrdbtables = array();
  $arrdbindex = array();
  $arrdbbez = array();
  $arrnchwebsite = array();
  $arrvondbtyp = array();
  $arrnchdbtyp = array();
  $arrvondbname = array();
  $arrnchdbname = array();
  $arrvondbuser = array();
  $arrnchdbuser = array();
  $arrvondbpassword = array();
  $arrnchdbpassword = array();
  $arrdbvonsyncnr = array();
  $arrdbnchsyncnr = array();
  while ($line = $results->fetchArray()) {
    $aktiv=$line['fldaktiv'];
    if ($aktiv=='J') {
      $anztables=$anztables+1;
      array_push($arrdbtables,$line['flddbtblname']);
      array_push($arrdbindex,$line['flddbtblindex']);
      array_push($arrdbbez,$line['flddbtblbez']);
      $qrydb = "SELECT * FROM tbldatabase WHERE fldindex=".$line['fldid_vondatabase'];
      $resdb = $db->query($qrydb);
      $lindb = $resdb->fetchArray();
      array_push($arrvondbtyp,$lindb['flddbtyp']);
      array_push($arrvondbname,$lindb['fldbez']);
      array_push($arrvondbuser,$lindb['flddbuser']);
      array_push($arrvondbpassword,$lindb['flddbpassword']);
      array_push($arrdbvonsyncnr,$lindb['flddbsyncnr']);
      $qrydb = "SELECT * FROM tbldatabase WHERE fldindex=".$line['fldid_nachdatabase'];
      //echo $qrydb."<br>";
      $resdb = $db->query($qrydb);
      $lindb = $resdb->fetchArray();
      //echo $lindb['fldpfad'];
      array_push($arrnchwebsite,$lindb['fldpfad']);
      array_push($arrnchdbtyp,$lindb['flddbtyp']);
      array_push($arrnchdbname,$lindb['fldbez']);
      array_push($arrnchdbuser,$lindb['flddbuser']);
      array_push($arrnchdbpassword,$lindb['flddbpassword']);
      array_push($arrdbnchsyncnr,$lindb['flddbsyncnr']);
    }
  }  

  if ($anztables==0) {
    echo "<div class='alert alert-warning'>";
    echo "Keine Tables zum synchronisieren aktiviert.";
    echo "</div>";
  } else {
    $strdbtables=json_encode($arrdbtables);
    $strdbindex=json_encode($arrdbindex);
    $strdbbez=json_encode($arrdbbez);
    $strnchwebsite=json_encode($arrnchwebsite);
    $strvondbtyp=json_encode($arrvondbtyp);
    $strnchdbtyp=json_encode($arrnchdbtyp);
    $strvondbname=json_encode($arrvondbname);
    $strnchdbname=json_encode($arrnchdbname);
    $strvondbuser=json_encode($arrvondbuser);
    $strnchdbuser=json_encode($arrnchdbuser);
    $strvondbpassword=json_encode($arrvondbpassword);
    $strnchdbpassword=json_encode($arrnchdbpassword);
    $strdbvonsyncnr=json_encode($arrdbvonsyncnr);
    $strdbnchsyncnr=json_encode($arrdbnchsyncnr);
    $value="Anzeige starten";
    if ($onlyshow=="N") {
      $value="Sync starten";
    }
	$auto=$_GET['auto'];
	//echo $auto."=auto<br>";
	if ($_GET['local']=="J") {
	  $synctyp='local';
	} else {
	  $synctyp='remote';
	}
    echo "<form class='form-horizontal' method='post' action='sync.php?showsync=1&menu=".$menu."&onlyshow=".$onlyshow."&debug=".$debug."'>";
    echo "<select name='typ' size='1'>";
	if ($synctyp=="local") {
      echo "<option style='background-color:#c0c0c0;' selected>local</option>";
      echo "<option style='background-color:#c0c0c0;' >remote</option>";
	} else {
      echo "<option style='background-color:#c0c0c0;' >local</option>";
      echo "<option style='background-color:#c0c0c0;' selected>remote</option>";
	}
    echo "</select>";
    echo "<input type='hidden' name='status' value='sync' />";
    echo "<input type='hidden' name='anztables' value=".$anztables." />";
    echo "<input type='hidden' name='strdbtables' value=".$strdbtables." />";
    echo "<input type='hidden' name='strdbindex' value=".$strdbindex." />";
    echo "<input type='hidden' name='strdbbez' value=".$strdbbez." />";
    echo "<input type='hidden' name='strnchwebsite' value=".$strnchwebsite." />";
    echo "<input type='hidden' name='strvondbtyp' value=".$strvondbtyp." />";
    echo "<input type='hidden' name='strnchdbtyp' value=".$strnchdbtyp." />";
    echo "<input type='hidden' name='strvondbname' value=".$strvondbname." />";
    echo "<input type='hidden' name='strnchdbname' value=".$strnchdbname." />";
    echo "<input type='hidden' name='strvondbuser' value=".$strvondbuser." />";
    echo "<input type='hidden' name='strnchdbuser' value=".$strnchdbuser." />";
    echo "<input type='hidden' name='strvondbpassword' value=".$strvondbpassword." />";
    echo "<input type='hidden' name='strnchdbpassword' value=".$strnchdbpassword." />";
    echo "<input type='hidden' name='strdbvonsyncnr' value=".$strdbvonsyncnr." />";
    echo "<input type='hidden' name='strdbnchsyncnr' value=".$strdbnchsyncnr." />";
	if ($auto=="J") {
      echo "<input type='hidden' name='auto' value=".$auto." />";
      echo "<input type='submit' name='submit' id='btnsubmit' value='Submit' />";
	} else {
      echo "<dd><input type='submit' value='".$value."' /></dd>";
	}
    echo "</form>";
  }	
  
}
 

function auslesen($menu,$database,$onlyshow) {

  $auto=$_POST["auto"];
  $aktpfad=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];  
  $callbackurl="http://".$aktpfad."?menu=".$menu;
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
  	 $strdbez=$strdbbez.",".$arrdbbez[$tablecount];
  }
  $arrnchwebsite=json_decode($_POST["strnchwebsite"]);
  $strnchwebsite=$arrnchwebsite[0];
  for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
  	 $strnchwebsite=$strnchwebsite.",".$arrnchwebsite[$tablecount];
  }
  $website=$arrnchwebsite[0]."classes/syncremote.php?menu=".$menu;
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
  $arrdbvonsyncnr=json_decode($_POST["strdbvonsyncnr"]);
  $strdbvonsyncnr=$arrdbvonsyncnr[0];
  for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
  	 $strdbvonsyncnr=$strdbvonsyncnr.",".$arrdbvonsyncnr[$tablecount];
  }
  
  echo "<div class='alert alert-info'>";
  echo "<table>";
  echo "<tr><td>Funktion</td><td> : Auslesen</td></tr>";
  echo "<tr><td>website</td><td> : ".$website."</td></tr>";
  echo "<tr><td>anztables</td><td> : ".$anztables."</td></tr>";
  echo "<tr><td>strdbtables</td><td> : ".$strdbtables."</td></tr>";
  echo "<tr><td>strdbindex</td><td> : ".$strdbindex."</td></tr>";
  echo "<tr><td>strdbbez</td><td> : ".$strdbbez."</td></tr>";
  echo "<tr><td>strvondbtyp</td><td> : ".$strvondbtyp."</td></tr>";
  echo "<tr><td>strvondbname</td><td> : ".$strvondbname."</td></tr>";
  echo "<tr><td>strvondbuser</td><td> : ".$strvondbuser."</td></tr>";
  echo "<tr><td>strvondbpassword</td><td> : ".$strvondbpassword."</td></tr>";
  echo "<tr><td>strnchdbtyp</td><td> : ".$strnchdbtyp."</td></tr>";
  echo "<tr><td>strnchdbname</td><td> : ".$strnchdbname."</td></tr>";
  echo "<tr><td>strnchdbuser</td><td> : ".$strnchdbuser."</td></tr>";
  echo "<tr><td>strnchdbpassword</td><td> : ".$strnchdbpassword."</td></tr>";
  echo "<tr><td>strdbsyncnr</td><td> : ".$strdbvonsyncnr."</td></tr>";

  $arrcolsel=array();
  for($tablecount = 0; $tablecount < $anztables; $tablecount++) {
    $column=getdbcolumn($arrvondbtyp[$tablecount],$arrvondbname[$tablecount],$arrdbtables[$tablecount],$arrvondbuser[$tablecount],$arrvondbpassword[$tablecount]);   
    array_push($arrcolsel,$column);
  }

  echo "</table>";
  echo "</div>";
  
  echo "<form class='form-horizontal' method='post' action='".$website."'>";
  echo "<input type='hidden' name='callbackurl' value='".$callbackurl."' />";
  echo "<input type='hidden' name='onlyshow' value='".$onlyshow."' />";
  echo "<input type='hidden' name='action' value='einspielen' />";
  echo "<input type='hidden' name='anztables' value=".$anztables." />";
  echo "<input type='hidden' name='strnchdbtyp' value=".json_encode($arrnchdbtyp)." />";
  echo "<input type='hidden' name='strnchdbname' value=".json_encode($arrnchdbname)." />";
  echo "<input type='hidden' name='strnchdbuser' value=".json_encode($arrnchdbuser)." />";
  echo "<input type='hidden' name='strnchdbpassword' value=".json_encode($arrnchdbpassword)." />";
  echo "<input type='hidden' name='strdbtables' value=".json_encode($arrdbtables)." />";
  echo "<input type='hidden' name='strdbindex' value=".json_encode($arrdbindex)." />";
  echo "<input type='hidden' name='strcolsel' value=".json_encode($arrcolsel)." />";
  echo "<input type='hidden' name='strdbvonsyncnr' value=".json_encode($arrdbvonsyncnr)." />";
  if ($auto=="J") {
    echo "<input type='hidden' name='auto' value=".$auto." />";
    echo "<input type='submit' name='submit' id='btnsubmit' value='Submit' />";
  } else {
    echo "<input type='submit' value='Daten einspielen' /><br>";
  }
  
  echo "<table class='table table-hover'>";
  echo "<tr><th>Table</th><th>Index</th><th>Bezeichnung</th><th>NOSYNC</th></tr>";
  $arranzds = array();
  $arridxds = array();
  $arrdaten = array();
  $db = new SQLite3('../data/'.$database);
  for($tablecount = 0; $tablecount < $anztables; $tablecount++) {
    $query="SELECT * FROM tblsyncstatus WHERE fldtable='".$arrdbtables[$tablecount]."' AND flddbsyncnr=".$arrdbvonsyncnr[$tablecount];
	//echo $query."<br>";
    $resst = $db->query($query);
    if ($linst = $resst->fetchArray()) {
	  $timestamp=$linst['fldtimestamp'];
	} else {
      $timestamp='2015-01-01 00:00:00'; 
	}
	//echo $timestamp."=timestamp<br>";
    $dbvonopen=dbopentyp($arrvondbtyp[$tablecount],$arrvondbname[$tablecount],$arrvondbuser[$tablecount],$arrvondbpassword[$tablecount]);
    $qryval = "SELECT ".$arrcolsel[$tablecount]." FROM ".$arrdbtables[$tablecount]." WHERE flddbsyncstatus='SYNC' AND fldtimestamp>'".$timestamp."'";
	//echo $qryval."<br>";
    $resval = dbquerytyp($arrvondbtyp[$tablecount],$dbvonopen,$qryval);
	$datcnt=0;
    while ($linval = dbfetchtyp($arrvondbtyp[$tablecount],$resval)) {
	   $datcnt=$datcnt+1;
	   $arrcolumn = explode(",", $arrcolsel[$tablecount]);
      for($colcnt = 0; $colcnt < count($arrcolumn); $colcnt++) {
        //$inh="#".$linval[$arrcolumn[$colcnt]]."#";	
		$inh=$linval[$arrcolumn[$colcnt]];
		$inh=str_replace(" ","#",$inh);
                $inh=str_replace("<br>","+br+",$inh); 
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
	 //echo $datcnt."=anzds<br>";
	 array_push($arranzds,$datcnt);
  }	
  echo "</table>";  
  //$strdaten=json_encode($arrdaten);
  //echo "<div class='alert alert-info'>";
  //echo $strdaten."=strdaten<br>";
  //echo "</div>";
  echo "<input type='hidden' name='stranzds' value=".json_encode($arranzds)." />";
  echo "<input type='hidden' name='stridxds' value=".json_encode($arridxds)." />";
  echo "<input type='hidden' name='strdaten' value=".json_encode($arrdaten)." />";
  
  echo "</form>";
}

function fernabfrage($menu,$onlyshow,$debug) {

  $auto=$_POST["auto"];
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
  $arrcolsel=array();
  for($tablecount = 0; $tablecount < $anztables; $tablecount++) {
    $column=getdbcolumn($arrvondbtyp[$tablecount],$arrvondbname[$tablecount],$arrdbtables[$tablecount],$arrvondbuser[$tablecount],$arrvondbpassword[$tablecount]);   
    array_push($arrcolsel,$column);
	//echo $column."<br>";
    //echo "<tr><td>strdatenstruc</td><td> : ".$column."</td></tr>";
  }
  
  $arrnchwebsite=json_decode($_POST["strnchwebsite"]);
  $website=$arrnchwebsite[0]."classes/syncauslesen.php?menu=".$menu."&debug=".$debug;
  $aktpfad=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];  
  $callbackurl="http://".$aktpfad."?menu=".$menu;

  echo "<div class='alert alert-info'>";
  echo "<table>";
  echo "<tr><td>Function</td><td> : Fernabfrage</td></tr>";
  if ($debug=="J") {
    echo "<tr><td>website</td><td> : ".$website."</td></tr>";
    echo "<tr><td>callbackurl</td><td> : ".$callbackurl."</td></tr>";
    echo "<tr><td>menu</td><td> : ".$menu."</td></tr>";
    echo "<tr><td>auto</td><td> : ".$auto."</td></tr>";
    echo "<tr><td>onlyshow</td><td> : ".$onlyshow."</td></tr>";
    echo "<tr><td>Anz Tables</td><td> : ".$anztables."</td></tr>";
    echo "<tr><td>strdbtables</td><td> : ".$strdbtables."</td></tr>";
    echo "<tr><td>strdbindex</td><td> : ".$strdbindex."</td></tr>";
    echo "<tr><td>strdbbez</td><td> : ".$strdbbez."</td></tr>";
    echo "<tr><td>strvondbtyp</td><td> : ".$strvondbtyp."</td></tr>";
    echo "<tr><td>strvondbname</td><td> : ".$strvondbname."</td></tr>";
    echo "<tr><td>strvondbuser</td><td> : ".$strvondbuser."</td></tr>";
    echo "<tr><td>strvondbpassword</td><td> : ".$strvondbpassword."</td></tr>";
    echo "<tr><td>strnchdbtyp</td><td> : ".$strnchdbtyp."</td></tr>";
    echo "<tr><td>strnchdbname</td><td> : ".$strnchdbname."</td></tr>";
    echo "<tr><td>strnchdbuser</td><td> : ".$strnchdbuser."</td></tr>";
    echo "<tr><td>strnchdbpassword</td><td> : ".$strnchdbpassword."</td></tr>";
    echo "<tr><td>strdbsyncnr</td><td> : ".$strdbnchsyncnr."</td></tr>";
  }
  echo "</table>";
  echo "</div>";
  
  echo "<form class='form-horizontal' method='post' action='".$website."'>";
  echo "<input type='hidden' name='callbackurl' value='".$callbackurl."' />";
  echo "<input type='hidden' name='onlyshow' value='".$onlyshow."' />";
  echo "<input type='hidden' name='anztables' value='".$anztables."' />";
  echo "<input type='hidden' name='strdbtables' value='".json_encode($arrdbtables)."' />";
  echo "<input type='hidden' name='strdbindex' value='".json_encode($arrdbindex)."' />";
  echo "<input type='hidden' name='strdbbez' value='".json_encode($arrdbbez)."' />";
  echo "<input type='hidden' name='strnchdbtyp' value='".json_encode($arrnchdbtyp)."' />";
  echo "<input type='hidden' name='strnchdbname' value='".json_encode($arrnchdbname)."' />";
  echo "<input type='hidden' name='strnchdbuser' value='".json_encode($arrnchdbuser)."' />";
  echo "<input type='hidden' name='strnchdbpassword' value='".json_encode($arrnchdbpassword)."' />";
  echo "<input type='hidden' name='strvondbtyp' value='".json_encode($arrvondbtyp)."' />";
  echo "<input type='hidden' name='strvondbname' value='".json_encode($arrvondbname)."' />";
  echo "<input type='hidden' name='strvondbuser' value='".json_encode($arrvondbuser)."' />";
  echo "<input type='hidden' name='strvondbpassword' value='".json_encode($arrvondbpassword)."' />";
  echo "<input type='hidden' name='strdbnchsyncnr' value='".json_encode($arrdbnchsyncnr)."' />";
  echo "<input type='hidden' name='strcolsel' value='".json_encode($arrcolsel)."' />";
  echo "<input type='hidden' name='strnchwebsite' value='".json_encode($arrnchwebsite)."' />";
  echo "<input type='hidden' name='action' value='auslesen' />";
  if ($auto=="J") {
    echo "<input type='hidden' name='auto' value=".$auto." />";
    echo "<input type='submit' name='submit' id='btnsubmit' value='Submit' />";
  } else {
    echo "<input type='submit' value='Daten auslesen' />";
  }	
  echo "</form>";
  
}

function einspielen($menu,$onlyshow) {
  $auto=$_POST["auto"];
  $anztables=$_POST["anztables"];
  $arrdbtables=json_decode($_POST["strdbtables"]);
  $arrdbindex=json_decode($_POST["strdbindex"]);
  $arrvondbtyp=json_decode($_POST["strvondbtyp"]);
  $arrvondbname=json_decode($_POST["strvondbname"]);
  $arrvondbuser=json_decode($_POST["strvondbuser"]);
  $arrvondbpassword=json_decode($_POST["strvondbpassword"]);
  $arrtblstatus=json_decode($_POST["strtblstatus"]);
  $arrdbnchsyncnr=json_decode($_POST["strdbnchsyncnr"]);
  $arranzds=json_decode($_POST["stranzds"]);
  $arridxds=json_decode($_POST["stridxds"]);
  $arrdaten=json_decode($_POST["strdaten"]);
  $arrnchwebsite=json_decode($_POST["strnchwebsite"]);
  echo "<div class='alert alert-info'>";
  echo "<table>";
  echo "<tr><td>Funktion</td><td>:</td><td> einspielen</td></tr>";
  echo "<tr><td>onlyshow</td><td>:</td><td> ".$onlyshow."</td></tr>";
  echo "<tr><td>Anzahl Tables</td><td>:</td><td> ".$anztables."</td></tr>";
  //$idxcnt=0;
  //$idxcol=0;
  for($tablecount = 0; $tablecount < $anztables; $tablecount++) {
    $idxcnt=0;
    $idxcol=0;
    if ($arrtblstatus[$tablecount]=="OK") {
      $dbvonopen=dbopentyp($arrvondbtyp[$tablecount],$arrvondbname[$tablecount],$arrvondbuser[$tablecount],$arrvondbpassword[$tablecount]);
      $column=getdbcolumn($arrvondbtyp[$tablecount],$arrvondbname[$tablecount],$arrdbtables[$tablecount],$arrvondbuser[$tablecount],$arrvondbpassword[$tablecount]);   
      $arrcolumn = explode(",", $column);
      echo "<tr><td>Anzahl DS</td><td>:</td><td> ".$arranzds[$tablecount]."</td></tr>";
      for( $i=1; $i <= $arranzds[$tablecount]; $i++ ) {
        $qryval="SELECT * FROM ".$arrdbtables[$tablecount]." WHERE ".$arrdbindex[$tablecount]."=".$arridxds[$idxcnt];
        echo "<tr><td>Abfrage</td><td>:</td><td> ".$qryval."</td></tr>";
	     //echo $qryval."<br>";
        $resval = dbquerytyp($arrvondbtyp[$tablecount],$dbvonopen,$qryval);
        if ($linval = dbfetchtyp($arrvondbtyp[$tablecount],$resval)) {
          $upd=$arrcolumn[0]."='".str_replace("#"," ",$arrdaten[$idxcol])."'";
          for( $colidx=1; $colidx <count($arrcolumn); $colidx++) {
          	$idxcol=$idxcol+1;
          	$upd=$upd.", ".$arrcolumn[$colidx]."='".str_replace("#"," ",$arrdaten[$idxcol])."'";
          }
          $pos=strpos($upd,",");
          $len=strlen($upd);
          $upd=substr($upd,$pos,$len-$pos);
          $upd=$arrdbindex[$tablecount]."=".$arridxds[$idxcnt].$upd;
		    $sql="UPDATE ".$arrdbtables[$tablecount]." SET ".$upd." WHERE ".$arrdbindex[$tablecount]."=".$arridxds[$idxcnt];
		  } else {
          for( $colidx=0; $colidx <count($arrcolumn); $colidx++) {
            $idxcol=$idxcol+1;
            if ($colidx==0) {
              $ins="'".str_replace("#"," ",$arrdaten[$idxcol-1])."'";
            } else {
              $ins=$ins.", '".str_replace("#"," ",$arrdaten[$idxcol-1])."'";
            }  
          }
         $idxcol=$idxcol-1;
         //$pos=strpos($ins,",");
          //$len=strlen($ins);
          //$ins=substr($ins,$pos,$len-$pos);
          //$ins=$arridxds[$idxcnt].$ins;
          //echo $pos.",".$len."=strpos<br>";
		    $sql="INSERT INTO ".$arrdbtables[$tablecount]." (".$column.") VALUES(".$ins.")";
		  }
        echo "<tr><td>Statement</td><td>:</td><td> ".$sql."</td></tr>";
        if ($arrvondbtyp[$tablecount]=="MYSQL") {
          echo "<tr><td>Set</td><td>:</td><td>utf8</td></tr>";
		    mysqli_query($dbvonopen,"SET NAMES 'utf8'");
		  }  
        dbexecutetyp($arrvondbtyp[$tablecount],$dbvonopen,$sql); 
        $idxcol=$idxcol+1;
     	  $idxcnt=$idxcnt+1;
	  }	
	}
  }
  $website=$arrnchwebsite[0]."classes/syncfertig.php?menu=".$menu;
  echo "<tr><td>website</td><td>:</td><td> ".$website."</td></tr>";
  echo "</table>";
  echo "</div>";
  $aktpfad=$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];  
  $callbackurl="http://".$aktpfad."?menu=".$menu;
  
  $onlyshow="";
  echo "<form class='form-horizontal' method='post' action='".$website."'>";
  echo "<input type='hidden' name='callbackurl' value='".$callbackurl."' />";
  echo "<input type='hidden' name='strdbtables' value='".json_encode($arrdbtables)."' />";
  echo "<input type='hidden' name='strtblstatus' value='".json_encode($arrtblstatus)."' />";
  echo "<input type='hidden' name='strdbnchsyncnr' value='".json_encode($arrdbnchsyncnr)."' />";
  if ($auto=="J") {
    echo "<input type='hidden' name='auto' value=".$auto." />";
    echo "<input type='submit' name='submit' id='btnsubmit' value='Submit' />";
  } else {
    echo "<input type='submit' value='Daten abschließen' />";
  }
  echo "</form>";
  
}

function abschliessen($menu,$onlyshow,$database) {
  echo "<br>";	
  echo "<div class='alert alert-info'>";
  if ($onlyshow=="J") {
    echo "Datenanzeige abgeschlossen.<br>";	
  } else {
    $arrtblstatus=json_decode($_POST["strtblstatus"]);
    $arrdbvonsyncnr=json_decode($_POST["strdbvonsyncnr"]);
    $db = new SQLite3('../data/'.$database);
    $date = date('Y-m-d');
    $time = date('H:i:s', time());
	$timestamp=$date." ".$time;
    $arrdbtables=json_decode($_POST["strdbtables"]);
	echo count($arrdbtables)."=anztable<br>";
    for($tblcnt = 0; $tblcnt < count($arrdbtables); $tblcnt++) {
	  if ($arrtblstatus[$tblcnt]=="OK") {
	    $query="SELECT * FROM tblsyncstatus WHERE fldtable='".$arrdbtables[$tblcnt]."'";
	    echo $query."<br>";
        $results = $db->query($query);
        if ($line = $results->fetchArray()) {
	      $sql="UPDATE tblsyncstatus SET fldtimestamp='".$timestamp."' WHERE fldtable='".$arrdbtables[$tblcnt]."' AND flddbsyncnr=".$arrdbvonsyncnr[$tblcnt];
	    } else {
	      $sql="INSERT INTO tblsyncstatus (fldtable,fldtimestamp,flddbsyncnr) VALUES('".$arrdbtables[$tblcnt]."','".$timestamp."',".$arrdbvonsyncnr[$tblcnt].")";
	    }
	    echo $sql."<br>";
        dbexecutetyp("SQLITE3",$db,$sql); 
	  } else {
	    echo $arrdbtables[$tblcnt]." not ok.<br>";
      }	  
	}
    echo "Timestamp:".$timestamp."<br>";
    echo "Datensynchronisation abgeschlossen.<br>";	
	$auto=$_POST["auto"];
	if ($auto=="J") {
      echo "<meta http-equiv='refresh' content='0; URL=showtab.php?menu=".$menu."'>";  
	}
  }
  echo "onlyshow=".$onlyshow."<br>";	
  echo "</div>";
}

?>