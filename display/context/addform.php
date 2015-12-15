<?php

// add feed form
function feedform ()  {

?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
<input type="hidden" name="MAX_FILE_SIZE" value="100000">

<?php echo "Please enter Blog URL"; ?>:
<input type="text" name="me_url" size="60" value="">
<input type="Submit" name="feedxml" value="Add Sporty Blog"><br><br>
<input type="hidden" name="feedadd" value="1"/>
</form>

<?php
}  // closes function



function feedattach ()  {

// first find out if a blog url has been already uploaded?
$db->query ="SELECT * FROM ".RSSDATA.".rssjoin WHERE ".RSSDATA.".rssjoin.userid = '$_SESSION[user_id]' ";
//echo $db->query;
$changefeed = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// present results in table
if ( (mysql_num_rows ($changefeed)) == 1 ) {

feedlisttable ();

}
// tidyup session data
unset($_SESSION[lifequalify]);
unset($_SESSION[lifemenu]);
unset($_SESSION[newtopm]);

} // closes function



function feedlisttable ()  {

global $mefeedt;

$db->query="SELECT * FROM ".RSSDATA.".rssjoin LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".rssjoin.feedsid WHERE ".RSSDATA.".rssjoin.userid = '$_SESSION[user_id]' ";
//echo $db->query;
$feeds = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// present results in table
if ( mysql_num_rows ($feeds) > 0) {

$mefeedt = "Current Blog Feed(s) URL saved and included in lifestyle linking.";

$mefeedt .= "<table width=\"660\" border=\"1\" >";
$mefeedt .= "<tr>";
//echo "<th>" .'Number'. "</th>";
$mefeedt .= "<th width=\"100\">" .'Feed URL'. "</th>";
//echo "<th>" .'Update'. "</th>";
$mefeedt .= "<th>" .'Delete'. "</th>";
$mefeedt .= "</tr>";

while($row = mysql_fetch_object($feeds)){

$mefeedt .="<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" "; 
$mefeedt .="<tr>";
$mefeedt .="<input type=\"hidden\"  name=\"feedid\" value=\"".$row->id."\"> ";
$mefeedt .="<td>";
$mefeedt .="<input type=\"text\" size=\"80\"  name=\"rssfeedurl\" value=\"".$row->url."\"></td> ";
$mefeedt .="<td>";
$mefeedt .="<input type=\"Submit\" name=\"deletefeed\" value=\"Delete Feed\"  >";
$mefeedt .="<input type=\"hidden\" name=\"feeddel\" value=\"1\"/>";
$mefeedt .="</td>";
$mefeedt .="</tr>";
$mefeedt .="</form>";

}
$mefeedt .= "</table>";
}

}  // closes function




// if users add a feed   a. check not already attached one if not  add new feed
function feedaddlink ()  {

global $meurlfeedid;
global $newfeedid;
global $newidadd;
global $lmenuexist;

if ($_POST[feedadd] == 1) {

// first test to see a url has been added
$meurla = mysql_real_escape_string($_POST['me_url']);

if (strlen($meurla) > 0)  {

// check if no feed already attached
$db->query ="SELECT * FROM ".RSSDATA.".rssjoin WHERE ".RSSDATA.".rssjoin.userid = '$_SESSION[user_id]' ";

$changefeed = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// present results in table
if ( mysql_num_rows ($changefeed) == 1 ) {

//  a feed already exists ask user if they want to replace this feed?
echo '<br /><br /><b>Only one url can be added right now. Please delete the existing feed before entering a new blog url.</b>';

}

else  {
// new feed was found - process the url

$meurla = mysql_real_escape_string($_POST['me_url']);

// need to update topmenu, but need to keep note of those lifestyles (if added) that were manually added.
$lmenuexist = $_SESSION[topmenu];
//echo 'set now menu before';
//print_r($lmenuexist);
//echo 'set now menu after';
//unset($_SESSION[topmenu]);
unset($_SESSION[me_url]);

// runs all the functions
feedurllogic ($meurla);


//personalize($meurla, $topmenu, $lifestylelid, $lifestylesubid);

}  // closes else ie first time url add or updated

}  // closes if  a url been entered?

// a url has not been entered
else  {

echo '<b>Please enter a blog url.</b><br />';

}

}  // closes opening if


}  // closes function




