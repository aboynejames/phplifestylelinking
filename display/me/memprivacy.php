<?php session_start();
include_once ("addlogic.inc.php");
checkLogin('1 2 3');


pageheader();
?>


<body>
<?php
// include the page header for signed in
include_once ("lifestylelinking/me/localheader.php");
?>  

<div class="mid-wrapper">
	<div class="sub-nav">

	</div>
	<div class="mid">
	<div class="content">

<div id="profilecontainer">
<b>Privacy Settings</b> for  <?php echo $_SESSION[username]; ?>.
<br />
<br />
<div id="profileone">


<form action="<?=$_SERVER['PHP_SELF']?>"  method="post"  >
<table width="80%" border="0" cellpadding="2" cellspacing="1" >


<?php

//find out current privacy setting
$db->query="SELECT ".LIFEDATA.".privacy.privstatusid, ".LIFEDATA.".privstatus.privacy FROM ".LIFEDATA.".privacy LEFT JOIN ".LIFEDATA.".privstatus ON ".LIFEDATA.".privstatus.privstatusid = ".LIFEDATA.".privacy.privstatusid WHERE (".LIFEDATA.".privacy.ID = '$_SESSION[user_id]') ";

$privsavestatus = mysql_query($db->query) or die(mysql_error());
$rowpriv = mysql_fetch_object($privsavestatus);


//display list box of privacy options
$db->query="SELECT DISTINCT ".LIFEDATA.".privstatus.privacy, ".LIFEDATA.".privstatus.privstatusid FROM ".LIFEDATA.".privstatus LEFT JOIN ".LIFEDATA.".privacy ON privacy.privstatusid = ".LIFEDATA.".privacy.privstatusid WHERE ( ".LIFEDATA.".privstatus.privstatusid BETWEEN 1 AND 4) ";

$result = mysql_query($db->query) or die(mysql_error());

// present results in table
if ( mysql_num_rows ($result) > 0) {
echo "<tr>";
//echo "<td width=\"40%\">".'Who can see products you have saved?'."</td>";
echo "<td width=\"20%\">".'Current setting'."</td>";
echo "<td width=\"20%\"><b>" .$rowpriv->privacy. "</b></td>";
echo "<td width=\"20%\">".'Change <select name="saveid">';

while($row = mysql_fetch_object($result)){

echo "<option value=\"".$row->privstatusid."\">".$row->privacy." </option>";
}
}

echo "<tr>";
//echo "<td width=\"40%\">" .'&nbsp'. "</td>";
echo "<td width=\"20%\">" .'&nbsp'. "</td>";
echo "<td width=\"20%\">" .'&nbsp'. "</td>";
echo "<td width=\"20%\">" .'<input name="submit" type="submit"  value="Set Privacy" >'. "</td>";
echo "</tr>";

?>
</table>

</form>
<?php


if (isset($_POST['submit'])) {
// form not submitted display the form

$savedsetting=$db->escapeString($_POST['saveid']);

// save privacy settings
$setprivacy=$db->query=("UPDATE ".LIFEDATA.".privacy SET ".LIFEDATA.".privacy.ID = '$_SESSION[user_id]', ".LIFEDATA.".privacy.privstatusid = '$savedsetting' WHERE (".LIFEDATA.".privacy.ID = '$_SESSION[user_id]')");

// execute query on product 
$savedset = mysql_query($setprivacy) or die ("Error in query: $savematch. ".mysql_error());  

if($savedset)	{
        
        echo 'Privacy setting for Saved products has been set.';}
	 
}   

?>


</div>  <!-- closesprofileone-->

<div id="profiletwo">

 

</div>  <!--closes profiletwo -->



</div> <!--closes profilecontainer-->

</div>
	</div>
	
	<br />

</div>

<?php
// include the page footer for signed in
include_once ("lifestylelinking/me/localfooter.php");
?> 
<?php
echo render_footer();  
?>
</body>
</html>
