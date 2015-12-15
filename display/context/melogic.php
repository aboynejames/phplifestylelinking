<?php

// setting session for new public use of site //
function publicme ()
{

global $startmag;
global $lifestylelid;

// for first time visitors
$_SESSION[pubuser] = 44;
//$lifestylelid = 1 ;
//echo $lifestylelid;
// make array for lifestyle magazine sections
if(!$topmenu  &&  !$_SESSION[topmenu])  {

$startmag = 1;
maggroupsel($startmag);
$lifestylelid = 1;

  }

if($_SESSION[mecid] )  {

$lifestylelid = $_SESSION[mecid];

}

if(!$lifestylelid)  {

$lifestylelid = 1;

}

if(!$_SESSION[metext])  {

 $_SESSION[metext] = 4;
}


}  // closes function




/////////// function to set menu context and presentation option for a page  //////////////////

function mecontexttt ()  {

global $topmenu;
global $lifestylelid;
global $lifestart;
global $newlifestyleid;


//  public logic and signed in
  if($_SESSION[pubuser] == 44 )  {
//echo 'START';

     if (($_SESSION[pubuser] == 44) && ($_SESSION[user_id]))  {
     //echo 'user unclear';

     unset($_SESSION[user_id]);
     unset($_SESSION[username]);
     unset($_SESSION[logged_in]);


     //first time at url then set metext to 4
    if (!$_SESSION[metext])  {

     $_SESSION[metext] = 4;

      }

     if ((!$_GET['lifestyleid']) || (!$_SESSION[mecid])) {

     $lifestylelid = 1;

     }
     else  {
     $idlifestart = $_SESSION[mecid];
     //echo $idlifestart;

     idstartconvert ($idlifestart); 

     $lifestylelid = $newlifestyleid;

     }
     
     }  // closes if user id info. exists, delete it


//  if lifestyle activity has been clicked, set next context with it.
  if ($_GET['lifestyleid'])  {
//echo 'AAA';    
  $lifestylelid = escapeinteger($_GET['lifestyleid']);
  $topmenu = $_SESSION[topmenu];

  if(!$topmenu)  {
  $topmenu['me'] = $lifestyleid;

  }

  memenucode ($topmenu, $lifestylelid);

  idconvert ($lifestylelid);

  $_SESSION[mecid] = $lifestart;

  }  // closes opening if


elseif ($_POST['lifestylesid'])  {
// echo 'BBB';   
// need a set of function that will take existing lifestyle menu and add/delete activities and then disply the new lifestyle menu
$humaddlist = escapeinteger($_POST['lifestylesid']);

humanaddpublic($humaddlist);

$idlifestart = $_SESSION[mecid];
    //echo $idlifestart;

    idstartconvert ($idlifestart); 

    $lifestylelid = $newlifestyleid;
memenucode ($topmenu, $lifestylelid);

$_SESSION[metext] = 4;


}  // closes elseif


elseif ($_GET['mags'])  {
// echo 'DDD';   

$getmag = escapeinteger($_GET['mags']);

maggroupsel($getmag);

memenucode ($topmenu, $lifestylelid);

$_SESSION[metext] = 4;


}  // closes elseif



//   if metext set context   else metext not set this it is a first time login 
else  {
//echo 'CCC';
    if ($_GET['metext'] || $_SESSION[metext] )  {

    // need to set a session variable to use to redisplay right context
    $_SESSION[chartreturn] = 1;

    // need to find what lifestlye activity is current and set lifestylelid for it and build menu, if first logged skip 
     if($_GET['metext'])  {

     $_SESSION[metext] = escapeinteger($_GET['metext']);
     }
        
     else  {
     
     $_SESSION[metext] = 4 ;
     
     }
  } // closes if

    if($_SESSION[mecid])  {
    
    $idlifestart = $_SESSION[mecid];
    //echo $idlifestart;

    idstartconvert ($idlifestart); 

    $lifestylelid = $newlifestyleid;
    //echo $lifestylelid;
    }
    
    else  {
    
    $lifestylelid = 1;
    //echo$lifestylelid;
    }

// if topmenu empty then make it me

    $topmenu = $_SESSION[topmenu];

       if(!$topmenu)  {

       $topmenu['me'] = 46;
       }


    memenucode ($topmenu, $lifestylelid);


  }  // closes else


}  // closes opening if (is public user set)

////////////////////////////////////////////////////    logic user sign in   //////////////////////////////////
// user is loggin in either openID or fbConnect
else {

//  if lifestyle activity has been clicked, set next context with it.
if ($_GET['lifestyleid'])  {
    
$lifestylelid = escapeinteger($_GET['lifestyleid']);
$_SESSION[filtext] = 2;
$topmenu = $_SESSION[topmenu];

if(!$topmenu)  {
$topmenu['me'] = $lifestyleid;

}

memenucode ($topmenu, $lifestylelid);

idconvert ($lifestylelid);

$_SESSION[mecid] = $lifestart;

}  // closes opening if


// if filtext is present then need to set current lifestyleid from session 
elseif ($_GET['filtext'])  {

$setfiltext = escapeinteger($_GET['filext']);
$_SESSION[filtext] = $setfiltext;

if ($setfiltext  ==1 )  {

$filtext = 1;

}

else  {

$filtext = 2;

}
// get current lifestyleid from session to keep its context
$idlifestart = $_SESSION[mecid];
    //echo $idlifestart;
idstartconvert ($idlifestart); 
$lifestylelid = $newlifestyleid;
    
$topmenu = $_SESSION[topmenu];

if(!$topmenu)  {
$topmenu['me'] = $lifestyleid;

}

memenucode ($topmenu, $lifestylelid);




}  // closes if filtext been pressed


elseif ($_POST['lifestylesid'])  {
    
// need a set of function that will take existing lifestyle menu and add/delete activities and then disply the new lifestyle menu
$humaddlist = escapeinteger($_POST['lifestylesid']);

humanadd ($humaddlist);
formmenuin ();
memenucode ($topmenu, $lifestylelid);

// if logged in to this

//header("Location: /lifestylelinking/me/mein.php");
$_SESSION[metext] = 1;


}  // closes elseif



//   if metext set context   else metext not set this it is a first time login 
else  {

    if ($_GET['metext'] && $_SESSION[mecid] )  {

    // need to set a session variable to use to redisplay right context
    $_SESSION[chartreturn] = 1;

    // need to find what lifestlye activity is current and set lifestylelid for it and build menu, if first logged skip 
    $_SESSION[metext] = escapeinteger($_GET['metext']);

    $idlifestart = $_SESSION[mecid];
    //echo $idlifestart;

    idstartconvert ($idlifestart); 

    $lifestylelid = $newlifestyleid;
    //echo $lifestylelid;

// if topmenu empty then make it me

    $topmenu = $_SESSION[topmenu];

       if(!$topmenu)  {

       $topmenu['me'] = 46;
       }


    memenucode ($topmenu, $lifestylelid);

      }  // closes if

    else {
    
    unset($_SESSION[mefid]);

    formmenuin ();
    if($topmenu)  {
    memenucode ($topmenu, $lifestylelid);
    $_SESSION[metext] = 1;
    $_SESSION[filtext] = 2;
    }
    else {
    $_SESSION[metext] = 3;
    }
    
    //  first time need to match user id to feedsid
    if (!$_SESSION[mefid])  {
    // need to find out feedid from login userid
    $db->query ="SELECT * FROM ".RSSDATA.".rssjoin WHERE ".RSSDATA.".rssjoin.userid = '$_SESSION[user_id]' ";

    $resultmefid = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
    //echo $db->query;
    if (mysql_num_rows($resultmefid) == 1 )  {

    $row = mysql_fetch_object($resultmefid);
    $feedmeid = $row->feedsid;

    $_SESSION[mefid] = $feedmeid;
    }
    
   else {
 
     $_SESSION[mefid] = 598;

         }
    
    }  // closes if feedid set
    
}


}  // closes else


}  // closes open else


}  // closes function





