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
<b>Here you can update profile information:</b>
<br />
<br />
<div id="profileone">
Your user name is <b> <?php echo $_SESSION[username]; ?></b>
<br />

</div>  <!-- closesprofileone-->

<div id="profiletwo">

</div>  <!--closes profiletwo -->

</div> <!--closes profilecontainer-->
<br />
<br />
<img src="/images/openid-icon-small.gif"><a href="/lifestylelinking/me/manageopenid.php">Manage OpenID</a>
<br />
<br />
DATA PORTABILITY: The individual owns the data. We can only provide this in SQL file format for now. Email james@aboynejames.co.uk to request your data.
<br />
<br />
Want to delete you account?  Press <a href="/lifestylelinking/loginfiles/delete.php">DELETE</a>

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
