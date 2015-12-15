<?php

////////   logic to produce a lifestyle stream based on personalized peer group members, post in the last 24 hrs period.


// any post from my per peer group last 24 hrs
function pergrpposts ($cfeedrid, $lifestylelid, $filtext)  {

global $mpllresults;
global $mpllresults;
global $llpaging;
global $entries_per_page;
global $offset;

//  first need to cross match list of per peers to posts.
// need to find that last 24 hrs period
$db->query ="SELECT * FROM  ".RSSDATA.".dailyposts ORDER BY ".RSSDATA.".dailyposts.enddate DESC LIMIT 1";

$resultmpdate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultmpdate) ==  1 )  {

$row = mysql_fetch_object($resultmpdate);
$mpdate = $row->enddate;

}

$startday = $mpdate - 86400;

// now check if current user has a peer group ie. has their userid ID been linked to one of the feeds in mepath?  If yes, bring back peer group, if NO a. either match to top peers for that lifestyle or match to another userid (only way to do that would be on basis of order/list of manually added lifestyle menu.


if ($cfeedrid == 598 )  {
//echo 'average joe';
// take list of lifestyle peers based on all feeds 
// need to build this function
$mepeers = peerlink ($cfeedrid, $lifestylelid);

}

else  {
//echo 'personziled me';
// individual has been linked to a feed, get personalized peers based on that id
// for which individual, form array of their peers
// is it lifestyle in 'top5' or manually added (for now if manually added then default to 'average' for those until figure out science to match to a peer)
// need function to pick out lifestylemenu manually added
$manadd = topfivemanual ($lifestylelid);
//echo 'man'.$manadd;
if ($manadd == 1)  {
//echo 'manual';
$mepeers = peerlink ($cfeedrid, $lifestylelid);
//print_r($mepeers);
}

else  {
//echo 'topfive, one of two options';
// if user linked to feed but the feed has no perpeer calculated then have to create 'average peer list again
$db->query ="SELECT * FROM ".RSSDATA.".perpeers WHERE ".RSSDATA.".perpeers.feedid = $cfeedrid ";
//echo $db->query;
$resultpergroup = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultpergroup) ==  0 )  {
//echo 'peeraverage';
$mepeers = peerlink ($cfeedrid, $lifestylelid);
  
}  // closes if

else  {
//echo 'personalized lifestylelinking';
//top5
//echo 'in top'.$cfeedrid.'lifestyle'.$lifestylelid;
$mepeers = perfeedids ($cfeedrid, $lifestylelid);

}
}
} // closes else

// is it all posts or flitered?
if ($filtext == 1 )  {

// UNFILTERED, latest from peergroup based on lifestyle but each post not filtered.
$db->query ="SELECT * FROM ".RSSDATA.".items LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".items.feed_id WHERE ";

foreach ($mepeers as $mps)  {

$db->query .=" (".RSSDATA.".items.feed_id = '$mps' ) OR";

}
$db->query =substr($db->query,0,(strLen($db->query)-3)); //this will eat the last OR
//$db->query .= " AND ".RSSDATA.".dailyposts.lifestyleid = $lifestylelid ";
$db->query .=" HAVING ".RSSDATA.".items.dcdate BETWEEN $startday AND $mpdate  LIMIT 100";
//echo $db->query;
$resultmps = mysql_query($db->query) or die(mysql_error());


paginationcount ($resultmps);


// UNFILTERED, latest from peergroup based on lifestyle but each post not filtered.
$db->query ="SELECT ".RSSDATA.".feeds.id, ".RSSDATA.".feeds.title AS author, ".RSSDATA.".feeds.link AS blogurl, ".RSSDATA.".items.link, ".RSSDATA.".items.title, ".RSSDATA.".items.content, ".RSSDATA.".items.dcdate FROM ".RSSDATA.".items LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".items.feed_id WHERE ";

foreach ($mepeers as $mps)  {

$db->query .=" (".RSSDATA.".items.feed_id = '$mps' ) OR";

}
$db->query =substr($db->query,0,(strLen($db->query)-3)); //this will eat the last OR
//$db->query .= " AND ".RSSDATA.".dailyposts.lifestyleid = $lifestylelid ";
$db->query .=" HAVING ".RSSDATA.".items.dcdate BETWEEN $startday AND $mpdate  ";
$db->query .="LIMIT $offset, $entries_per_page";
//echo $db->query;
$resultmps = mysql_query($db->query) or die(mysql_error());


peergroupdisplay ($resultmps, $filtext);

echo $mpllresults;
echo $llpaging;

}


