<?php
session_start();
$typ=$_GET['typ'];
include("bootstrapfunc.php");
include("../config.php");
bootstraphead();
bootstrapbegin("Hilfe");
$menu=$_GET['menu'];
$url=$_GET['url'];
$pageno=1;
$pagename=$_GET['pagename'];
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
$db = new SQLite3('../data/'.$database);
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
  if ($url<>"") {
    echo "<a href='".$url."' class='btn btn-primary btn-sm active' role='button'>zur Anwendung</a> "; 
  } else {
    echo "<a href='showtab.php?menu=".$menu."' class='btn btn-primary btn-sm active' role='button'>zur Anwendung</a> "; 
  }
  echo "<a href='help.php?menu=".$menu."&pagename=helpindex' class='btn btn-primary btn-sm active' role='button'>Index</a> "; 
  echo "<a href='help.php?menu=".$menu."&pageno=".$pageno."&action=back' class='btn btn-primary btn-sm active' role='button'>zurück</a> "; 
  echo "<a href='help.php?menu=".$menu."&pageno=".$pageno."&action=forward' class='btn btn-primary btn-sm active' role='button'>vor</a> "; 
  //echo "<a href='html2pdf.php' target='_blank' class='btn btn-primary btn-sm active' role='button'>Export to PDF</a> "; 
}
echo "<p class='text-justify'>";
if ($lok) { 
  $_SESSION["menu"]=$menu;
  include("../sites/help/de-DE/".$row['fldhelpurl']);
  echo "<br> Seite ".$pageno;
} else {
  echo "<div class='alert alert-error'>";
  echo "helppage nicht gefunden.";	
  echo "</dic>";
}

echo "</p>";
bootstrapend();
?>