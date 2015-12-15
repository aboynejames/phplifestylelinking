<?php	//header("Content-Type: application/xml; charset=ISO-8859-1");
  include_once ("addlogic.inc.php");
  include_once("lifestylelinking/rsscode/classes/RSS.class.php");	
   
$lifestylelid = escapeinteger($_GET['lifestyleid']);
  //echo $lifestylelid;

//$lifestylesubid = escapeinteger($_GET['lifestylesubid']);
//echo $lifestylesubid;
  
  $rss = new RSS();	echo $rss->GetFeed($lifestylelid);?>