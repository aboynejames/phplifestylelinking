<?php

function lifestylestartobj ($wikiobj, $idlifestart)  {
//print_r($wikiobj);
     $wikiobja = html_entity_decode($wikiobj);
//echo $row->content;    
    $wikiobjb = strip_tags($wikiobja);
     $remove = array("'", "-", ",", "(",")", "?", ".", "&rsquo;", "&ldquo;", "&rsquo;", "&rdquo;", ":", "@", "!", "#",  "^", "%", "/", "|", '\'', "+", "=", "{", "}", "[", "]", '"', ";", "<", ">", "_", "~", "<br />", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );

     $wikiobjc = str_replace($remove," ", $wikiobja);
     $wikiobjd = trim($wikiobjc);
     $wikiobje = explode(" ", $wikiobjd);
     

$db->query = "INSERT INTO ".RSSDATA.".objectwords (word, idlifestart) VALUES ";

while(list($key, $val)=each($wikiobje))   {

$val = ereg_replace("(\\\*)+(/*)+('*)", "", $val);
$val = substr($val, 0, 30); 
$val = trim($val); 
 //&&  !ereg("^\*", $val)

	   if(strlen($val) > 0 )   {
     
       $db->query .= "( '$val', '$idlifestart'),";
          
       }
    }
    
  
$db->query=substr($db->query,0,(strLen($db->query)-1));//this will eat the last comma
//echo $db->query;
// execute query
$resultw = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());



$db->query="SELECT DISTINCT ".RSSDATA.".objectwords.word FROM  ".RSSDATA.".objectwords WHERE ".RSSDATA.".objectwords.idlifestart = '$idlifestart' ";

$resultfreq = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultfreq) > 0 )  {



while ($row=mysql_fetch_object($resultfreq)) {


$db->query="SELECT COUNT(".RSSDATA.".objectwords.word) as frequencyscore, ".RSSDATA.".objectwords.word FROM ".RSSDATA.".objectwords WHERE  ".RSSDATA.".objectwords.idlifestart = '$idlifestart'  GROUP BY ".RSSDATA.".objectwords.word HAVING ".RSSDATA.".objectwords.word = '$row->word' ";  

$resultcount = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
echo $db->query;

$rowa = mysql_fetch_object($resultcount);

$db->query="INSERT INTO ".RSSDATA.".wikipediascore (idlifestart, word, frequencyscore) VALUES ('$idlifestart', '$row->word', '$rowa->frequencyscore')";
echo $db->query;

$resultsliscore = mysql_query($db->query) or die(mysql_error());

} 
} 

//  empty crawl urls ready for next batch
$db->query = "TRUNCATE TABLE ".RSSDATA.".objectwords ";
$resultempty = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;



}//  closes function