function formmenuin ()  {

global $topmenu;
global $lifestylelid;
global $lifestart;

$db->query ="SELECT * FROM ".RSSDATA.".userlife LEFT JOIN ".RSSDATA.".lifestyle ON ".RSSDATA.".lifestyle.idlifestyle = ".RSSDATA.".userlife.lifestyleid  WHERE ".RSSDATA.".userlife.userid = '$_SESSION[user_id]'  AND (".RSSDATA.".userlife.humadd = '0' OR ".RSSDATA.".userlife.humadd = '1') ";

$resultmemenu = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;

if (mysql_num_rows($resultmemenu)  > 0 )  {

$topmenu['me'] = 46;

while($row = mysql_fetch_object($resultmemenu))  {

$topmenu[$row->name] = $row->lifestyleid;

}

$_SESSION[topmenu] = $topmenu;

//print_r($topmenu);

}

// what if no save meun, a. give them list of everything or invite to add url of context they like (to former first)
else  {

unset($_SESSION[topmenu]);


}  // closes else


$lifestylelid = 46;

idconvert ($lifestylelid);

$_SESSION[mecid] = $lifestart;



}  // closes function



// user personalizes lifestlye menu manually
function humanadd ($humaddlist)  {

if (isset($_POST['editlifestyle']))  {

// could be that lifestyle has been already added, and deleted, if this is the case update rather than insert new lifestyle into database table
// first check to see if lifestyle item has ever been included

foreach ($humaddlist as $lifestyleadd) {

$db->query="SELECT * FROM ".RSSDATA.".userlife WHERE (".RSSDATA.".userlife.userid = '$_SESSION[user_id]' AND ".RSSDATA.".userlife.lifestyleid = '$lifestyleadd')";
//echo $db->query;
$resultexistallr = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

//  if the lifestyle has been added to the database already then update the database
if (mysql_num_rows($resultexistallr) > 0 )  {

// first need to check if this one of the users top5 lifestyles
$db->query="SELECT * FROM ".RSSDATA.".toplife WHERE ".RSSDATA.".toplife.feedid = '$_SESSION[mefid]' AND ".RSSDATA.".toplife.lifestyleid = '$lifestyleadd' ";

$resultintop = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultintop) > 0 )  {

$db->query ="UPDATE ".RSSDATA.".userlife SET ".RSSDATA.".userlife.humadd = '0' WHERE (".RSSDATA.".userlife.userid = '$_SESSION[user_id]' AND ".RSSDATA.".userlife.lifestyleid = '$lifestyleadd' ) ";

$resultreadd = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

}

else  {
// status 1

$db->query ="UPDATE ".RSSDATA.".userlife SET ".RSSDATA.".userlife.humadd = '1' WHERE (".RSSDATA.".userlife.userid = '$_SESSION[user_id]' AND ".RSSDATA.".userlife.lifestyleid = '$lifestyleadd' ) ";

$resultreadd = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
}

}  // closes if

// first time added so use insert
else  {

//  first item the insert
$db->query="INSERT INTO ".RSSDATA.".userlife (userid, lifestyleid, humadd) VALUES ('$_SESSION[user_id]', '$lifestyleadd', '1') ";
//echo $db->query;
$resultnewadd = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

}

} // closes foreach loop 


}  // closes opening if statement