// delete blog feed
function feeddelink ()  {

if ($_POST['feeddel'] == 1) {

$feedtodelete = empty($_POST['feedid']) ? die ("There is no RSS Url to delete") : mysql_escape_string($_POST['feedid']);

// delete userlife data to reset lifestylemenu
// create query
$db->query="DELETE FROM ".RSSDATA.".userlife WHERE ".RSSDATA.".userlife.userid = '$_SESSION[user_id]'  AND ".RSSDATA.".userlife.humadd = '0' ";

// execute query grouped words
$deluserlife = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// need some extra logic here it possible a manually added lifestyle can be set but be top5 therefore, it does not get delete.  Check for this and delete



// create query
$db->query="DELETE FROM ".RSSDATA.".rssjoin WHERE ".RSSDATA.".rssjoin.userid = '$_SESSION[user_id]' ";

// execute query grouped words
$savelocal = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

// finally reset topmenu session to nil or the topmenu lifestlye manually added
//unset($_SESSION[lifequalify]);
//unset($_SESSION[lifemenu]);
formmenuin ();
//memenucode ();
$_SESSION[mefid] = 598;
}

}  // close function



// compare existing manual menu with new top5 and add back manual if different
function comptop5man ($lmenuexist, $topfivem)  {
// both arrays in same structure
//print_r($lmenuexist);
//echo '<br />postfive';
//print_r($topfivem);


foreach ($topfivem as $na=>$naid)  {

$topfmex[$naid]= 0;

}

$newlinsert = $topfmex;
//echo 'newinsert<br />';
//print_r($newlinsert);
$_SESSION[newtopm] = $newlinsert;

//print_r($lmenuexist);
//print_r($topfivem);
// need to find out if any of the existing manually added lifestyles are in the top5 if so delete existing menu entry from userlife table)
$matchman = array_intersect($lmenuexist, $topfivem);
//print_r($matchman);
//echo '<br />matchedany<br />';

foreach ($matchman as $del=>$d)  {

$db->query ="DELETE FROM ".RSSDATA.".userlife WHERE ".RSSDATA.".userlife.userid = '$_SESSION[user_id]' AND ".RSSDATA.".userlife.lifestyleid = '$d' ";
//echo $db->query;
$resultdelm = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

}


$manadd = array_diff($lmenuexist, $topfivem);
//echo 'manaddnotin<br />';
//print_r($manadd);
//$_SESSION[newtopm] = $newlmenu;

foreach ($manadd as $na=>$naid)  {

$lmex[$naid]= 1;
}
//print_r($lmex);
//echo '<br />two array to merege before';
//print_r($topfmex);
// newdata to insert 

// next stage add these manually added to the new top5

if (count($manadd)  > 0 )  {
$newlmenu = array_merge($manadd, $topfivem);

//echo 'newtopme<br />';
//print_r($newlmenu);
$_SESSION[topmenu] = $newlmenu;

}

else  {
//echo 'nothing to add';
$_SESSION[topmenu] = $topfivem;
}

// need to form array that give insert 0 or 1  top5 or manuually added  then pass ot insert.

}  // closes function





