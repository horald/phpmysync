<?php
session_start();
$menu=$_SESSION['menu'];
//echo $menu."=menu<br>";
$db = new SQLite3('../data/joorgsqlite.db');
echo "Helpindex - php<br><br>";
$query="SELECT * FROM tblhelppage ORDER BY fldpageno";
$results = $db->query($query);
echo "<ul>";
while ($row = $results->fetchArray()) {
  echo "<li><a href='help.php?menu=".$menu."&pagename=".$row['fldpagename']."'>".$row['fldheadline']."</a></li>";
}
echo "</ul>";
?>