<?php

//////////////////////////////////////    results formation functions  ie. producing post content for results  //////////////////////////////////////////

//   data to present daily blog posts to mepath.com   1. pull together data into array, 2. apply three inclusion rules , preform ranking and save.
function dailylifestlyeposts ($finishtime)  {

global $lifeobjects;
global $dlposts;
global $feedd;
global $dayparr;
global $finishtime;

lifestylestartarray ();

//  this should be performed on a 24 regular period thus the time period for loop should be a period of 24hrs of seconds.
$secondsday =  86400;

$startday =   $finishtime - $secondsday;  
$stoptime = $finishtime - 1;

for ($x = $startday; $x <= $stoptime; $x += $secondsday)  {

//unset($dayparr);
$newday = $x + 1;
$endday = ($x + $secondsday);
//echo $endday;

// first find all post id for the 24 hr time period
$db->query ="SELECT DISTINCT ".RSSDATA.".items.id, ".RSSDATA.".items.feed_id FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.dcdate BETWEEN $newday AND $endday ";
//echo $db->query;
$resultdayposts = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultdayposts)  >  0 )  {

while ($row = mysql_fetch_object($resultdayposts)) {

$dayparr[$row->id] = $row->feed_id;

}
} 

$prefeed = $dayparr;
$dfeeds = array_unique($prefeed);

if (count($dayparr) > 0 )  
{

// need to find out last date used to calculate melife data 
$db->query ="SELECT * FROM ".RSSDATA.".melife  ORDER BY ".RSSDATA.".melife.date DESC LIMIT 1 ";

$resultdaymelife = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultdaymelife)  == 1 )  {

$rowmed = mysql_fetch_object($resultdaymelife);
$med  = $rowmed->date;

}


//  need to find out last scoredate for lifestylelightstats(take out so not repeated in loop?
$db->query ="SELECT * FROM ".RSSDATA.".lifestylelightstats  ORDER BY ".RSSDATA.".lifestylelightstats.scoredate DESC LIMIT 1 ";

$resultdaystatlife = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultdaystatlife)  == 1 )  {

$rowstd = mysql_fetch_object($resultdaystatlife);
$statd  = $rowstd->scoredate;

}

foreach ($dfeeds  as  $fedd)  {

//  for each post looks up feedid to find out if lifestyleid is within top5 places for this individual, if yes, post get included in results
$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $fedd  AND  ".RSSDATA.".melife.date = $med ORDER BY ".RSSDATA.".melife.diffavg DESC LIMIT 5";
//echo $db->query;
$resultmel = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultmel)  >  0 )  {

while ($row = mysql_fetch_object($resultmel)) {

$dlmel[$row->feed_id][1][] = $row->idlifestart;

}
}

}  // closes foreach loop

// loop through each deinfinition to build array of score data per post
foreach ($lifeobjects as $lifestyleidc)  {

// build array of scoring data for each post
$db->query ="SELECT ".RSSDATA.".lifestylelightscorea.itemid, ".RSSDATA.".lifestylelightscorea.lifestyleid, ".RSSDATA.".lifestylelightscorea.matched1,  ".RSSDATA.".lifestylelightscorea.matched2,  ".RSSDATA.".lifestylelightscorea.matched3,  ".RSSDATA.".lifestylelightscorea.matched5,  ".RSSDATA.".lifestylelightscorea.matched10, ".RSSDATA.".lifestylelightscorea.matched50, ".RSSDATA.".lifestylelightscorea.score10, ".RSSDATA.".lifestylelightscorea.score50 FROM ".RSSDATA.".lifestylelightscorea WHERE ";

foreach ($dayparr  as  $ditem=>$fe )  {

$db->query .="(".RSSDATA.".lifestylelightscorea.itemid = $ditem) OR";

}

$db->query=substr($db->query,0,(strLen($db->query)-3));//this will eat the last OR
$db->query .="HAVING ".RSSDATA.".lifestylelightscorea.lifestyleid = $lifestyleidc ";
//echo $db->query;
$resultscoredate = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// create array for further analysis etc.
if (mysql_num_rows($resultscoredate)  >  0 )  {

while ($row = mysql_fetch_object($resultscoredate)) {


$dlposts[$lifestyleidc][$row->itemid][] = $row->matched1;
$dlposts[$lifestyleidc][$row->itemid][] = $row->matched2;
//$dlposts[$lifestyleidc][$row->itemid][] = $row->matched10;
$dlposts[$lifestyleidc][$row->itemid][] = $row->score50;

}
}

// form array of lifedata on a per feed basis
$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ";

foreach ($dfeeds  as  $fedd)  {

$db->query .="(".RSSDATA.".melife.feed_id = $fedd ) OR";

}

$db->query=substr($db->query,0,(strLen($db->query)-3));//this will eat the last OR
$db->query .="HAVING ".RSSDATA.".melife.idlifestart = $lifestyleidc AND ".RSSDATA.".melife.date = $med ";
//echo $db->query;
$resultdmelife = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultdmelife)  >  0 )  {

while ($row = mysql_fetch_object($resultdmelife)) {

$feedd[$lifestyleidc][$row->feed_id][] = $row->topmatch;
$feedd[$lifestyleidc][$row->feed_id][] = $row->diffavg;


}
}