function feedurllogic ($entryurl)  {

global $newfeedid;
global $newidadd;
global $lmenuexist;

//echo 'feedlogic';
// first find out xml feed source rss and atom,  post and comments.
$feedsource = findrssxmllink ($entryurl);
//print_r($feedsource);
// has any xml feeds been found
if ($feedsource)  {


      $addalready = alreadyaddurl ($entryurl);

      // if the url has been added already then do nothing, else go through a whole lot of logic
      if (isset($addalready))  {
      //echo 'allready add';

      // then process further with personalize logic
      feedexistadd ($addalready, $entryurl);
      //echo 'already in database';

      $newfeedid = $addalready;

      $_SESSION[me_url] = $entryurl;
      $_SESSION[mefid] = $newfeedid;
      //echo $newfeedid.'setting session existing';

      }

      else  {

      // add new url and process scores, stats, melife etc

                // if got back url add it
                if ($entryurl)  {
                //echo 'new add';
                $before = addfeedcheck ();
                // could check feedid too

                // we have a list of feeds, try rss then atom, if not fed in then we cannot process for now.
                if ($feedsource[rss])  {
                //echo 'rss feed add try';
                //echo $feedsource[rss][0];
                //error_reporting(5);
                fof_add_feed($feedsource[rss][0]);
                $newidadd = alreadyaddurl ($entryurl);


                        if ($newidadd && ($newidadd !== $before))  {

                        //echo 'before process new';
                        $newfeedid = alreadyaddurl ($entryurl);
                        //echo $newfeedid;

                        processnewurl ($entryurl, $newfeedid);
                        //echo 'after newprocess add';
                        // generate new individual perpeergroup
                        indivtopfivelife ($newfeedid);
                        indivmostlikeme ($newfeedid);
                        keepfeed ($newfeedid);

                        //echo 'successful rss';
                        $addedfeed = 1;

                        }

                if ($addedfeed !== 1)  {

                //echo 'atom feed try adding';
                fof_add_feed($feedsource[atom][0]);

                $newfeedid = alreadyaddurl ($entryurl);

                        if ($newfeedid && ($newfeedid !== $before))  {

                        processnewurl ($entryurl, $newfeedid);

                        // generate new individual perpeergroup
                        indivtopfivelife ($newfeedid);
                        indivmostlikeme ($newfeedid);
                        keepfeed ($newfeedid);

                        //echo 'successful atom';
                        $atomaddedfeed = 1;

                        }  // closes if

                // one last try, let feed reader have a go at find the xml feed file
                        if ($atomaddedfeed !== 1)  {

                        //echo 'letting rss feed reader have a go with source url';
                        fof_add_feed($entryurl);

                        $newfeedid = alreadyaddurl ($entryurl);

                                  if ($newfeedid && ($newfeedid !== $before))  {

                                  processnewurl ($entryurl, $newfeedid);

                                  // generate new individual perpeergroup
                                  indivtopfivelife ($newfeedid);
                                  indivmostlikeme ($newfeedid);
                                  keepfeed ($newfeedid);

                                  //echo 'successful feed reader attempt';
                                  $feedreaderyes = 1;
                                  }

                                  else {

                                  echo '<b>mepath can not add the feed for the blog right now, please try again or accept our apology for it not working.</b>';

                                  }

                        }

                }  // closes if


              if ($addedfeed == 1 || $atomaddedfeed == 1 || $feedreaderyes == 1)  {

              $_SESSION[me_url] = $entryurl;
              $_SESSION[mefid] = $newfeedid;
              //echo $newfeedid.'setting session';
              $topfivem = $_SESSION[topmenu];
              // now need to add manually added back lifestlye menu items not included by mepath in the individuals users top5. (obviously mepath would not want this outcome if they blog out the lifestlye in question ie. mepath has got it wrong).
              // compare topmenubefore with current menu.
              //echo 'existing menu<br />';
              //print_r($lmenuexist);
              //echo 'existing menu after<br />';
              comptop5man ($lmenuexist, $topfivem);


              $db->query="INSERT INTO ".RSSDATA.".rssjoin (userid, feedsid) VALUES ('$_SESSION[user_id]', '$newfeedid')";
              // execute query grouped words
              $savejoinrss = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
              //echo $db->query;


              // need array to contain top lifesytle and save so on return personalized lifestyle menu is presented
              // need to differenciate between man add items and autotop5
              $toplifestle = $_SESSION[newtopm];

              $db->query ="INSERT INTO ".RSSDATA.".userlife (userid, lifestyleid, humadd) VALUES ";

              foreach ($toplifestle as $toplsi=>$mortf)  {

              $db->query .=" ('$_SESSION[user_id]', '$toplsi', '$mortf'), ";

              }

              $db->query =substr($db->query,0,(strLen($db->query)-2));//this will eat the last comma

              $resultlmenu = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
              //echo $db->query;

              // RUN ALL INPUT TESTS
              newurltest($newfeedid);
              
              }  // closes if a feed has been added.


          }

          }

      } // closes first else

} // closes if xml feed found


else  {

//echo 'letting rss feed reader have a go with source url  TWO';

$before = alreadyaddurl ($entryurl);

fof_add_feed($entryurl);

$newfeedid = alreadyaddurl ($entryurl);

        if ($newfeedid && ($newfeedid !== $before))  {

        processnewurl ($entryurl, $newfeedid);

        // generate new individual perpeergroup
        indivtopfivelife ($newfeedid);
        indivmostlikeme ($newfeedid);
        keepfeed ($newfeedid);

        //echo 'successful feed reader attempt TWO';
        $feedreaderyes = 1;

        }

// no xml feed found.
        else  {

        echo '<b>mepath can not find an xml blog feed right now, please try again or accept our apology for it not working.</b>';
        }  // closes else


        if ($feedreaderyes == 1)  {

        $_SESSION[me_url] = $entryurl;
        $_SESSION[mefid] = $newfeedid;

        $topfivem = $_SESSION[topmenu];
        // now need to add manually added back lifestlye menu items not included by mepath in the individuals users top5. (obviously mepath would not want this outcome if they blog out the lifestlye in question ie. mepath has got it wrong).
        // compare topmenubefore with current menu.
        comptop5man ($lmenuexist, $topfivem);


        $db->query="INSERT INTO ".RSSDATA.".rssjoin (userid, feedsid) VALUES ('$_SESSION[user_id]', '$newfeedid')";
        // execute query grouped words
        $savejoinrss = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
        //echo $db->query;


        // need array to contain top lifesytle and save so on return personalized lifestyle menu is presented
        // need to differenciate between man add items and autotop5
        $toplifestle = $_SESSION[newtopm];

        $db->query ="INSERT INTO ".RSSDATA.".userlife (userid, lifestyleid, humadd) VALUES ";

        foreach ($toplifestle as $toplsi=>$mortf)  {

        $db->query .=" ('$_SESSION[user_id]', '$toplsi', '$mortf'), ";

        }

        $db->query =substr($db->query,0,(strLen($db->query)-2));//this will eat the last comma

        $resultlmenu = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
        //echo $db->query;

        }  // closes if a feed has been added.

    }  // closes last else


}  // closes function




