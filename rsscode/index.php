<?php
  include_once ("addlogic.inc.php");
  include_once("lifestylelinking/rsscode/classes/RSS.class.php");
   
$lifestylelid = escapeinteger($_GET['lifestyleid']);
  //echo $lifestylelid;

//$lifestylesubid = escapeinteger($_GET['lifestylesubid']);
//echo $lifestylesubid;
  
  $rss = new RSS();