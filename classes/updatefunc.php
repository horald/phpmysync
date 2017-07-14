<?php
header("content-type: text/html; charset=utf-8");


?>
<script type="text/javascript">
function myfunc() {
var e = document.getElementByName("etage");
var strUser = e.options[e.selectedIndex].value;
alert(strUser);
}
</script>
<?php        	

function updatepreis($rowid,$show) {
  $db = dbopen('../','../data/mysqlitesync.db');
  $sql = "SELECT * FROM tblartikel WHERE fldBez='".$_POST['fldBez']."' AND fldOrt='".$_POST['kaufort']."'";
  //echo $sql."<br>";
  $results = $db->query($sql);
  $count=0;
  while ($row = $results->fetchArray()) {
    $count=$count+1;
    $arr=$row;
  }	
  //echo $count."=count<br>";
  if ($count==1) {
    if ($_POST['fldPreis']<>"") {
      if ($_POST['kaufort']<>"") {  
        $sql="UPDATE tblartikel SET fldPreis='".$_POST['fldPreis']."' WHERE fldindex=".$arr['fldindex'];
        $db->exec($sql);
        if ($show=="anzeigen") {
          echo "<div class='alert alert-success'>";
          echo $db->lastErrorMsg()."<br>";
          echo $sql."<br>";
          echo "</div>";
        }
      }
    }  
  }
  if ($count==0) {
    if ($_POST['fldPreis']<>"") {
      if ($_POST['kaufort']<>"") {  
        $sql="INSERT INTO tblartikel (fldBez,fldKonto,fldPreis,fldOrt,fldAnz,fldJN) VALUES ('".$_POST['fldBez']."','".$_POST['fldKonto']."','".$_POST['fldPreis']."','".$_POST['kaufort']."','1','J')";
        //echo $sql."<br>";
        $db->exec($sql);
        //echo $db->lastErrorMsg()."<br>";
      }
    }
  }  
}

