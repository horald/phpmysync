<?php

function bootstraphead($loadbootstrap) {
include("../config.php");
echo "<!DOCTYPE html>";
echo "<html lang='de'>";
echo "<head>";
echo "<meta charset='utf-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>";
echo "<link href='../includes/bootstrap/css/bootstrap.css' rel='stylesheet'>";
echo "<link href='../includes/bootstrap/datetime/css/bootstrap-datetimepicker.min.css' rel='stylesheet' media='screen'>";
echo "<script src='../includes/bootstrap/js/jquery-latest.js'></script>";

//autoform
echo "<script src='../includes/autoform/autoform.js'></script>";

echo "<title>".$headline."</title>";
echo "</head>";
}

function bootstrapbegin($headline,$showheadline) {
  echo "<body onload='submitForm()'>";
  echo "<div class='row-fluid'>";
  echo "<div class='span12'>";
  if ($headline<>"") {
    echo "<legend>".$headline."</legend>";
  }
}

function bootstrapend() {
  echo "</div>";
  echo "</div>";
  echo "</body>";
  echo "</html>";
}

?>