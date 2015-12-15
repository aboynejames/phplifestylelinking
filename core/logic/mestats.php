<?php


// new sats cal for individual feed
function singlefeedstat ($fed)  {

global $lifeobjects;
global $feeditems;
global $meavgnewstats;
global $scoredate;

$scoredate = time();
$feeditems = array();  
lifestylestartarray ();

//echo $fed.'fed';
foreach ($lifeobjects as $def)  {

// could LEFT JOIN scores and then use memory to do sum need to test.

//$db->query="SELECT ".RSSDATA.".items.id FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.feed_id = $fed";
$db->query="SELECT ".RSSDATA.".items.id, ".RSSDATA.".lifestylelightscorea.lifestyleid, ".RSSDATA.".lifestylelightscorea.score50, ".RSSDATA.".lifestylelightscorea.lifestyleid, ".RSSDATA.".lifestylelightscorea.matched1  FROM ".RSSDATA.".items LEFT JOIN ".RSSDATA.".lifestylelightscorea ON ".RSSDATA.".lifestylelightscorea.itemid = items.id  WHERE ".RSSDATA.".items.feed_id = '$fed ' AND ".RSSDATA.".lifestylelightscorea.lifestyleid = $def ";
//echo $db->query; 
$resultfeditem= mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultfeditem)  > 0  )  {

while ($row = mysql_fetch_object($resultfeditem))  {

$feeditems[$fed][$def][0][] = $row->score50;
$feeditems[$fed][$def][1][] = $row->matched1;
  
}
}

// put calculatation of stats in here???
statcalulator ($fed, $def, $feeditems);

}  // closes foreach lifestyle loop

$meavgnewstats =substr($meavgnewstats,0,(strLen($meavgnewstats)-2));//this will eat the last comma

//echo $meavgnewstats;
$db->query="INSERT INTO ".RSSDATA.".lifestylelightstats (feed_id, idlifestart, noposts, scoposts, lifestylescore, topmatch, avgscore, scoreratio, scoredate) VALUES ";
$db->query .= $meavgnewstats;
//echo $db->query;
$resultinsertavg = mysql_query($db->query) or die(mysql_error());


}  // closes function




// build array of all item ids  for each feed
function feeditems ()  {

global $feedids;
global $feeditems;
global $lifeobjects;
global $meavgnewstats;
global $scoredate;

$scoredate = time();

feedarray ();

$feedsize = count($feedids);
// need to look at number of feeds needing processed and if over 400 batch
$batch = 100;
if (($feedsize/$batch) < 1 )  
{
$nochunks = 0;
}

else {
$nochunks = ceil($feedsize/$batch);
//$nochunks = 0;
//echo $nochunks;
}

$batchchunk = array_chunk($feedids, $batch, true);
//print_r($batchchunk);

$feeditems = array();  
lifestylestartarray ();

for ($x = 0; $x <= $nochunks; $x++) {

//echo $x;
//echo '<br /><br />';
//print_r($batchchunk[$x]);
$meavgnewstats = '';

foreach ($batchchunk[$x] as $fed)  {

//echo $fed.'fed';
foreach ($lifeobjects as $def)  {

// could LEFT JOIN scores and then use memory to do sum need to test.
$db->query="SELECT ".RSSDATA.".items.id, ".RSSDATA.".lifestylelightscorea.lifestyleid, ".RSSDATA.".lifestylelightscorea.score50, ".RSSDATA.".lifestylelightscorea.lifestyleid, ".RSSDATA.".lifestylelightscorea.matched1  FROM ".RSSDATA.".items LEFT JOIN ".RSSDATA.".lifestylelightscorea ON ".RSSDATA.".lifestylelightscorea.itemid = items.id  WHERE ".RSSDATA.".items.feed_id = '$fed ' AND ".RSSDATA.".lifestylelightscorea.lifestyleid = $def ";
//echo $db->query; 
$resultfeditem= mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultfeditem)  > 0  )  {

while ($row = mysql_fetch_object($resultfeditem))  {

$feeditems[$fed][$def][0][] = $row->score50;
$feeditems[$fed][$def][1][] = $row->matched1;
  
}
}

// put calculatation of stats in here???
statcalulator ($fed, $def, $feeditems);



}  // closes foreach lifestyle loop



} // closes foreach feed loop

//print_r($feeditems);


$meavgnewstats =substr($meavgnewstats,0,(strLen($meavgnewstats)-2));//this will eat the last comma
//echo $meavgnewstats;


$db->query="INSERT INTO ".RSSDATA.".lifestylelightstats (feed_id, idlifestart, noposts, scoposts, lifestylescore, topmatch, avgscore, scoreratio, scoredate) VALUES ";
$db->query .= $meavgnewstats;
//echo $db->query;
$resultinsertavg = mysql_query($db->query) or die(mysql_error());

}  // closes for chunkloop


}  // closes function





function  statcalulator ($fed, $def, $feeditems)  {

global $feeditems;
global $meavgnewstats;
global $scoredate;

//$meavgnewstats = '';
$gotdata = '';

$gotdata = count($feeditems[$fed][$def][0]);
//echo $gotdata;
if ($gotdata  > 0 )  {

$meavsum = '';
$meavcount = '';
$meavscposcou = '';
$topmat = '';
//echo '<br />'.$feda.'fed'.$def.'def';
$meavsum = array_sum($feeditems[$fed][$def][0]);
//echo $meavsum.'sum';
$meavcount = count($feeditems[$fed][$def][0]);
//echo $meavcount.'count';

// no. post that have scored
//print_r($feeditems[$feda][$def]);
$meavscpos = array();

foreach ($feeditems[$fed][$def][0] as $scou)  {

if ($scou > 0 )  {

$meavscpos[] = $scou;

}
}

//print_r($meavscpos);
$meavscposcou = count($meavscpos);
//echo $meavscposcou.'scocount';

if ($meavscposcou > 0 )  {

$avgscore = ($meavsum)/($meavscposcou);
$avgscore =  round($avgscore, 2);
// scoreposts ration
$scavg = round(($meavscposcou/$meavcount), 2);

}

else  {
$avgscore = -1;
$scavg = -1;
}

$meavtpm = array();
// find no. topmatches
foreach ($feeditems[$fed][$def][1] as $tpm)  {

if ($tpm == 1 )  {

$meavtpm[] = $tpm;

}
}

//print_r($meavscpos);
$meavscpotpm = count($meavtpm);
//echo $meavscposcou.'scocount';

if ($meavscpotpm > 0 )  {

$topmat = $meavscpotpm;
//echo $topmat.'topcount';
}

else  {
$topmat = -1;
}


}  // closes if no array to score

else  {

$meavsum = -1;
$meavcount = -1;
$meavscposcou = -1;
$avgscore = -1;
$topmat = -1;
$scavg = -1;
}

// build to create insert sql string.
$meavgnewstats .= "($fed, $def, $meavcount, $meavscposcou, $meavsum, $topmat, $avgscore, $scavg, $scoredate), ";


} // closes function




?>