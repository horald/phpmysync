<?php
include("bootstrapfunc.php");
include("dbtool.php");
include("../config.php");
$callbackurl=$_POST['callbackurl'];
$auto=$_POST['auto'];

bootstraphead();
bootstrapbegin("Datenaustausch");
$callbackurl=str_replace("sync.php","showtab.php",$callbackurl);

    $arrtblstatus=json_decode($_POST["strtblstatus"]);
    $arrdbnchsyncnr=json_decode($_POST["strdbnchsyncnr"]);
    $db = new SQLite3('../data/'.$database);
    $date = date('Y-m-d');
    $time = date('H:i:s', time());
	$timestamp=$date." ".$time;
    $arrdbtables=json_decode($_POST["strdbtables"]);
    for($tblcnt = 0; $tblcnt < count($arrdbtables); $tblcnt++) {
	  if ($arrtblstatus[$tblcnt]=="OK") {
	    $query="SELECT * FROM tblsyncstatus WHERE fldtable='".$arrdbtables[$tblcnt]."'";
	    //echo $query."<br>";
        $results = $db->query($query);
        if ($line = $results->fetchArray()) {
	      $sql="UPDATE tblsyncstatus SET fldtimestamp='".$timestamp."' WHERE fldtable='".$arrdbtables[$tblcnt]."' AND flddbsyncnr=".$arrdbnchsyncnr[$tblcnt];
	    } else {
	      $sql="INSERT INTO tblsyncstatus (fldtable,fldtimestamp,flddbsyncnr) VALUES('".$arrdbtables[$tblcnt]."','".$timestamp."',".$arrdbnchsyncnr[$tblcnt].")";
	    }
        echo "<div class='alert alert-info'>";
	     echo $sql."<br>";
        echo "</div>";
        dbexecutetyp("SQLITE3",$db,$sql); 
	  } else {
        echo "<div class='alert alert-warning'>";
	    echo $arrdbtables[$tblcnt]." not ok.<br>";
		echo "</div>";
      }	
	}


echo "<a href='".$callbackurl."'  class='btn btn-primary btn-sm active' role='button'>Zurück</a> ";
echo "<div class='alert alert-info'>";
echo "Anzahl Tables:".count($arrdbtables)."<br>";
echo "Timestamp:".$timestamp."<br>";
echo "Datensynchronisation abgeschlossen.";
echo "</div>";
bootstrapend();
if ($auto=="J") {
  echo "<meta http-equiv='refresh' content='0; URL=".$callbackurl."'>";  
}
?>