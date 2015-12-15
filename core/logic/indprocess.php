<?php

//  not in use as far as I know , superceeded by superior ranking algorithm JL 5jan 09
function lifeystlequalify ()  {


$db->query ="SELECT ".RSSDATA.".feeds.id FROM ".RSSDATA.".feeds ";

$resultblogs = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultblogs)  >  0 )  {

while ($row = mysql_fetch_object($resultblogs)) {

$feedids[] = $row->id;

}
}


$db->query ="SELECT * FROM ".RSSDATA.".lifestylestart ";

$resultlifeobj = mysql_query($db->query) or die(mysql_error());


if (mysql_num_rows($resultlifeobj)  >  0 )  {

while ($rowl = mysql_fetch_object($resultlifeobj)) {


// also going to need averages
$db->query ="SELECT * FROM ".RSSDATA.".lifestyleaverage WHERE ".RSSDATA.".lifestyleaverage.idlifestart = '$rowl->idlifestart'";

$resultavgs = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultavgs)  >  0 )  {


while ($rowla = mysql_fetch_object($resultavgs)) {

$lifeaverages[$rowl->idlifestart]['number'] = $rowla->idlifestart;;
$lifeaverages[$rowl->idlifestart]['avglife'] = $rowla->avglife;
$lifeaverages[$rowl->idlifestart]['postratio'] = $rowla->postratio;


}
}

}
}

//print_r($feedids);
//print_r($lifeaverages);

$date = time();


foreach ($feedids  as $feid )  {

$melife = '';

foreach ($lifeaverages as $favg)  {

$db->query ="SELECT * FROM (SELECT feed_id, idlifestart, (scoposts/noposts) as ratio, avgscore FROM ".RSSDATA.".lifestylelightstats WHERE ".RSSDATA.".lifestylelightstats.feed_id = '$feid' AND ".RSSDATA.".lifestylelightstats.idlifestart = $favg[number]) AS melife WHERE melife.ratio > $favg[postratio] AND melife.avgscore > $favg[avglife] ";
//echo $db->query;
$resultquali = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultquali) == 1 )  {

while ($rowins = mysql_fetch_object($resultquali) )  {


$melife .="('$rowins->idlifestart', '$rowins->feed_id', '$date' ), ";
//echo $db->query;
$resultinsqual = mysql_query($db->query) or die(mysql_error());

}
}

}  // closes foreach

$melife =substr($melife,0,(strLen($melife)-2));//this will eat the last comma
//echo $melife;
//echo '<br /><br />';

if (strLen($melife) > 0 )  {

$db->query =" INSERT INTO ".RSSDATA.".lifestylequalifiers (idlifestart, feed_id, date) VALUES ";

$db->query .= $melife;
//echo $db->query;
//echo '<br /><br />';
$resultavgfeed = mysql_query($db->query) or die(mysql_error());

}


}  // closes foreach


}  // closes function



?>