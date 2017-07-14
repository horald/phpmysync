<?php

function showtabfunc($menu,$id) {
  echo "<a href='../index.php?id=".$id."'  class='btn btn-primary btn-sm active' role='button'>Menü</a> ";
  echo "<a href='insert.php?menu=".$menu."' class='btn btn-primary btn-sm active' role='button'>Einfügen</a> ";
  $db = dbopen('../','../data/mysqlitesync.db');
  $sql="SELECT * FROM tblfunc WHERE fldMenuID='".$menu."' AND fldaktiv='J' ORDER BY fldName";
  //echo $sql."<br>";
  $results = $db->query($sql);
  while ($row = $results->fetchArray()) {
    //echo $row['fldBez']."<br>";  
    $param="";
    if ($row['fldParam']=="NOPARAM") {
      echo "<a href='".$row['fldphp']."' class='btn btn-primary btn-sm active' role='button'>".$row['fldBez']."</a> ";
	} else {
      if ($row['fldParam']<>"") {
    	  //$param="&".$row['fldParam']."=".$_GET[$row['fldParam']];
    	  $param="&".$row['fldParam'];
      }
      //echo "<a href='".$row['fldphp']."?menu=".$menu."&menugrp=".$menugrp.$param."' class='btn btn-primary btn-sm active' role='button'>".$row['fldBez']."</a> ";
      echo "<a href='".$row['fldphp']."?menu=".$menu.$param."' class='btn btn-primary btn-sm active' role='button'>".$row['fldBez']."</a> ";
	}  
  }  
}

