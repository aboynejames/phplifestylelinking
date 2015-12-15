<?php

// find out top 50% of existing feeds for each definition, (second time of use, need to see if url has been already used ie. keep a list of url used already)
function scalelist ()  {

global $lifeobjects;
global $newinputurl;

//  need to find out last date.
$db->query ="SELECT * FROM ".RSSDATA.".melife ORDER BY ".RSSDATA.".melife.date DESC LIMIT 1 ";

//echo $db->query;
$resultmeldate = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultmeldate) > 0)  {

$row = mysql_fetch_object($resultmeldate);

$newfdate = $row->date;

}  // closes if

// need wrap this in a loop for each current lifesstyle definition
lifestylestartarray ();

foreach ($lifeobjects as $ldef )  {

$newurls = '';
$newurlmatch = '';
$newinputfeeds = '';
$newinputurl = '';

//$db->query ="SELECT * FROM ".RSSDATA.".feeds ORDER BY ".RSSDATA.".feeds.id DESC LIMIT 1 ";
$db->query ="SELECT  ".RSSDATA.".melife.feed_id, ".RSSDATA.".feeds.url, ".RSSDATA.".melife.idlifestart, ".RSSDATA.".melife.topmatch, ".RSSDATA.".melife.diffavg  FROM ".RSSDATA.".melife LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".melife.feed_id   WHERE ".RSSDATA.".melife.diffavg > 0 AND ".RSSDATA.".melife.date = $newfdate AND ".RSSDATA.".melife.idlifestart = $ldef GROUP BY ".RSSDATA.".melife.feed_id ORDER BY ".RSSDATA.".melife.diffavg desc";

echo $db->query;
$resulttopfeeds = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resulttopfeeds) > 0)  {

while($row = mysql_fetch_object($resulttopfeeds))  {

// create array of feed_ids
$newurls[$row->feed_id] = $row->url;

}  // closes while loop
}  // closes if

echo '<br />starttttt';
print_r($newurls);


//  see whether feeds_id have already been use to add new urls?  (need to setup new database table to log this)

$db->query ="SELECT * FROM ".RSSDATA.".newcheck WHERE ".RSSDATA.".newcheck.feed_id = ";

foreach ($newurls as $key=>$chfeed )  {

$db->query .= " '$key'  OR";

}  //closes foreach

$db->query = substr($db->query,0,(strLen($db->query)-2));//this will eat the last comma
echo $db->query;

$resultcheckn = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultcheckn) > 0)  {

while($row = mysql_fetch_object($resultcheckn))  {

// if they are matched, then the feed has already been used.
// left with a list of feedsids and urls that are new and should progress for inclusion in mepath.com
$newurlmatch[$row->feed_id] = $row->url;

}  // closes while loop
}  // closes if

else {  $newurlmatch = null;   }
echo '<br />matcheeeeed';
print_r($newurlmatch);

// we have original array and those that have been used, so 'merge' arrays to remove urls already used.

echo 'countone '.count($newurlmatch);
if (count($newurlmatch) > 0 )  {

$newinputfeeds = array_diff_key($newurls, $newurlmatch); 
echo 'INPUTTTa <br /><br />';
print_r($newinputfeeds);
echo 'closes inputtta';
}

else  {

$newinputfeeds = $newurls;
echo 'INPUTTTb';
print_r($newinputfeeds);

}
//echo 'INPUTTT';
//print_r($newinputfeeds);

echo count($newinputfeeds); 

if (count($newinputfeeds)  > 0 )  {

$chedurls = '';

// need to add these urls to newcheck to show the have been used.
$db->query =" INSERT INTO ".RSSDATA.".newcheck (feed_id, newcheck) VALUES ";

foreach ($newinputfeeds as $keych=>$checkupd )  {

$chedurls .="('$keych', '1' ), ";

}  // closes foreach

$chedurls =substr($chedurls,0,(strLen($chedurls)-2));//this will eat the last comma

$db->query .= $chedurls;
echo $db->query;
echo '<br /><br />';
$resultcheckurls = mysql_query($db->query) or die(mysql_error());


print_r($newinputfeeds);

//  now need to lookup those urls to find new crawled urls from that domain

if (count($newinputfeeds) > 0 )  {

$db->query ="SELECT * FROM ".RSSDATA.".crawlurlsb WHERE ".RSSDATA.".crawlurlsb.rawurl LIKE  ";

foreach ($newinputfeeds as $feid=>$starturl)  {
// need to loose the http://www from url
$removehttp = 'http://www.';
$shorturl = trim($starturl, $removehttp);

// form array of new ursl to be added
$db->query .= "'%$shorturl%' OR ";

}  // closes while loop

$db->query =substr($db->query,0,(strLen($db->query)-3));//this will eat the last comma
//echo $db->query;
$resultfindnurl = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


//  create array of all url that will make it be inputted for the first time
if (mysql_num_rows($resultfindnurl) > 0)  {

while($row = mysql_fetch_object($resultfindnurl))  {

// if they are matched, then the feed has already been used.
// left with a list of feedsids and urls that are new and should progress for inclusion in mepath.com
$newinputurl[] = $row->rawurl;

}  // closes while loop
}  // closes if
// make global
print_r($newinputurl);

}  // closes if new urls are ready
}  //  closes if  no new urls matched

}  // closes foreach loop each definition

echo '<br />total new';
print_r($newinputurl);

}  // closes function





function  addnewurl ()  {


//  put new urls out for RSS feed collection and first go at scoring.
foreach ($newinputfeeds  as  $keyn=>$newfurl )  {

echo $newfurl;
//  activates  monkeychow to add url to feedreader
fof_add_feed($newfurl);
//echo 'afteradd';

// split text into individual words in a table
blogpostwords ();

if($lastchow && $firstitem)  {
// allocate votes/scoring based on wikipedia lifestyle definitions
blostposttopfifty (); 

//  sum votes to an indiviudal to see if they qualify for a lifestyle inclusion
lifestylelightstatsi ();

}  // closes if

}  // closes foreach



}  // closes function

