<?php

class RSS  {



	public function GetFeed($lifestylelid)  {

		return $this->getDetails(). $this->getItems($lifestylelid);
	}
	

	
	private function getDetails()  	{
  
		
		$db->query = "SELECT * FROM ".RSSDATA.".webrefrssdetails WHERE ".RSSDATA.".webrefrssdetails.idrss = '1' ";
		//echo $db->query;
    $result = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());;
		
    
		while($row = mysql_fetch_object($result))  	{
    
			$details = '<?xml version="1.0" encoding="ISO-8859-1" ?>
					<rss version="2.0">
						<channel>
							<title>'. $row->title .'</title>
							<link>'. $row->link .'</link>
							<description>'. $row->description .'</description>';
							
		}
		
    return $details;
	
  }
	
  
  
	private function getItems($lifestylelid)  	{
  
 $flid =idconvert($lifestylelid); 
   
   $db->query ="SELECT * FROM ".RSSDATA.".dailyposts LEFT JOIN ".RSSDATA.".items ON ".RSSDATA.".items.id = ".RSSDATA.".dailyposts.postid WHERE ".RSSDATA.".dailyposts.lifestyleid = $flid  ORDER BY enddate DESC LIMIT 10 "; 
    //echo $db->query;
 	$resultitems = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());;

 
		
		$items = '';
	
	while($row = mysql_fetch_object($resultitems))  		{
  
$cleanhtml = html_entity_decode($row->content);
$cleantags= strip_tags($cleanhtml);
$summary = $cleantags;
$limit = 100;

if (strlen($summary) > $limit) {
      $summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . '...';
   
    }  

  
			$items .= '<item>
						 <title>'. $row->title .'</title>
						 <link>'. $row->link .'</link>
						 <description><![CDATA['. $summary.']]></description>
					 </item>';
		}
    
		$items .= '</channel>
				 </rss>';
		
    return $items;
	
  
  }


}  // closes class

?>