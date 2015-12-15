<?php

// unit tests and data available test

function definitionspresent () 
{

// query definitions table
$db->query = "SELECT * FROM ".RSSDATA.".lifestyledefinition ";
//echo $db->query;
$resulttestdef = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resulttestdef) > 0) 
{
// do nothing
return true;
}

else {

//echo 'Please add a definition before proceeding';
return false;
}


}  // closes function






function  newitemtest ($nfid)
{

//$db->query = "SELECT COUNT(id) as nitems FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.feed_id = $nfid ";
$db->query = "SELECT ".RSSDATA.".items.id  FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.feed_id = $nfid ";
//echo $db->query;
$resultpostw = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

        if (mysql_num_rows($resultpostw) > 0 ) 
        {
        while($row = mysql_fetch_object($resultpostw))
        {
          
          $newitems[] = $row->id;

        }
        
       // count total no.
        $itemtotal = count($newitems);
        $firstitemid = array_shift($newitems);
        $lastitemid = end($newitems);
        $iteminfo[0] = $itemtotal;
        $iteminfo[1] = $firstitemid;
        $iteminfo[2] = $lastitemid;
        
        }
        
//print_r($iteminfo);
// array of iteminfo
return $iteminfo;

}  // closes function



function  postwordtest ($startit, $endit) 
{

// need items list

$db->query = "SELECT DISTINCT ".RSSDATA.".postwords.itemid  FROM  ".RSSDATA.".postwords WHERE ".RSSDATA.".postwords.itemid BETWEEN $startit AND $endit ";
//echo $db->query;
$resultpostww = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

      if (mysql_num_rows($resultpostww) >  0) 
      {
      // do nothing
      while ($row = mysql_fetch_object($resultpostww)) {
    
        $postwordsin[] = $row->itemid;
      }
      }
      $nopostitems = count($postwordsin);

      return $nopostitems;



}  // closes function





function scoretabletest ($startit, $endit) 
{

$db->query = "SELECT COUNT(itemid) as nscore FROM ".RSSDATA.".lifestylelightscorea WHERE ".RSSDATA.".lifestylelightscorea.itemid BETWEEN $startit AND $endit ";
//echo $db->query;
$resultpostsc = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

      if (mysql_num_rows($resultpostsc) ==  1) 
      {
      // do nothing
      $row = mysql_fetch_object($resultpostsc);
      $noscoreitems = $row->nscore;

      return $noscoreitems;

      }


}  // closes function




function statstest ($nfid)
{

$db->query = "SELECT COUNT(feed_id) as nstats FROM ".RSSDATA.".lifestylelightstats WHERE ".RSSDATA.".lifestylelightstats.feed_id = $nfid ";
//echo $db->query;
$resultpoststa = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

      if (mysql_num_rows($resultpoststa) ==  1) 
      {
      $row = mysql_fetch_object($resultpoststa);
      $statstotal = $row->nstats;

      return $statstotal;

      }  // closes if

}  // closes function




function melifetest ($nfid)
{

$db->query = "SELECT COUNT(feed_id) as nmelife FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $nfid ";
//echo $db->query;
$resultpostmel = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

      if (mysql_num_rows($resultpostmel) ==  1) 
       {
      $row = mysql_fetch_object($resultpostmel);
      $melifetotal = $row->nmelife;

      return $melifetotal;

      }  // closes if




}  // closes function




function topfivetest ($nfid)
{

$db->query = "SELECT COUNT(feedid) as ntoplife FROM ".RSSDATA.".toplife WHERE ".RSSDATA.".toplife.feedid = $nfid ";
//echo $db->query;
$resultpostfv = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

        if (mysql_num_rows($resultpostfv) ==  1) 
        {
        $row = mysql_fetch_object($resultpostfv);
        $topfivetotal = $row->ntoplife;

        return $topfivetotal;

        }  // closes if

}  // closes function




function  feelinktest ($nfid)
{

$db->query = "SELECT * FROM ".RSSDATA.".feedlinking WHERE ".RSSDATA.".feedlinking.id = $nfid ";
//echo $db->query;
$resultpostfl = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

        if (mysql_num_rows($resultpostfl) ==  1) 
        {
        $keptstatus = 1;

        return $keptstatus;
        }  // closes if

}  // closes function




function numberdefinitions ()
{

$db->query = "SELECT COUNT(idlifestart) as nlstart FROM ".RSSDATA.".lifestylestart ";
//echo $db->query;
$resultclid = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

      if (mysql_num_rows($resultclid) > 0 ) 
      {
      $row = mysql_fetch_object($resultclid);
      $ncountdef = $row->nlstart;

      // do nothing
      return $ncountdef;

      }  // closes if

}  // closes function



// when new url added the following tables should be popluated, feeds, item, postwords, lifestylelinkingscorea, lifestylelightstats, melife, toplife, feedlinking 
function newurltest ($nfeedid)  
{

// need feedid and no. definitions
$nodeftotal = numberdefinitions ();

$litemsinfo = newitemtest ($nfeedid);
// postwords, should be no items if contains words post cleaning
$postwcount = postwordtest ($litemsinfo[1], $litemsinfo[2]);
// div. by no definitions will get no. items (will be wrong if the no. defintions change over time)

// this this value eqaual the no. of items gather?  (could be different if post item has nil words afer cleaning, how many then blank?)
if ($postwcount && $litemsinfo[0])
{

$differencep = $litemsinfo[0] - $postwcount;

}  // closes if

// compare no. items to scorea table (could be different if no content)
$scoreitcount = scoretabletest ($litemsinfo[1], $litemsinfo[2]);
// div. by no definitions will get no. items (will be wrong if the no. defintions change over time)
$uniquescoreitems = $scoreitcount/$nodeftotal;

// this this value eqaual the no. of items gather?  (could be different if post item has nil words afer cleaning, how many then blank?)
if ($uniquescoreitems && $litemsinfo[0])
{

$differencescoreit = $litemsinfo[0] - $uniquescoreitems;

}  // closes if


// no postwords and no. lifestylelightscorea itemid should be the same
if ($differencep == $differencescoreit)  
{

echo 'Items test passed<br /><br />';

}  // closes if



// stats, one entry per definition
$statdeftotal = statstest ($nfeedid);
// here no. stats should equal the no defintions
if ($statdeftotal == $nodeftotal) 
{

echo 'Stats test passed<br /><br />';

}  // closes


// melife, one etry per definition
$melifestotal = melifetest ($nfeedid);
// here no. stats should equal the no defintions
if ($melifestotal  == $nodeftotal) 
{

echo 'melife test passed<br /><br />';

}  // closes



// toplife, up to five (if five definitions are added)
$topftotal = topfivetest ($nfeedid);
if ($topftotal < 6)
{

echo 'Toplife test passed<br /><br />';

}  // closes if


// feedlinking each feed should have corresponding feedlinking entry, 1 live 0 not qualifying)
$feedkept = feelinktest ($nfeedid);
if ($feedkept == 1)
{

echo 'Feed status checked<br /><br />';

}  // closes if

// what to do with testresults. Feedback to user to try gain, need to delete then auto restart processes.  Populate table with test results for audit checking



}  //closes function




?>