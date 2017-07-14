<?php
session_start();
include("bootstrapfunc.php");
include("dbtool.php");
include("showtabfunc.php");
$menu=$_GET['menu'];
$filter=$_GET['filter'];
$id=$_GET['id'];
include("../sites/views/".$menu."/showtab.inc.php");
bootstraphead();
bootstrapbegin($pararray['headline']);

$db = dbopen('../','../data/mysqlitesync.db');

$sql=showtabfilter($filter,$filterarray,$pararray,$menu);
showtabfunc($menu,$id);
showtabbrowse($listarray,$filterarray,$pararray,$sql,$menu);

bootstrapend();
?>