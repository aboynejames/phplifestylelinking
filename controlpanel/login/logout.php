<?php
logoutfunction();
?>


<?php
// logout function

function logoutfunction () {
session_start();
unset($_SESSION);
// Unset all of the session variables.
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie((session_name), '', time()-42000, '/');}
session_destroy(); 


header("location: login.php");
exit();


}  // closes logoutfunction
?>
