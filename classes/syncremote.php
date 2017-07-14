<?php
include("bootstrapfunc.php");
include("dbtool.php");
$auto=$_POST['auto'];
$action=$_POST['action'];
$onlyshow=$_POST['onlyshow'];
$callbackurl=$_POST['callbackurl'];
$anztables=$_POST['anztables'];
bootstraphead();
bootstrapbegin("Datenaustausch");

  $arrnchdbtyp=json_decode($_POST["strnchdbtyp"]);
  $strnchdbtyp=$arrnchdbtyp[0];
  for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
  	 $strnchdbtyp=$strnchdbtyp.",".$arrnchdbtyp[$tablecount];
  }
  $arrnchdbname=json_decode($_POST["strnchdbname"]);
  $strnchdbname=$arrnchdbname[0];
  for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
  	 $strnchdbname=$strnchdbname.",".$arrnchdbname[$tablecount];
  }
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
  $arranzds=json_decode($_POST["stranzds"]);
  $stranzds=$arranzds[0];
  for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
  	 $stranzds=$stranzds.",".$arranzds[$tablecount];
  }
  $arridxds=json_decode($_POST["stridxds"]);
  $arrdaten=json_decode($_POST["strdaten"]);
  //echo $_POST["strdaten"]."=daten<br>";
  //echo count($arrdaten)."=count<br>";
  $arrcolsel=json_decode($_POST["strcolsel"]);
  $arrnchdbuser=json_decode($_POST["strnchdbuser"]);
  $strnchdbuser=$arrnchdbuser[0];
  for($tablecount = 1; $tablecount < $anztables; $tablecount++) {
  	 $strnchdbuser=$strnchdbuser.",".$arrnchdbuser[$tablecount];
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
  
  echo "<a href='".$callbackurl."'  class='btn btn-primary btn-sm active' role='button'>Zurück</a> ";
  echo "<div class='alert alert-success'>";
  echo "Remotezugang";
  echo "</div>";

  echo "<div class='alert alert-info'>";
  echo "<table>";
  echo "<tr><td>callbackurl</td><td> : ".$callbackurl."</td></tr>";
  echo "<tr><td>action</td><td> : ".$action."</td></tr>";
  echo "<tr><td>onlyshow</td><td> : ".$onlyshow."</td></tr>";
  echo "<tr><td>anztables</td><td> : ".$anztables."</td></tr>";
  echo "<tr><td>stranzds</td><td> : ".$stranzds."</td></tr>";
  echo "<tr><td>strnchdbtyp</td><td> : ".$strnchdbtyp."</td></tr>";
  echo "<tr><td>strnchdbname</td><td> : ".$strnchdbname."</td></tr>";
  echo "<tr><td>strnchdbuser</td><td> : ".$strnchdbuser."</td></tr>";
  echo "<tr><td>strnchdbpassword</td><td> : ".$strnchdbpassword."</td></tr>";
  echo "<tr><td>strdbtables</td><td> : ".$strdbtables."</td></tr>";
  echo "<tr><td>strdbindex</td><td> : ".$strdbindex."</td></tr>";
  echo "<tr><td>strdbsyncnr</td><td> : ".$strdbvonsyncnr."</td></tr>";
  echo "</table>";
  echo "</div>";
  //echo $anztables."=anztables<br>";

  $idxcnt=0;
  $idxcol=0;
  $arrtblstatus=array();
  for($tablecount = 0; $tablecount < $anztables; $tablecount++) {
    //echo $tablecount.",".$arrnchdbtyp[$tablecount].",".$arrnchdbname[$tablecount]."=tablecount<br>"; 
    $dbnchopen=dbopentyp($arrnchdbtyp[$tablecount],$arrnchdbname[$tablecount],$arrnchdbuser[$tablecount],$arrnchdbpassword[$tablecount]);
    $nchcolumn=getdbcolumn($arrnchdbtyp[$tablecount],$arrnchdbname[$tablecount],$arrdbtables[$tablecount],$arrnchdbuser[$tablecount],$arrnchdbpassword[$tablecount]);   
    //echo "nch:".$nchcolumn."<br>";
  	//echo "von:".$arrcolsel[$tablecount]."<br>";
    //echo $arrnchdbtyp[$tablecount]."<br>";   
    if ($nchcolumn==$arrcolsel[$tablecount]) { 
      $tblstatus="OK";
      for( $i=1; $i <= $arranzds[$tablecount]; $i++ ) {
        //$qryval = "SELECT * FROM ".$dbtable." WHERE ".$fldindex."=".$index;
        $arrcolumn = explode(",", $arrcolsel[$tablecount]);
        $qryval = "SELECT ".$arrcolsel[$tablecount]." FROM ".$arrdbtables[$tablecount]." WHERE ".$arrdbindex[$tablecount]."=".$arridxds[$idxcnt];
	    //echo $qryval."<br>";
		 
        $resval = dbquerytyp($arrnchdbtyp[$tablecount],$dbnchopen,$qryval);
        
        if ($linval = dbfetchtyp($arrnchdbtyp[$tablecount],$resval)) {
          $upd=$arrcolumn[0]."='".str_replace("#"," ",$arrdaten[$idxcol])."'";
          for( $colidx=1; $colidx <count($arrcolumn); $colidx++) {
          	$idxcol=$idxcol+1;
          	$upd=$upd.", ".$arrcolumn[$colidx]."='".utf8_decode(str_replace("#"," ",$arrdaten[$idxcol]))."'";
          }
          $sql="UPDATE ".$arrdbtables[$tablecount]." SET ".$upd." WHERE ".$arrdbindex[$tablecount]."=".$arridxds[$idxcnt];
        } else {
          $idxcol=$idxcol+1;
          $ins="'".str_replace("#"," ",$arrdaten[$idxcol-1])."'";
          for( $colidx=1; $colidx <count($arrcolumn); $colidx++) {
          	$idxcol=$idxcol+1;
          	$ins=$ins.",'".str_replace("#"," ",$arrdaten[$idxcol-1])."'";
          }	
          $sql="INSERT INTO ".$arrdbtables[$tablecount]." (".$arrcolsel[$tablecount].") VALUES(".$ins.")";	
        }
        echo "<div class='alert alert-info'>";
        echo $sql."<br>";
        echo "</div>";
        dbexecutetyp($arrnchdbtyp[$tablecount],$dbnchopen,$sql); 
		
		
     	$idxcnt=$idxcnt+1;
      
      }

	} else {
	  $tblstatus="FEHLER";
      $arrcolumn = explode(",", $arrcolsel[$tablecount]);
      $idxcol=$idxcol+count($arrcolumn);
      echo "<div class='alert alert-warning'>";
      //echo $idxcol."=idxcol<br>";    
      echo "nch:".$nchcolumn."<br>";
  	  echo "von:".$arrcolsel[$tablecount]."<br>";
	  echo "</div>";
	}
	array_push($arrtblstatus,$tblstatus);
	
  }
  
  echo "<form class='form-horizontal' method='post' action='".$callbackurl."&onlyshow=".$onlyshow."'>";
  if ($action=="einspielen") {
    echo "<input type='hidden' name='status' value='fertig' />";
	echo "<input type='hidden' name='strdbtables' value=".$_POST["strdbtables"]." />";
	echo "<input type='hidden' name='strtblstatus' value=".json_encode($arrtblstatus)." />";
	echo "<input type='hidden' name='strdbvonsyncnr' value=".json_encode($arrdbvonsyncnr)." />";
    if ($auto=="J") {
      echo "<input type='hidden' name='auto' value=".$auto." />";
      echo "<input type='submit' name='submit' id='btnsubmit' value='Submit' />";
	} else {
      echo "<input type='submit' value='Daten fertig' />";
	}
  } else {
    echo "<input type='hidden' name='status' value='einspielen' />";
    echo "<input type='submit' value='Daten einspielen' />";
  }  
  echo "</form>";

bootstrapend();
?>