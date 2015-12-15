<?php session_start();
include_once ("addlogic.inc.php");
include_once ('lifestylelinking/logic/functions.php');
checkLogin('1 2');

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
<b>Help</b>
<div id="profileone">
What is lifestyle linking?
<br /><br />
Lifestyle linking is the vision behind mepath.com.  Let's start explaining what this means with a couple of definitions:
<br />
<br />
<b>lifestyle = act of living life.</b><br />
This is what we all do, authoring our story in blogs, forums, photos, videos, biostats etc.<br />
<br />
<b>linking = transfer of information to create personalized products/services.</b><br />
This is the bit mepath will give you the power to do.<br />
<br />
<b>community = sharing, learning and collaborating together.</b><br />
The best individual lifestyle choices and life outcomes can be discoverd and achieved together.

<br />
</div>  <!-- closesprofileone-->

<div id="profiletwo">
 <br /><b>Be cool to have a www.getsatisfaction.com  widget here.</b>
 <P>
 <a name="me"></a> 
 <b>me</b>
 <br />
 <br />
 This page provides the most up-to-date lifestyle linking.
 </p>
 <P>
 <a name="make"></a> 
 <b>make</b>
 <br />
 <br />
 This part of the service will allow the management of information and businesses to make personalized products and services.  Some people call this VRM, vendor relationhip managment.
 </p>
 <P>
 <a name="metrics"></a> 
 <b>metrics</b>
 <br />
 <br />
 Interested in all the analysis thats supports lifestyle linking?  This is the place to review the underlying information flows.
 </p>
</div>  <!--closes profiletwo -->



</div> <!--closes profilecontainer-->

</div>
	</div>
	
	<br />

</div>

<?php
// include the page footer for signed in
include_once ("lifestylelinking/me/localheader.php");
?> 
<?php
echo render_footer();  
?>

</body>
</html>
