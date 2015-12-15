<?php 

// utility screenin input data 

// is data a integer ie. chekcing GET ids from urls address etc.
function escapeinteger ($dataget)  {

//print_r($dataget);

if (is_array($dataget) == TRUE)  {

foreach ($dataget as $dget)  {

if ( (intval($dget)) > 0  &&  (intval($dget)) <= 92)  {

$dgetclean[] = intval($dget);


} // closes if

}  // closes loop

// what if all array values are invalid?
if (count($dgetclean) > 0)  {

return $dgetclean;
}

else  {

$dataget = 1;
return $dataget;

}

}  // closes if an array received


// not an array do 
else  {

if ( (intval($dataget)) > 0  &&  (intval($dataget)) <= 92)  {

return intval($dataget);

}

else  {

// dodgy input set at 1
$dataget = 1;
return $dataget;

}

}  // closes else

//echo 'dataget'.$dataget;

}  // closes function



// takes input string data and makes safe, e.g. adding / repacing slashes and other characters
function escapestring ($dataget)  {

return AddSlashes($dataget);

}  // closes function


//  query involving LIKE need exrtra checking
function stringlike ($stringget)  {

return str_replace(array('%','_'), array('\\%', '\\_'), $stringget);


}  // closes function



//////// produce HTML /////////////////////////////////////////////

function pageheader()  {
?>  
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
	<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
	   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		
		<title>mepath - lifestyle linking</title>
		<meta name="description" content="Main Page" />
		<meta name="page-topic" content="Homepage" />
		
		<meta name="robots" content="all" />
		<meta name="author" content="" />
		<meta name="author" content="" />
		<meta name="Keywords" content="" /> 	
		
		<!--Main Style Sheet-->
		<!--session selector to pick which stylesheet based on context if this header is in a php function-->
		<link href="<?php makeurl('display/css/style.css') ?>" type="text/css" rel="stylesheet" id="stylesheet" />
		<?php
		if ($_SESSION[metext] == 5)  {
		?>    
		<link href="<?php makeurl('display/css/mepathmake.css') ?>" type="text/css" rel="stylesheet" id="stylesheet" />
		<?php
		}
		?>
		
		<!--Javascript -->
		<script type="text/javascript" language="javascript" src="<?php makeurl('display/javascript/mepathjs.js') ?>"></script>  
		
    </head>
	
	<?php   
}  // closes pageheader()  

//  display all lifestyles on homepage
function grouplists ()  
{
	global $grouplist;
	
	$db->query ="SELECT * FROM  ".LIFEDATA.".lifestylegroups ORDER BY ".LIFEDATA.".lifestylegroups.groupname ASC";
	
	$resultgroups = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
	//echo $db->query;
	
	if (mysql_num_rows($resultgroups)  > 0  )  
	{
		$grouplists = '';
	
		while ($row=mysql_fetch_object($resultgroups))  
		{
		
			$db->query ="SELECT * FROM  ".LIFEDATA.".lifestylegroups LEFT JOIN ".LIFEDATA.".grouplink ON ".LIFEDATA.".grouplink.groupid = ".LIFEDATA.".lifestylegroups.groupid LEFT JOIN ".LIFEDATA.".lifestyle ON ".LIFEDATA.".lifestyle.idlifestyle = ".LIFEDATA.".grouplink.idlifestyle WHERE ".LIFEDATA.".lifestylegroups.groupid = $row->groupid  ORDER BY ".LIFEDATA.".lifestyle.name ASC  ";
			
			$resultglist = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
			//echo $db->query;
			
			if (mysql_num_rows($resultglist)  > 0  )  
			{
				$grouplist .="$row->groupname";
				
				while ($row=mysql_fetch_object($resultglist)) 
				{
				
					if ($row->idlifestyle) 
					{
						$grouplist .="<input type=\"checkbox\"  name=\"lifestylesid[]\" value=\"".$row->idlifestyle."\"> <a href=\"http://www.aboynejames.co.uk/lifestylelinking/lifestylelinkingview.php?lifestyleiid=".$row->idlifestyle."\"> ".$row->name."</a>";
					}
					else 
					{
						$grouplist .="<i>coming soon</i>";
					}
		
				}  // closes while loop
			}//closes if
		}  // closes while
	}//closes if
}  // closes grouplists()



function makeurl ($relurl)  
{

echo LLFOLDER . $relurl;

}  // closes function

function makeurla ($relurl)  
{

$makeurl = LLFOLDER . $relurl;

return $makeurl;

}  // closes function




