<?php
$listarray = array ( array ( 'label' => 'Seitennummer',
                             'name' => 'pageno', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldpageno' ),
                     array ( 'label' => 'Seitenname',
                             'name' => 'pagename', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldpagename' ),
                     array ( 'label' => 'Überschrift',
                             'name' => 'headline', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldheadline' ),
                     array ( 'label' => 'URL',
                             'name' => 'helpurl', 
                             'width' => 100, 
                             'type' => 'text',
                             'dbfield' => 'fldhelpurl' ));

$pararray = array ( 'headline' => 'Hilfeseiten',
                    'dbtable' => 'tblhelppage',
                    'orderby' => '',
                    'strwhere' => '',
                    'fldindex' => 'fldindex');
?>