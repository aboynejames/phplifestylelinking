<?php session_start();
define( 'ABSPATH', dirname(dirname(__FILE__)) .'/' );
include_once(ABSPATH . 'controlpanel/controlhead.php'); 

?>
<h1>Themes </h1>
<br /><br />
Current theme<br />
<?php echo 'style.css'; ?>



<?php include_once (ABSPATH . "controlpanel/controlfooter.php"); ?> 