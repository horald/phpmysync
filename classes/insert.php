<?php
include("bootstrapfunc.php");
include("dbtool.php");
include("insertfunc.php");
include("../config.php");
$menu=$_GET['menu'];
$menugrp=$_GET['menugrp'];
//echo $menu."<br>";
include("../sites/views/".$menu."/showtab.inc.php");
bootstraphead();
bootstrapbegin($pararray['headline']);
$insert = $_GET['insert'];
$idwert = $_GET['id'];
if ($insert==1) {
  $show = $_POST['chkanzeigen'];
  //echo $show."=show<br>"; 
  insertsave($pararray,$listarray,$menu,$show,$autoinc_step,$autoinc_start,$menugrp);
  if ($show<>"anzeigen") {
    echo "<meta http-equiv='refresh' content='0; URL=showtab.php?menu=".$menu."&menugrp=".$menugrp."'>";  
  }
} else {
  insertinput($listarray,$idwert,$menu,$menugrp);
}  
bootstrapend();
?>
