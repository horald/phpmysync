<?php
include("bootstrapfunc.php");
include("dbtool.php");
include("syncfunc.php");
include("../config.php");
$menu=$_GET['menu'];
$onlyshow=$_GET['onlyshow'];
if ($onlyshow=="") {
  $onlyshow="N";
}	
include("../sites/views/".$menu."/showtab.inc.php");
bootstraphead();
bootstrapbegin("Datenaustausch");
echo "<a href='showtab.php?menu=".$menu."'  class='btn btn-primary btn-sm active' role='button'>Zurück</a> ";
//$debug=$_GET['debug'];
if ($debug<>"J") {
	$debug="N";
}	
$status=$_POST['status'];
//echo $status."=status<br>";
switch ( $status ) {
  case 'sync':
    $typ=$_POST['typ'];
    if ($typ=="local") { 	
      auslesen($menu,$database,$onlyshow);
    } else {
      fernabfrage($menu,$onlyshow,$debug);
    }
  break;
  case 'senden':
    $datcnt=$_POST['datcnt'];
    $dbtable=$_POST['dbtable'];
    $dbvontyp=$_POST['dbvontyp'];
    syncsenden($database,$datcnt,$dbvontyp,$dbtable,$debug);
  break;
  case 'einspielen':
    einspielen($menu,$onlyshow);
  break;
  case 'fertig':
    abschliessen($menu,$onlyshow,$database);
  break;
  default:
    showauswahl($menu,$database,$onlyshow,$debug);
}
bootstrapend();
?>