function memenu ($meurlfeedid)  {

global $lifeobjects;
global $feedids;
global $lifequalify;

lifestylestartarray ();
feedarray ();

// select lifestyle that an individual qualify for and build array
//  form two arrays of ranks based on top score and distance from average,   merge out rankings and that will be order menu list will be, limit to 5 and suggested add more button.  NB need to remove lifestyle presented to edit add, remove,  order too I suppose.
$fid = $meurlfeedid ;
//echo $fid;

// need to find out date of latest me avarage data
$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $fid ORDER BY ".RSSDATA.".melife.date DESC LIMIT 1 ";

//echo $db->query;
$resultldate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultldate)  == 1 )  {

$rowldate = mysql_fetch_object($resultldate);

$ldate = $rowldate->date;

}




$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $fid AND ".RSSDATA.".melife.date = $ldate ORDER BY ".RSSDATA.".melife.topmatch DESC ";
//echo $db->query;
$resulttopm = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resulttopm)  > 0)  {

while ($rowtop = mysql_fetch_object($resulttopm))  {

$toprank[] = $rowtop->idlifestart;

}
}

$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $fid AND ".RSSDATA.".melife.date = $ldate ORDER BY ".RSSDATA.".melife.diffavg DESC ";
//echo $db->query;
$resultdiffavg = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultdiffavg)  > 0)  {

while ($rowdif = mysql_fetch_object($resultdiffavg))  {

$topdif[] = $rowdif->idlifestart;

}
}


if ($toprank && $topdif)  {

//print_r($toprank);
//print_r($topdif);

$rankone = array_intersect($toprank, $lifeobjects);
$rankonea = array_flip($rankone);

//print_r($rankonea);

$ranktwo = array_intersect($topdif, $lifeobjects);
$ranktwoa = array_flip($ranktwo);

//print_r($ranktwoa);

foreach ($lifeobjects as $key => $obj )  {


$memrank = ($rankonea[$obj] + $ranktwoa[$obj])/2 ;
//echo $memrank;

$lifequalify[$key] = $memrank;
//$lifequalify[$obj][] = $key;

}

$lifesort = asort($lifequalify);
//print_r($lifesort);
//print_r($lifequalify);
$_SESSION[lifequalify] = $lifequalify;

}

return $lifequalify;

}  //closes function





function lifestylestartarray ()  {

global $lifeobjects;


$db->query ="SELECT * FROM ".LIFEDATA.".lifestylestart ";

$resultlifeobj = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlifeobj)  >  0 )  {

while ($row = mysql_fetch_object($resultlifeobj)) {

$lifeobjects[$row->definition] = $row->idlifestart;

}
}

//print_r($lifeobjects);

}  // closes function





function feedarray ()  {

global $feedids;


$db->query ="SELECT ".RSSDATA.".feeds.id FROM ".RSSDATA.".feeds ";
//echo $db->query;
$resultblogs = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultblogs)  >  0 )  {

while ($row = mysql_fetch_object($resultblogs)) {

$feedids[] = $row->id;

}
}

//print_r($feedids);

}  // closes function



function dayfeedslist ($finishtime)  {

global $feedidsd;

$secondsday =  86400;
//echo $finishtime;

$startdayd =   $finishtime - $secondsday;  
//$datestart = date("c", $startday);
//echo $datestart;
//echo '<br /><br />';
//$datestart = date("c", $endday);
//echo $datestart;

$stoptimed = $finishtime - 1;


$db->query="SELECT DISTINCT ".RSSDATA.".items.feed_id FROM ".RSSDATA.".items WHERE ".RSSDATA.".items.dcdate BETWEEN $startdayd AND $stoptimed ";
//$db->query ="SELECT ".RSSDATA.".feeds.id FROM ".RSSDATA.".feeds ";
//echo $db->query;
$resultdblogs = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultdblogs)  >  0 )  {

while ($row = mysql_fetch_object($resultdblogs)) {

$feedidsd[] = $row->feed_id;

}
}
//print_r($feedidsd);

}  // closes function





function compareusermenu ($lifemenu, $lifequalify)  {

global $topmenu;
global $lifestylelid;
global $lifemenu;
global $lifequalify;


// find lifestyles that qualify
$topmenu = array_intersect($lifemenu, $lifequalify);
//print_r($topmenu);

$_SESSION[topmenu] = $topmenu;

if ($lifestylelid == 0)  {

//make first top menu lifestyle the default menu selected.
$topflip = array_flip($topmenu);
//print_r($topflip);

$topkeys = array_keys($topflip);
//print_r($topkeys);
//echo $topkeys[0];

$lifestylelid = $topkeys[0];

}
//echo $lifestylelid;

}  // closes function