function updateinput($pararray,$listarray,$idwert,$menu,$menugrp) {
  $db = dbopen('../','../data/mysqlitesync.db');
  $results = $db->query("SELECT * FROM ".$pararray['dbtable']." WHERE fldindex=".$idwert);
  while ($row = $results->fetchArray()) {
    $arr=$row;
  }	
  echo "<a href='showtab.php?menu=".$menu."&menugrp=".$menugrp."' class='btn btn-primary btn-sm active' role='button'>Zurück</a>"; 
  echo "<form class='form-horizontal' method='post' action='update.php?update=1&menu=".$menu."&menugrp=".$menugrp."' role='form'>";

  foreach ( $listarray as $arrelement ) {
  	 if ($arrelement['fieldsave']<>"NO") {
    switch ( $arrelement['type'] )
    {
      case 'text':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$arr[$arrelement['dbfield']]."'/></dd>";
        echo "</dl>";
      break;
      case 'JN':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<select name='".$arrelement['dbfield']."' size='1'>";
        if ($arr[$arrelement['dbfield']]=='J') {		
          echo "<option style='background-color:#c0c0c0;' selected value='J'>J</option>";
          echo "<option style='background-color:#c0c0c0;' value='N'>N</option>";
		} else {
          echo "<option style='background-color:#c0c0c0;' value='J'>J</option>";
          echo "<option style='background-color:#c0c0c0;' selected value='N'>N</option>";
		}
		echo "</select>";
        echo "</dl>";
      break;
      case 'selectid':
        $seldbwhere="";
        if ($arrelement['name']=="zimmer") {
//          $seldbwhere=" WHERE fldid_etage=(SELECT fldindex FROM tblorte WHERE fldBez='2. Stock')";
        }
        if ($arrelement['seldbwhere']<>"") {
        	 if ($seldbwhere<>"") {
            $seldbwhere=$seldbwhere." AND ".$arrelement['seldbwhere'];
        	 } else {
            $seldbwhere=" WHERE ".$arrelement['seldbwhere'];
          }
        }
        $sql="SELECT * FROM ".$arrelement['dbtable'].$seldbwhere;
        //echo $sql."<br>";
        $results = $db->query($sql);
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<select name='".$arrelement['name']."' size='1' onchange='myfunc()'>";
        echo "<option style='background-color:#c0c0c0;' >(ohne)</option>";
        while ($row = $results->fetchArray()) {
          if ($arr[$arrelement['dbfield']]==$row[$arrelement['seldbindex']]) {
            echo "<option style='background-color:#c0c0c0;' selected value=".$row[$arrelement['seldbindex']].">".$row[$arrelement['seldbfield']]."</option>";
          } else {
            echo "<option style='background-color:#c0c0c0;' value=".$row[$arrelement['seldbindex']].">".$row[$arrelement['seldbfield']]."</option>";
          }
        }
        echo "</select> ";
        echo "</dl>";
      break;
      case 'select':
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
          if ($arr[$arrelement['dbfield']]==$row[$arrelement['seldbfield']]) {
            echo "<option style='background-color:#c0c0c0;' selected>".$row[$arrelement['seldbfield']]."</option>";
          } else {
            echo "<option style='background-color:#c0c0c0;' >".$row[$arrelement['seldbfield']]."</option>";
          }
        }
        echo "</select> ";
        echo "</dl>";
      break;
      case 'time':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$arr[$arrelement['dbfield']]."'/></dd>";
        echo "</dl>";
      break;
      case 'calc':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$arr[$arrelement['dbfield']]."'/></dd>";
        echo "</dl>";
      break;
      case 'date':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<div class='input-group date form_date col-md-2' data-date='' data-date-format='yyyy-mm-dd' data-link-field='dtp_input2' data-link-format='yyyy-mm-dd'>";
        echo "<dd><input class='form-control' size='8' type='text' name='".$arrelement['dbfield']."' value='".$arr[$arrelement['dbfield']]."' readonly></dd>";
        //echo "<dd><input class='form-control' size='8' type='text' name='".$arrelement['dbfield']."' value='".$arr[$arrelement['dbfield']]."' ></dd>";
		  echo "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>";
        echo "</div>";
		  echo "<input type='hidden' id='dtp_input2' value='' /><br/>";
        echo "</dl>";
      break;
      case 'prozref':
        $proz="0";
        $sqlfil="SELECT * FROM tblfilter WHERE fldtablename='tblorte' AND fldfeld='fldid_suchobj'";
        $resfil = $db->query($sqlfil);
        if ($rowfil = $resfil->fetchArray()) {
          if ($rowfil['fldwert']<>"(ohne)") {
            $sqlsuch="SELECT * FROM tblsuchobj WHERE fldbez='".$rowfil['fldwert']."'";
            $ressuch = $db->query($sqlsuch);
            if ($rowsuch = $ressuch->fetchArray()) {
              $refwhere="fldid_suchobj=".$rowsuch['fldindex']." AND fldid_orte=".$arr['fldindex'];
              $sqlref="SELECT * FROM tblrefsuchobj WHERE ".$refwhere;
              $resref = $db->query($sqlref);
              if ($rowref = $resref->fetchArray()) {
                $proz=$rowref[$arrelement['dbfield']];
              }
            }
          }
        }        
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$proz."'/></dd>";
        echo "</dl>";
      break;
      case 'calc':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$default."'/></dd>";
        echo "</dl>";
      break;
      case 'proz':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$arr[$arrelement['dbfield']]."'/></dd>";
        echo "</dl>";
      break;
      case 'zahl':
        echo "<dl>";
        echo "<dt><label >".$arrelement['label'].":</label></dt>";
        echo "<dd><input type='text' name='".$arrelement['dbfield']."' value='".$arr[$arrelement['dbfield']]."'/></dd>";
        echo "</dl>";
      break;
    }
    }
  }

  echo "<input type='hidden' name='id' value=".$idwert.">";
  if ($pararray['chkpreis']=="J") {
    echo "<input type='checkbox' name='chkpreis' value='preis' checked> Preis speichern<br>";
  }
  echo "<input type='checkbox' name='chkanzeigen' value='anzeigen'> Speichern anzeigen<br>";

  echo "<input type='checkbox' name='dscopy' value='dscopy'> Kopieren<br><br>";
  
  echo "<dd><input type='submit' value='Speichern' /></dd>";
  echo "</form>";
}