// 
foreach ($dfeeds  as  $fedd)  {

// check another avg stat.  if scoring post for lifestyle is over 75% then include the post even though the postscore context is low.
$db->query ="SELECT * FROM ".RSSDATA.".lifestylelightstats WHERE ".RSSDATA.".lifestylelightstats.feed_id = $fedd AND ".RSSDATA.".lifestylelightstats.idlifestart = $lifestyleidc  ORDER BY  ".RSSDATA.".lifestylelightstats.date DESC LIMIT 1 ";
//echo $db->query;
$resultfstats = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultfstats)  == 1 )  {

$row = mysql_fetch_object($resultfstats);

$dlmel[$row->feed_id][] = $row->scoreratio;


}

} // closes foreach feed

//echo 'before calc';
//print_r($dayparr);
//print_r($lifestyleidc);
resultcalc ($lifestyleidc, $dayparr, $dlmel);


}  // closes foreach defintion loop

}  // closes if new feed items for time period
}  // closes for each 24hr for loop


}  // closes function





function resultcalc ($lifestyleidc, $dayparr, $dlmel)   {

global $dlposts;
global $feedd;
global $dlmel;
global $dayparr;
global $finishtime;

// Does any post contain top lifestyle definition word?
$wordtopm = array();
$unsurep = array();
$scavgh = array();

foreach ($dayparr as $dpost=>$pfed)  {


$topmm = $dlposts[$lifestyleidc][$dpost][1];


if ($topmm  >= 1 )  {

$wordtopm[$dpost] = $topmm;
}

}  // closes foreach post loop

if (is_array($wordtopm) )  {

}

// prepare feed list minus those already in results via topword scoring.
if ($wordtopm)  {

$unsurep = (array_diff_key($dayparr, $wordtopm));
}

else  { $unsurep = $dayparr;  }

// what about post that have context but not top word, include those if a. feed me top5  or b. score avg. > 0.75 lifestlyes includes that lifestyle definition being processed.
foreach ($unsurep as $post=>$pfedid)  {

$fivematch = array_search($lifestyleidc, $dlmel[$pfedid][1]);

if ($dlmel[$pfedid][0] > 0.75 )  {

$scavgh[$post] = '1';
}

if ($fivematch === 0 )  {

$fivematch = true;

}

$topfive = array();

if ($fivematch > 0 )  {

$topfive[$post] = $fivematch;

}  // closes if match found

}  // closes loop of all post (minus topmatched posts)

// create an array those that match 
if (is_array($topfive) )  {


}


// limit to unique postids ie key index  that is if top5 and score freq. is over 75, even though context weak or low then post get included
$lifeavginc = array_intersect_key($topfive, $scavgh);
//echo 'top sco interse';
//print_r($lifeavginc);
//
// form array with all posts the qualify and contain topmatched data, topscore50 and diffavg data  (lastone trickiest to rank/build)
$rankdata = array_merge_keys($wordtopm, $lifeavginc);

$lppp = $dlposts[$lifestyleidc];

$rankexpand = array_intersect_key($dlposts[$lifestyleidc], $rankdata);

// set sort 
$SortOrder=0; // desc by default , 1- asc

// lifestyle rank for each  post
//  thinking here is to have two based on word context e.g. matched2 and score50   and  one/two on  lifestyleavg.  e.g diffavg/topmatched     could weight two group 2/3 word context  1/2 avg context.
// rank for topword
unset($indexord);
$indexord = (sortByField($rankexpand,'1',$SortOrder));

unset($trorder);
foreach ($indexord as $keytr => $trank)  {

$trorder[] = $keytr;

}

// rank for post score points
unset($indexordsc);
$indexordsc = (sortByField($rankexpand,'2',$SortOrder));

unset($scorder);
foreach ($indexordsc as $keytr => $trank)  {

$scorder[] = $keytr;

}


// need to combine rankings to an over all ranking
if ($trorder && $scorder)  {

unset($trordera);
unset($scordera);
//unset($drordera);
unset($postaggrank);
unset($postaggranka);



$trordera = array_flip($trorder);

$scordera = array_flip($scorder);


foreach ($trorder as $keyo => $postid )  {

$aggrank = ($trordera[$postid]/2) + ($scordera[$postid]/2) ;

$postaggrank[$postid] = round(($aggrank), 4);

}

asort($postaggrank);

$postaggranka = array_reverse($postaggrank, true);


// OK, last stage save ranking and appropriate info. to make display results as quick as possible.
$drposts = '';
$rank = 0;

foreach ($postaggranka as $key => $dayps)  {

$rank++;

$drposts .="( '$rank', '$finishtime', '$lifestyleidc', '$key' ), ";

}

$drposts=substr($drposts,0,(strLen($drposts)-2));//this will eat the last comma


if (strLen($drposts) > 0 )  {  //  if no posts for that day, no need for query

$db->query ="INSERT INTO ".RSSDATA.".dailyposts (rank, enddate, lifestyleid, postid) VALUES ";

$db->query .="$drposts";
//echo $db->query;
$resultpostinsert = mysql_query($db->query) or die(mysql_error());

}
}


}  // closes function



