<?php
session_start();
include_once ("addlogic.inc.php");

error_reporting(5);


global $lifestylelid;
//print_r($_SESSION);
pageheader();
?>


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
	<div class="content">

<br />
To Personalize lifestyle linking, please enter the URL of a sporty blog you author or like to read:
<div class="leftmid">

<?php 
// if no form pressed then dispaly feedform and if added delete form)
if (!$_POST[feedadd] && !$_POST[feeddel])  {
feedform ();

// if feed already attached then display that too
feedattach (); 

}

elseif ($_POST[feedadd] == 1)  {

feedaddlink ();

feedform ();

// if feed already attached then display that too
feedattach (); 

}

elseif ($_POST[feeddel] == 1)  {

feeddelink ();

feedform ();

// if feed already attached then display that too
feedattach (); 

}
echo $mefeedt;

?>



</p>
</div>  <!-- closes leftmid-->

<div class="rightmid">

</div>  <!-- closes rightmid-->


</div>  <!-- closes content-->
	</div>
	
	<br />

</div>
<?php
// include the page footer for signed in
include_once ("lifestylelinking/me/localfooter.php");
?>  
  

</body>
</html>