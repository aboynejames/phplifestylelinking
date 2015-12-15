<?php session_start();
define( 'ABSPATH', dirname(dirname(__FILE__)) .'/' );
include_once(ABSPATH . 'controlpanel/controlhead.php'); 

function setdailytime ()  {

global $datest;
global $datefin;

$starttime = ' 00:00:01';
$endtime = ' 23:59:59';

$num = date("d") -1;

$day = sprintf("%02d", $num);

$datest = date("Y-m-").$day.$starttime;

$datefin = date("Y-m-").$day.$endtime;


}  // closes function

setdailytime();


if (isset($_POST['fdates'])) {

$fsdate = empty($_POST['fstartdate']) ? die ("Error: Enter fstartdate") : mysql_real_escape_string($_POST['fstartdate']);
$fedate = empty($_POST['fenddate']) ? die ("Error: Enter fenddate") : mysql_real_escape_string($_POST['fenddate']);
//$idstart = empty($_POST['idstart']) ? die ("Error: Enter idstart") : mysql_real_escape_string($_POST['idstart']);

require_once(ABSPATH . 'phpFlickr-2.3.0.1/phpFlickr.php');

$f = new phpFlickr("6cb8b950ac1b34c2300dc789c8da52af");

// all the search variables
//$searcharglist = array("user_id"=>"", "tags"=>"", "tag_mode"=>"", "text"=>"", "min_upload_date"=>"", "max_upload_date"=>"", "min_taken_date"=>"", "max_taken_date"=>"", "license"=>"", "sort"=>"", "privacy_filter"=>"", "bbox"=>"", "accuracy"=>"", "safe_search"=>"", "content_type"=>"", "machine_tags"=>"", "machine_tag_mode"=>"", "group_id"=>"", "contacts"=>"", "woe_id"=>"", "place_id"=>"", "media"=>"", "has_geo"=>"", "geo_context"=>"", "lat"=>"", "lon"=>"", "radius"=>"", "radius_units"=>"", "is_commons"=>"", "extras"=>"", "per_page"=>"", "page"=>"" );

lifestylestartarray ();
// loop to find top photos for each lifestyle object
foreach ( $lifeobjects as $idstart)  {


$db->query ="SELECT * FROM ".RSSDATA.".lifestyledefinition WHERE ".RSSDATA.".lifestyledefinition.idlifestart = '$idstart' ORDER BY ".RSSDATA.".lifestyledefinition.votes DESC LIMIT 4";

$resultdef = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;

$defwords = '';

if (mysql_num_rows($resultdef) > 0)  {

while($row = mysql_fetch_object($resultdef))  {
 

$defwords .= "$row->lifestylewords OR ";


}
}
//print_r($defwords);
$defwords =substr($defwords,0,(strLen($defwords)-4));//this will eat the last OR
//echo $defwords;

unset($argst);

$argst = array( "text"=>"".$defwords."", "min_taken_date"=>"".$fsdate."", "max_taken_date"=>"".$fedate."", "per_page"=>"20", "sort"=>"relevance", "page"=>"1");
//print_r($argst);

$triathlondaily = $f->photos_search ($argst);
//print_r($triathlondaily);


for ($x = 0; $x <= 15; $x++)  {

$photosre = '';

$photosre = $triathlondaily[photo][$x];

$photo_id = '';

$photo_id = $triathlondaily[photo][$x][id];
//echo $photo_id;

//  need to find photo username to get data to allow form piciture flickr url
$photoid = $f->photos_getInfo ($photo_id, $secret);
//print_r($photoid);
//echo $photoid[urls][url][$x][_content];

$image = $f->buildPhotoURL ($photosre, $size = "Thumbnail");

$imgtoday[$x][url] = $photoid[urls][url][0][_content];
$imgtoday[$x][title] = $photoid[title];
$imgtoday[$x] [image]= $image;
//print_r($imgtoday);


}  //closes forloop

/*<div class="rphoto"><a href="<?php echo $photoid[urls][url][0][_content]?>" title="<?php echo $photoid[title]?>"><img src="<?php echo $image ?>"  border ="1" /></a></div>
*/

if ($imgtoday)  {

$db->query ="INSERT INTO ".RSSDATA.".imagesource (idstart, orderid, imgurl, title, sourceurl, enddate) VALUES ";

foreach ($imgtoday as $key=>$meimg )  {

$smeurl = mysql_real_escape_string($meimg[url]);
$smetitle = mysql_real_escape_string($meimg[title]);
$smeimg = mysql_real_escape_string($meimg[image]);


$db->query .= "('$idstart', '$key',  '$smeurl',  '$smetitle',  '$smeimg', '$fedate'),";

 }  // closes foreach

$db->query=substr($db->query,0,(strLen($db->query)-1));//this will eat the last comma
//echo $db->query;
// execute query
$resultfsixteen = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

}


} // closes if statement

echo 'finished';
}  // closes foreach loop for each lifestyle object


?>



<br />
<br />
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
Update of flickr photos for each sport
<input type="text" name="idstart" size="4" value="">
<input type="text" name="fstartdate" size="42" value="<?php echo $datest ?>">
<input type="text" name="fenddate" size="42" value="<?php echo $datefin ?>">
<input type="Submit" name="fdates" value="Daily flickr photos"><br><br>
</form>
<br />
<br />

<?php
$lifestylelid = 1;

?>

<?php include_once (ABSPATH . "controlpanel/controlfooter.php"); ?> 