// dispaly current lifestyle to display context and eventually more stats on that lifestyle, more stats if signed in privacy,
function lifestylesummary ()  {

global $lifestylelid;
global $celname;

$db->query ="SELECT * FROM ".LIFEDATA.".lifestyle WHERE ".LIFEDATA.".lifestyle.idlifestyle = '$lifestylelid' ";

$resultcsel = mysql_query($db->query) or die(mysql_error());
//echo $db->query;  

if (mysql_num_rows($resultcsel) == 1)  {

$row = mysql_fetch_object($resultcsel);
$celname = $row->name;

}

echo $celname;

}  // closes function




// find out no. of lifestyles object live on mepath.com
function lifestylenum ()  {

global $lifestylenum;

$db->query ="SELECT COUNT(idlifestyle) as lno  FROM ".LIFEDATA.".lifestyle ";

$resultlsnum = mysql_query($db->query) or die(mysql_error());
//echo $db->query;  

if (mysql_num_rows($resultlsnum) == 1)  {

$row = mysql_fetch_object($resultlsnum);
$lifestylenum = $row->lno;

}

//echo $lifestylenum;

}  // closes function





// process lifestyle suggestions
function lifestylesuggest ()  {

global $thankyou;

if ($_POST['suggl'])  {

$lstylesugg = mysql_real_escape_string($_POST['suggl']);

$limit = 120;

$lstylesugg = substr($lstylesugg, 0, $limit);
//echo $lstylesugg; 


$sdate =time();

$db->query ="INSERT INTO ".LIFEDATA.".lifestylesug (lifestylesug, sdate) VALUES ('$lstylesugg', '$sdate') ";

$resultlsnum = mysql_query($db->query) or die(mysql_error());
//echo $db->query;  

$thankyou = '<br />Thank you for the lifestyle suggestion.';


}

}  //  closes function





// save lifestlye activities menu
function savesetme ()  {

// first check to see if openid sign/reg has come from processing savebutton
if ($_POST['savelife'])  {

// set a session variable
$_SESSION[savelifep] = 1 ;

}  // closes if came from save button being pressed


}  // closes function





function tidyurl ($rawurl)  {

global $urlcleanish;

// get host name from URL
preg_match('@^(?:http://)?([^/]+)@i', $rawurl, $matches);
$host = $matches[1];


// get last two segments of host name
preg_match('/[^.]+\.[^.]+\.[^.]+$/', $host, $matchesa);
//echo "domain two part name is: {$matchesa[0]}\n";
$urlcleanish = $matchesa[0];
//echo '<br /><br />';

if ($matchesa[0] == null)  {


// get last two segments of host name
preg_match('/[^.]+\.[^.]+$/', $host, $matches);
//echo "domain name is: {$matches[0]}\n";
$urlcleanish = $matches[0];
//echo '<br /><br />';

}
//echo $urlcleanish;
//echo '<br /><br />';

}  // closes function





//  lists all lifestyle objects that have been set for a top menu item classification
function standardtopmenutwo ()  {

global $lifemenu;

// find out the number of top level lifestyles, first form arrary of all possible lifestyle options
$db->query="SELECT * FROM ".LIFEDATA.".lifestyle LEFT JOIN ".LIFEDATA.".lifestylestart ON ".LIFEDATA.".lifestylestart.idlifestart = ".LIFEDATA.".lifestyle.idlifestart";

$resultlifemenu = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultlifemenu ) > 0 )  {

while ($row = mysql_fetch_object($resultlifemenu ))  {

$lifemenu[$row->name] = $row->idlifestyle;

}  //closes whileloop
}

//print_r($lifemenu);
return $lifemenu;

}  // closes function





function compareusermenutwo ($lifemenu, $lifequalify)  {

global $topmenu;
global $lifestylelid;
global $lifemenu;
global $lifequalify;

//echo 'men topmenu';
// find lifestyles that qualify
$topmenua = array_intersect_key($lifequalify, $lifemenu);

$i= 1; 
foreach ($topmenua as $key => $val) { 
    $cleandataArr[$key]= $i ;
    $i++;
}

//print_r($cleandataArr);

$topmenu = $cleandataArr;

foreach ($lifemenu as $key => $val) { 

    $topmenu[$key]=$lifemenu[$key];
    $i++;
}

//print_r($topmenu);
// limit to top 5 lifestyle
array_splice($topmenu, 5);


$_SESSION[topmenu] = $topmenu;

if ($lifestylelid == 0)  {

//make first top menu lifestyle the default menu selected.
$topflip = array_flip($topmenu);
//print_r($topflip);

$topkeys = array_keys($topflip);
//print_r($topkeys);
//echo $topkeys[0];

$lifestylelid = $topkeys[0];

}
//echo $lifestylelid;
return $topmenu;

}  // closes function


?>