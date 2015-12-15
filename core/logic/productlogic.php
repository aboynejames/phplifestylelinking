<?php

// logic to present right product given context, then fill in all field of personalization, third stage, free up whole make process

//  display products for a lifestyle 
function productlink ($lifestylelid)  {

global $perproduct;

$db->query ="SELECT * FROM ".RSSDATA.".websitelifestyle LEFT JOIN ".RSSDATA.".websites ON ".RSSDATA.".websites.webid = ".RSSDATA.".websitelifestyle.webid WHERE  ".RSSDATA.".websitelifestyle.lifestyleid = '$lifestylelid' ";

$resultperpro = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;
if (mysql_num_rows($resultperpro) >  0 )  {

$perproduct = '';

while ($row = mysql_fetch_object($resultperpro))  {
  
$perproduct .= "<div class=\"perlogo\"><a href=\"".$row->startper."\"><img src=\"".$row->logourl."\" height=\"28\" width=\"150\"><a></div>";
$perproduct .= "<div class=\"pername\"><a href=\"".$row->startper."\">Personalize product<a></div>";
$perproduct .= "<br /><br />";

}
}


}  // closes function



















?>