elseif (isset($_POST['deletelifestyle']))   {


// need to delete lifestyle menu selected
$db->query ="UPDATE ".RSSDATA.".userlife SET ".RSSDATA.".userlife.humadd = '2' WHERE "; 

foreach ($humaddlist as $lifestyleadd) {

$db->query .="(".RSSDATA.".userlife.userid = '$_SESSION[user_id]' AND ".RSSDATA.".userlife.lifestyleid = '$lifestyleadd' ) OR ";

}

$db->query=substr($db->query,0,(strLen($db->query)-3));//this will eat the last OR
//echo $db->query;

$resultdelstat = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

}  // closes elseif


}  // closes function





function humanaddpublic ($humaddlist)  {

global $topmenu;
global $lifestylelid;

// user not signed in then add the select sports to a session variable
if (isset($_POST['editlifestyle']))  {

foreach ($humaddlist as $lifestyleadd) {

// need to use lifesytleid to find out name of lifestyle
$db->query="SELECT * FROM ".RSSDATA.".lifestyle WHERE ".RSSDATA.".lifestyle.idlifestyle = '$lifestyleadd' ";
//echo $db->query;
$resultaddlife = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if(mysql_num_rows($resultaddlife) == 1 )  {

$row= mysql_fetch_object($resultaddlife);
$addlifepub = $row->name;

$newlifelist[$addlifepub] = $lifestyleadd;

}

}  // closes foreach loop

// need to get existing lifestyle topmenu add these new sports
// merge the two arrays  newlifelist and existing topmenu
if($_SESSION[topmenu])  {
$newtopm = array_merge($_SESSION[topmenu], $newlifelist);
//print_r($newtopm);
$_SESSION[topmenu] = $newtopm;
$topmenu = $_SESSION[topmenu];
}

else  {
$_SESSION[topmenu] = $newlifelist;
$topmenu = $_SESSION[topmenu];

}

$lifestylelid = $_SESSION[mecid];

}  // closes if

elseif (isset($_POST['deletelifestyle']))   {


// need to remove these lifestyles(sports) from $
foreach ($humaddlist as $lifestyleadd)  {

// need to use lifesytleid to find out name of lifestyle
$db->query="SELECT * FROM ".RSSDATA.".lifestyle WHERE ".RSSDATA.".lifestyle.idlifestyle = '$lifestyleadd' ";
//echo $db->query;
$resultaddlife = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if(mysql_num_rows($resultaddlife) == 1 )  {

$row= mysql_fetch_object($resultaddlife);
$relifepub = $row->name;

$removelist[$relifepub] = $lifestyleadd;

}

}  // closes loop


if($_SESSION[topmenu])  {
$newtopm = array_diff($_SESSION[topmenu], $removelist);
//print_r($newtopm);
$_SESSION[topmenu] = $newtopm;
$topmenu = $_SESSION[topmenu];
}

else  {
$_SESSION[topmenu] = $removelist;
$topmenu = $_SESSION[topmenu];

}

$lifestylelid = $_SESSION[mecid];

}  // closes elseif





}  // closes function




