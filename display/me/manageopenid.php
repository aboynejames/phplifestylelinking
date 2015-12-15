<?php
// The above must be the VERY FIRST LINE in the ENTIRE file.
// There must be NO blank lines, doctypes, etc. before it.
session_start();
//print_r($_SESSION);
include_once ("addlogic.inc.php");
checkLogin('1 2');
require_once('lifestylelinking/logic/openid.php');

if ($_GET['alldone']) {
  allDone();
} elseif ($_POST['attached']) {
  submit();
} else {
  form();
}

?>


<?php
// this script captures the repeat pressing of the refreshbutton,  

function submit() {
session_start();
include_once ("addlogic.inc.php");
require_once('lifestylelinking/logic/openid.php');
//include('lifestylelinking/logic/functions.php');
//checkLogin('1 2');

//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

$self = $_SERVER['SCRIPT_NAME'];
  
$attachopenid = empty($_POST['attopenid']) ? die ("<br />Please type in openID Url") : mysql_escape_string($_POST['attopenid']);
$_SESSION [attached] = $attachopenid;


AttachOpenID($attachopenid, $_SESSION[user_id]);

echo render_footer();  
 

header("Location: $self?alldone=1");
  exit(0);


}  // closes function


function allDone() {
session_start();
include_once ("addlogic.inc.php");
require_once('lifestylelinking/logic/openid.php');
//include('lifestylelinking/logic/functions.php');

//checkLogin('1 2');


  $self = $_SERVER['SCRIPT_NAME'];

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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


<?php
// include the page header for signed in
//include_once ("lifestylelinking/loginfiles/menav.php");
?>


<?php



 
?>
<br />
<br />
<h1>The openID URL  has been attached.</h1>
<a href="<?php echo $self?>">Click here upload another openID url.</a>
<br />
</div>  <!--closes content-->
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
<?php
}  // closes alldone function






function form() {
session_start();
//print_r($_SESSION);
include_once ("addlogic.inc.php");
require_once('lifestylelinking/logic/openid.php');

//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

pageheader();

  $self = $_SERVER['SCRIPT_NAME'];
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
<?php
// include the page header for signed in
//include_once ("lifestylelinking/loginfiles/menav.php");


?>
<br />
Attach an openID here:
<div class="leftmid">
<?php 


if (!isset($_POST['attached'])) {
// form not submitted
?>

<BR><br>
<form method="post" action=" <?php echo $self ?> " >
 Please enter the openID URL:
<input type="text" name="attopenid" size="60" value="">
<input type="Submit" name="attached" value="Attach"><br><br>
</form>

<BR>
</br />
<br />
<?php
}

if (!isset($_POST['removeid']) && !isset($_POST['attached'])) {
// display current feeds and give ability to edit/update or delete

//GetOpenIDsByUser($_SESSION[user_id]);

$db->query="SELECT * FROM ".LIFEDATA.".user_openids WHERE ".LIFEDATA.".user_openids.user_id = '$_SESSION[user_id]' ";
//echo $db->query;

$result = mysql_query($db->query) or die(mysql_error());


// present results in table
if ( mysql_num_rows ($result)> 0) {

echo "Current openID URL(s) attached.";

echo "<table width=\"660\" border=\"1\" >";
echo "<tr>";
//echo "<th>" .'Number'. "</th>";
echo "<th width=\"100\">" .'openID URL'. "</th>";
//echo "<th>" .'Update'. "</th>";
echo "<th>" .'remove'. "</th>";
echo "</tr>";

while ($row=mysql_fetch_object($result)) {

?>

<form method="post" action=" <?php echo $self ?> " 
<tr>
<input type="hidden"  name="opendidid" value="<?php echo $row->openid_url  ?>">
<td>
<input type="text" size="80"  name="openidurl" value="<?php echo $row->openid_url ?>"></td>
<td>
<input type="Submit" name="removeid" value="Remove"  >
</td>
</tr>
</form>
<?php

}
echo "</table>";
}

}   // if feed not set for delete yet.


if (isset($_POST['attached']) && !isset($_POST['removeid']))  {
// its the first time a rss feed has been uploaded
//check if text typed in and start save process
$attachopenid = empty($_POST['attopenid']) ? die ("<br />Please type in openID Url") : mysql_escape_string($_POST['attopenid']);
$_SESSION [attached] = $attachopenid;


AttachOpenID($attachopenid, $_SESSION[user_id]);

}


// if feed needs to be deleted
// first need to identify which feed the user has selected.  Explode/trim deletefeed to get feedid

if (isset($_POST['removeid'])) {

$feedtodelete = empty($_POST['opendidid']) ? die ("There is no openID URL to delete") : mysql_escape_string($_POST['opendidid']);

$db->query="DELETE FROM ".LIFEDATA.".user_openids WHERE ".LIFEDATA.".user_openids.openid_url = '$feedtodelete' AND ".LIFEDATA.".user_openids.user_id = '$_SESSION[user_id]' ";

// execute query grouped words
$savelocal = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());



if ($savelocal) {


  $self = $_SERVER['SCRIPT_NAME'];

$db->query="SELECT * FROM ".LIFEDATA.".user_openids WHERE ".LIFEDATA.".user_openids.user_id = '$_SESSION[user_id]' ";
//echo $db->query;

$result = mysql_query($db->query) or die(mysql_error());

// present results in table
if ( mysql_num_rows($result) > 0) {

echo "Current openID URL(s) saved and attached.";

echo "<table width=\"660\" border=\"1\" >";
echo "<tr>";
//echo "<th>" .'Number'. "</th>";
echo "<th width=\"100\">" .'openID URL'. "</th>";
//echo "<th>" .'Update'. "</th>";
echo "<th>" .'remove'. "</th>";
echo "</tr>";

while($row=mysql_fetch_object($result)) {

?>
<form method="post" action=" <?php echo $self ?> " 
<tr>
<input type="hidden"  name="opendidid" value="<?php echo $row->openid_url ?>">
<td>
<input type="text" size="80"  name="openidurl" value="<?php echo $row->openid_url ?>"></td>
<td>
<input type="Submit" name="removeid" value="Remove"  >
</form>
</td>
</tr>
</form>

<?php

}
echo "</table>";
}


}  //closes if what do dispaly if delete pressed
} // closes if deleted pressed


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

<?php
echo render_footer();  
?>  

</body>
</html>

<?php

} //closes form function

?>