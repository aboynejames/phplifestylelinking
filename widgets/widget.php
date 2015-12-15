<?php
include_once ("addlogic.inc.php");
include_once  ("lifestylelinking/widgets/widgetapi.php");
  
  
// get info from url to find out what has been asked for.   START of mepath API/ REST/Oauth implementation  	
$lifestylelidr = escapeinteger($_REQUEST['lifestyleid']);
//echo $lifestylelid; 
$lifestylesubid = escapeinteger($_GET['lifestylesubid']);
//echo $lifestylesubid;

$widlim = escapeinteger($_GET['widlim']);
//echo $widlim;
if ($widim = null )  {

$widlim = 5;
}


widgetcodelimit ($lifestylelidr, $widlim);

//echo $file;
//echo $widgetcode;

$widget = '';

$widget .="

var lifestylelinks = '';   

// write the badge
	
lifestylelinks+= '".$widgetcode."';

lifestylelinks = unescape(lifestylelinks);

document.write(lifestylelinks); 

";



echo $widget;


  
  ?>