function memenucode ($topmenu, $lifestylelid)  {

// build top menus  live version, /lifestylelinking.php

global $memenulist;
global $topmenu;
global $lifestylelid;


// find out the url the is calling for a menu
//$currurl = $_SERVER['SCRIPT_NAME'];
$currurl = 'index.php';
//echo $currurl;
////  builds lifestyle menu for left section ////
$memenulist = '';

if(!$topmenu)  {

$topmenu['me'] = 46;

}

//  here we need to produce top lifestyle category buttons and left hand side category buttons
foreach ($topmenu as $menuimage=>$menulifeid)  {

//echo $lifestylelid;
if ($lifestylelid == $menulifeid)  {

$memenulist .= "<style type=\"text/css\">
.leftboxmenusel  {
	background-color: rgb(238,77,133);	
}
</style>";

$memenulist .= "<div class=\"leftboxmenusel\">";
$memenulist .= "<a href=\"".makeurla($currurl)."?lifestyleid=".$menulifeid."\">".$menuimage."</a>
<img src=\"/lifestylelinking/display/images/llinking.gif\">";
$memenulist .= "</div>";

}


else  {

$memenulist .= "<style type=\"text/css\">
}
</style>";


$memenulist .= "<div class=\"leftboxmenu\">";
$memenulist .= "<a href=\"".makeurla($currurl)."?lifestyleid=".$menulifeid."\">".$menuimage."</a>";
$memenulist .= "</div>";


// need to add delete link to each box,  javascript?

}

//  need to add box that says, other suggested lifestyle,  or add from list


}  //closes foreachloop



return $memenulist;


}  // closes function



function melifechart ()  {

foreach ($toplifestyle as $mtlife )  {

$db->query ="SELECT * FROM ".RSSDATA.".melife WHERE ";

}



}  // closes function





function idconvert ($lifestylelid)  {

global $lifestart;

// need to convert lifestyle id to idlifestart always
$db->query ="SELECT * FROM ".RSSDATA.".lifestyle WHERE ".RSSDATA.".lifestyle.idlifestyle = '$lifestylelid'";
//echo $db->query;
$resultsl = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultsl)  == 1 )  {

$row = mysql_fetch_object($resultsl);
$lifestart = $row->idlifestart;

return $lifestart;

}

}  // closes function


