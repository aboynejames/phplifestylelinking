<?php session_start();
include_once ("addlogic.inc.php");
include_once  ("lifestylelinking/widgets/widgetapi.php");

?>


<?php

//print_r($_SESSION);

//print_r($_SESSION[lifemenu]);
//print_r($_SESSION[lifequalify]);
//print_r($_SESSION[topmenu]);

pageheader();
?>


<body>

<?php
// include the page header
include_once ("lifestylelinking/lifestylelinkinghead.php");
?>  

<div class="mid-wrapper">


  
	<div class="mid">
	<div class="content">           

        <div class="leftme">
<b>mepath widget maker</b>

<?php
lifestyledropdown ();

// code to make list drop down of lifestyles
echo $lifestyledrop;
?>
      </div>  <!-- closes leftme -->
      <div class="rightme">
      <b>code box: cut and paste to use</b>
<?php
//  display box with javascript code.
makewidgetcode ();
echo $widgeturl;
?>
<br />
<br />
<b>Preview of widget</b>
<br />
<br />
<?php
echo $widgetprev;
?>
</div>  <!-- closes rightme -->

</div> <!-- closes content-->

</div>   <!-- closes mid -->
	
	<br />

</div>  <!-- closes mid wrapper-->

<?php
// include the page footer for signed in
include_once ("lifestylelinking/me/localfooter.php");
?> 

</body>


</html>