elseif ($filtext == 2 )  {

$idlifestartp = idconvert($lifestylelid);

// FILTERED
// take list of feedids and see if any post from those in last 24 hrs
$db->query ="SELECT * FROM ".RSSDATA.".dailyposts  LEFT JOIN ".RSSDATA.".items on ".RSSDATA.".items.id = ".RSSDATA.".dailyposts.postid LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".items.feed_id  WHERE ";

foreach ($mepeers as $mps)  {

$db->query .=" (".RSSDATA.".items.feed_id = '$mps' ) OR";

}

$db->query =substr($db->query,0,(strLen($db->query)-3)); //this will eat the last OR
$db->query .=" HAVING ".RSSDATA.".dailyposts.enddate = $mpdate AND ".RSSDATA.".dailyposts.lifestyleid = $idlifestartp ";
//echo $db->query;
$resultmps = mysql_query($db->query) or die(mysql_error());

paginationcount ($resultmps);


// take list of feedids and see if any post from those in last 24 hrs
$db->query ="SELECT ".RSSDATA.".feeds.id, ".RSSDATA.".feeds.title AS author, ".RSSDATA.".feeds.link AS blogurl, ".RSSDATA.".items.link, ".RSSDATA.".items.title, ".RSSDATA.".items.content, ".RSSDATA.".items.dcdate, ".RSSDATA.".dailyposts.enddate, ".RSSDATA.".dailyposts.lifestyleid FROM ".RSSDATA.".dailyposts  LEFT JOIN ".RSSDATA.".items on ".RSSDATA.".items.id = ".RSSDATA.".dailyposts.postid LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".items.feed_id  WHERE ";

foreach ($mepeers as $mps)  {

$db->query .=" (".RSSDATA.".items.feed_id = '$mps' ) OR";

}

$db->query =substr($db->query,0,(strLen($db->query)-3)); //this will eat the last OR
$db->query .=" HAVING ".RSSDATA.".dailyposts.enddate = $mpdate AND ".RSSDATA.".dailyposts.lifestyleid = $idlifestartp ";
$db->query .="LIMIT $offset, $entries_per_page";
//echo $db->query;
$resultmps = mysql_query($db->query) or die(mysql_error());


// for now bash out display html but should re order results on peer rank rather an results context rank (need to put into array re order results as sql takes too long with left joins)
// call html presention function
peergroupdisplay ($resultmps, $filtext);

echo $mpllresults;
echo $llpaging;

}  //  closes elseif 


}  // closes function




// pagination count sums
function paginationcount ($firstres)  {

global $entries_per_page;
global $offset;
global $total_pages;

// pagination to prepare
$row = mysql_num_rows($firstres);
$total_entries = $row;
//echo $total_entries;
 $entries_per_page = 10;


 if(isset($_REQUEST['page_number']))
    { $page_number = $_REQUEST['page_number']; }
    else { $page_number = 1; }

//echo $page_number;

$pagenumber = $_REQUEST['page_number'];
//echo $pagenumber;


 $total_pages = ceil($total_entries / $entries_per_page);
// echo $total_pages;
 
 $offset = ($page_number - 1) * $entries_per_page;



}  // closes function





