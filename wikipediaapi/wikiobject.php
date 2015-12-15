<?php
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] );
include_once('wikipediaapi/botclasses.php');


//$start = new http();

$lifeobj = new wikipedia();

$page = 'Yoga';

$lifeobj->getpage($page, $revid=null);

echo $wwords;


?>