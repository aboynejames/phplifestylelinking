<?php session_start();
define( 'ABSPATH', dirname(dirname(__FILE__)) .'/' );
include_once(ABSPATH . 'controlpanel/controlhead.php'); 

?>
<br />
<br />
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
Go prepare new peer groups for each lifestyle  
<input type="Submit" name="dailypeers" value="Every 24 hrs prepare new Peer group tables"><br><br>
</form>
<br />
<br />

<?php
if (isset($_POST['dailypeers'])) {

error_reporting(5);

lifestylestartarray ();

lifestylerank ();

associativeArraySlicep($lifeorder, $start, $end); 


}  // closes button pressed
?>

<br />
<br />
<br />
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
Find out top 5 lifestyles for each individual ie. feed 
<input type="Submit" name="melifetopfive" value="Find out top 5 lifestyles all feeds"><br><br>
</form>
<br />
<br />

<?php
if (isset($_POST['melifetopfive'])) {

error_reporting(5);
$date = time();

feedarray ();

standardtopmenutwo ();

foreach ($feedids as $efid )  {

$lifequalify = '';
$topmenu = array();
$rank = '';

memenu ($efid);
compareusermenutwo ($lifemenu, $lifequalify);

$db->query =" INSERT INTO ".RSSDATA.".toplife (feedid, rank, date, lifestyleid) VALUES ";


foreach ($topmenu as $tpf)  {

$rank++;

$db->query .="('$efid', '$rank', '$date', '$tpf'), ";

}

$db->query =substr($db->query,0,(strLen($db->query)-2)); //this will eat the last comma
$resultmelifet = mysql_query($db->query) or die(mysql_error());

}  // closes foreach

}  // closes button pressed

?>

<br />
<br />
<br />
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
Go prepare new personalized peer groups for each lifestyle  
<input type="Submit" name="perspeers" value="Create personalized peer groups"><br><br>
</form>
<br />
<br />

<?php
if (isset($_POST['perspeers'])) {

error_reporting(5);

mostlikeme ();


}  // closes button pressed

?>

<?php include_once (ABSPATH . "controlpanel/controlfooter.php"); ?> 