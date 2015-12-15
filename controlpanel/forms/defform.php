<?php

// 
function   formmag ()  
{

global $t;

?>
<br />
<br />
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
<?php echo _("The magazine section to be created"); ?>:<br />
Magazine section name<input type="text" name="magname" size="24" value="">
<input type="hidden" name="_token" value="<?php echo $t; ?>" />
<input type="Submit" name="magsubmit" value="Create magazine section"><br><br>
</form>
<br />
<br />
<?php

}



function  formlifestyle ()
{

global $t;
?>

<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
<?php echo _("The lifestyle category to be created"); ?>:<br />
<input type="hidden" name="_token" value="<?php echo $t; ?>" />
Start definition <a href='http://www.wikipedia.org'>Wikipedia URL only</a> <input type="text" name="startdefurl" size="24" value="">
Lifestyle live yes = 1 <input type="text" name="lifecatyn" size="2" value="1">

<?php
//display list box of privacy options
$db->query="SELECT  * FROM  ".RSSDATA.".lifestylegroups";

$result = mysql_query($db->query) or die(mysql_error());

// present results in table
if ( mysql_num_rows ($result) > 0) {

echo 'Magazine section';
echo $row->groupname;
echo '<select name="magsid">';

while($row = mysql_fetch_object($result)){

echo "<option value=\"".$row->groupid."\">".$row->groupname." </option>";
}
}
?>
<input type="Submit" name="startdef" value="Create lifestyle category"><br><br>
</form>
<br />

<?php
} // closes function




function newdataform () 
{

global $t;
?>
<BR><br>
Firsttime entered ursl startingplace 
<BR><br>
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
Add a singleurl<input type="text" name="jprssid" size="80" value="">
<input type="hidden" name="_token" value="<?php echo $t; ?>" />
<input type="Submit" name="startrss" value="first time entry of urls"><br><br>
</form>
<br />
<br />
<?php


}  // closes function




function dateresultform () 
{

global $t;
global $ddate;
?>

 <?php dailytimes () ?>

<?php nextrestime(); ?>

<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
<input type="text" name="resultnewd" size="12" value="<?php echo $ddate; ?>">
<input type="hidden" name="_token" value="<?php echo $t; ?>" />
<input type="Submit" name="resultdateid" value="Change the result end date"><br><br>
</form>

<?php
}  // closes form



function dailyupdatefeeds () 
{
global $t;
?>
<br>
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
Update all Qualifying feeds. 
<input type="hidden" name="_token" value="<?php echo $t; ?>" />
<input type="Submit" name="dailyqualid" value="Every 24 hrs update QUALIFYING URLS"><br><br>
</form>
<?php

} // closes function



function dailymestatsform ()  
{

global $t;
?>
<br />
NEW ME STATS,  Every 24 hrs   (batched processed for now) 
<br>
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
<input type="hidden" name="_token" value="<?php echo $t; ?>" />
<input type="Submit" name="mestatcal" value="Every 24 re cal. ME STATAS"><br><br>
</form>
<br />
<?php

}  // closes function



function melifenormform ()
{
global $t;
?>
NEW MELIFE NORMALIZATION,  Every 24 hrs   (batched processed for now) 
<br>
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
<input type="hidden" name="_token" value="<?php echo $t; ?>" />
<input type="Submit" name="mecalstats" value="Every 24 re cal. MELIFE normalization"><br><br>
</form>
<br />
<?php

} // closes function



function dailyresultform ()
{
global $t;
global $ddate;
?>
<br />
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
Go prepare new results tables for mepath.com 
<!--<input type="text" name="startdate" size="12" value="">-->
<input type="text" name="finishdate" size="12" value="<?php echo $ddate; ?>">
<input type="hidden" name="_token" value="<?php echo $t; ?>" />
<input type="Submit" name="dailyresults" value="Every 24 hrs prepare new results tables"><br><br>
</form>
<br />
<?php

} // closes function



function maginsert ($indata) 
{

// need to add to lifestyle groups table and link lifestyle in it
$db->query="INSERT INTO ".RSSDATA.".lifestylegroups (groupname) VALUES ( '$indata') ";
// execute query grouped words
$resultgroup = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

echo ' successful add';

} // closes function





// form processing
function preventdform ()
{

$randtok = md5(uniqid(microtime()));

$token = substr($randtok, 0, 20);

return $token;

}// closes function




function dblifestyleinsert ($page, $startdefurl) 
{

global $objid;

// create query
$db->query="INSERT INTO ".RSSDATA.".lifestylestart (iddata, definition, defurl) VALUES ('2', '$page', '$startdefurl') ";
// execute query grouped words
$resultstartdef = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// find out the lifestyleid allocated to this new object
$db->query="SELECT * FROM ".RSSDATA.".lifestylestart ORDER BY ".RSSDATA.".lifestylestart.idlifestart DESC LIMIT 1 ";

$resultobid = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if(mysql_num_rows($resultobid) == 1)  {

$row = mysql_fetch_object($resultobid);
$objid = $row->idlifestart;
}

if ( $_POST['lifecatyn'] == 1) {
// if 1 add to lifestyle menu list too with same name as idlifestart
$db->query="INSERT INTO ".RSSDATA.".lifestyle (idlifestart, name, menuurl) VALUES ('$objid', '$page', '') ";
// execute query grouped words
$resultlifemenu = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

} // closes if lifestyle yes no 

if ( isset($_POST['magsid']))
{
// if 1 add to lifestyle menu list too with same name as idlifestart
$magroup = empty($_POST['magsid']) ? die ("<br />Please select.") : mysql_real_escape_string($_POST['magsid']);


// if 1 add to lifestyle menu list too with same name as idlifestart
$db->query="INSERT INTO ".RSSDATA.".grouplink (groupid, idlifestyle) VALUES ('$magroup', '$objid' ) ";
// execute query grouped words
$resultlifemenu = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

} // closes if lifestyle yes no 


}  // closes function



function callwikipedia ($page, $objid)  
{

//  1. make sure wikipedia url  2.  extract  page last par wiki/pagename   3.  pass pagename to wikipedia api  4.  prepare top words

$lifeobj = new wikipedia();

$wdefwords = $lifeobj->getpage($page, $revid=null);
//print_r($wdefwords);
$cleandef = stripclean ($wdefwords);
defininitionwords ($objid, $cleandef);
specialwords ();
defweightedw ($objid);
echo 'finished';

$sdate = time();
//  also need to create a new lifestyle average start score of 1
$db->query="INSERT INTO ".RSSDATA.".lifestyleaverage (date, idlifestart, postratio, avglife) VALUES ('$sdate', '$objid', '1', '1' ) ";
// execute query grouped words
$resultavgs = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


}  // closes function




?>