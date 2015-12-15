<?php

// tidy rawdata 
function stripclean ($rawcontent)  {

     $rawcontent = html_entity_decode($rawcontent);
//echo $row->content;    
    $rawcontentb = strip_tags($rawcontent);
     $remove = array("'", "-", ",", "(",")", "?", ".", "&rsquo;", "&ldquo;", "&rsquo;", "&rdquo;", ":", "@", "!", "#",  "^", "%", "/", "|", '\'', "+", "=", "{", "}", "[", "]", '"', ";", "<", ">", "_", "~", "<br />", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "also", "www", "jpg", "org", "html", "html", "http" );
     $rawcontentc = str_replace($remove," ", $rawcontentb); 
     $rawcontentd = trim($rawcontentc); 
     
         
$wlen = strlen($rawcontentd);      
//echo $wlen;

if ($wlen > 0)  {

     $rawcontente = explode(" ", $rawcontentd);
     $postcontent = $rawcontente; 
     
     }

     return $postcontent;


} // closes function




///////////////////  preparation of lifestyle definitions via wikipeida ////////////////////////////////////////////

// definition prep
function defininitionwords ($defid, $defwords) {


if (strlen($defwords) > 1 )  {

$db->query = "INSERT INTO ".RSSDATA.".definwords (word, idlifestart) VALUES ";

while(list($key, $val)=each($defwords))   {

$val = ereg_replace("(\\\*)+(/*)+('*)", "", $val);
$val = substr($val, 0, 30);
$val = trim($val); 
 //&&  !ereg("^\*", $val)

	   if(strlen($val) > 0 )   {
     
       $db->query .= "('$val', '$defid'),";
          
       }
    }
      
$db->query=substr($db->query,0,(strLen($db->query)-1));//this will eat the last comma
//echo $db->query;
// execute query
$resultw = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
 //mysql_free_result($resultw);

unset($defid);
unset($defwords);
//unset($itemid);
} // closes opening if


}  // closes function




// list top 50 definition words
function defweightedw ($defid)  {

global $specwords;
//echo 'special';
//print_r($specwords);

$db->query ="SELECT * FROM ".RSSDATA.".definwords WHERE ".RSSDATA.".definwords.idlifestart = '$defid' ";
//echo $db->query;

$resultpostwords = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultpostwords) > 0 )  {

while ($rowitw = mysql_fetch_object($resultpostwords))  {

if (strlen($rowitw->word) > 2 )  {

$words[] = strtolower($rowitw->word);

}


}  //closes while loop to create word from a post array greater the two characters
}
//print_r($words);

if (empty($words))  { }

else {

//  remove special words from post words
if ((isset($words) == 1)  &&  (isset($specwords) == 1) )  {

//$specwords = array('0'=>'the');
// contains an array of words that belong to both post and special words arrays
$a3 = array_intersect($words, $specwords);
//echo count($a3);
//echo '<br /><br />';
//print_r($a3);
//echo '<br /><br />';

if ( (isset($words) == 1 ) && (isset($a3) == 1 ) )  {

// removes special words to leave an array ready for sort and limit size
$result = array_diff_assoc($words, $a3);
//echo '<br /><br />prepwords';
//print_r($result);
//echo '<br /><br />';


$wordsorder = (array_count_values($result));
//print_r($wordsorder);

arsort($wordsorder);
//print_r($wordsorder);

// contains array of words order by frequency they scored, highest first, limited to fifty.
$postwords = array_slice($wordsorder, 0, 50);
}

//insert into db top50 def words

$db->query = "INSERT INTO ".RSSDATA.".lifestyledefinition (idlifestart, lifestylewords, votes) VALUES ";

foreach ($postwords as $lwords=>$votes)  {
      
$db->query .=" ('$defid', '$lwords', '$votes'),";

}

$db->query=substr($db->query,0,(strLen($db->query)-1));//this will eat the last comma
//echo $db->query;
// execute query
$resultw = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
 //mysql_free_result($resultw);

}
}
//print_r($postwords);

}  // closes function