function showtabfilter($filter,$filterarray,$pararray,$menu) {
  $db = dbopen('../','../data/mysqlitesync.db');
  $dbtable=$pararray['dbtable'];
  $fldort="";
  $dbwhere="";
  $selarr=array();
  $anzfilter = sizeof($filterarray);
  if ($filter==1) {
    //echo "filter==1<br>";
    foreach ( $filterarray as $arrelement ) {
      $wert=$_POST[$arrelement['name']];
      //$selarr[$arrelement['dbfield']]=$arr['fldwert'];
      //echo $arrelement['dbfield']."=".$wert."<br>";
      if (($wert<>"(ohne)") && ($arrelement['type']<>"prozref")) {
        $lweiter="J";
        if ($arrelement['type']=="date") {
        	 if ($wert=="") {
        	 	$lweiter="N";
        	 }
        }	
        //echo $arrelement['name']."<br>";
        //echo $lweiter.",".$arrelement['type'].",".$wert;
        if ($lweiter=="J") {	
          if ($dbwhere=="") {
            $dbwhere=" WHERE ".$arrelement['dbfield'].$arrelement['sign']."'".$wert."'";
          } else {
            $dbwhere=$dbwhere." AND ".$arrelement['dbfield'].$arrelement['sign']."'".$wert."'";
          }
        }  
      }

      $sql="SELECT * FROM tblfilter WHERE fldfeld='".$arrelement['dbfield']."' AND fldtablename='".$dbtable."'";
      //echo $sql."<br>";
      $results = $db->query($sql);
      while ($row = $results->fetchArray()) {
        $arr=$row;
      }  	
      if (isset($arr['fldwert'])) {
        //echo $arr['fldfeld'].",".$arr['fldtablename'].",".$arrelement['dbfield'].",".$pararray['dbtable']."<br>";
        if (($arr['fldtablename']==$pararray['dbtable']) && ($arr['fldfeld']==$arrelement['dbfield'])) {
          $sql="UPDATE tblfilter SET fldwert='".$wert."' WHERE fldindex=".$arr['fldindex'];
        } else {
          $sql="INSERT INTO tblfilter (fldfeld,fldwert,fldtablename) VALUES('".$arrelement['dbfield']."','".$wert."','".$dbtable."')";
        }
      } else {
        $sql="INSERT INTO tblfilter (fldfeld,fldwert,fldtablename) VALUES('".$arrelement['dbfield']."','".$wert."','".$dbtable."')";
      }
      //echo $sql."<br>";
      $results = $db->query($sql);

    }

  } else {
    //echo "no filter<br>";

    foreach ( $filterarray as $arrelement ) {
      $sql="SELECT * FROM tblfilter WHERE fldfeld='".$arrelement['dbfield']."' AND fldtablename='".$dbtable."'";
      //echo $sql."<br>";
      $results = $db->query($sql);
      while ($row = $results->fetchArray()) {
        $arr=$row;
      }	
      if (isset($arr['fldwert'])) {
        //echo $arr['fldfeld'].",".$arr['fldtablename'].",".$pararray['dbtable'].",".$arrelement['dbfield']."<br>";
        if (($arr['fldwert']<>"(ohne)") && ($arrelement['type']<>"prozref")) {
          if ($dbwhere=="") { 
            $dbwhere=" WHERE ".$arrelement['dbfield'].$arrelement['sign']."'".$arr['fldwert']."'";
         } else {
            $dbwhere=$dbwhere." AND ".$arrelement['dbfield'].$arrelement['sign']."'".$arr['fldwert']."'";
          }
        }
        $selarr[$arrelement['dbfield']]=$arr['fldwert'];
      }
    }
  }

  $etagenid=$_GET['ETAGENID'];
  if ($etagenid=="") {
  	 $etagenid=$_POST['ETAGENID'];
  }
  //echo $anzfilter."=anz<br>";
  echo "<form class='form-horizontal' method='post' action='showtab.php?filter=1&menu=".$menu."'>";
  foreach ( $filterarray as $arrelement ) {
    switch ( $arrelement['type'] )
    {
      case 'select':
        $seldbwhere="";
        if ($arrelement['seldbwhere']<>"") {
          $seldbwhere=" WHERE ".$arrelement['seldbwhere'];
        }
        $sql="SELECT * FROM ".$arrelement['dbtable'].$seldbwhere;
        $results = $db->query($sql);
        echo $arrelement['label'];
        echo "<select name='".$arrelement['name']."' size='1'>";
        echo "<option style='background-color:#c0c0c0;' >(ohne)</option>";
        while ($row = $results->fetchArray()) {
          if ($filter==1) {
            $wert=$_POST[$arrelement['name']];
          } else {
            $wert=$selarr[$arrelement['dbfield']];
          }
          if ($wert==$row[$arrelement['seldbfield']]) {
            echo "<option style='background-color:#c0c0c0;' selected>".$row[$arrelement['seldbfield']]."</option>";
          } else {
            echo "<option style='background-color:#c0c0c0;' >".$row[$arrelement['seldbfield']]."</option>";
          }
        }
        echo "</select> ";
      break;
      case 'selectid':
        $seldbwhere="";
        if ($arrelement['seldbwhere']<>"") {
          $seldbwhere=" WHERE ".$arrelement['seldbwhere'];
        }
        /*
        if ($arrelement['auswahl']=="ETAGEN" && $etagenid=="") {
          echo "<input type='hidden' name='ETAGENID' value=48>";
          echo $selarr[$arrelement['dbfield']]."=wert<br>";
        }
        if ($arrelement['idwhere']=="ETAGEN" && $etagenid<>"") {
          if ($seldbwhere=="") {
          	$seldbwhere="WHERE fldid_etagen=".$etagenid;
          } else {
          	$seldbwhere=$seldbwhere." AND fldid_etage=".$etagenid;
          }
        }
        */
        $sql="SELECT * FROM ".$arrelement['dbtable'].$seldbwhere;
        //echo $sql."<br>";
        $results = $db->query($sql);  
        echo $arrelement['label'];
        $onchange="";
        if ($arrelement['onchange']=="yes") {
        	 $auswahl='"'.$arrelement['auswahl'].'"'; 
        	 //echo $auswahl."<br>";
          $onchange="id='".$arrelement['auswahl']."' onchange='selectbox_auswahl(".$auswahl.")'"; 
          //echo $onchange."<br>";       
        }
        echo "<select name='".$arrelement['name']."' ".$onchange." size='1'>";
        echo "<option style='background-color:#c0c0c0;' >(ohne)</option>";
        while ($row = $results->fetchArray()) {
          if ($filter==1) {
            $wert=$_POST[$arrelement['name']];
          } else {
          	if ($etagenid<>"") {
          	  $wert=$etagenid;	
          	} else {
              $wert=$selarr[$arrelement['dbfield']];
            }  
          }
          if ($wert==$row[$arrelement['seldbindex']]) {
            echo "<option style='background-color:#c0c0c0;' value=".$row[$arrelement['seldbindex']]." selected>".$row[$arrelement['seldbfield']]."</option>";
          } else {
            echo "<option style='background-color:#c0c0c0;' value=".$row[$arrelement['seldbindex']].">".$row[$arrelement['seldbfield']]."</option>";
          }
        }  
        echo "</select> ";
      break;
      case 'date':
        if ($filter==1) {
          $wert=$_POST[$arrelement['name']];
        } else {
          $sqlfilter="SELECT * FROM tblfilter WHERE fldfeld='".$arrelement['dbfield']."' AND fldtablename='".$dbtable."'";
          //echo $sql."<br>";
          $resfilter = $db->query($sqlfilter);
          if ($rowfilter = $resfilter->fetchArray()) {
          	$wert=$rowfilter['fldwert'];
          }
        }  
        echo $arrelement['label'];
        echo "<span class='form_date' data-date='' data-date-format='yyyy-mm-dd' data-link-field='dtp_input2' data-link-format='yyyy-mm-dd'>";
        echo "<input id='dtp_input2' size='8' type='text' name='".$arrelement['name']."' value='".$wert."' >";
        echo "<button><span class='glyphicon glyphicon-calendar'></span></button>";
        echo "</span>";

      break;
    }
  }
  if ($anzfilter>0) {
    echo "<button type='submit' name='submit' class='btn btn-primary'>Ok</button>";
  }
  echo "</form>"; 

  $dborder="";
  if ($pararray['orderby']<>"") {
    $dborder=" ORDER BY ".$pararray['orderby'];
  }

  $dbjoin="";
  if ($pararray['dbreftable']<>"") {
  	 if ($wert<>"(ohne)") {
      $dbjoin=" LEFT JOIN ".$pararray['dbreftable']." ON ".$pararray['dbreftable'].".".$pararray['dbrefindex']." = ".$pararray['dbtable'].".".$pararray['fldindex'];  
    }
  }
  
  if ($pararray['dbwhere']<>"") {
    if ($dbwhere<>"") {
      $dbwhere=$dbwhere." AND ".$pararray['dbwhere'];
    } else {
      $dbwhere=" WHERE ".$pararray['dbwhere'];
    }
  }
  $sql="SELECT * FROM ".$dbtable.$dbjoin.$dbwhere.$dborder;
  //echo $sql."<br>";
  $retarr=array ( 'sqlfilter' => $sql,
                  'sqlbetrag' => 'sqlbetrag');
  return $sql;
}


