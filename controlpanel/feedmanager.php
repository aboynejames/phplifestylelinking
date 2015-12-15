<?php session_start();
include_once ("mysqlstart2.inc");
include('lifestylelinking/logic/functions.php');
checkLogin('3');
include_once("lifestylelinking/logic/scalelogic.php");  
include_once ("lifestylelinking/logic/startpage.php");
include_once ("lifestylelinking/logic/indprocess.php");
include_once("lifestylelinking/logic/dailyprocess.php"); 
include_once ("lifestylelinking/logic/dailyresults.php");
include_once("lifestylelinking/feedreader/init.php"); 
include_once("lifestylelinking/feedreader/fof-main.php");  

//echo RSSDATA;


// how to scale?  1. from list of unqiue urls start by querying out those taken from existing feedsid that are scoring well for any lifestyle,   2 keep repeating,  3.  as this goes on the scoring definition logic should kick in too, ie. not to score every blog on every  ideally this new module should be working in tandem.

//  4.  the other way is to create an aggregate word heirarchy for each blog and then select, most promosing definition for scoring.

//  NB   defninitions at this stage are being limited to the sports we have.   If we want to create new definitions on the fly, then that logic needs to be discovered, tested and implemented.

?>

<br />
Deal with the BACKLOG OF URLS, Smart-ly 
<BR><br>
<form method="post" action=" <?php echo $_SERVER['PHP_SELF'] ?> " >
Select new urls for mepath.com lifestyle linking
<input type="Submit" name="newfeeds" value="Start new feeds manager"><br><br>
</form>


<?php
if (isset($_POST['newfeeds'])) {

error_reporting(5);

// set arrays for speical words and lifestyle words
specialwords();
lifestylearray();

//  query to find top 20% of scorers for each lifestyle?  Do as a function
scalelist();

//  put new urls out for RSS feed collection and first go at scoring.
foreach ($newinputurl  as  $newfurl )  {

echo $newfurl;
//  activates  monkeychow to add url to feedreader
fof_add_feed('http://'.$newfurl);
//echo 'afteradd';

// split text into individual words in a table
blogpostwords ();

if($lastchow && $firstitem)  {
// allocate votes/scoring based on wikipedia lifestyle definitions
blostposttopfifty (); 

//  sum votes to an indiviudal to see if they qualify for a lifestyle inclusion
lifestylelightstatsi ();

}  // closes if

}  // closes foreach




}  // closes if




?>