// takes post, prepares words ready for processing, ie. splits and tidys and put them in a table ready for scoring.
function  blogpostwords ()  {

global $itemid;
global $projectidf;
global $firstchow;
global $lastchow;
global $firstitem;

// need to find the item range for the last feed_id added?
$db->query ="SELECT * FROM ".RSSDATA.".feeds ORDER BY ".RSSDATA.".feeds.id DESC LIMIT 1 ";
//echo $db->query;
$resultnewitems = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

$rowcurrfeed = mysql_fetch_object($resultnewitems);
$firstitem = $rowcurrfeed->id;



$db->query ="SELECT * FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.feed_id = '$firstitem' ORDER BY  ".RSSDATA.".items.id ASC LIMIT 1 ";
//echo $db->query;
$resultnewitemsfirst = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

$rowfirst = mysql_fetch_object($resultnewitemsfirst);
$firstchow = $rowfirst->id;

$db->query ="SELECT * FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.feed_id = '$firstitem' ORDER BY ".RSSDATA.".items.id DESC LIMIT 1 ";
//echo $db->query;
$resultchowlast = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

$rowsecond = mysql_fetch_object($resultchowlast);
$lastchow = $rowsecond->id;



// create a list of posts for this individual
$db->query = "SELECT * FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.id BETWEEN $firstchow  AND $lastchow ";
//echo $db->query;
$resultitems = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


if (mysql_num_rows($resultitems) > 0)  {

while($row = mysql_fetch_object($resultitems))  {
     
    $itemid = $row->id; 
    //echo $itemid; 
     
     $row->content = html_entity_decode($row->content);
//echo $row->content;    
    $rowtwo->content = strip_tags($row->content);
     $remove = array("'", "-", ",", "(",")", "?", ".", "&rsquo;", "&ldquo;", "&rsquo;", "&rdquo;", ":", "@", "!", "#",  "^", "%", "/", "|", '\'', "+", "=", "{", "}", "[", "]", '"', ";", "<", ">", "_", "~", "<br />", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
     $rowthree->content = str_replace($remove," ", $rowtwo->content); 
     $rowfour->content = trim($rowthree->content); 
    
// need to add regex to remove word starting with /  that databases don't like   add at  insert stage see below   
     
$wlen = strlen($rowfour->content);      
//echo $wlen;

     $rowthreespace->content = str_replace($remove," ", $rowtwo->content);
     $rowfourspace->content = trim($rowthreespace->content);
     $rowfive->content = explode(" ", $rowthree->content);
     $postcontent = $rowfive->content;
//print_r($postcontent);




postwords ($wlen, $postcontent, $itemid);


} //  closes while loop containing list of blog posts
}  // closes if a post ready for word prep.

// allocate votes/scoring based on wikipedia lifestyle definitions
blostposttopfifty ($firstchow, $lastchow); 


}  // closes individualdata function




function postwords ($wlen, $postcontent, $itemid)  {

if ($wlen > 1 )  {

$db->query = "INSERT INTO ".RSSDATA.".postwords (itemid, word) VALUES ";

while(list($key, $val)=each($postcontent))   {

$val = ereg_replace("(\\\*)+(/*)+('*)", "", $val);
$val = substr($val, 0, 30);
$val = trim($val); 
 //&&  !ereg("^\*", $val)

	   if(strlen($val) > 0 )   {
     
       $db->query .= "('$itemid', '$val'),";
          
       }
    }
      
$db->query=substr($db->query,0,(strLen($db->query)-1));//this will eat the last comma
//echo $db->query;
// execute query
$resultw = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
 //mysql_free_result($resultw);

unset($wlen);
unset($postcontent);
unset($itemid);
} // closes opening if


} // closes function



function specialwords ()  {


global $specwords;

// load up word type extentions
$db->query = "SELECT * FROM ".RSSDATA.".wordtypes ";
//echo $querytwo;

// execute query
$resultwtypes = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


while($row = mysql_fetch_object($resultwtypes))  {

// find those new words that already have a WordID
$db->query = "SELECT * FROM ".RSSDATA.".".$row->wordtype." ";
//echo $querytwo;

// execute query
$resulttwo = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


// see if any rows were returned
if (mysql_num_rows($resulttwo) > 0)  {
     // yes

while($row = mysql_fetch_object($resulttwo))  {
     
     $sword = trim($row->word);
	   $specwords[] = strtolower($sword);
          
       }


}


}  //  closes while loop
//print_r($specwords);


}  // closes function





function lifestylearray ()  {

global $lifewordsagg;
global $lifeawords;


$db->query ="SELECT * FROM ".RSSDATA.".lifestylestart ";
//echo $db->query;
$resultlifedef = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


if (mysql_num_rows($resultlifedef) > 0)  {

// creates loop to score every post on every lifestyle definition created
while ($rowa = mysql_fetch_object($resultlifedef))  {

//unset($postwords);
unset($lifewords);
unset($aa3);


$db->query ="SELECT * FROM ".RSSDATA.".lifestyledefinition WHERE ".RSSDATA.".lifestyledefinition.idlifestart = '$rowa->idlifestart' ORDER BY ".RSSDATA.".lifestyledefinition.votes DESC";
//echo $db->query;

$resultlifefifty = mysql_query($db->query) or die(mysql_error());

$num = $rowa->idlifestart;
//echo $num;
//$lifewords = array(array());

if (mysql_num_rows($resultlifefifty) > 0)  {

while ($rowlf = mysql_fetch_object($resultlifefifty))  {

$lifewords[strtolower($rowlf->lifestylewords)] = ($rowlf->votes) ;

}
//print_r($lifewords);
 
$lifewords;
//print_r($lifewords);

$lifewordsagg[$num] = $lifewords;

}

}  // cloes opening loop
}  // closes if
//echo '<br /><br />';
//print_r($lifewordsagg);

}  //closes lifestyle array





//////////////////////  lifestyle  averages   updated  24hrs   then more frequently,  allow user to update button? then real time   ///////////////////////////////////
//echo RSSDATA;
function dailyupdatedate ()  {

//$dupdate = time();
//echo $dupdate;

$secday = 86400;

$db->query="SELECT * FROM ".RSSDATA.".resultsdate ";

$resultsdate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultsdate) == 1 )  {

$row = mysql_fetch_object($resultsdate);

$lastresdate = $row->resultdate;

} // closes if

