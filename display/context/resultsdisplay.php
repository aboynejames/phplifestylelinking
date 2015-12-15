<?php


// *************************************************** Time Ranking Results********************************************//

function timerankresults ($lifestylelid)  
{

	// need lifestylelid here for use in query
	global $lifestylelid;
	
	global $resulttotals;
	global $resulttotalspage;
	global $page;
	global $total_pages;
	global $page_number;
	
	
	// need to convert lifestyleid to  startlifestyle object id
	$flid = idconvert ($lifestylelid);
  	
	$db->query ="SELECT * FROM ".RSSDATA.".dailyposts LEFT JOIN ".RSSDATA.".items ON ".RSSDATA.".items.id = ".RSSDATA.".dailyposts.postid WHERE ".RSSDATA.".dailyposts.lifestyleid = $flid  ORDER BY enddate DESC  LIMIT 100 ";
	//echo $db->query;   
	$resulttotals = mysql_query($db->query) or die(mysql_error());
	
		
	// pagination prepare
	$row = mysql_num_rows($resulttotals);
	$total_entries = $row;
	//echo $total_entries;
	$entries_per_page = 10;
	
	
	 if(isset($_REQUEST['page_number']))
	 { 
	 	$page_number = $_REQUEST['page_number']; 
	 }
	 else 
	 { 
	 	$page_number = 1; 
	 }
	
	//echo $page_number;
	
	$pagenumber = $_REQUEST['page_number'];
	//echo $pagenumber;
	
	
	 $total_pages = ceil($total_entries / $entries_per_page);
	// echo $total_pages;
	 
	 $offset = ($page_number - 1) * $entries_per_page;
	
	
	$db->query ="SELECT ".RSSDATA.".dailyposts.drid, ".RSSDATA.".feeds.id, ".RSSDATA.".feeds.title AS author, ".RSSDATA.".feeds.link AS blogurl, ".RSSDATA.".items.link, ".RSSDATA.".items.title, ".RSSDATA.".items.content, ".RSSDATA.".items.dcdate  FROM ".RSSDATA.".dailyposts LEFT JOIN ".RSSDATA.".items ON ".RSSDATA.".items.id = ".RSSDATA.".dailyposts.postid LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".items.feed_id  WHERE ".RSSDATA.".dailyposts.lifestyleid = $flid  ORDER BY enddate DESC ";
	
	$db->query .="LIMIT $offset, $entries_per_page";
	
	$resulttotalspage = mysql_query($db->query) or die(mysql_error());
	//echo $db->query;   // contains list of word ids that match lifeterms entered.
	
}  // closes timerankresults()



// *************************************************** Blog Posts ********************************************//

// prepared code for results presentation
function inpostresults ($lifestylelid, $lifestylesubid)  
{

	//Function Output's variables with no html markup
	global $resulttotals;
	global $resulttotalspage;
	global $page;
	global $total_pages;
	global $page_number;
	//global $lifestyleid;
	
	//global $llresults;
	global $streamstyle;
	global $llpaging;
	
	if ($resulttotals)  
	{	
    $streamstyle = array();		
		$llpaging = '';
		
		// present results in table
		if ( mysql_num_rows ($resulttotalspage) > 0) 
		{
			while($row = mysql_fetch_object($resulttotalspage))
			{
		
				$datepost = $row->dcdate;
				$postdate = date("l jS \of F Y h:i:s A", $datepost);
				
				$limit = 100;
				   
            //  add functions to see if image or video can be extract from post?
            //images extraction
            $contentb = $row->content;
            $matchesimgi = '';
            $matchesimgi = str_img_src($contentb);
            
            if ($matchesimgi) {

            $jpgstring = '.jpg';
            $pos = stristr($matchesimgi, $jpgstring);
            //echo $pos;
            if ($pos === false )  {
                $blogimg =   makeurla('display/images/blogimg.gif');
                }

            else  {
                $blogimg =   $matchesimgi;
                }
                }  // closes if


            //  video extraction
            //videoembed ($row->link);
        
        $cleanhtml = html_entity_decode($row->content);
				$cleantags= strip_tags($cleanhtml);
				$summary = $cleantags;
		
				if (strlen($summary) > $limit) 
				{
				      $summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . '...';
				}
				      
				$streamstyle[$row->drid][strblogurl] =  $row->blogurl;
				$streamstyle[$row->drid][strauth] = $row->author;
				$streamstyle[$row->drid][strtitle] =  $row->title;
       	$streamstyle[$row->drid][strturl] =  $row->link;
       	$streamstyle[$row->drid][inimag] = $blogimg;
				$streamstyle[$row->drid][strsumm] = $summary;
				$streamstyle[$row->drid][strdate] = $postdate;
			}  // closes while loop
		} 
		
		for($page = 1; $page <= $total_pages; $page++)
		{ 
			if($page == $page_number)
			{ // This is the current page. Don't make it a link.
			    $llpaging .= "$page "; 
			}
		   else 
		   { // This is not the current page. Make it a link. 
		          $llpaging .= "<a href=\"".makeurla('index.php')."?lifestyleid=".$lifestylelid."&page_number=".$page."\">".$page."</a>"; 
              
        } 
		}
	}  // closes if  results for this category?
	
	else 
	{
		$llresults .= "No posts could be presented.";  
	}
  
//  print_r($streamstyle);
  return $streamstyle;
  
}  //closes postresults()



