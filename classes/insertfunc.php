<?php
header("content-type: text/html; charset=utf-8");

function insertinput($listarray,$idwert,$menu,$menugrp) {
  $db = dbopen('../','../data/mysqlitesync.db');
  echo "<a href='showtab.php?menu=".$menu."&menugrp=".$menugrp."' class='btn btn-primary btn-sm active' role='button'>Zurück</a>"; 
  echo "<form class='form-horizontal' method='post' action='insert.php?insert=1&menu=".$menu."&menugrp=".$menugrp."'>";

  foreach ( $listarray as $arrelement ) {
  	 if ($arrelement['fieldsave']<>"NO") {
    $default="";
    if ($arrelement['default']!="") {
      $default=$arrelement['default'];
    }
    $defwert='';
    if ($arrelement['name']<>"") {
      if ($arrelement['getdefault']=="true") {
        $defquery="SELECT * FROM tblfilter WHERE fldmaske='".strtoupper($menu)."_DEFAULT' AND fldName='".$arrelement['name']."'";
        $defresult = $db->query($defquery);
      }  
    }  
    switch ( $arrelement['type'] )
    {
      case 'text':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$default."'/></dd>";
        echo "</dl>";
      break;
      case 'password':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='password' name='".$arrelement['dbfield']."' value='".$default."'/></dd>";
        echo "</dl>";
      break;
      case 'calc':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$default."'/></dd>";
        echo "</dl>";
      break;
      case 'zahl':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$default."'/></dd>";
        echo "</dl>";
      break;
      case 'selectid':
        if ($defwert<>'') {
          $wert=$defwert;
        }
        $seldbwhere="";
        if ($arrelement['seldbwhere']<>"") {
          $seldbwhere=" WHERE ".$arrelement['seldbwhere'];
        }
        $sql="SELECT * FROM ".$arrelement['dbtable'].$seldbwhere;
        $results = $db->query($sql);
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<select name='".$arrelement['name']."' size='1'>";
        echo "<option style='background-color:#c0c0c0;' >(ohne)</option>";
        while ($row = $results->fetchArray()) {
        	 $arrdbfield = explode(",", $arrelement['seldbfield']);
        	 $arrcnt=count($arrdbfield);
          $strdbfield=$row[$arrdbfield[0]];
        	 for ($i = 1; $i < $arrcnt; $i++) {
            $strdbfield=$strdbfield.",".$row[$arrdbfield[$i]];
          }
        	 //$strdbfield=$strdbfield."-".$arrcnt;
          echo "<option style='background-color:#c0c0c0;'  value=".$row[$arrelement['seldbindex']].">".$strdbfield."</option>";
        }
        echo "</select> ";
        echo "</dl>";
      break;
      case 'select':
        if ($defwert<>'') {
          $wert=$defwert;
        }
        $seldbwhere="";
        if ($arrelement['seldbwhere']<>"") {
          $seldbwhere=" WHERE ".$arrelement['seldbwhere'];
        }
        $sql="SELECT * FROM ".$arrelement['dbtable'].$seldbwhere;
        $results = $db->query($sql);
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<select name='".$arrelement['name']."' size='1'>";
        echo "<option style='background-color:#c0c0c0;' >(ohne)</option>";
        while ($row = $results->fetchArray()) {
          $strstatus = $row[$arrelement['seldbfield']];
          if ($wert == $strstatus) {
            echo "<option style='background-color:#c0c0c0;' selected>".$row[$arrelement['seldbfield']]."</option>";
          } else {
            echo "<option style='background-color:#c0c0c0;' >".$row[$arrelement['seldbfield']]."</option>";
          }
        }
        echo "</select> ";
        echo "</dl>";
      break;
      case 'time':
        if ($default=="now()") {
          $timestamp = time();
          $default = date("H:i",$timestamp);
        }
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$default."'/></dd>";
        echo "</dl>";
      break;
      case 'date':
        if ($default=="now()") {
          $default = date('Y-m-d');
        }
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<div class='input-group date form_date col-md-2' data-date='' data-date-format='yyyy-mm-dd' data-link-field='dtp_input2' data-link-format='yyyy-mm-dd'>";
        echo "<dd><input class='form-control' size='8' type='text' name='".$arrelement['dbfield']."' value='".$default."' ></dd>";
		  echo "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>";
        echo "</div>";
		  echo "<input type='hidden' id='dtp_input2' value='' /><br/>";
        echo "</dl>";
      break;
      case 'prozref':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$default."'/></dd>";
        echo "</dl>";
      break;
      }
    }
  }

  echo "<input type='checkbox' name='chkanzeigen' value='anzeigen'> Speichern anzeigen<br>";
  echo "<input type='hidden' name='id' value=".$idwert.">";
  echo "<dd><input type='submit' value='Speichern' /></dd>";
  echo "</form>";
}

