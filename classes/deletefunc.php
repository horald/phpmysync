<?php
header("content-type: text/html; charset=utf-8");

function deleteinput($database,$pararray,$idwert,$menu) {
  $db = new SQLite3('../data/'.$database);
  $dbtable=$pararray['dbtable'];
  $sql="SELECT * FROM ".$dbtable." WHERE fldindex=".$idwert; 
  $results = $db->query($sql);
  while ($row = $results->fetchArray()) {
    $arr=$row;
  }	
  echo "<a href='showtab.php?menu=".$menu."' class='btn btn-primary btn-sm active' role='button'>Zurück</a>"; 
  echo "<form class='form-horizontal' method='post' action='delete.php?delete=1&menu=".$menu."&id=".$idwert."'>";

  echo "<dl>";
  echo "<dt>'".$arr[1]."' wirklich löschen?</dt>";
  echo "</dl>";

  echo "<input type='hidden' name='fldbez' value='".$arr[1]."'>";
  echo "<dd><input type='submit' value='Löschen' /></dd>";
  echo "</form>";
}

function deletesave($database,$pararray,$idwert,$fldbez,$menu) {
  echo "<a href='showtab.php?menu=".$menu."' class='btn btn-primary btn-sm active' role='button'>Liste</a>"; 
  $db = new SQLite3('../data/'.$database);
  $dbtable=$pararray['dbtable'];
  if ($pararray['dellogical']=="J") {
  	 $sql="UPDATE ".$dbtable." SET flddel='J' WHERE fldindex=".$idwert;
  } else {
    $sql="DELETE FROM ".$dbtable." WHERE fldindex=".$idwert;
  }  
  $db->exec($sql);
  echo "<div class='alert alert-success'>";
  echo "Daten '".$fldbez."' gelöscht.";
  echo "</div>";
}

?>