function peergroupdisplay ($resobject, $filtext)  {

global $mpllresults;
global $llpaging;
global $entries_per_page;
global $offset;
global $total_pages;

if ($resobject)  {

$mpllresults = '';
$llpaging = '';

// present results in table
if ( mysql_num_rows ($resobject) > 0) {

while($row = mysql_fetch_object($resobject)){

$datepost = $row->dcdate;

$postdate = date("l jS \of F Y h:i:s A", $datepost);


$limit = 100;

//  add functions to see if image or video can be extract from post?
//images extraction

$contentb = $row->content;

$matchesimg[2] = '';
str_img_src($contentb);

//  video extraction
//videoembed ($row->link);

//  if either then add html code below blog summary below


$cleanhtml = html_entity_decode($contentb);
$cleantags= strip_tags($cleanhtml);
$summary = $cleantags;

if (strlen($summary) > $limit) {
      $summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . '...';
      //echo $summary; 
      }
      
//$llresults .= "<div class=\"type\"><a href=\"".$currurl."?metext=1&lifestyleid=$lifestylelid&feedid=$row->id\"><img src=\"/lifestylelinking/images/rssicon.gif\"><a></div>";
$mpllresults .= "<div class=\"stream\">";

$mpllresults .= "<div class=\"imgbox\"><img src=\"/lifestylelinking/images/profpic.gif\"></div>";
$mpllresults .=   "<div class=\"btitle\"> ";
$mpllresults .=  "<div class=\"author\"><a href=\"".$row->blogurl."\"><b>".$row->author."</b></a></div><br />";

//<img src=\"/lifestylelinking/images/upchartsmall.gif\" >- <a href=\"".$currurl."?metext=2&lifestyleid=$lifestylelid&feedid=$row->id\"><img src=\"/lifestylelinking/images/charticonsmall.gif\"></a></div>";
$mpllresults .=  "<a href=\"".$row->link."\"><b>".$row->title."</b></a> </div>";


if ($matchesimg[2]) {

$jpgstring = '.jpg';
$pos = stristr($matchesimg[2], $jpgstring);
//echo $pos;
if ($pos === false )  {


$mpllresults .=   "<div class=\"bimg\"><img src=\"/lifestylelinking/images/blogimg.gif\"> </div> ";

}

else  {

$mpllresults .=   "<div class=\"bimg\"> ";
$mpllresults .=   "<a href=\"".$row->link."\"><img src=\"".$matchesimg[2]."\"  width=\"75\" height=\"75\" ></a></div>";
}
}  // closes if

$mpllresults .=   "<div class=\"bsum\"> ";
$mpllresults .=  "<div font-size=\"80%\" font-family=\"Verdana, sans-serif\" >$summary  <a href=\"".$row->link."\">more</a> </div> ";
$mpllresults .=  "<div style=\"font-size:75%\" ><b>$postdate</b></div><br /><br /></div>"; 



// if video (just youtube for now)
//if ($ytcode)  {

//$llresults .= '<object width=\"100\" height=\"100\"><param name="movie" value="'.$ytcode.'"></param><embed src="'.$ytcode.'" type="application/x-shockwave-flash" width="100" height="100"></embed></object>';

//}


$mpllresults .=  "</div>";
}  // closes while loop
} 


$llpaging .= "<div class=\"mepaging\" >";
for($page = 1; $page <= $total_pages; $page++)
{ if($page == $page_number)
  { // This is the current page. Don't make it a link.
    $llpaging .= "$page "; }

   else { // This is not the current page. Make it a link. 
          $llpaging .= " <a href=\"".$currurl."?lifestyleid=$lifestylelid&page_number=$page&filtext=$filtext\">$page</a> "; } 
}

$llpaging .= "</div>";

}  // closes if  results for this category?

else {

$mpllresults .= "No posts could be presented.";  

}

}  // closes function




// build array of an individuals peer group feedids
function  perfeedids ($fuserid, $lifestylelid)  {

$idlifestartp = idconvert($lifestylelid);

// check does lifestyle id need convert to idlifestart??
$db->query ="SELECT * FROM ".RSSDATA.".perpeers WHERE ".RSSDATA.".perpeers.feedid = '$fuserid' AND ".RSSDATA.".perpeers.idlifestart = '$idlifestartp' ";
//echo $db->query;
$resultperf = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultperf) >  0 )  {

while ($row = mysql_fetch_object($resultperf))  {

$inperfeeds[$row->rank] = $row->peerid;

}  
}  // closes if

