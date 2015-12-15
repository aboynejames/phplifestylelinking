<?php


//////////////////////////////   widget creation   start of   Oauth API  ////////////////////////////////////////////////////////////

//  forms html code to be pulled in via Javascript
function widgetcodelimit ($lifestylelidr, $widlim)  {

global $widgetcode;

// find out the name of the lifestyle object

   $db->query ="SELECT * FROM ".RSSDATA.".lifestylestart WHERE ".RSSDATA.".lifestylestart.idlifestart= $lifestylelidr "; 
    
		$resultlname = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());;

if (mysql_num_rows($resultlname) == 1 )  {

$row = mysql_fetch_object($resultlname);
$lname = $row->definition;
}


   $db->query ="SELECT * FROM ".RSSDATA.".dailyposts LEFT JOIN ".RSSDATA.".items ON ".RSSDATA.".items.id = ".RSSDATA.".dailyposts.postid WHERE ".RSSDATA.".dailyposts.lifestyleid = $lifestylelidr  ORDER BY enddate DESC LIMIT $widlim "; 
    
		$resultwitems = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());;

 
$widgetcode = '';

$widgetcode = "<style type=\"text/css\">
.sportsbox{

	background-color: rgb(240,240,240);
  font-size: 80%;
	font-family: Verdana, sans-serif;
  margin-top: 1em;
	width: 15em;
  min-height: 5em;
	border: 1px solid  rgb(238,77,133);
	padding: 10px 10px 10px 10px;
}

</style>";


$widgetcode .= "<div class=\"sportsbox\">";

$widgetcode .= "".$lname." news<br />";


if (mysql_num_rows($resultwitems)  >  0 )  {

	while($row = mysql_fetch_object($resultwitems))  		{
  
//$cleanhtml = html_entity_decode($row->content);
//$cleantags= strip_tags($cleanhtml);
//$summary = $cleantags;
//$limit = 100;

//if (strlen($summary) > $limit) {
  //    $summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . '...';
   
    //}  

  
$widgetcode .=  "<a href=\"" .$row->link. "\">".substr($row->title, 0, 40)."</a> <br /> ";
 
 }
 }   
 $widgetcode .= "<a href=\"http://www.mepath.com\"><img src=\"http://75.101.138.19/lifestylelinking/images/mepathsport.gif\"></a>";
$widgetcode .= "</div>";



$widgetcode = rawurlencode($widgetcode); 



}  //closes function





// formation of dropdown menu
function lifestyledropdown ()  {


global $lifestyledrop;

$lifestyledrop = '';

$lifestyledrop .= "<form action=\"/lifestylelinking/widgets/makewidget.php\"  method=\"post\" name=\"lifestyle\" >";

// create a list box of  lifestyles 
$db->query="SELECT * FROM ".RSSDATA.".lifestyle LEFT JOIN ".RSSDATA.".lifestylestart ON ".RSSDATA.".lifestylestart.idlifestart = ".RSSDATA.".lifestyle.idlifestart ";

$resultldrop = mysql_query($db->query) or die(mysql_error());

// present results in table
if ( mysql_num_rows ($resultldrop) > 0) {

//present check boxes
$lifestyledrop .= "Select lifestyle to create widget for: ";
$lifestyledrop .= "<select name=\"lifestylecat\">";


while($row = mysql_fetch_object($resultldrop)){

$lifestyledrop .= "<option value=\"".$row->idlifestart."\">".$row->name." </option>";

}

$lifestyledrop .= "</select>";

// input box for no. of 
$lifestyledrop .= "<br />How many headlines? ";
$lifestyledrop .= "<select name=\"widcur\">";
$lifestyledrop .= "<option value=\"1\"> 1 </option>";
$lifestyledrop .= "<option value=\"2\"> 2 </option>";
$lifestyledrop .= "<option value=\"3\"> 3 </option>";
$lifestyledrop .= "<option value=\"4\"> 4 </option>";
$lifestyledrop .= "<option value=\"5\"> 5 </option>";
$lifestyledrop .= "<option value=\"10\"> 10 </option>";


//  submit button
$lifestyledrop .= "<input name=\"submit\" type=\"submit\"  value=\"Create lifestyle widget\" >";

}

$lifestyledrop .= "</form>";

//echo $lifestyledrop; 


}  //  closes function





// allow creation of script code for user to cut and paste.
function makewidgetcode ()  {

global $widgeturl;
global $widgetprev;


if ($_POST['lifestylecat'])   {

$lifestylewid = mysql_real_escape_string($_POST['lifestylecat']);

$widid = mysql_real_escape_string($_POST['widcur']);

// create url,  the url will call widgetefunction to action the widget

$widgeturl = '';

$widgeturl .= "<textarea name=\"jswidgecode\" cols=\"42\" rows=\"8\" wrap=\"ON\"  id=\"message\" > ";

$widgeturl .="<script type=\"text/javascript\" src=\"http://75.101.138.19/lifestylelinking/widgets/widget.php?lifestyleid=$lifestylewid&widlim=$widid\"></script> ";

$widgeturl .= "</textarea>";


$widgetprev = '';

$widgetprev ="<script type=\"text/javascript\" src=\"http://75.101.138.19/lifestylelinking/widgets/widget.php?lifestyleid=$lifestylewid&widlim=$widid\"></script> ";



}


}  // closes function


?>