// need to update feeds for new data, score, stats, melife,   whole lifestyle averages  before running this function
// need to create online control panel secure for James to perform 24hrs updates.  NOW ONLINE at /lifestylelinking/loginfiles/rssfeed/dailyupdate

// merges arrays based on keys
function array_merge_keys($arr1, $arr2)  {

foreach($arr2 as $k=>$v) {
        if (!array_key_exists($k, $arr1)) { //K DOESN'T EXISTS //
            $arr1[$k]=$v;
        }
        else { // K EXISTS //
            if (is_array($v)) { // K IS AN ARRAY //
                $arr1[$k]=array_merge_keys($arr1[$k], $arr2[$k]);
            }
        }
    }
    return $arr1;
}





// function to sort multidimentional array
function sortByField($multArray,$sortField,$desc=true)  {

            $tmpKey='';
            $ResArray=array();

            $maIndex=array_keys($multArray);
            $maSize=count($multArray)-1;

            for($i=0; $i < $maSize ; $i++) {

               $minElement=$i;
               $tempMin=$multArray[$maIndex[$i]][$sortField];
               $tmpKey=$maIndex[$i];

                for($j=$i+1; $j <= $maSize; $j++)
                  if($multArray[$maIndex[$j]][$sortField] < $tempMin ) {
                     $minElement=$j;
                     $tmpKey=$maIndex[$j];
                     $tempMin=$multArray[$maIndex[$j]][$sortField];

                  }
                  $maIndex[$minElement]=$maIndex[$i];
                  $maIndex[$i]=$tmpKey;
            }

           if($desc)
               for($j=0;$j<=$maSize;$j++)
                  $ResArray[$maIndex[$j]]=$multArray[$maIndex[$j]];
           else
              for($j=$maSize;$j>=0;$j--)
                  $ResArray[$maIndex[$j]]=$multArray[$maIndex[$j]];

           return $ResArray;
       
       }   // closes function



//////////////  set results time //////////////////////////////////////////


function resulttime ($newresdate)
{

// if first time prompt for start date
// save privacy settings
$db->query="UPDATE ".RSSDATA.".resultsdate SET ".RSSDATA.".resultsdate.resultdate = '$newresdate' ";

// execute query on product 
$resultupdr = mysql_query($db->query) or die(mysql_error());


}  // closes function