//  for simple txt file input
function lifestylestartdef ($idlifestart)  {


$db->query ="SELECT * FROM ".RSSDATA.".lifestylestart WHERE ".RSSDATA.".lifestylestart.idlifestart = '$idlifestart' ";

$resultdefurl = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if(mysql_num_rows($resultdefurl) == 1)  {

$row = mysql_fetch_object($resultdefurl);
$url = $row->defurl;
}
//$url = 'C:/apache/htdocs/aboynejames.co.uk/lifestylelinking/loginfiles/textfiles/mountaineering.txt';

// load in files
$content = fopen($url, "r"); //open file in read mode

$data = fread($content, filesize($url)) or die('Could not read file!'); 


//if ($content)  {

//$line = file_get_contents($url);

//(!feof($content)){
 //   $line = fgets($content);




     $remove = array("'", "-", ",", "(",")", "?", ".", "&rsquo;", "&ldquo;", "&rsquo;", "&rdquo;", ":", "@", "!", "#",  "^", "%", "/", "|", '\'', "+", "=", "{", "}", "[", "]", '"', ";", "<", ">", "_", "~", "<br />", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0" );
     $data = str_replace($remove," ", $data); 
     $datatwo = trim($data); 


$dataexplodethree = explode(" ", $datatwo);


$db->query = "INSERT INTO ".RSSDATA.".objectwords (word, idlifestart) VALUES ";

while(list($key, $val)=each($dataexplodethree))   {

$val = ereg_replace("(\\\*)+(/*)+('*)", "", $val);
$val = substr($val, 0, 30); 
$val = trim($val); 
 //&&  !ereg("^\*", $val)

	   if(strlen($val) > 0 )   {
     
       $db->query .= "( '$val', '$idlifestart'),";
          
       }
    }
    
  
$db->query=substr($db->query,0,(strLen($db->query)-1));//this will eat the last comma
//echo $db->query;
// execute query
$resultw = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


//fclose($url); //close file when done

//}  // closes if file successfully read


$db->query="SELECT DISTINCT ".RSSDATA.".objectwords.word FROM  ".RSSDATA.".objectwords WHERE ".RSSDATA.".objectwords.idlifestart = '$idlifestart' ";

$resultfreq = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultfreq) > 0 )  {



while ($row=mysql_fetch_object($resultfreq)) {


$db->query="SELECT COUNT(".RSSDATA.".objectwords.word) as frequencyscore, ".RSSDATA.".objectwords.word FROM ".RSSDATA.".objectwords WHERE  ".RSSDATA.".objectwords.idlifestart = '$idlifestart'  GROUP BY ".RSSDATA.".objectwords.word HAVING ".RSSDATA.".objectwords.word = '$row->word' ";  

$resultcount = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
echo $db->query;

$rowa = mysql_fetch_object($resultcount);

$db->query="INSERT INTO ".RSSDATA.".wikipediascore (idlifestart, word, frequencyscore) VALUES ('$idlifestart', '$row->word', '$rowa->frequencyscore')";
echo $db->query;

$resultsliscore = mysql_query($db->query) or die(mysql_error());

} 
} 


}//  closes function




function shortremove ($dataminedb)  {

$db->query .= "CREATE TABLE aboyn0_".$dataminedb.".charlength ";
$db->query .= "SELECT CHAR_LENGTH(".RSSDATA.".wikipediascore.word) AS length, ".RSSDATA.".wikipediascore.word FROM ".RSSDATA.".wikipediascore ";
echo $db->query;

// execute query
$resultone = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


$db->query = "SELECT * FROM aboyn0_".$dataminedb.".charlength WHERE aboyn0_".$dataminedb.".charlength.length <= '2' ";
echo $db->query;

// execute query
$resulttwo = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// see if any rows were returned
if (mysql_num_rows($resulttwo) > 0)  {
     // yes

//  create new table  that contains words that have already been given a WordID
$db->query = "DELETE FROM ".RSSDATA.".wikipediascore WHERE ";

     while($row = mysql_fetch_object($resulttwo))  {
          
	   $db->query .= "(".RSSDATA.".wikipediascore.word = '$row->word') OR";
          
       }

 $db->query=substr($db->query,0,(strLen($db->query)-3));//this will eat the last comma
echo $db->query;

// execute query
$resultthree = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

}

$db->query ="DROP TABLE aboyn0_".$dataminedb.".charlength";
$resultdrop = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());


}  //  closes function



function lifestylestartwords ($idlifestart)  {


$db->query="SELECT * FROM ".RSSDATA.".wikipediascore WHERE ".RSSDATA.".wikipediascore.idlifestart = '$idlifestart' ORDER BY ".RSSDATA.".wikipediascore.frequencyscore DESC LIMIT 50 ";
echo $db->query;
$resultdefw = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// see if any rows were returned
if (mysql_num_rows($resultdefw) > 0)  {


//  create new table  that contains words that have already been given a WordID
$db->query = "INSERT INTO ".RSSDATA.".lifestyledefinition (idlifestart, lifestylewords, votes) VALUES ";

     while($row = mysql_fetch_object($resultdefw))  {
          
	   $db->query .= "('$idlifestart', '$row->word', '$row->frequencyscore' ),";
          
       }

 $db->query=substr($db->query,0,(strLen($db->query)-1));//this will eat the last comma
echo $db->query;

// execute query
$resultthree = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

       }



}  // closes function


?>