else {
// first time use of lifestylelinking first end result date
$firstresdate = time();

$db->query="INSERT INTO ".RSSDATA.".resultsdate (resultdate) VALUES ('$firstredate') ";

$resultsdate = mysql_query($db->query) or die(mysql_error());


}

$newresdate = $lastresdate + $secday;

return $newresdate;

} // closes function





// control management 

function itemmangement ()  {

//  first find out the itemid of the last row in the item table
$db->query ="SELECT ".RSSDATA.".items.id FROM ".RSSDATA.".items ORDER BY ".RSSDATA.".items.id DESC LIMIT 1";
//echo $db->query;
$resultslastid = mysql_query($db->query) or die(mysql_error());

$rowid = mysql_fetch_object($resultslastid);
$firstupdateid = $rowid->id;

// insert this value into  updatecontrol
$db->query ="INSERT INTO ".RSSDATA.".updatecontrol (itemid) VALUES ('$firstupdateid') ";
//echo $db->query;
$resultupdate = mysql_query($db->query) or die(mysql_error());


return $firstupdateid;

}  // closes function



// process new post items, tidy, split, matrix build
function newdailyitems ($todayfeeds, $firstupdateid)  {

global $firstchow;
global $lastchow;

$fedalready = $firstupdateid -1;

foreach ($todayfeeds as $tfid=>$turl)  { 

unset($count);
unset($curfeed);

//   this function actives monekchow rss reader to see if new feed exist for this blog url
$count = fof_update_feed($turl);

$curfeed = $tfid;

//  do scoring on a per blog post basis/ has any new blog posts been add for this feed?
$db->query ="SELECT * FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.feed_id = $tfid AND ".RSSDATA.".items.id > $fedalready ORDER BY ".RSSDATA.".items.id ASC LIMIT 1";
//echo $db->query;
$resultnewitemsfirst = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultnewitemsfirst) == 1 )  {

$rowfirst = mysql_fetch_object($resultnewitemsfirst);
$firstchow = $rowfirst->id;
//echo $firstchow;

//  find out id of last blog post added for this blog
$db->query ="SELECT * FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.feed_id = $tfid AND ".RSSDATA.".items.id > $firstupdateid ORDER BY ".RSSDATA.".items.id DESC LIMIT 1";
//echo $db->query;
$resultchowlast = mysql_query($db->query) or die(mysql_error());

$rowsecond = mysql_fetch_object($resultchowlast);
$lastchow = $rowsecond->id;
//echo $lastchow;

}  //  closes if no new posts then skip


if ($firstchow && $lastchow)  {

// create a list of posts for this individual
$db->query = "SELECT * FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.id BETWEEN $firstchow  AND $lastchow ";
//echo $db->query;
$resultitems = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultitems) > 0)  {

while($row = mysql_fetch_object($resultitems))  {

//unset($rowfour->content);

    $itemid = $row->id; 
    //echo $itemid; 
     
     $row->content = html_entity_decode($row->content);
    //echo $row->content;    
    $rowtwo->content = strip_tags($row->content);
     $remove = array("'", "-", ",", "(",")", "?", ".", "&rsquo;", "&ldquo;", "&rsquo;", "&rdquo;", ":", "@", "!", "#",  "^", "%", "/", "|", '\'', "+", "=", "{", "}", "[", "]", '"', ";", "<", ">", "_", "~", "<br />", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
     $rowthree->content = str_replace($remove," ", $rowtwo->content); 
     $rowfour->content = trim($rowthree->content); 
    
// need to add regex to remove word starting with /  that databases don't like   add at  insert stage see below   
     
$wlen = strlen($rowfour->content);      
//echo $wlen;

     $rowthreespace->content = str_replace($remove," ", $rowtwo->content);
     $rowfourspace->content = trim($rowthreespace->content);
     $rowfive->content = explode(" ", $rowthree->content);
     $postcontent = $rowfive->content;
//print_r($postcontent);

postwords ($wlen, $postcontent, $itemid);


} //  closes while loop containing list of blog posts
}  // closes if a post ready for word prep.

// allocate votes/scoring based on wikipedia lifestyle definitions
blostposttopfifty ($firstchow, $lastchow); 
unset($firstchow);
unset($lastchow);


}  // if there has been new post for this blog url


}  //closes foreach for each feed that needs split into single words and scored




} // closes function




?>