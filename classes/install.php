<?php
  include("bootstrapfunc.php");
  include("../config.php");
  bootstraphead();
  bootstrapbegin("Installation");
  $dir=getcwd();

  $file = $dir.'/sites/install/'.$database;
  $newfile = $dir.'/data/'.$database;

  if (!copy($file, $newfile)) {
    echo "<div class='alert alert-warning'>";
    echo "copy $file schlug fehl...\n<br>";
    echo "Installation der Datenbank (".$file.") fehlgeschlagen!";
    echo "</div>";
  } else {
    echo "<div class='alert alert-success'>";
    echo "Datenbank erfolgreich installiert. (".$newfile.")";
    echo "</div>";
    echo "<a href='index.php' class='btn btn-primary btn-sm active' role='button'>Neustart</a><br>"; 
  }
  bootstrapend();  
?>