function streamstyle ()
{

global $streamstyle;

timerankresults ($lifestylelid);      
inpostresults ($lifestylelid, $lifestylesubid); 		

?>

<div id="stream">

<?php
foreach ($streamstyle as $rord=>$stre)  {
?>
      		<img src="display/images/profpic.gif" alt="Profile Picture" title="Profile Picture" class="imgbox"/>
	      	<div class="btitle"> 
		      		<p class="author"><a href=" <?php echo $stre[strblogurl] ?>"><?php echo $stre[strauthl] ?></a></p>
		      		<p class="post_title"><a href="<?php echo $stre[strturl] ?>"><?php echo $stre[strtitle] ?></a></p>
		       </div>
		      <div class="bimg"><a href="<?php echo $stre[strturl] ?>"><img src="<?php echo $stre[inimag] ?>"  width=\"75\" height=\"75\"  alt="Blog Image" title="Blog image"/></a> 
		      </div> 
		      <div class="bsum"> 
		      		<p><?php echo $stre[strsumm] ?> ... </p> 
		      		<a href="<?php echo $stre[strtturl] ?>">more</a> 
		      		<p class="date"><?php echo $stre[strdate] ?></p>
		      		
		      		<hr/>
		      	</div>	
		      		
		      	
<?php
}  // closes foreach loop
?>            
              		      
 		 </div><!--Closes Stream-->    
		
    <div id="paging"><?php echo $llpaging; ?></div>
		  
 </div><!--Closes magtext--> 
 	 		
<?php      
}  // closes function






// *************************************************** Flickr Images ********************************************//
function flickrfour ($lifestylelid)  
{

	global $imagedisplay;
  global $lifestylelid;
	
	$lifestart =	idconvert($lifestylelid);
	
	// first need to find latest date
	$db->query ="SELECT * FROM ".RSSDATA.".imagesource WHERE ".RSSDATA.".imagesource.idstart = '1' ORDER BY ".RSSDATA.".imagesource.enddate DESC LIMIT 1";
	
	$resultfdate = mysql_query($db->query) or die(mysql_error());
	//echo $db->query;   // contains list of word ids that match lifeterms entered.
	
	if ( mysql_num_rows ($resultfdate) == 1 ) 
	{
		$row = mysql_fetch_object($resultfdate);
		$fenddate = $row->enddate;
	}
	
	$db->query ="SELECT * FROM ".RSSDATA.".imagesource WHERE ".RSSDATA.".imagesource.enddate = '$fenddate' AND ".RSSDATA.".imagesource.idstart = '$lifestart' LIMIT 6";
	
	$resultffour = mysql_query($db->query) or die(mysql_error());
	//echo $db->query;   // contains list of word ids that match lifeterms entered.
	
	$imagedisplay = '';
	
	// present results in table
	if ( mysql_num_rows ($resultffour) > 0 ) 
	{
		while($row = mysql_fetch_object($resultffour))
		{	
			$imagedisplay .= "<p><a href=\"$row->imgurl\" title=\"$row->title\"><img src=\"$row->sourceurl\"  border =\"1\" /></a></p>";
		}  // closes while	
	}  // closes if.
	
//	echo $imagedisplay; //needed ?
}  // closes flickrfour()




function displayphotos () 
{

global $imagedisplay;

flickrfour ($lifestylelid); 
	
?>

 <div id="photos">
   <h3 class="hidden">Photos</h3>

<?php  echo $imagedisplay; ?>

</div>  <!-- closes photos -->



<?php
}  // closes function



// *************************************************** Tou Tube Videos*********************************************//

function  videoembed ($htmlcv)  
{

	global $ytcode;
	//echo $htmlcv;
	
	$sourcepc = file_get_contents($htmlcv); 
	
	preg_match('/http:\/\/www\.youtube[^"]+/', $sourcepc, $matchesv); 
	
	//print_r($matchesv);
	$ytcode = $matchesv[0];
	
}  // closes videoembed()




function displayvideo ()  
{

  global $lifestylelid;
  global $searchTerms;

 
?>
<div id="videos">
   <h3 class="hidden">Videos</h3>

<?php 
youtubekeywords($lifestylelid);
searchAndPrint ($searchTerms);
?>  
  
 </div>  <!-- closes video list -->

<?php
}  // closes function







// ***************************************************Media From Blog Posts*********************************************//

// extracting media from a blog post

 function str_img_src($html) 
 {
	 global $matchesimg; 
  
    if (stripos($html, '<img') !== false) 
    {  
      $imgsrc_regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
        preg_match($imgsrc_regex, $html, $matchesimg);
        
        unset($imgsrc_regex);
        unset($html);
        
      //print_r($matchesimg);  
        
        if (is_array($matchesimg) && !empty($matchesimg)) {
            
          return $matchesimg[2];         
        } 
        
        else 
        {
            return false;
        }
    }  // closes if

	else 
	{
        return false;
    }

}  // closes function



?>