function updatepreis($rowid) {
  $db = new SQLite3('../data/joorgsqlite.db');
  $sql = "SELECT * FROM tblartikel WHERE fldBez='".$_POST['fldBez']."' AND fldOrt='".$_POST['fldort']."'";
//  echo $sql."<br>";
  $results = $db->query($sql);
  $count=0;
  while ($row = $results->fetchArray()) {
    $count=$count+1;
    $arr=$row;
  }	
  //echo $count."=count<br>";
  if ($count==1) {
    //echo $arr['fldPreis']."<br>";
    $sql="UPDATE tblEinkauf_liste SET fldpreis='".$arr['fldPreis']."' WHERE fldindex=".$rowid;
    //echo $sql."<br>";
    $db->exec($sql);
    //echo $db->lastErrorMsg()."<br>";

  }
  if ($count==0) {
    if ($_POST['fldpreis']<>"") {
      if ($_POST['fldort']<>"") {  
        $sql="INSERT INTO tblartikel (fldBez,fldPreis,fldOrt) VALUES ('".$_POST['fldBez']."','".$_POST['fldpreis']."','".$_POST['fldort']."')";
        //echo $sql."<br>";
        $db->exec($sql);
      }
    }
  }
}

function insertsave($pararray,$listarray,$menu,$show,$autoinc_step,$autoinc_start,$menugrp) {
  echo "<a href='showtab.php?menu=".$menu."&menugrp=".$menugrp."' class='btn btn-primary btn-sm active' role='button'>Liste</a>"; 
  $db = dbopen('../','../data/mysqlitesync.db');
//  echo $db->lastErrorMsg()."<br>";

  //$sqlid = "select ".$pararray['fldindex']." from ".$pararray['dbtable']." order by ".$pararray['fldindex']." desc limit 1";
  $sqlid="SELECT * FROM tblindex WHERE fldtable='".$pararray['dbtable']."'";
  $results = $db->query($sqlid);
  if ($row = $results->fetchArray()) {
    $newrowid=$row['fldid'] + $autoinc_step;
    //echo $newrowid."=newrowid<br>";
  } else {
    $newrowid=$autoinc_start;  
  }

  $prozref="N"; 
  $dbtable=$pararray['dbtable'];
//  $sql="INSERT INTO ".$dbtable." (".$pararray['fldindex'].",";
  $sql="INSERT INTO ".$dbtable." (";
  foreach ( $listarray as $arrelement ) {
  	 if ($arrelement['fieldsave']<>"NO") {
      switch ( $arrelement['type'] )
      {
        case 'text':
          $sql=$sql.$arrelement['dbfield'].",";
        break;
        case 'password':
          $sql=$sql.$arrelement['dbfield'].",";
        break;
        case 'select':
          $sql=$sql.$arrelement['dbfield'].",";
        break;
        case 'selectid':
          $sql=$sql.$arrelement['dbfield'].",";
        break;
        case 'time':
          $sql=$sql.$arrelement['dbfield'].",";
        break;
        case 'date':
          $sql=$sql.$arrelement['dbfield'].",";
        break;
        case 'calc':
          $sql=$sql.$arrelement['dbfield'].",";
        break;
        case 'timestamp':
          $sql=$sql.$arrelement['dbfield'].",";
        break;
      }
    }  
  }
//  $sql=substr($sql,0,-1).") VALUES (".$newrowid.",";
  $sql=substr($sql,0,-1).") VALUES (";
  foreach ( $listarray as $arrelement ) {
  	 if ($arrelement['fieldsave']<>"NO") {
      switch ( $arrelement['type'] )
      {
        case 'text':
          $sql=$sql."'".$_POST[$arrelement['dbfield']]."',";
        break;
        case 'password':
          $sql=$sql."'".$_POST[$arrelement['dbfield']]."',";
        break;
        case 'selectid':
          $sql=$sql."'".$_POST[$arrelement['name']]."',";
        break;
        case 'select':
          $sql=$sql."'".$_POST[$arrelement['name']]."',";
        break;
        case 'time':
          $sql=$sql."'".$_POST[$arrelement['dbfield']]."',";
        break;
        case 'date':
          $sql=$sql."'".$_POST[$arrelement['dbfield']]."',";
        break;
        case 'calc':
          $sql=$sql."'".$_POST[$arrelement['dbfield']]."',";
        break;
        case 'timestamp':
          $sql=$sql."CURRENT_TIMESTAMP,";
        break;
        case 'prozref':
          $prozref="J";
          $dbfield=$arrelement['dbfield'];
        break;
      }
    }
  }
  $sql=substr($sql,0,-1).")";

  //echo $sql."<br>";
  $db->exec($sql);
  $sqlid = "SELECT last_insert_rowid() as lastid FROM ".$pararray['dbtable'];
  $results = $db->query($sqlid);
  if ($row = $results->fetchArray()) {
    $rowid=$row[0]; 
    //echo $rowid."=rowid<br>"; 
  }
  if ($show=="anzeigen") {
    echo "<div class='alert alert-success'>";
    echo $sql."<br>";
    echo $db->lastErrorMsg()."<br>";
    echo "</div>";
  }  
  if ($prozref=="J") {
    $sqlfil="SELECT * FROM tblfilter WHERE fldtablename='tblorte' AND fldfeld='fldid_suchobj'";
    $resfil = $db->query($sqlfil);
    if ($rowfil = $resfil->fetchArray()) {
     	if ($rowfil['fldwert']<>"(ohne)") {
        $sqlsuch="SELECT * FROM tblsuchobj WHERE fldbez='".$rowfil['fldwert']."'";
        $ressuch = $db->query($sqlsuch);
        if ($rowsuch = $ressuch->fetchArray()) {
          $refwhere="fldid_suchobj=".$rowsuch['fldindex']." AND fldid_orte=".$rowid;
          $sqlref="SELECT * FROM tblrefsuchobj WHERE ".$refwhere;
          $resref = $db->query($sqlref);
          if ($rowref = $resref->fetchArray()) {
            $sqlupdref="UPDATE tblrefsuchobj SET ".$dbfield."=".$_POST[$dbfield]." WHERE ".$refwhere;          
          } else {
            $sqlupdref="INSERT INTO tblrefsuchobj (fldid_suchobj,fldid_orte,".$dbfield.") VALUES(".$rowsuch['fldindex'].",".$rowid.",'".$_POST[$dbfield]."')";          
          }	
        }  
      }  
    }  
    echo "<div class='alert alert-success'>";
    echo $sqlupdref."=prozref";
    echo "</div>";
    $reserr = $db->exec($sqlupdref);
  }
  if ($newrowid==$autoinc_start) {
    $sqlupd="INSERT INTO tblindex (fldtable,fldid) VALUES('".$pararray['dbtable']."',".$newrowid.")";
  } else {
    $sqlupd="UPDATE tblindex SET fldid=".$newrowid."  WHERE fldtable='".$pararray['dbtable']."'";
  }
  //echo $sqlupd."<br>";
  $resupd = $db->exec($sqlupd);
  $db->close();
  //echo $pararray['chkpreis']."=chkpreis<br>"; 
  if ($pararray['chkpreis']=="J") {
    updatepreis($rowid);
  }
  echo "<div class='alert alert-success'>";
  echo "Daten '".$_POST['fldBez']."' mit rowid=".$rowid." eingefügt.";
  echo "</div>";
}

?>