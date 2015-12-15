<?php

//  summary statistics for the site built

// stats on no. feed polled each day
function activefeeds ()  
{

$db->query ="SELECT ".RSSDATA.".feedlinking.id FROM ".RSSDATA.".feedlinking ";

$resultafeds = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultafeds) > 0)  {

while($row = mysql_fetch_object($resultafeds))  {
// create array of list of today urls to update

$actfeeds[] = $row->id;

}  // closes loop
}  // closes if

$noactivef = count($actfeeds);
?>
<div id="">
Number of active feeds = <?php echo $noactivef ?> 
</div>

<?php
}  // closes function



// no. post collected all time
function postitemsall ()  
{
$db->query ="SELECT count(".RSSDATA.".items.id) as totms FROM ".RSSDATA.".items ";

$resulttotms = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resulttotms) == 1 ) 
  {
  $row = mysql_fetch_object($resulttotms);

  $totitems = $row->totms;

  }  // closes if

?>
<div id="">
Total number blogpost items = <?php echo $totitems ?> 
</div>


<?php
}  // closes function



function dailytimes ()  
{

global $lastupdate;

$lastupdate = dailyupdatedate ();
$engtime = date("F j, Y, g:i a", $lastupdate);

?>
<div id="">
Last results end date = <?php echo $engtime ?> 
</div>

<?php

}  // closes function



function  nextrestime ()  
{

global $lastupdate;

$lastupdate = dailyupdatedate ();
$rengtime = date("F j, Y, g:i a", ($lastupdate + 86400));

?>
<div id="">
Next results date (edit if required. UNIX time format) = <?php echo $rengtime ?> 
</div>

<?php

}  // closes function




function setnextrestime ()  
{

global $lastupdate;

$newdate = dailyupdatedate ();

// update db.
$db->query ="UPDATE ".RSSDATA.".resultsdate SET ".RSSDATA.".resultsdate.resultdate = '$newdate' ";

$resultuprest = mysql_query($db->query) or die(mysql_error());


}  // closes function



function livelifemags ()
{

$db->query="SELECT * FROM ".RSSDATA.".grouplink LEFT JOIN ".RSSDATA.".lifestylegroups ON ".RSSDATA.".lifestylegroups.groupid = ".RSSDATA.".grouplink.groupid LEFT JOIN ".RSSDATA.".lifestylestart ON ".RSSDATA.".lifestylestart.idlifestart =  ".RSSDATA.".grouplink.idlifestyle ";

$resultglink = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if(mysql_num_rows($resultglink) > 0)  {

echo 'LIVE ON SITE <br /><br />';

while ($row = mysql_fetch_object($resultglink)) {

echo $row->groupid  .  $row->groupname . '&nbsp lifestyle '  . $row->definition . '<br />';

}
}  // closes if


}  // closes function





// no. post last 24hrs period
function lasttwofourperiod ()  
{

global $lastupdate;
//echo $lastupdate;
// make a loop to get last 7 days daily new blog post volumes
// form array of time period
$lastweek = array(1, 2, 3, 4, 5, 6, 7);
$enddated = $lastupdate;

// form array of start and end dates
foreach ($lastweek as $wd)
{

$startd = $enddated;
$enddated = $startd - (86400 * $wd);

$startendd[$enddated] = $startd;

}
//print_r($startendd);

foreach ($startendd as $wds=>$wde)
{
$wdsp = $wds + 1;

$db->query ="SELECT count(".RSSDATA.".items.id) as totd FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.dcdate BETWEEN $wdsp AND $wde";

$resulttotmd = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resulttotmd) == 1 ) 
  {
  $row = mysql_fetch_object($resulttotmd);

  $dtotf[$wde] = $row->totd;

  }  // closes if

}  // closes foreach loop

?>
<div id="">
Daily blogpost items<br />
<?php
foreach ($dtotf as $dateend=>$totd )  
{

echo date("F j, Y, g:i a", $dateend) . ' volume= ' . $totd .'<br />';

}
?>
</div>

<?php
}  // closes function






?>