<?php
$listarray = array ( array ( 'label' => 'Pfad',
                             'name' => 'pfad', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldpfad' ),
                     array ( 'label' => 'Bezeichnung',
                             'name' => 'bez', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldbez' ),
                     array ( 'label' => 'Bemerkung',
                             'name' => 'bez', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldbemerk' ),
                     array ( 'label' => 'DBSyncNr',
                             'name' => 'dbsyncnr', 
                             'width' => 10, 
                             'type' => 'text',
                             'dbfield' => 'flddbsyncnr' ),
                     array ( 'label' => 'DB-Typ',
                             'name' => 'dbtyp', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'flddbtyp' ),
                     array ( 'label' => 'DB-User',
                             'name' => 'dbuser', 
                             'width' => 100, 
                             'fieldhide' => 'true',
                             'type' => 'text',
                             'dbfield' => 'flddbuser' ),
                     array ( 'label' => 'DB-Passwort',
                             'name' => 'dbpassword', 
                             'width' => 100, 
                             'fieldhide' => 'true',
                             'type' => 'text',
                             'dbfield' => 'flddbpassword' ),
                     array ( 'label' => 'Typ',
                             'name' => 'dbtyp',
                             'width' => 20, 
                             'type' => 'selectid',
                             'dbtable' => 'tblselect',
                             'seldbfield' => 'fldbez',
                             'seldbindex' => 'fldindex',
                             'dbfield' => 'fldid_select' ),
                     array ( 'label' => 'List',
                             'width' => 5, 
                             'type' => 'icon',
                             'func' => 'tablelist.php?menu=table',
                             'dbfield' => 'icon-book' ));


$pararray = array ( 'headline' => 'Datenbanken',
                    'dbtable' => 'tbldatabase',
                    'orderby' => 'flddbsyncnr',
                    'strwhere' => '',
                    'fldindex' => 'fldindex');
?>