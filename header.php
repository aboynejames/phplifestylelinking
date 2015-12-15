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


publicme ();
// sets display and user context for the page
mecontexttt ();

pageheader();

?>

<body>

	<div id="container"> <!--is closed in footer.php-->
	 
	 <div id="header">
	 
		  <div id="topTitle">
		  <!--For Logo and Title Text-->
		  
			  <a href="<?php makeurl('index.php') ?>?mags=1"><img src="<?php makeurl('display/images/lifestylelinking.png') ?>" title="lifestylelinking" alt="lifestylelinking" class="logo" /></a>
		 
		 	   <!--Need way to reference this definitions text for styling-->
		 	   <a href="<?php makeurl('definitions/index.php?letid=S') ?>">A to Z index</a>
		 
		  </div><!-- end #topTitle -->
		 
		  <div id="topNav">
		  	<!--Site Functions Navigation-->
				<ul>
					<li><a href="<?php makeurl('index.php') ?>">Home</a> </li>
					<!-- <li><a href="<?php makeurl('') ?>" id="current">Sign-in/openID</a></li> -->
					<!-- <li><a href="<?php makeurl('register.php') ?>" >Register</a> </li>  -->
				    <li><a href="<?php makeurl('help.php') ?>">Help</a></li>
				</ul>
			
			<h2 class="clear">Welcome to lifestylelinking</h2>         
			
		  </div><!-- end #topNav -->
	
	 </div><!-- end #header -->

	   
	 