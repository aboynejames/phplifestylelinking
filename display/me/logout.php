<?php session_start();
//print_r($_SESSION);
include_once ("addlogic.inc.php");

checkLogin('1 2');


pageheader();
?>


<body>
<?php
echo render_footer();  
?>

<?php
// fbconnnect logout
// checks that fb key, secret, callback url as they should be 
 
 if (is_fbconnect_enabled()) {
    ensure_loaded_on_correct_url();
  }
$fb_uidout = facebook_client()->get_loggedin_user();
//echo 'out'.$fb_uidout;
if($fb_uidout)  { 
fbconnectlogout ();
?>

<?php
echo render_footer();  
?>

<script type="text/javascript" >
<!--
FB.Connect.logoutAndRedirect('http://75.101.138.19/lifestylelinking/index.php');
-->
</script>


<?php

//echo 'after';
//print_r($_SESSION);
}


else {
logoutfunction();
}
?>


</body>
</html>

<?php
// logout function

function logoutfunction () {
unset($_SESSION);
// Unset all of the session variables.
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie((session_name), '', time()-42000, '/');}
session_destroy(); 


set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] );


header("location: http://www.mepath.com");
exit();


}  // closes logoutfunction



function fbconnectlogout () {
unset($_SESSION);
// Unset all of the session variables.
$_SESSION = array();
//if (isset($_COOKIE[session_name()])) {
  //  setcookie((session_name), '', time()-42000, '/');}
session_destroy(); 


}  // closes fbconnect logout


