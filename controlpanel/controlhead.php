<?php session_start();
define( 'ABSPATH', dirname(__FILE__) .'/' );
include_once (ABSPATH . "addlogic.php");
//print_r($_SESSION);

// query db to find base folder url
$db->query ="SELECT * FROM ".RSSDATA.".siteinfo";
//echo $db->query;
$resultsetf = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultsetf)  > 0 )  {

while ($row = mysql_fetch_object($resultsetf))  {

$siteinfo[$row->type] = $row->siteinfo;

} 
}

$basefolderurl = $siteinfo[baseurl];
$abspath = $siteinfo[absolpath];

define( 'LLFOLDER', $basefolderurl );

function adminheader()  {
?>  
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
	<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
	   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		
		<title>open - lifestyle linking</title>
		<meta name="description" content="Main Page" />
		<meta name="page-topic" content="Homepage" />
		
		<meta name="robots" content="all" />
		<meta name="author" content="" />
		<meta name="author" content="" />
		<meta name="Keywords" content="" /> 	
		
		<!--Main Style Sheet-->
		<!--session selector to pick which stylesheet based on context if this header is in a php function-->
		<link href="<?php makeurl('controlpanel/css/admin.css') ?>" type="text/css" rel="stylesheet" id="stylesheet" />
		
	</head>
	
	<?php   
}  // closes pageheader()  

adminheader();

?>

<body>

	<div id="container"> <!--is closed in footer.php-->

<div class="controlheader">
<a href="<?php makeurl('controlpanel/index.php') ?>">HOME</a>
<a href="<?php makeurl('controlpanel/dailyupdate.php') ?>">Dailyupdate</a>
<a href="<?php makeurl('controlpanel/flickrdaily.php') ?>">Daily flickr photos</a>
<!--<a href="/lifestylelinking/oauth/oauth-php-r50/test/firstinstantance.php">OAuth</a> -->
Youtube done live
<a href="<?php makeurl('controlpanel/definitioncontrol.php') ?>">Definitions Control</a> 
<!-- <a href="/lifestylelinking/crawl/rsscrawlauto.php">Crawl centre</a>  -->
<a href="<?php makeurl('controlpanel/newdata.php') ?>">Add newdata</a>  
<a href="<?php makeurl('controlpanel/peercontrol.php') ?>">Peer group Control</a>
<a href="<?php makeurl('controlpanel/themes.php') ?>">Themes</a>
<a href="<?php makeurl('controlpanel/login/logout.php') ?>">logout</a>
</div>  <!-- closes controlheader class -->