//print_r($inperfeeds);
return $inperfeeds;

}  // closes function




// does this user attached to a feedid and thus a peer group or is 'average' or match on combition of lifestyles added manually
function peerlink ($curruserid, $lifestylelid)  {

// this user has no feedid associated with them.  Have they added lifestyles manually?

// b. find out all lifestyles and use to match to particular feedid
/*$db->query ="SELECT * FROM ".RSSDATA.".userlife WHERE ".RSSDATA.".userlife.userid = '$_SESSION[user_id]' ";

$resultmanfed = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultmanfed) >  0 )  {

while ($row = mysql_fetch_object($resultmanfed))  {

$lifemenu[] = $row->lifestyleid;

}  
}

//  Now give the combintion of manually added lifestyles which peer ie feedid is most life this user?
// NEED TO FINISH THINK OUT LOGIC
*/
// a.  mepath can use lifestyleid to get top peers for that lifestyle ie. query dailypeers table
$topeers = avgtoppeers  ($lifestylelid);

return $topeers;

}  // closes function





// find list of toppeers for a given lifestlye
function avgtoppeers  ($lifestyleid)  {

$idlifestartp = idconvert($lifestyleid);


// need to get latest individual lifestyle rankings date wise
$db->query ="SELECT ".RSSDATA.".dailypeers.date FROM ".RSSDATA.".dailypeers ORDER BY date DESC LIMIT 1 ";

$resultldate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultldate) == 1)  {

$rowdate = mysql_fetch_object($resultldate);


$ldate = $rowdate->date;
//echo $flid;
}

$db->query ="SELECT * FROM ".RSSDATA.".dailypeers LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".dailypeers.peerid  WHERE  ".RSSDATA.".dailypeers.idlifestart = $idlifestartp AND ".RSSDATA.".dailypeers.date = $ldate";
//echo $db->query;

$resultlikelife = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlikelife))  {

while ($row = mysql_fetch_object($resultlikelife))  {

$inperfeeds[$row->rank] = $row->peerid;

}
}

return $inperfeeds;

}  // closes function




function topfivemanual ($lifestylelida)  {

$db->query ="SELECT * FROM ".LIFEDATA.".userlife  WHERE ".LIFEDATA.".userlife.userid = '$_SESSION[user_id]'  AND ".LIFEDATA.".userlife.humadd = '1' ";

$resultmemenu = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;

if (mysql_num_rows($resultmemenu)  > 0 )  {

while($row = mysql_fetch_object($resultmemenu))  {

// pick out those manually added
if ( $lifestylelida == idconvert($row->lifestyleid))  {

$manaddm = 1;

}

}
}
//print_r($row);
return $manaddm;


}  // closes function



