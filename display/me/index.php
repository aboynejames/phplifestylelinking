<?php session_start();
include_once ("addlogic.inc.php");
checkLogin('1 2');

unset($_SESSION[pubuser]);

mecontexttt ();

pageheader();
?>


<body>
<?php
// include the page header for signed in
include_once ("lifestylelinking/me/localheader.php");
?>  

<div class="mid-wrapper">

	<div class="mid">

     <div class="topbox">   
        <!--   <div class="topboxleft">  
   
           </div>  <!-- closes topboxleft--> 

<!--           <div class="topboxright">  
<?php
//lifestylesummary ();

//echo '<br /><b>Current: <h2>'.$celname.'</h2></b>';

?>
           </div>  <!-- closes topboxright-->


          <div class="leftbox">           
               <div class="leftboxedit">
               <a href="/lifestylelinking/me/index.php?metext=3">Add/Edit lifestyle</a>
               </div>
<?php

echo $memenulist;

?>

        </div> <!-- closes leftbox-->

<?php
// what content to display logic
if ($_GET['metext'])  {
    
    $_SESSION[metext] = escapeinteger($_GET['metext']);
    
}



if ($_SESSION[metext] == 4 )  {

include_once('lifestylelinking/me/homestart.php');

}


elseif ($_SESSION[metext] == 3 )  {

include_once('lifestylelinking/me/lifestylelist.php');

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

<script type="text/javascript" charset="utf-8">
  var is_ssl = ("https:" == document.location.protocol);
  var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
  document.write(unescape("%3Cscript src='" + asset_host + "javascripts/feedback-v2.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript" charset="utf-8">
  var feedback_widget_options = {};

  feedback_widget_options.display = "overlay";  
  feedback_widget_options.company = "mepath";
  feedback_widget_options.placement = "right";
  feedback_widget_options.color = "#ff0099";
  feedback_widget_options.style = "idea";
  
  
  
  
  var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
  <script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9823449-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</script>
<?php //print_r($_SESSION);  ?>
</body>


</html>