function updatesave($pararray,$listarray,$menu,$show,$chkpreis,$menugrp) {
  echo "<a href='showtab.php?menu=".$menu."&menugrp=".$menugrp."' class='btn btn-primary btn-sm active' role='button'>Liste</a>"; 
  $db = dbopen('../','../data/mysqlitesync.db');

  $sql="UPDATE ".$pararray['dbtable']." SET ";
  $strfld="";
  $strval="";
  foreach ( $listarray as $arrelement ) {
  	 if ($arrelement['fieldsave']<>"NO") {
      if ($strfld=="") {
        $strfld=$arrelement['dbfield'];
        $strval="'".$_POST[$arrelement['dbfield']]."'";
      } else {
        if ($arrelement['type']<>"icon") {	
          $strfld=$strfld.",".$arrelement['dbfield'];
          $strval=$strval.",'".$_POST[$arrelement['dbfield']]."'";
        }  
      }
      switch ( $arrelement['type'] )
      {
        case 'text':
          $sql=$sql.$arrelement['dbfield']."='".$_POST[$arrelement['dbfield']]."', ";
        break;
        case 'JN':
          $sql=$sql.$arrelement['dbfield']."='".$_POST[$arrelement['dbfield']]."', ";
        break;
        case 'zahl':
          $sql=$sql.$arrelement['dbfield']."='".$_POST[$arrelement['dbfield']]."', ";
        break;
        case 'selectid':
          $wert=$_POST[$arrelement['name']];
          if ($wert=="(ohne)") {
          	$wert=0;
          }
          $sql=$sql.$arrelement['dbfield']."='".$wert."', ";
        break;
        case 'select':
          $sql=$sql.$arrelement['dbfield']."='".$_POST[$arrelement['name']]."', ";
        break;
        case 'time':
          $sql=$sql.$arrelement['dbfield']."='".$_POST[$arrelement['dbfield']]."', ";
        break;
        case 'date':
          $sql=$sql.$arrelement['dbfield']."='".$_POST[$arrelement['dbfield']]."', ";
        break;
        case 'calc':
          $sql=$sql.$arrelement['dbfield']."='".$_POST[$arrelement['dbfield']]."', ";
        break;
        case 'proz':
          $sql=$sql.$arrelement['dbfield']."='".$_POST[$arrelement['dbfield']]."', ";
        break;
        case 'timestamp':
          $sql=$sql.$arrelement['dbfield']."=CURRENT_TIMESTAMP, ";
        break;
        case 'prozref':
          $sqlfil="SELECT * FROM tblfilter WHERE fldtablename='tblorte' AND fldfeld='fldid_suchobj'";
          echo $sqlfil."<br>";
          $resfil = $db->query($sqlfil);
          if ($rowfil = $resfil->fetchArray()) {
          	if ($rowfil['fldwert']<>"(ohne)") {
              $sqlsuch="SELECT * FROM tblsuchobj WHERE fldbez='".$rowfil['fldwert']."'";
              $ressuch = $db->query($sqlsuch);
              if ($rowsuch = $ressuch->fetchArray()) {
                $refwhere="fldid_suchobj=".$rowsuch['fldindex']." AND fldid_orte=".$_POST['id'];
                $sqlref="SELECT * FROM tblrefsuchobj WHERE ".$refwhere;
                $resref = $db->query($sqlref);
                if ($rowref = $resref->fetchArray()) {
                  $sqlupdref="UPDATE tblrefsuchobj SET ".$arrelement['dbfield']."=".$_POST[$arrelement['dbfield']].",fldtyp='".$arrelement['reftyp']."',fldid_moebel=".$_POST['moebel'].",fldid_zimmer=".$_POST['zimmer']." AND fldid_etage=".$_POST['etage']." WHERE ".$refwhere;          
                } else {
                  $sqlupdref="INSERT INTO tblrefsuchobj (fldid_suchobj,fldid_orte,".$arrelement['dbfield'].",fldtyp) VALUES(".$rowsuch['fldindex'].",".$_POST['id'].",'".$_POST[$arrelement['dbfield']]."','".$arrelement['reftyp']."')";          
                }	
                echo "<div class='alert alert-success'>";
                echo $sqlupdref."=prozref";
                echo "</div>";
                //$reserr = $db->exec($sqlupdref);
              }  
            }  
          }  
        break;
      }
    }  
  }

  $sql=substr($sql,0,-2);
  $sql=$sql." WHERE fldindex=".$_POST['id'];
  $query = $db->exec($sql);
  if ($pararray['chkpreis']=="J") {
    if ($chkpreis=="preis") {
      $rowid=$_POST['id'];
      updatepreis($rowid,$show);
    }
  }

    $dscopy=$_POST['dscopy'];
    //echo $dscopy."=dscopy?<br>";
    if ($dscopy=="dscopy") {
      $qrycopy = "INSERT INTO ".$pararray['dbtable']." (".$strfld.") VALUES(".$strval.") ";
      echo "<div class='alert alert-success'>";
      echo $qrycopy."<br>";
      echo "</div>";
      $query = $db->exec($qrycopy);
      //mysql_query($qrycopy) or die("Error using mysql_query($qrycopy): ".mysql_error());
    }

  if ($show=="anzeigen") {
    echo "<div class='alert alert-success'>";
    echo $db->lastErrorMsg()."<br>";
    echo $sql."<br>";
    echo "</div>";
  }
  $fldbez="fldbez";
  if ($pararray['fldbez']<>"") {
  	 $fldbez=$pararray['fldbez'];
  }
  echo "<div class='alert alert-success'>";
  echo "Daten '".$_POST[$fldbez]."' aktualisiert.";
  echo "</div>";
}
?>