<?
/* Last updated with phpFlickr 1.3.2
 *
 * This example file shows you how to call the 100 most recent public
 * photos.  It parses through them and prints out a link to each of them
 * along with the owner's name.
 *
 * Most of the processing time in this file comes from the 100 calls to
 * flickr.people.getInfo.  Enabling caching will help a whole lot with
 * this as there are many people who post multiple photos at once.
 *
 * Obviously, you'll want to replace the "<api key>" with one provided 
 * by Flickr: http://www.flickr.com/services/api/key.gne
 */

require_once("phpFlickr.php");
$f = new phpFlickr("6cb8b950ac1b34c2300dc789c8da52af");

$photo_id = "2720343784";
$secret = "ceb3e059cf";

$detail = $f->photos_getInfo ($photo_id, $secret); 

//print_r($detail);


echo '<br /><br />';

// all the search variables
//$searcharglist = array("user_id"=>"", "tags"=>"", "tag_mode"=>"", "text"=>"", "min_upload_date"=>"", "max_upload_date"=>"", "min_taken_date"=>"", "max_taken_date"=>"", "license"=>"", "sort"=>"", "privacy_filter"=>"", "bbox"=>"", "accuracy"=>"", "safe_search"=>"", "content_type"=>"", "machine_tags"=>"", "machine_tag_mode"=>"", "group_id"=>"", "contacts"=>"", "woe_id"=>"", "place_id"=>"", "media"=>"", "has_geo"=>"", "geo_context"=>"", "lat"=>"", "lon"=>"", "radius"=>"", "radius_units"=>"", "is_commons"=>"", "extras"=>"", "per_page"=>"", "page"=>"" );


$argst = array( "text"=>"swimming OR running OR triathlon OR cycling OR ironman", "min_taken_date"=>"2009-03-29 00:00:01", "max_taken_date"=>"2009-03-30 23:59:59", "per_page"=>"20", "sort"=>"relevance", "page"=>"1");

$args = array("tags"=>"triathlon", "tag_mode"=>"any");

$triathlondaily = $f->photos_search ($argst);

//print_r($triathlondaily);

echo '<br /><br />';
?>

<html>

<header></header>
<style>

.sumphoto {
	width: 420px;
  height: 200px;
  background-color: white;
	margin-top: 10px;
	border: 3px solid rgb(225,219,220);
	
}


.rphoto {
	float: left;
	border: 0px;
	
}


</style>

<body>

<?php
echo '<div class="sumphoto">';

for ($x = 0; $x <= 3; $x++)  {

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

foreach ($imgtoday as $meimg )  {

?>
<div class="rphoto"><a href="<?php echo $meimg[url]?>" title="<?php echo $meimg[title]?>"><img src="<?php echo $meimg[image] ?>"  border ="1" /></a></div>


<?php
}

echo '</div>';


?>
</body>

</html>


<?php


/*
$userme = $f->people_findByUsername ('ecotorch');


print_r($userme);

echo '<br /><br />';

$usermeurl = $f->urls_getUserPhotos ('57668278@N00');

print_r($usermeurl);

echo '<br /><br />';



$userid = '57668278@N00';

$userlastest =$f->people_getPublicPhotos($userid, $extras, $per_page, $page);
//print_r($userlastest);

*/

function getMyRecent($userid = NULL, $extras = NULL, $per_page = NULL, $page = NULL)  {

global $photos;

$f = new phpFlickr("6cb8b950ac1b34c2300dc789c8da52af");

$photos = $f->people_getPublicPhotos($userid, $extras, $per_page, $page);
//print_r($photos);

//return $return;
//print_r($return);

}  // closes function


$userid = '57668278@N00' ;
$extras = NULL;
$per_page = NULL;
$page = NULL;

/*
getMyRecent($userid, $extras, $per_page, $page);

//print_r($photos);
//echo '<br /><br /> oneline';
//echo $photos[photos][photo][0][id];
//echo '<br /><br /> onelinedfd';
//print_r($photos[photos][photo][0]);

for ($x = 0; $x <= 100; $x++)  {

$photosre = '';

$photosre = $photos[photos][photo][$x];

//$f = new phpFlickr("6cb8b950ac1b34c2300dc789c8da52af");

$image = $f->buildPhotoURL ($photosre, $size = "Medium");

?>

<img src="<?php// echo $image ?>" /><br />

<?php
}  //closes forloop
*/
?>



<?php

/*
$extras = "[owner_name] => ecotorch";
$per_page = 10;
$page = NULL;


$recent = $f->photos_getRecent($extras, $per_page, $page);

foreach ($recent['photo'] as $photo) {
    $owner = $f->people_getInfo($photo['owner']);
    echo "<a href='http://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'] . "/'>";
    echo $photo['title'];
    echo "</a> Owner: ";
    echo "<a href='http://www.flickr.com/people/" . $photo['owner'] . "/'>";
    echo $owner['username'];
    echo "</a><br>";
}
*/


?>
