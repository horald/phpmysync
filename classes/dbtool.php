<?php

function dbopen($pfad,$dbname) {
  include($pfad."config.php");
  //echo $dbtyp."=dbtyp<br>";
  switch($dbtyp)
  {
    case 'SQLITE2';
     $db = new SQLiteDatabase($dbname);
      break;
    case 'SQLITE3';
      $db = new SQLite3($dbname);
      break;
    case 'MYSQL';
        echo 'Mysql noch nicht fertig.';
      break;
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
  return $db;
}

function dbopentyp($dbtyp,$dbname,$dbuser,$dbpass) {
  switch($dbtyp)
  {
    case 'SQLITE2';
     $db = new SQLiteDatabase($dbname);
      break;
    case 'SQLITE3';
      $db = new SQLite3($dbname);
      break;
    case 'MYSQL';
      //$dbcon = mysql_connect('localhost',$dbuser,$dbpass) or die(mysql_error());
      //$db = mysql_select_db($dbname) or die(mysql_error());
      $db = mysqli_connect('localhost',$dbuser,$dbpass,$dbname);
      //echo $dbname."=dbopen<br>";
      break;
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
  return $db;
}

function dbquery($pfad,$db,$query) {
  include($pfad."config.php");
  switch($dbtyp)
  {
    case 'SQLITE2';
      $result = $db->query($query);
      break;
    case 'SQLITE3';
      $result = $db->query($query);
      break;
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
  return $result;
}

function dbquerytyp($dbtyp,$db,$query) {
  switch($dbtyp)
  {
    case 'SQLITE2';
      $result = $db->query($query);
      break;
    case 'SQLITE3';
      $result = $db->query($query);
      break;
    case 'MYSQL';
      //$result = mysql_query($query) or die(mysql_error());
      $result = mysqli_query($db,$query);
      //echo "DB-Error:".mysqli_error($db)."<br>";
      break;
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
  return $result;
}

function dbfetch($pfad,$result) {
  include($pfad."config.php");
  switch($dbtyp)
  {
    case 'SQLITE2';
      $row = $result->fetch(SQLITE_ASSOC);
      break;
    case 'SQLITE3';
      $row = $result->fetchArray();
      break;
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
  return $row;
}  

function dbfetchtyp($dbtyp,$result) {
  switch($dbtyp)
  {
    case 'SQLITE2';
      $row = $result->fetch(SQLITE_ASSOC);
      break;
    case 'SQLITE3';
      $row = $result->fetchArray();
      break;
    case 'MYSQL';
      //$row = mysql_fetch_array($result);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      break;
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
  return $row;
}  

function dbexecute($pfad,$db,$sql) {
  include($pfad."config.php");
  switch($dbtyp)
  {
    case 'SQLITE2';
      if(!$db->queryExec($sql, $error)) {
        die($error);
      }  
      break;
    case 'SQLITE3';
      $query = $db->exec($sql);
      break;
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
}

function dbexecutetyp($dbtyp,$db,$sql) {
  switch($dbtyp)
  {
    case 'SQLITE2';
      if(!$db->queryExec($sql, $error)) {
        die($error);
      }  
      break;
    case 'SQLITE3';
      $query = $db->exec($sql);
      //if(!$db->exec($sql, $error)) {
      //  die($error);
      //}  
      break;
    case 'MYSQL';
      //mysql_query($sql) or die("Error using mysql_query($sql): ".mysql_error());
      mysqli_query($db,$sql);
      break;
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
}

function getdbcolumn($dbtyp,$dbname,$dbtable,$dbuser,$dbpassword) {
  switch($dbtyp)
  {
    case 'SQLITE3';
      $dbopen=dbopentyp($dbtyp,$dbname,"","");
      $col = "";
      $lincnt = 1;
      $count = 0;
      $query="PRAGMA table_info(".$dbtable.")";
      $results = dbquerytyp($dbtyp,$dbopen,$query);
      while ($row = dbfetchtyp($dbtyp,$results)) {
        $colstr=$row['name'];
        $lincnt = $lincnt + 1;
        if ($col=="") {
          $col=$colstr;
        } else {	
          $col=$col.",".$colstr;
        }    
      }
	  return $col;
	break;
    case 'MYSQL';
      $dbopen=dbopentyp($dbtyp,$dbname,$dbuser,$dbpassword);
      //$col="*";
      $col="";
      //$query="describe ".$dbtable;
      $query="SHOW COLUMNS FROM ".$dbtable;
      //echo $query."=query<br>";
      $results = dbquerytyp($dbtyp,$dbopen,$query);
      $lincnt=0;
      while ($row = dbfetchtyp($dbtyp,$results)) {
        $colstr=$row['Field'];
        $lincnt = $lincnt + 1;
        if ($col=="") {
          $col=$colstr;
        } else {	
          $col=$col.",".$colstr;
        }    
      }
      //echo $lincnt."=lincnt<br>";
      return $col;
	break;  
    default;
        echo 'Unbekannter dbtyp '.$dbtyp;
    break;
  }
}

?>