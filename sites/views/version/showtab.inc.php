<?php
$listarray = array ( array ( 'label' => 'Datum',
                             'name' => 'bez', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'flddatum' ),
                     array ( 'label' => 'Bezeichnung',
                             'name' => 'bez',      
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldbez' ),
                     array ( 'label' => 'Version',
                             'name' => 'version', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldversion' ));

$pararray = array ( 'headline' => 'Version',
                    'dbtable' => 'tblversion',
                    'orderby' => 'fldversion desc',
                    'strwhere' => '',
                    'fldindex' => 'fldindex');

?>