// old peergroups based on 'average'
//   rank peer group individuals
function lifestylerank ()  {

global $lifeobjects;
global $lifeorder;
global $new;


$date = time();

//  need to find the latest date  of melife date produced
$db->query ="SELECT * FROM ".RSSDATA.".melife ORDER BY date DESC LIMIT 1 ";

$resultlmedate = mysql_query($db->query) or die(mysql_error());
//echo $db->query;  


if (mysql_num_rows($resultlmedate)  == 1  )  {

$row = mysql_fetch_object($resultlmedate);

$lmedate = $row->date;

}


foreach ($lifeobjects as $lifestyleidc)  {

unset($lifelistc);
unset($lifeavgc);
unset($toplifec);


$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.idlifestart = $lifestyleidc AND ".RSSDATA.".melife.topmatch >= 1  HAVING ".RSSDATA.".melife.date = $lmedate ";

$resultlifelist = mysql_query($db->query) or die(mysql_error());
//echo $db->query;  


if (mysql_num_rows($resultlifelist)  >  0  )  {

while ($row = mysql_fetch_object($resultlifelist))  {

$lifelistc[] = $row->feed_id;

}



$db->query ="SELECT * FROM ".RSSDATA.".melife LEFT JOIN ".RSSDATA.".feeds on ".RSSDATA.".feeds.id = ".RSSDATA.".melife.feed_id WHERE ".RSSDATA.".melife.idlifestart = $lifestyleidc AND ".RSSDATA.".melife.topmatch >= 1  HAVING ".RSSDATA.".melife.date = $lmedate ORDER BY ".RSSDATA.".melife.diffavg DESC ";

$resultavglife = mysql_query($db->query) or die(mysql_error());
//echo $db->query;  


if (mysql_num_rows($resultavglife)  >  0  )  {

while ($row = mysql_fetch_object($resultavglife))  {

$lifeavgc[] = $row->feed_id;

}
}


$db->query =" SELECT * FROM ".RSSDATA.".melife LEFT JOIN ".RSSDATA.".feeds on ".RSSDATA.".feeds.id = ".RSSDATA.".melife.feed_id WHERE ".RSSDATA.".melife.idlifestart = $lifestyleidc AND ".RSSDATA.".melife.topmatch >= 1  HAVING ".RSSDATA.".melife.date = $lmedate ORDER BY ".RSSDATA.".melife.topmatch DESC  ";

$resulttoplife = mysql_query($db->query) or die(mysql_error());
//echo $db->query;  

if (mysql_num_rows($resulttoplife)  >  0  )  {

while ($row = mysql_fetch_object($resulttoplife))  {

$toplifec[] = $row->feed_id;

}
}


if ($lifeavgc && $toplifec)  {

unset($lifeorder);
unset($lifeavgca);
unset($toplifeca);

//$daypostsca = array_flip($daypostsc);
//print_r($lifelistc);
//echo '<br /><br />';
$lifeavgca = array_flip($lifeavgc);
//print_r($lifeavgca);
//echo '<br /><br />';
$toplifeca = array_flip($toplifec);
//print_r($toplifeca);


foreach ($lifelistc as $key => $obj )  {


$lifelistrankc = ($lifeavgca[$obj] + $toplifeca[$obj])/2 ;
//echo $lifelistrankc;

$lifeorder[$obj] = $lifelistrankc;


}

$lifeordersort = asort($lifeorder);
//echo '<br /><br />';
//print_r($lifeorder);

}

// find out length of peergroup list



$start = 0;
$end = 200;

//unset($new);
associativeArraySlicep($lifeorder, $start, $end);

//print_r($new);

$drpeers = '';
$rank = 0;


foreach ($new as $key => $rankid)  {


$rank++;

$drpeers .="( '$rank', '$date', '$lifestyleidc', '$key' ), ";
//echo $drposts;


}  // closes foreach loop



$drpeers=substr($drpeers,0,(strLen($drpeers)-2));//this will eat the last comma

if (strLen($drpeers) > 0 )  {  //  if no posts for that day, no need for query

$db->query ="INSERT INTO ".RSSDATA.".dailypeers (rank, date, idlifestart, peerid) VALUES ";

$db->query .="$drpeers";
//echo $db->query;
$resultpeerinsert = mysql_query($db->query) or die(mysql_error());

}

}  // closes if statement   any individuals qualify for this lifestyle?

}  // closes  foreach lifesstyle object loop




}  // closes function






function associativeArraySlicep($lifeorder, $start, $end) { 
    
global $new;   
   
    // Method param restrictions 
    if($start < 0) $start = 0; 
    if($end > count($lifeorder)) $end = count($lifeorder); 

    // Process vars 
   
    $new = Array(); 
    $i = 0; 

    // Loop 
    foreach($lifeorder as $key => $value) { 
        if($i >= $start && $i < $end) { 
            $new[$key] = $value; 
        } 
        $i++; 
    } 
    return($new); 


}   // closes function


?>