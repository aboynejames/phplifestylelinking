<?php session_start();
include_once ("addlogic.inc.php");
include_once ('lifestylelinking/logic/functions.php');
checkLogin('1 2');

mecontexttt ();
//$_SESSION[metext] = 1;
//print_r($_SESSION);
//echo $lifestylelid;

  //We've included ../Includes/FusionCharts.php, which contains functions
  //to help us easily embed the charts.
  include("lifestylelinking/charts/FusionCharts.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" >
<head><title>lifestyle linking </title>

<SCRIPT LANGUAGE="Javascript" SRC="/fusionchart/JSClass/FusionCharts.js"></SCRIPT>

<link href="/lifestylelinking/css/mepathpink2.css" type="text/css" rel="stylesheet" id="stylesheet" />
<link href="/lifestylelinking/css/mepathmake.css" type="text/css" rel="stylesheet" id="stylesheet" />
</head>

<body>
<?php
// include the page header for signed in
include_once ("lifestylelinking/me/localheader.php");
?>  

<div class="mid-wrapper">
	<div class="sub-nav">
<?php
// include the page header for signed in
include_once ("lifestylelinking/me/menav.php");
?>
	</div>
	<div class="mid">




     <div class="topbox">   
           <div class="topboxleft">  
   
           </div>  <!-- closes topboxleft-->

           <div class="topboxright">  
<?php
lifestylesummary ();

echo '<br /><b>Current: <h2>'.$celname.'</h2></b>';

?>
           </div>  <!-- closes topboxright-->


          <div class="leftbox">           

<?php

echo $memenulist;

?>

        </div> <!-- closes leftbox-->

<?php
if ($_GET['metext'])  {
    
    $_SESSION[metext] = $_GET['metext'];
    
}

$_SESSION[metext] = 5;

if ($_SESSION[metext] == 5 )  {

include_once('lifestylelinking/make/createinc.php');

}




?>



</div> <!-- closes topbox-->

<br />
<br />

</div>   <!-- closes mid -->
	
<br />

</div>  <!-- closes mid wrapper-->

<?php
// include the page footer for signed in
include_once ("lifestylelinking/me/localfooter.php");
//print_r($_SESSION);

?> 
<?php
echo render_footer();  
?>
</body>


</html>