// check url been added
function  addfeedcheck ()  {

$db->query ="SELECT * FROM ".RSSDATA.".feeds ORDER BY ".RSSDATA.".feeds.id DESC LIMIT 1 ";
//echo $db->query;
$resultnewitems = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

$rowcurrfeed = mysql_fetch_object($resultnewitems);
$firstitem = $rowcurrfeed->id;

return $firstitem;


}  // closes function




function findrssxmllink ($sourcef)  {

//echo 'parse for source xml link';
//echo $sourcef.'url or what???';
$page = file_get_contents($sourcef, true);


// serach for all 'RSS Feed' declarations
if (preg_match_all('#<link[^>]+type="application/rss\+xml"[^>]*>#is', $page, $rawMatches)) {
// extract url from each declaration
foreach ($rawMatches[0] as $rawMatch) {

if (preg_match('#href="([^"]+)"#i', $rawMatch, $rawUrl)) {

//echo $rawUrl[1].'<br>';
// create array
$feedxml[rss][] = $rawUrl[1];


}
} 
}

// serach for all 'ATOM Feed' declarations
if (preg_match_all('#<link[^>]+type="application/atom\+xml"[^>]*>#is', $page, $rawMatches)) {
// extract url from each declaration
foreach ($rawMatches[0] as $rawMatch) {

if (preg_match('#href="([^"]+)"#i', $rawMatch, $rawUrl)) {

//echo $rawUrl[1].'<br>';
// create array
$feedxml[atom][] = $rawUrl[1];


}
} 
}


//print_r($feedxml);
return $feedxml;

}  // closes function


function tidyurla ($rawurl)  {

global $urlcleanish;

// get host name from URL
preg_match('@^(?:http://)?([^/]+)@i', $rawurl, $matches);
$host = $matches[1];

//preg_match('#href="([^"]+)"#i', $rawurl, $matches);
//$host = $matches[1];


// get last two segments of host name
preg_match('/[^.]+\.[^.]+\.[^.]+$/', $host, $matchesa);
//preg_match('#href="([^"]+)"#i', $host, $matchesa);

//echo "domain two part name is: {$matchesa[0]}\n";
$urlcleanish = $matchesa[0];
//echo '<br /><br />';

if ($matchesa[0] == null)  {


// get last two segments of host name
preg_match('/[^.]+\.[^.]+$/', $host, $matches);
//preg_match('#href="([^"]+)"#i', $host, $matches);
//echo "domain name is: {$matches[0]}\n";
$urlcleanish = $matches[0];
//echo '<br /><br />';

}
//echo $urlcleanish;
//echo '<br /><br />';
return $urlcleanish;

}  // closes function


function alreadyaddurl ($turl)  {

// make sure new url has been added
$db->query ="SELECT * FROM ".RSSDATA.".feeds WHERE (".RSSDATA.".feeds.url LIKE '%$turl%') LIMIT 1 ";

$resultunique = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;
// if successfully add the progress
if (mysql_num_rows($resultunique) == 1 )  {

$row = mysql_fetch_object($resultunique);

$alreadyurl = $row->id;

}  // closes if

return $alreadyurl; 


} // closes function



