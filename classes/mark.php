<?php
$menu=$_GET['menu'];
include("../sites/views/".$menu."/showtab.inc.php");
include("../config.php");
$id=$_GET['id'];
//echo $menu.",".$id."<br>";
$db = new SQLite3('../data/'.$database);
$status="J";
$sql="SELECT * FROM ".$pararray['dbtable']." WHERE fldindex=".$id;
//echo $sql."<br>";
$results = $db->query($sql);
while ($row = $results->fetchArray()) {
  $arr=$row;
}
if ($arr['fldaktiv']=="J") {
  $status="N";
}	
$sql="UPDATE ".$pararray['dbtable']." SET fldaktiv='".$status."' WHERE fldindex=".$id;
echo $sql."<br>";
$query = $db->exec($sql);
echo "<meta http-equiv='refresh' content='0; URL=showtab.php?menu=".$menu."'>";  
?>