function showtabbrowse($listarray,$filterarray,$pararray,$sql,$menu) {
$db = dbopen('../','../data/mysqlitesync.db');
$dbselarr=array();
$results = $db->query($sql);
echo "<table class='table table-hover'>";
echo "<tr>";
foreach ( $listarray as $arrelement ) {
  if ($arrelement['fieldhide']!="true") {
    switch ( $arrelement['type'] )
    {
      case 'icon':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'nummer':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'show':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'JN':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'text':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'select':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'selectid':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'selectref':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'time':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'date':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'calcaddsum':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'calcdiffsum':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'calcdiff':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'calc':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'calcsum':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'prozref':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'proz':
        echo "<th>".$arrelement['label']."</th>";
      break;
      case 'timestamp':
        echo "<th>".$arrelement['label']."</th>";
      break;
    }
  }
}

$filterwhere="";
foreach ( $filterarray as $arrelement ) {
  $wert="";	
  $sqlfilter="SELECT * FROM tblfilter WHERE fldfeld='".$arrelement['dbfield']."' AND fldtablename='".$pararray['dbtable']."'";
  $resfilter = $db->query($sqlfilter);
  if ($rowfilter = $resfilter->fetchArray()) {
    $wert=$rowfilter['fldwert'];
  }
  if ($wert<>"(ohne)") { 
    $sign=$arrelement['sign'];
    if ($sign==">=") {
      $sign="<";
    }
    if ($filterwhere=="") {
      $filterwhere = " WHERE ".$arrelement['dbfield'].$sign."'".$wert."'";
    } else {
  	   $filterwhere = $filterwhere." AND ".$arrelement['dbfield'].$sign."'".$wert."'";
    }
  }  
  //echo $sqlfilter."<br>";
}

$calcsum=0;
if ($pararray['calcsum']=='J') {
  if ($filterwhere<>"") {
    if ($pararray['dellogical']=="J") {	
      $filterwhere=$filterwhere." AND flddel='N'";
    }  
    $sqlbetrag="SELECT sum(fldBetrag) as sumbetrag FROM ".$pararray['dbtable'].$filterwhere;
    //echo $sqlbetrag."<br>";
    $resbetrag = $db->query($sqlbetrag);
    if ($rowbetrag = $resbetrag->fetchArray()) {
      $calcsum=$rowbetrag['sumbetrag'];
    }
  } 
}   
//echo $filterwhere."=filterwhere<br>";
//echo $calcsum."=calcsum<br>";

$nummer=0;
$prozsum=0;
$count=0;
//$calcsum=0;
//$calcsum=8.16;
$sum=$calcsum;
$summe=$sum;
$sumdiff=0;
while ($row = $results->fetchArray()) {
  if ($pararray['markseldb']=="J") {
  	 $summe=$summe+$row['fldBetrag'];
  	 //echo "#".number_format($row['fldFix'],2).",".$summe."#<br>";
    if ($row['fldFix']<>"") {
      if (number_format($row['fldFix'],2)==number_format($summe,2)) {
        echo "<tr bgcolor=#00ff00>";
      } else {  
        echo "<tr bgcolor=#ff6699>";
      }  
    } else {
      echo "<tr>";
    }
  } else {  
    echo "<tr>";
  }  
  foreach ( $listarray as $arrelement ) {
    if ($arrelement['fieldhide']!="true") {
      switch ( $arrelement['type'] )
      {
        case 'icon':
            echo "<td><a href='".$arrelement['func']."?menu=".$menu."&id=".$row['fldindex']."' class='btn btn-primary btn-sm active' role='button'>".$arrelement['label']."</a></td> ";
        break;
        case 'nummer':
          $nummer=$nummer+1;
          echo "<td>".$nummer."</td>";
        break;
        case 'show':
          echo "<td>".$row[$arrelement['dbfield']]."</td>";
        break;
        case 'JN':
          echo "<td>".$row[$arrelement['dbfield']]."</td>";
        break;
        case 'timestamp':
          echo "<td>".$row[$arrelement['dbfield']]."</td>";
        break;
        case 'text':
          if ($arrelement['grafiklink']=="J") {
            echo "<td><a href='".$arrelement['grafikurl']."?id=".$id."&etagenid=".$row[$arrelement['grafiketageid']]."&roomtyp=".$arrelement['roomtyp']."&menu=".$menu."'>".$row[$arrelement['dbfield']]."</a></td>";
          } else {	
            echo "<td>".$row[$arrelement['dbfield']]."</td>";
          }
        break;
        case 'select':
          echo "<td>".$row[$arrelement['dbfield']]."</td>";
        break;
        case 'selectid':
          $id=$row[$arrelement['dbfield']];
          if ($id=="") {
            $id='0';          
          }
          $sqlsel = "SELECT * FROM ".$arrelement['dbtable']." WHERE ".$arrelement['seldbindex']."=".$id;
          //echo $sqlsel."<br>";           
          $ressel = $db->query($sqlsel);
          $arrsel=array();
          while ($rowsel = $ressel->fetchArray()) {
          	$arrsel=$rowsel;
          }	
          if (isset($arrsel)) {
          	//$bez=$arrsel[$arrelement['seldbfield']];
            $arrdbfield = explode(",", $arrelement['seldbfield']);
        	$arrcnt=count($arrdbfield);
            $bez=$arrsel[$arrdbfield[0]];
        	for ($i = 1; $i < $arrcnt; $i++) {
              $bez=$bez.",".$arrsel[$arrdbfield[$i]];
            }
          } else {
          	$bez="";
          }
          //echo $bez."=bez<br>";	
          echo "<td>".$bez."</td>";
        break;
        case 'selectref':
          $sqlsel = "SELECT * FROM ".$arrelement['dbtable']." WHERE ".$arrelement['fldindex']."=".$row[$arrelement['dbindex']];
          //echo $sqlsel."<br>";
          $ressel = $db->query($sqlsel);
          echo "<td>";
          echo "<select name='".$arrelement['name']."' size='1'>";
          while ($rowsel = $ressel->fetchArray()) {
            $sqlref = "SELECT * FROM ".$arrelement['reftable']." WHERE ".$arrelement['fldrefindex']."=".$rowsel[$arrelement['dbrefindex']];
			//echo $sqlref."<br>";
            $resref = $db->query($sqlref);
            $bez="<unbekannt>";
            if ($rowref = $resref->fetchArray()) {
              $bez=$rowref[$arrelement['dbfield']];
            }
            echo "<option style='background-color:#c0c0c0;' >".$bez."</option>";
          }
          echo "</select>";
          echo "</td>";
        break;
        case 'time':
          echo "<td>".$row[$arrelement['dbfield']]."</td>";
        break;
        case 'date':
          echo "<td>".$row[$arrelement['dbfield']]."</td>";
        break;
        case 'calcaddsum':
          $nachkomma=2;
          $wert=strval($row[$arrelement['dbfield']]);
          if ($arrelement['calcfield']!="") {
            $wert=$wert - strval($row[$arrelement['calcfield']]);
            $wert=$wert * strval($arrelement['calcfix']);
            $zeitpreis=strval($row[$arrelement['calcaddfield']])*strval($row[$arrelement['calcadddbfield']]);
            //echo $arrelement['calcaddfield']."<br>"; 
            $wert=$wert + $zeitpreis;
          }
          $sumadd=$sumadd+$wert;
          echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>".sprintf("%.".$nachkomma."f",$wert)."</td>";
        break;
        case 'calcdiffsum':
          $nachkomma=2;
          $wert=strval($row[$arrelement['dbfield']]);
          if ($arrelement['calcfield']!="") {
            $wert=$wert - strval($row[$arrelement['calcfield']]);
            $wert=$wert * strval($arrelement['calcfix']);
          }
          $sumdiff=$sumdiff+$wert;
          echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>".sprintf("%.".$nachkomma."f",$wert)."</td>";
        break;
        case 'calcdiff':
          $nachkomma=0;
          $wert=strval($row[$arrelement['dbfield']]);
          if ($arrelement['calcfield']!="") {
            $wert=$wert - strval($row[$arrelement['calcfield']]);
          }
          echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>".sprintf("%.".$nachkomma."f",$wert)."</td>";
        break;
        case 'calc':
          $nachkomma=2;
          $wert=strval($row[$arrelement['dbfield']]);
          if ($arrelement['calcfield']!="") {
            $wert=$wert * strval($row[$arrelement['calcfield']]);
          }
          $sum=$sum+$wert;
          echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>".sprintf("%.".$nachkomma."f",$wert)."</td>";
        break;
        case 'calcsum':
          $nachkomma=2;
          $wert=strval($row[$arrelement['dbfield']]);
          //$calcsum=$calcsum+$wert+$startsum;
          $calcsum=$calcsum+$wert;
          echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>".sprintf("%.".$nachkomma."f",$calcsum)."</td>";
        break;
        case 'prozref':
          //echo "<br>";
          $nachkomma=1;
          $count=$count+1;
          $wert=0;
          $sqlfil="SELECT * FROM tblfilter WHERE (fldtablename='tblorte' OR fldtablename='tbletagen') AND fldfeld='fldid_suchobj'";
          $resfil = $db->query($sqlfil);
          if ($rowfil = $resfil->fetchArray()) {
            if ($rowfil['fldwert']<>"(ohne)") {
              $sqlsuch="SELECT * FROM tblsuchobj WHERE fldbez='".$rowfil['fldwert']."'";
              $ressuch = $db->query($sqlsuch);
              if ($rowsuch = $ressuch->fetchArray()) {
              	 //echo $rowfil['fldtablename']."=tablename<br>";
                $refwhere="fldid_suchobj=".$rowsuch['fldindex']." AND ".$arrelement['roomid']."=".$row['fldindex'];
                $sqlref="SELECT * FROM tblrefsuchobj WHERE ".$refwhere;
 //echo $sqlref."<br>";
                $resref = $db->query($sqlref);
                if ($rowref = $resref->fetchArray()) {
             	   $wert=strval($rowref[$arrelement['dbfield']]);
                }
              }
            }
          }        
          $prozsum=$prozsum+$wert;
          $prozposdif=100-$wert;
          echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>";
          echo "<div style='float:left; background-color:darkgreen; color:lightgreen; height:16px; width:".$wert."px; top:0; left:0;' align=center></div>";
          echo "<div style='float:left; background-color:lightgreen; color:white; height:16px; width:".$prozposdif."px; top:0; left:0;' align=center></div>";
          echo sprintf("%.".$nachkomma."f",$wert)."</td>";
        break;
        case 'proz':
          $nachkomma=1;
          $count=$count+1;
          $wert=strval($row[$arrelement['dbfield']]);
          $prozsum=$prozsum+$wert;
          $prozposdif=100-$wert;
          echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>";
          echo "<div style='float:left; background-color:darkgreen; color:lightgreen; height:16px; width:".$wert."px; top:0; left:0;' align=center></div>";
          echo "<div style='float:left; background-color:lightgreen; color:white; height:16px; width:".$prozposdif."px; top:0; left:0;' align=center></div>";
          echo sprintf("%.".$nachkomma."f",$wert)."</td>";
        break;
      }
    }
  }
//  echo "<td><a href='mark.php?menu=".$menu."&menugrp=".$menugrp."&id=".$row['fldindex']."' class='btn btn-primary btn-sm active' role='button'>OK</a></td> ";
  echo "<td><a href='update.php?menu=".$menu."&menugrp=".$menugrp."&id=".$row['fldindex']."' class='btn btn-primary btn-sm active' role='button'>U</a></td> ";
  echo "<td><a href='delete.php?menu=".$menu."&menugrp=".$menugrp."&id=".$row['fldindex']."' class='btn btn-primary btn-sm active' role='button'>D</a></td>";
  echo "</tr>";
  $menge = array_push ( $dbselarr, $row[$pararray['fldindex']] );
}
$_SESSION['DBSELARR']=$dbselarr;
echo "<tr>";
foreach ( $listarray as $arrelement ) {
  if ($arrelement['fieldhide']!="true") {
    switch ( $arrelement['type'] )
    {
      case 'nummer':
        echo "<td></td>";
      break;
      case 'text':
        echo "<td></td>";
      break;
      case 'select':
        echo "<td></td>";
      break;
      case 'selectid':
        echo "<td></td>";
      break;
      case 'date':
        echo "<td></td>";
      break;
      case 'time':
        echo "<td></td>";
      break;
      case 'calcdiffsum':
        $nachkomma=2;
        echo "<td style='text-align:right;padding-right:10px;'>".sprintf("%.".$nachkomma."f",$sumdiff)."</td>";
      break;
      case 'calcdiff':
        echo "<td></td>";
      break;
      case 'calc':
        $nachkomma=2;
        echo "<td style='text-align:right;padding-right:10px;'>".sprintf("%.".$nachkomma."f",$sum)."</td>";
      break;
      case 'calcaddsum':
        $nachkomma=2;
        echo "<td style='text-align:right;padding-right:10px;'>".sprintf("%.".$nachkomma."f",$sumadd)."</td>";
      break;
      case 'prozref':
        $nachkomma=1;
        $wert=$prozsum / $count;
        $prozposdif=100-$wert;
        echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>";
        echo "<div style='float:left; background-color:darkgreen; color:lightgreen; height:16px; width:".$wert."px; top:0; left:0;' align=center></div>";
        echo "<div style='float:left; background-color:lightgreen; color:white; height:16px; width:".$prozposdif."px; top:0; left:0;' align=center></div>";
        echo sprintf("%.".$nachkomma."f",$wert)."</td>";
      break;
      case 'proz':
        $nachkomma=1;
        $wert=$prozsum / $count;
        $prozposdif=100-$wert;
        echo "<td style='text-align:right;padding-right:10px;' width='".$arrelement['width']."'>";
        echo "<div style='float:left; background-color:darkgreen; color:lightgreen; height:16px; width:".$wert."px; top:0; left:0;' align=center></div>";
        echo "<div style='float:left; background-color:lightgreen; color:white; height:16px; width:".$prozposdif."px; top:0; left:0;' align=center></div>";
        echo sprintf("%.".$nachkomma."f",$wert)."</td>";
      break;
    }
  }
}

echo "</tr>";
echo "</table>";
}

?>