<?php
// will a feed be kept for daily updating and thus have an opportunty to be in the daily results.

// how to decide?   a. feed active? - date time b. stats, greater zero  or c. melife great zero  probably combo of them all

function keepfeed ($feedidst)  {

//first need to findout date for last melife for this id
// this use melife and says if 25% above current lifestyle average feed can say, if not put of later list
$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $feedidst ORDER BY ".RSSDATA.".melife.date DESC LIMIT 1";

$resultstaydate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultstaydate) == 1 )  {

$rowdate = mysql_fetch_object($resultstaydate);
$datestay = $rowdate->date;

}


// this use melife and says if 25% above current lifestyle average feed can say, if not put of later list
$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $feedidst AND date = $datestay AND diffavg > 25";

$resultstay = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultstay) > 0)  {

// then flag feed as a 1
$db->query ="INSERT INTO  ".RSSDATA.".feedlinking (id, liveflag) VALUES ('$feedidst', '1') ";

$resultflagyes = mysql_query($db->query) or die(mysql_error());
// put out for crawl of brand new
//add function

}  // closes if


else  {

// flag as not to be included 0
// then flag feed as a 1
$db->query ="INSERT INTO  ".RSSDATA.".feedlinking (id, liveflag) VALUES ('$feedidst', '0') ";

$resultflagno = mysql_query($db->query) or die(mysql_error());
// not going to be crawl but put in list to be crawl when resources allow


}


}  // closes function




//  batch check all feeds to ensure they are worth daily updating
function batchkeep ()  {

global $feedids;

// get list of feeds
feedarray();

foreach ($feedids as $fidst)  {

//first need to findout date for last melife for this id
// this use melife and says if 25% above current lifestyle average feed can say, if not put of later list
$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $fidst ORDER BY ".RSSDATA.".melife.date DESC LIMIT 1";

$resultstaydate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultstaydate) == 1 )  {

$rowdate = mysql_fetch_object($resultstaydate);
$datestay = $rowdate->date;

}


// this use melife and says if 25% above current lifestyle average feed can say, if not put of later list
$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $fidst AND date = $datestay AND diffavg > 25";
echo $db->query;
$resultstay = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultstay) > 0)  {

// then flag feed as a 1
$db->query ="UPDATE ".RSSDATA.".feedlinking SET ".RSSDATA.".feedlinking.liveflag = 1 WHERE ".RSSDATA.".feedlinking.liveflag = '$fidst' ";

}  // closes if


else  {

// flag as not to be included 0
$db->query ="UPDATE ".RSSDATA.".feedlinking SET ".RSSDATA.".feedlinking.liveflag = 0 WHERE ".RSSDATA.".feedlinking.liveflag = '$fidst' ";

}

}  // closes foreach



}  // closes function




// find out which feeds are eligible for dailyupdate today
function livefeeds ()  {

global $todayfeeds;

$db->query ="SELECT ".RSSDATA.".feedlinking.id, ".RSSDATA.".feeds.url FROM ".RSSDATA.".feedlinking LEFT JOIN ".RSSDATA.".feeds ON  ".RSSDATA.".feeds.id = ".RSSDATA.".feedlinking.id  WHERE ".RSSDATA.".feedlinking.liveflag = 1 ";
//echo $db->query;
$resultliveupd = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultliveupd) > 0)  {

while($row = mysql_fetch_object($resultliveupd))  {
// create array of list of today urls to update

$todayfeeds[$row->id] = $row->url;

}  // closes loop
}  // closes if

return $todayfeeds;

}  // closes function











?>