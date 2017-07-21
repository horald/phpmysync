<?php
session_start();
$typ=$_GET['typ'];
include("bootstrapfunc.php");
bootstraphead();
bootstrapbegin("Hilfe");
$menu=$_GET['menu'];
if (isset($_GET['pagename'])) {
  $pagename=$_GET['pagename'];
} else {	
  $pagename=$menu;
}
$action=$_GET['action'];
if ($action=="back") {
  $pageno=$_GET['pageno'];
  if ($pageno>0) {
  	 $pageno=$pageno-1;
  	 $pagename="";
  }
}	
if ($action=="forward") {
  $pageno=$_GET['pageno'];
  $pageno=$pageno+1;
  $pagename="";
}	
$db = new SQLite3('../data/mysqlitesync.db');
if ($pagename=="") {
  $query="SELECT * FROM tblhelppage WHERE fldpageno='".$pageno."'";
} else {
  $query="SELECT * FROM tblhelppage WHERE fldpagename='".$pagename."'";
}  
//echo $query."<br>";
$results = $db->query($query);
$lok=false;
$pageno=0;
if ($row = $results->fetchArray() ) { 
  $lok=true;
  $pageno=$row['fldpageno'];
}
echo "<br>";
if ($typ<>"print") {
  echo "<a href='../index.php' class='btn btn-primary btn-sm active' role='button'>Menü</a> "; 
  if ($menu<>"") {
    echo "<a href='showtab.php?menu=".$menu."' class='btn btn-primary btn-sm active' role='button'>zur Anwendung</a> "; 
  }  
  echo "<a href='help.php?menu=".$menu."&pagename=helpindex' class='btn btn-primary btn-sm active' role='button'>Index</a> "; 
  echo "<a href='help.php?menu=".$menu."&pageno=".$pageno."&action=back' class='btn btn-primary btn-sm active' role='button'>zurück</a> "; 
  echo "<a href='help.php?menu=".$menu."&pageno=".$pageno."&action=forward' class='btn btn-primary btn-sm active' role='button'>vor</a> "; 
  echo "<a href='showtab.php?menu=helppage' class='btn btn-primary btn-sm active' role='button'>Hilfemneü</a> "; 
  //echo "<a href='html2pdf.php' target='_blank' class='btn btn-primary btn-sm active' role='button'>Export to PDF</a> "; 
}
echo "<p class='text-justify'>";
if ($lok) { 
  $_SESSION["menu"]=$menu;
  $url=$row['fldhelpurl'];
  $versnr="0.007";
  if ($pagename=="zeigupdates") {
    $url="updates/updateprot_".$versnr.".html";
  }
  include("../sites/help/de-DE/".$url);
  echo "<br> Seite ".$pageno;
} else {
  echo "<div class='alert alert-error'>";
  echo "helppage '".$pagename."' nicht gefunden.";	
  echo "</dic>";
}

echo "</p>";
bootstrapend();
?>