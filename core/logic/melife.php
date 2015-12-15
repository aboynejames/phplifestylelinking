<?php

// single feed melife calculator
function singlemelifecalc ($fed)  {

global $lifeobjects;
global $avgofavg;

$normdate = time();

avgofavg ();
lifestylestartarray ();
$melife = '';

foreach ($lifeobjects as $def)  {

// need topword match data (added to me avg stats table)
// need latest mestats foreach feed
$db->query="SELECT * FROM ".RSSDATA.".lifestylelightstats WHERE ".RSSDATA.".lifestylelightstats.feed_id = $fed AND ".RSSDATA.".lifestylelightstats.idlifestart = $def ORDER BY ".RSSDATA.".lifestylelightstats.scoredate DESC LIMIT 1 ";
//echo $db->query;
$resultstatarr = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultstatarr)  >  0 )  {

while ($row = mysql_fetch_object($resultstatarr)) {
// need to create new difference distance as % from data and save to db.
// need to do sums on avgavg array and stats avg array

if ((isset($row->avgscore)) == true  && (($row->avgscore) > 0) )  {

$diffpercent = (($row->avgscore - $avgofavg[$def][1])/$avgofavg[$def][1]) * 100;
$diffpercent = round($diffpercent, 2);

}

else {

$diffpercent = -1 ;

}
//$row->topmatch = '16';
$melife .="('$fed', '$def', '$row->topmatch', '$diffpercent', $normdate), ";

}
}

}  // closes defin. loop
//print_r($avgstats);


$melife =substr($melife,0,(strLen($melife)-2));//this will eat the last comma
//echo $melife;


$db->query ="INSERT INTO ".RSSDATA.".melife (feed_id, idlifestart, topmatch, diffavg, date) VALUES ";

$db->query .="$melife";
//echo $db->query;
$resultmelife = mysql_query($db->query) or die(mysql_error());


}  // closes function




//   MELIFE
//  updates the normalization of an individuals distance from a lifestyle average (the lifestyle average has been updated too)
function melifestyle ()  {

global $feedidsd;
global $feedids;
global $lifeobjects;
global $lifeaverages;

$lifedate = time();

foreach ($feedidsd as $fid)  {
echo $fid;
$melife = '';

foreach ($lifeobjects as $obj)  {

// query to find out how many topwords match
$db->query ="SELECT SUM(lifestyle.matched1) as noone FROM (SELECT * FROM ".RSSDATA.".lifestylelightscorea LEFT JOIN ".RSSDATA.".items on ".RSSDATA.".items.id = ".RSSDATA.".lifestylelightscorea.itemid WHERE ".RSSDATA.".items.feed_id = $fid AND ".RSSDATA.".lifestylelightscorea.lifestyleid = $obj) AS lifestyle ";
//echo $db->query;
$resultmecat = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultmecat) == 1 )  {

$row= mysql_fetch_object($resultmecat);

$wordonematch = $row->noone;


if (is_null($wordonematch))  {

$wordonematch = 0;

}

//echo $wordonematch;
}

$db->query ="SELECT * FROM (SELECT * FROM ".RSSDATA.".lifestylelightstats WHERE ".RSSDATA.".lifestylelightstats.feed_id = $fid AND ".RSSDATA.".lifestylelightstats.idlifestart = $obj ORDER BY ".RSSDATA.".lifestylelightstats.scoredate DESC LIMIT 1) AS meavg LEFT JOIN ".RSSDATA.".lifestyleaverage ON ".RSSDATA.".lifestyleaverage.idlifestart = meavg.idlifestart ORDER BY ".RSSDATA.".lifestyleaverage.date DESC LIMIT 1 ";
//echo $db->query;
$resultlifeobavg = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlifeobavg) == 1 )  {

$row= mysql_fetch_object($resultlifeobavg);

$melifeobavg = $row->avgscore;
$objavg = $row->avglife;

$diffpercent = (($melifeobavg-$objavg)/$objavg) * 100;

$diffpercent = round($diffpercent, 2);

}

else {

$diffpercent = 0 ;

}

$melife .="('$fid', '$obj', '$wordonematch', '$diffpercent', $lifedate), ";
//echo $melife;


}  // closes 2nd foreach


$melife =substr($melife,0,(strLen($melife)-2));//this will eat the last comma
//echo $melife;



$db->query ="INSERT INTO ".RSSDATA.".melife (feed_id, idlifestart, topmatch, diffavg, date) VALUES ";

$db->query .="$melife";
//echo $db->query;
$resultmelife = mysql_query($db->query) or die(mysql_error());


}  // closes open foreach



}  // closes function




