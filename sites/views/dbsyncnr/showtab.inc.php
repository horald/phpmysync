<?php
$listarray = array ( array ( 'label' => 'SyncNr',
                             'name' => 'syncnr', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'flddbsyncnr' ),
                     array ( 'label' => 'Gerät',
                             'name' => 'geraet', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldgeraet' ),
                     array ( 'label' => 'Datenbank',
                             'name' => 'datenbank', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'flddatabase' ),
                     array ( 'label' => 'DB-Typ',
                             'name' => 'dbtyp', 
                             'width' => 10, 
                             'type' => 'text',
                             'dbfield' => 'flddbtyp' ));


$pararray = array ( 'headline' => 'Sync-Nr ',
                    'dbtable' => 'tbldbsyncnr',
                    'orderby' => '',
                    'strwhere' => '',
                    'fldindex' => 'fldindex');
?>