// converts lifestyleid to  idlifestart
function idstartconvert ($lifestylelid)  {

global $newlifestyleid;

// need to convert lifestyle id to idlifestart always
$db->query ="SELECT * FROM ".RSSDATA.".lifestyle WHERE ".RSSDATA.".lifestyle.idlifestart = '$lifestylelid'";
//echo $db->query;
$resultsli = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultsli)  == 1 )  {

$row = mysql_fetch_object($resultsli);
$newlifestyleid = $row->idlifestyle;
//echo $newlifestyleid;
}

return $newlifestyleid;

}  // closes function





function hexcolors ($loopno)  {

global $charcolor;

$hex_colors = array('#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99', '#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99', '#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99', '#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99', '#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99', '#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99', '#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99', '#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99', '#3399FF',
 '#FFCC00', '#CC33FF', '#CC0000',
'#00CC66','#FFCCCC', '#00CCFF',
'#CCFFCC','#FF33FF', '#FFFF99');


$charcolor = $hex_colors[$loopno];



}  // closes function



function magazinesections ()  {

global $magsect;

// display lifestyle magazine sections
// first find out which magazine section have been setup
$db->query ="SELECT * FROM ".RSSDATA.".lifestylegroups LIMIT 6 ";

$resultlg = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultslg)  > 0 )
  {
  while ($row = mysql_fetch_object($resultlg))  {
  
    $livegroup[] = $row->groupid;
  
  }
  }  // closesif

$db->query ="SELECT * FROM ".RSSDATA.".lifestylegroups WHERE ";

foreach ($livegroup as $lgrp)  
  {
$db->query .=" ".RSSDATA.".lifestylegroups.groupid = $lgrp OR";

    }  // closes foreach
$db->query=substr($db->query,0,(strLen($db->query)-3));//this will eat the last OR
$resultslg = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultslg)  > 0 )  {

$magsect = '';

//  need to find out current magazine section selected, via getmags or via lifestylelid
if(!$_GET['mags'])  {

   if($_GET['lifestyleid'])  {

   $lifeselmag = escapeinteger($_GET['lifestyleid']);

   $db->query ="SELECT * FROM  ".RSSDATA.".grouplink WHERE ".RSSDATA.".grouplink.idlifestyle = '$lifeselmag' ";
  $resultcmagl = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

   if (mysql_num_rows($resultcmagl)  == 1 )  {

   $row = mysql_fetch_object($resultcmagl);
   $curmagset = $row->groupid;

     }
     }  // closes if lifestlelid
     
     else {

     $_GET['mags'] = 1;
     
     }
} // closes if no mags

else {
$curmagset = escapeinteger($_GET['mags']);
}


while($row = mysql_fetch_object($resultslg))  {

//  if groupid matches currentid then underline
if($curmagset == $row->groupid )  {

$magsect .= "<div class=\"magalist\"><b><a href=\"".makeurla('index.php')."?mags=$row->groupid\" id=\"current\" >$row->groupname</a></b>&nbsp &nbsp</div>";

}

else  {

$magsect .= "<div class=\"magalist\"><b><a href=\"".makeurla('index.php')."?mags=$row->groupid\">$row->groupname</a></b>&nbsp &nbsp</div>";

}


}

//$magsect .= "<div class=\"magalist\"><a href=\"".makeurla('index.php')."?metext=3\">more</a></div>";


}

echo $magsect;

}  // closes function




function maggroupsel ($magsid)  {

global $topmenu;
global $lifestylelid;

$db->query ="SELECT * FROM ".RSSDATA.".lifestyle LEFT JOIN ".RSSDATA.".grouplink ON ".RSSDATA.".grouplink.idlifestyle = ".RSSDATA.".lifestyle.idlifestyle WHERE ".RSSDATA.".grouplink.groupid = '$magsid' ";
//echo $db->query;
$resultmagid = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());

if (mysql_num_rows($resultmagid)  > 0 )  {

$topmenu = '';

while($row = mysql_fetch_object($resultmagid))  {

$topmenu[$row->name] = $row->idlifestyle;

}
}

$_SESSION[topmenu] = $topmenu;

// set lifestyleid as first time in magazine section
$i = 0;

foreach ($topmenu as $key => $value) {
$i++;

$lifestylelid = $value;

if ( $i == 1 )
break;
} 


}  // closes function



?>