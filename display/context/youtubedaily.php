<?php
require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
Zend_Loader::loadClass('Zend_Gdata_YouTube');


//  need to take lifestyleid and look up top two def words and pass them on to search function
function youtubekeywords ($lifestylelid)  {

unset($lifestart);
global $searchTerms;
global $lifestart;
global $lifestylelid;


$lifestylelid = $lifestart;


$db->query ="SELECT * FROM ".RSSDATA.".lifestyledefinition WHERE ".RSSDATA.".lifestyledefinition.idlifestart = '$lifestart' LIMIT 2";

$resultdeft = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;

$defwordst = '';

if (mysql_num_rows($resultdeft) > 0)  {

while($row = mysql_fetch_object($resultdeft))  {
 

$defwordst .= "$row->lifestylewords +";


}

$defwordst =substr($defwordst,0,(strLen($defwordst)-2));//this will eat the last OR
//echo $defwords;
}

$searchTerms = $defwordst;
//echo $searchTermsa;
//$searchTerms = "swimming | swim ";


}  // closes function



// forms query string for youtube
function searchAndPrint($searchTerms)
{

  $yt = new Zend_Gdata_YouTube();
  $yt->setMajorProtocolVersion(2);
  $query = $yt->newVideoQuery();
  $query->setOrderBy('relevance');  
  //$query->setSafeSearch('moderate');
  $query->settime('today');
  $query->setMaxResults(6);
  $query->setVideoQuery($searchTerms);

  // Note that we need to pass the version number to the query URL function
  // to ensure backward compatibility with version 1 of the API.
  $videoFeed = $yt->getVideoFeed($query->getQueryUrl(2));
  printVideoFeed($videoFeed);
  //, 'Search results for: ' . $searchTerms);
}



//
function getAndPrintVideoFeed($location = Zend_Gdata_YouTube::VIDEO_URI)
{
  $yt = new Zend_Gdata_YouTube();
  // set the version to 2 to receive a version 2 feed of entries
  $yt->setMajorProtocolVersion(2);
  $videoFeed = $yt->getVideoFeed($location);
  printVideoFeed($videoFeed);
}
 
 
//  returns results  and echo out code required for displaying video thumbnail and embedded code
function printVideoFeed($videoFeed)
{
  $count = 1;
  foreach ($videoFeed as $videoEntry) {
   // print "Entry # " . $count . "\n";
    //printVideoEntry($videoEntry);
    //embedvideo ($videoEntry);
    ythumbnail ($videoEntry, $count);
    jsdivcode ($videoEntry, $count);
    
    //print "\n";
    $count++;
  }
}



//  echo out code for each video entry
function printVideoEntry($videoEntry) 
{
  // the videoEntry object contains many helper functions
  // that access the underlying mediaGroup object
  echo 'Video: ' . $videoEntry->getVideoTitle() . "\n";
  echo 'Video ID: ' . $videoEntry->getVideoId() . "\n";
  echo 'Updated: ' . $videoEntry->getUpdated() . "\n";
  echo 'Description: ' . $videoEntry->getVideoDescription() . "\n";
  echo 'Category: ' . $videoEntry->getVideoCategory() . "\n";
  echo 'Tags: ' . implode(", ", $videoEntry->getVideoTags()) . "\n";
  echo 'Watch page: ' . $videoEntry->getVideoWatchPageUrl() . "\n";
  echo 'Flash Player Url: ' . $videoEntry->getFlashPlayerUrl() . "\n";
  echo 'Duration: ' . $videoEntry->getVideoDuration() . "\n";
  echo 'View count: ' . $videoEntry->getVideoViewCount() . "\n";
  echo 'Rating: ' . $videoEntry->getVideoRatingInfo() . "\n";
  echo 'Geo Location: ' . $videoEntry->getVideoGeoLocation() . "\n";
  echo 'Recorded on: ' . $videoEntry->getVideoRecorded() . "\n";
  
  // see the paragraph above this function for more information on the 
  // 'mediaGroup' object. in the following code, we use the mediaGroup
  // object directly to retrieve its 'Mobile RSTP link' child
  foreach ($videoEntry->mediaGroup->content as $content) {
    if ($content->type === "video/3gpp") {
      echo 'Mobile RTSP link: ' . $content->url . "\n";
    }
  }
  
  echo "Thumbnails:\n";
  $videoThumbnails = $videoEntry->getVideoThumbnails();

  foreach($videoThumbnails as $videoThumbnail) {
    echo $videoThumbnail['time'] . ' - ' . $videoThumbnail['url'];
    echo ' height=' . $videoThumbnail['height'];
    echo ' width=' . $videoThumbnail['width'] . "\n";
  }
}



// form HTML code for embedding a youtube video
function embedvideo($videoEntry) 
{
  // the videoEntry object contains many helper functions
  // that access the underlying mediaGroup object
 // echo $videoEntry->getFlashPlayerUrl()
?>
 <object width="250" height="220">
                        <param name="movie" value="<?php echo $videoEntry->getFlashPlayerUrl() ?>">
                        <param name="wmode" value="transparent">
                        <embed src="<?php echo $videoEntry->getFlashPlayerUrl()?>" type="application/x-shockwave-flash" wmode="transparent" width="260" height="260">
                        </embed> 
                      </object>
<?php

}


//  extracts the First thumb nail entry for each video
function ythumbnail ($videoEntry, $count)  {


$videoThumbnails = $videoEntry->getVideoThumbnails();
//print_r($videoThumbnails);

echo '<img src="'.$videoThumbnails[0]['url'].'"   alt="click to view" title="click to view" onclick="createDiv('.$count.')" />';

//foreach($videoThumbnails as $videoThumbnail) {
  //  echo '<img src="'.$videoThumbnail['url'].'">';
   
 // }


}




//  converts HTML code safe via rawlurl,  JS converts back to normal HTML when required
function jsdivcode ($videoEntry, $count) 
{
  // the videoEntry object contains many helper functions
  // that access the underlying mediaGroup object
 // echo $videoEntry->getFlashPlayerUrl()

$jsvcode .= '<span id="youtube'.$count.'" title="';

$jsencode .= '<object width="250" height="220">
                        <param name="movie" value="'.$videoEntry->getFlashPlayerUrl().'">
                        <param name="wmode" value="transparent">
                        <embed src="'.$videoEntry->getFlashPlayerUrl().'" type="application/x-shockwave-flash" wmode="transparent" width="260" height="260">
                        </embed> 
                      </object>';

$jcoded = rawurlencode($jsencode);

$jsvcode .= $jcoded;

$jsvcode .= '"></span><span id="newone'.$count.'" ></span>';
                      
                                            
echo $jsvcode;

}  // closes function









?>