//  calculate normalizations
function melifecal ()  {

global $feedids;
global $lifeobjects;
global $avgofavg;
$normdate = time();
// need array of lastest averages
avgofavg ();

//start feedlist
feedarray ();
// defintion list
lifestylestartarray ();
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

for ($x = 0; $x <= $nochunks; $x++) {

$melife = '';

foreach ($batchchunk[$x] as $fed)  {

foreach ($lifeobjects as $def)  {

// need topword match data (added to me avg stats table)
// need latest mestats foreach feed
$db->query="SELECT * FROM ".RSSDATA.".lifestylelightstats WHERE ".RSSDATA.".lifestylelightstats.feed_id = $fed AND ".RSSDATA.".lifestylelightstats.idlifestart = $def ORDER BY ".RSSDATA.".lifestylelightstats.scoredate DESC LIMIT 1 ";
//echo $db->query;
$resultstatarr = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultstatarr)  >  0 )  {

while ($row = mysql_fetch_object($resultstatarr)) {


// need to create new difference distance as % from data and save to db.
// need to do sums on avgavg array and stats avg array
if ((isset($row->avgscore)) == true  && (($row->avgscore) > 0) )  {

$diffpercent = (($row->avgscore - $avgofavg[$def][1])/$avgofavg[$def][1]) * 100;
$diffpercent = round($diffpercent, 2);

}

else {

$diffpercent = -1 ;

}
//$row->topmatch = '16';
$melife .="('$fed', '$def', '$row->topmatch', '$diffpercent', $normdate), ";

}
}

}  // closes defin. loop
}  // closes feed loop
//print_r($avgstats);


$melife =substr($melife,0,(strLen($melife)-2));//this will eat the last comma
//echo $melife;


$db->query ="INSERT INTO ".RSSDATA.".melife (feed_id, idlifestart, topmatch, diffavg, date) VALUES ";

$db->query .="$melife";
//echo $db->query;
$resultmelife = mysql_query($db->query) or die(mysql_error());

}  // closes for loop


setmelifedate ($normdate);

} // closes function



// set last melife date
function setmelifedate ($wdate)  {

$db->query =" INSERT INTO ".RSSDATA.".weeklydate (wdate) VALUES ($wdate)";

$resultdatew = mysql_query($db->query) or die(mysql_error());

}  // closes function




// build array of last averages of averages
function avgofavg ()  {

global $lifeobjects;
global $avgofavg;
// need list of all definitions
lifestylestartarray ();
//$date = time();


foreach ($lifeobjects as $def)  {

// find out latest avg avg date
$db->query ="SELECT * FROM ".RSSDATA.".lifestyleaverage  WHERE ".RSSDATA.".lifestyleaverage.idlifestart = $def  ORDER BY ".RSSDATA.".lifestyleaverage.date DESC LIMIT 1 ";

$resultavdate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultavdate)  ==  1 )  {

$row = mysql_fetch_object($resultavdate);
$avgdate = $row->date;

}

// need array of lastest averages
$db->query=" SELECT * FROM ".RSSDATA.".lifestyleaverage WHERE ".RSSDATA.".lifestyleaverage.idlifestart = $def  HAVING ".RSSDATA.".lifestyleaverage.date = $avgdate ";
//echo $db->query;
$resultavgavg = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultavgavg)  == 1 )  {

$row = mysql_fetch_object($resultavgavg);

$avgofavg[$row->idlifestart][] = $row->postratio;
$avgofavg[$row->idlifestart][] = $row->avglife;

}
}  // closes foreachloop

//return $avgofavg;
//print_r($avgofavg);

}  // closes function





//   creates the lifestyle average score for each lifestyle object we track.

function  avglifestats ()  {

$db->query ="SELECT * FROM ".RSSDATA.".lifestylestart ";

$resultlifeobj = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlifeobj)  >  0 )  {

$date = time();

while ($rowl = mysql_fetch_object($resultlifeobj)) {

$db->query ="SELECT (lifestyleavg.lifeposts/lifestyleavg.total) AS postratio, (lifestyleavg.lifescore/lifestyleavg.total) AS avglife  FROM (SELECT SUM(lifeavg.postavg) AS lifeposts, SUM(lifeavg.avgscore) AS lifescore, COUNT(lifeavg.postavg) AS total, COUNT(lifeavg.avgscore)  FROM (SELECT (".RSSDATA.".lifestylelightstats.scoposts/".RSSDATA.".lifestylelightstats.noposts) AS postavg, ".RSSDATA.".lifestylelightstats.avgscore FROM ".RSSDATA.".lifestylelightstats WHERE ".RSSDATA.".lifestylelightstats.idlifestart = '$rowl->idlifestart') AS lifeavg ) AS lifestyleavg ";

$resultlifeavg = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlifeavg)  == 1 )  {

$row = mysql_fetch_object($resultlifeavg);

// tiday rounding
$postratio =  round($row->postratio, 4);
$avglife =  round($row->avglife, 4);

$db->query ="INSERT INTO ".RSSDATA.".lifestyleaverage (date, idlifestart, postratio, avglife) VALUES ('$date', '$rowl->idlifestart', '$postratio', '$avglife' )";
//echo $db->query;
$resultinsertavg = mysql_query($db->query) or die(mysql_error());

}  //closes if averaged


}  // closes lifeobject loop
}


}  // closes function






//  makes lifestyle averages into array for efficent use in other functions

function lifeavgarray ()  {

global $lifeaverages;


// also going to need averages
$db->query ="SELECT * FROM ".RSSDATA.".lifestyleaverage ";

$resultavgs = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultavgs)  >  0 )  {


while ($rowla = mysql_fetch_object($resultavgs)) {

$lifeaverages[$rowl->idlifestart] = $rowla->postratio;


}
}

}  // closes functions





?>