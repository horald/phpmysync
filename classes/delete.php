<?php
include("bootstrapfunc.php");
include("deletefunc.php");
include("../config.php");
$menu=$_GET['menu'];
//echo $menu."<br>";
include("../sites/views/".$menu."/showtab.inc.php");
bootstraphead();
bootstrapbegin($pararray['headline']);
$delete = $_GET['delete'];
$idwert = $_GET['id'];
if ($delete==1) {
  $fldbez = $_POST['fldbez'];
  deletesave($database,$pararray,$idwert,$fldbez,$menu);
  echo "<meta http-equiv='refresh' content='0; URL=showtab.php?menu=".$menu."'>";  
} else {
  deleteinput($database,$pararray,$idwert,$menu);
}  
bootstrapend();
?>
