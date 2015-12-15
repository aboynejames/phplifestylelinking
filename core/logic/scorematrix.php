<?php

//////////////////////////////////////////// need to allocate votes in a'light' manner based on wikipedia lifestyle definition ///////////////////////////////////


function  blostposttopfifty ($firstchow, $lastchow)  {

global $specwords;
global $lifewordsagg;

$topseg = array ( 1, 2, 3, 4, 5, 10, 20, 50 );
//print_r($topseg);

$itembatch = '';


$db->query ="SELECT DISTINCT ".RSSDATA.".postwords.itemid FROM ".RSSDATA.".postwords WHERE ".RSSDATA.".postwords.itemid BETWEEN $firstchow  AND $lastchow ";
//echo $db->query;

$resultpost = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultpost) > 0 )  {

while ($rowbat = mysql_fetch_object($resultpost))  {

$itembatch[] = $rowbat->itemid;

}  // closes loop to create hold all itemids that need scored


foreach ($itembatch as $itemwords)  {

unset($words);
unset($a3);
unset($wordsorder);
unset($postwords);


$db->query ="SELECT * FROM ".RSSDATA.".postwords WHERE ".RSSDATA.".postwords.itemid = '$itemwords' ";
//echo $db->query;

$resultpostwords = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultpostwords) > 0 )  {

while ($rowitw = mysql_fetch_object($resultpostwords))  {

if (strlen($rowitw->word) > 2 )  {

$words[] = strtolower($rowitw->word);

}


}  //closes while loop to create word from a post array greater the two characters
//print_r($words);


if (empty($words))  { }

else {

//  remove special words from post words
if ((isset($words) == 1)  &&  (isset($specwords) == 1) )  {

// contains an array of words that belong to both post and special words arrays
$a3 = array_intersect($words, $specwords);
//echo count($a3);


if ( (isset($words) == 1 ) && (isset($a3) == 1 ) )  {

// removes special words to leave an array ready for sort and limit size
$result = array_diff_assoc($words, $a3);
//print_r($result);

$wordsorder = (array_count_values($result));
//print_r($wordsorder);

arsort($wordsorder);
//print_r($wordsorder);

// contains array of words order by frequency they scored, highest first, limited to twenty?
$postwords = array_slice($wordsorder, 0, 20);
//print_r($postwords);



if ( (isset($postwords) == 1)  &&  (isset($lifewordsagg) == 1) )  {

//print_r($lifewordsagg);
//echo "<br /><br />";


foreach ($lifewordsagg as $lifeindexid=>$lifewordsarray)  {

//print_r($lifewordsarray);

foreach ($topseg as $seg)  {

unset($aa3);
unset($lifewordsarrays);
unset($insertscore);


$lifewordsarrays = array_slice($lifewordsarray, 0, $seg);
//print_r($lifewordsarrays);

//echo "<br />newwww<br />";
$aa3 = array_intersect_key( $lifewordsarrays, $postwords);
//print_r($aa3);

if (count($aa3) > 0 ) {
//echo '<br /><br />';
$wordsmatched = count($aa3);
//echo $wordsmatched;
//echo '<br /><br />';
$postscore = array_sum($aa3);
//echo $postscore;
 
$scoring[$seg] = $postscore;
$matched[$seg] = $wordsmatched;

}

else {

$scoring[$seg] = 0;
$matched[$seg] = 0;

}




}  // closes slice foreachloop

$insertscore['matched'] = $matched;
$insertscore['score'] = $scoring;
//print_r($scoring);
//print_r($matched);

$insertmatched = '';

// now can create variable ready for inserting into a query
foreach ($matched as $pmat)  {

$insertmatched .= " '".$pmat."', ";

}
//echo $insertmatched;

$insertscored = '';
// now can create variable ready for inserting into a query
foreach ($scoring as $pscore)   {

$insertscored .= " '".$pscore."', ";


}
$insertscored=substr($insertscored,0,(strLen($insertscored)-2));//this will eat the last comma
//echo $insertscored;



$db->query ="INSERT INTO ".RSSDATA.".lifestylelightscorea (itemid, lifestyleid, matched1, matched2, matched3, matched4, matched5, matched10, matched20, matched50,  score1, score2, score3, score4, score5, score10, score20, score50 ) VALUES ";

$db->query .="( '$itemwords', '$lifeindexid', ";

$db->query .="$insertmatched ";

$db->query .=" $insertscored ) ";

//echo $db->query;

$resultinserts = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

//mysql_free_result($resultinserts);


}


}  // closes if both word array are present 

}  // closes are there any special words that need deleting

}  //  closes if both array in process if not start next loop


}  // closes else  array has been formed


}  // closes foreach loop for each item ie. blogpost to be processed

}  // if find no. blog posts ie. item query

} // if no post list skip all processing above and start with another post item


}  //  closes function





