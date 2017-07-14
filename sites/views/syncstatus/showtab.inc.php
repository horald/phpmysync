<?php

$listarray = array ( array ( 'label' => 'Table',
                             'name' => 'table',
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldtable' ),
                     array ( 'label' => 'Timestamp',
                             'name' => 'timestamp',
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldtimestamp' ),
                    array ( 'label' => 'DB-Syncnr',
                             'name' => 'dbsyncnr',
                             'width' => 50, 
                             'type' => 'text',
                             'dbfield' => 'flddbsyncnr' ));


$pararray = array ( 'headline' => 'Sync-Status',
                    'name' => 'syncstatus',
                    'dbtable' => 'tblsyncstatus',
                    'orderby' => '',
                    'fldindex' => 'fldindex');

?>