// first time entry of blog url feed
function processnewurl ($meurl, $meurlfeedid)  {

global $meurlfeedid;

// make sure new url has been added
$db->query ="SELECT * FROM ".RSSDATA.".feeds WHERE (".RSSDATA.".feeds.url LIKE '%$meurl%') ";

$resultunique = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;
// if successfully add the progress
if (mysql_num_rows($resultunique) == 1 )  {

$row = mysql_fetch_object($resultunique);

// then find out which lifestyle qualify for this url?
$meurlmatched = $newblog;
$meurlfeedid = $row->id;


specialwords ();
lifestylearray ();

// split text into individual words in a table
blogpostwords ();



//  sum votes to an indiviudal to see if they qualify for a lifestyle inclusion
singlefeedstat ($meurlfeedid);
//  create me averages and compare to 'crowd' averages
singlemelifecalc ($meurlfeedid);


// find out the number of top level lifestyles, first form arrary of all possible lifestyle options
standardtopmenutwo ();

// find out if this url qualifies for any lifestyle? 
memenu ($meurlfeedid);

$_SESSION[lifemenu] = $lifemenu;
$_SESSION[lifequalify] = $lifequalify;

// find lifestyles that qualify
compareusermenutwo ($lifemenu, $lifequalify);

// builds menus  /////////////////////////////////////////////////////////////////////////////////////////////////////////////
memenucode ($topmenu, $lifestylelid); 


}

// if usr url qualifies for no lifestyles then give them the start start UI but with saying, "no lifestyles identifie but please select lifestyle from below manually)
else  {

standardtopmenutwo ();
$topmenu =  $lifemenu;
//echo $topmenu;

$_SESSION[topmenu] = $topmenu;


if ($_GET['lifestyleid'])  {

$lifestylelid = ($_GET['lifestyleid']);
//echo $lifestylelid;

}

else  {

$lifestylelid = 1;
}


if ($_GET['lifestylesubid'])  {

$lifestylesubid = ($_GET['lifestylesubid']);
//echo $lifestylesubid;

}

else  {

$lifestylesubid = 0;
}

}  // closes else






}  // closes function






function feedexistadd ($meurlfeedid, $entryurl)  {

global $lifemenu;
global $lifequalify;

$lmenuexist = $_SESSION['topmenu'];
unset($_SESSION[topmenu]);
//print_r($lmenuexist);
//print_r($_SESSION['topmenu']);
//echo 'pastexisting';
// find out the number of top level lifestyles, first form arrary of all possible lifestyle options
standardtopmenutwo ();

// find out if this url qualifies for any lifestyle? 
memenu ($meurlfeedid);

$_SESSION[lifemenu] = $lifemenu;
$_SESSION[lifequalify] = $lifequalify;

// find lifestyles that qualify
compareusermenutwo ($lifemenu, $lifequalify);

// builds menus  /////////////////////////////////////////////////////////////////////////////////////////////////////////////
memenucode ($topmenu, $lifestylelid); 


$_SESSION[me_url] = $entryurl;
$_SESSION[mefid] = $newfeedid;

$topfivem = $_SESSION[topmenu];
//print_r($topfivem);
//echo 'pasttopfive';
// now need to add manually added back lifestlye menu items not included by mepath in the individuals users top5. (obviously mepath would not want this outcome if they blog out the lifestlye in question ie. mepath has got it wrong).
// compare topmenubefore with current menu.
comptop5man ($lmenuexist, $topfivem);


$db->query="INSERT INTO ".RSSDATA.".rssjoin (userid, feedsid) VALUES ('$_SESSION[user_id]', '$meurlfeedid')";
// execute query grouped words
$savejoinrss = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;


// need array to contain top lifesytle and save so on return personalized lifestyle menu is presented
// need to differenciate between man add items and autotop5
$toplifestle = $_SESSION[newtopm];

$db->query ="INSERT INTO ".RSSDATA.".userlife (userid, lifestyleid, humadd) VALUES ";

foreach ($toplifestle as $toplsi=>$mortf)  {

$db->query .=" ('$_SESSION[user_id]', '$toplsi', '$mortf'), ";

}

$db->query =substr($db->query,0,(strLen($db->query)-2));//this will eat the last comma

$resultlmenu = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;



}  // closes function



?>

