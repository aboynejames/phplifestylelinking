<?php
// based on the individual find those closest to them in 'average', over all relevant lifestyle lifestyle or ability to switch on and off the lifestylelinking and see how the results disply change
function mostlikeme ()  {

global $feedids;
global $peergroup;
global $meuqni;
global $melifeavglist;
global $perfeed;

// NEED TO SETUP UPFRONT LOGIC IS BATCH OR REFRESH FOR PARTICUALR INDIVIDUAL?

// what is feed id of this individual
//$feedindv  = 2;  // need to GET_(feed_id) or something
$date = time();

// need list of all feeds
feedarray ();
//$feedids = Array ( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20');//, '21' => '21', '22' => '23', '23' => '24', '24' => '25', '25' => '26', '26' => '27', '27' => '28', '29' => '29', '30' => '31', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35', '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40');
//print_r($feedids);

prepmelife ($feedids);

//print_r($meuqni);
// call melifedata array with list of feed ids mepath needs data on


//how many individual, count feedarray
$indpeers = count($feedids);
//echo $indpeers.'nofeeds<br />';
//80-20 rule
$peertwenty = $indpeers * 0.1 ;
//echo $peertwenty.'20% feeds<br />';


// batch per lifestlyeid  save memory getting over maxed
foreach ($meuqni as $unid)  {

$perdiffarr = array();
$newadf = 0;
$perdiffarr = melifearray ($unid, $newadf, $feedid);
//print_r($melifeavglist);
//print_r($perdiffarr);
// now look up the individuals melife stat and then order all indivials stas and then use 80-20 rules, ie take20% of indivudals clostest to that individual.  Then display results, have any post been published from this peergroup of in the last 24hrs?


// new list of feeds that have lifestyle in top5 $perfeed;
//foreach ($feedids as $fma)  {

foreach ($perfeed[$unid] as $pfma)  {

$steps = '';
// form an array for all the lifestyleids, count no. feeds find out 20% of that number, find out individuals average then identify feeds 10% plus this indivudals avarage and -10% from their average
//print_r($melifeavglist[$mell]);
$mecou = count($perdiffarr[$unid]);

if ( $mecou  > 0  )  {

//gives us array containing the individuals average
// next need to find -10 and +10% either side of that position

//  now need to count back and forward along the array to pick out peer list.
$steps = new Steps();
$poslist = array();

foreach ($perdiffarr[$unid] as $key=>$reindex)  {

 $steps->add($key);  
 
 }
 //echo 'listorder';
 //print_r($steps->all);
 $feedindv = $pfma;
 //echo 'indiv feedid'.$feedindv.'<br /><br />';
// set this individuals avg. position
 $steps->setCurrent($feedindv);

for ($i = 1; $i <= $peertwenty; $i++) {
  
//echo $i;
//echo $steps->getNext().'<br>';
$newset = $steps->getNext();
$indivset = $steps->setCurrent($newset);

if (strlen($newset) > 0)  {

$poslist[] = $newset;

}

}
//print_r($poslist);
$steps->setCurrent($feedindv);

for ($i = 1; $i <= $peertwenty; $i++) {
  
//echo $i;
//echo $steps->getPrev().'<br>';
$newset = $steps->getPrev();
$indivset = $steps->setCurrent($newset);

if (strlen($newset) > 0)  {

$poslist[] = $newset;

}
}

//echo 'postlist';
//print_r($poslist);
//echo 'postlist';

$arraycou = count($poslist);
//echo $arraycou.'numcou';
// save to perpeers table to have on demand quickly and update on a per individual basis
if ($arraycou > 0 )  {

//print_r($poslist);
//echo 'heelo';
$peerinslist = '';
$rank = '';

foreach ($poslist as $peerid)   { 

$rank++;

$peerinslist .="('$pfma', '$rank', '$date', '$unid', '$peerid' ), ";

}

$peerinslist =substr($peerinslist,0,(strLen($peerinslist)-2)); //this will eat the last comma
//echo $peerinslist.'insertcode';

if (strLen($peerinslist) > 0 )  {

$db->query =" INSERT INTO ".RSSDATA.".perpeers (feedid, rank, date, idlifestart, peerid) VALUES ";

$db->query .= $peerinslist;
//echo $db->query;
$resultinsqual = mysql_query($db->query) or die(mysql_error());

} //closes if 
} // closes if anything to insert

}  // closes if no melifedata

}  // closes foreach loop ie eachfeed.


}  // closes for each unqiue lifestyle definition


}  // closes function




// presentation code of a personalized peer group
function pergroupdplay ($lifestyleid, $feedid)  {

global $ppeergroup;
global $peergroup;

// input, need to know what lifestyle is in context (and if ll on multiple lifestlye then produce peer group on those priorities e.g. yoga flitered to those post authored by a triathlete)
$lifeid = $lifestyleid; 
//$lifeid = 1;
//$feedid = 1;
if ($feedid == 598)  {
//echo 'dispaly aveage';
// average then list peers from daily peers
mostlikelifestyle ($lifestyleid);
echo $peergroup;
}

else  {

// user has feedid then get personalized peers on that basis

// need to combine with feeds to get url info for each to be able to display.  Also maybe save personalized peer grounds in a new table?
$db->query="SELECT * FROM ".RSSDATA.".perpeers LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".perpeers.peerid  WHERE   ".RSSDATA.".perpeers.idlifestart = '$lifeid'  AND ".RSSDATA.".perpeers.feedid = '$feedid' ";
//echo $db->query;

// eat the last comma
//$db->query =substr($db->query,0,(strLen($db->query)-2));//this will eat the last or
$db->query .= " LIMIT 50";
//echo $db->query;
$resultppeer = mysql_query($db->query) or die(mysql_error());

$ppeergroup = '';

if (mysql_num_rows($resultppeer) > 0 )  {

$ppeergroup .=  "<img src=\"/lifestylelinking/images/peergroup.gif\">";
// display search results
$ppeergroup .=  "<table width=\"100\" border=\"1\" style='table-layout:fixed' >";
$nocolumns = 5;

//column width
//$colwidth = (int)(100/$nocolumns);
$colwidth = 25;

$i = 0;

while ($row = mysql_fetch_object($resultppeer))  {

       if ($i % $nocolumns == 0)  {
// start a new row
$peergroup .= "<tr>";
}

$noproducts = mysql_num_rows($resultppeer);
$norows = ceil($noproducts/$nocolumns);
//echo $norows;

if ($noproducts > 1 )  {

$imagef = $row->image;

}

//$imagef = "/images/meico.gif";

if ( strlen($imagef) == 0 )  {

$imagef = "/lifestylelinking/images/meico.gif";

}


$ppeergroup .= "<td width = ".$colwidth." ><a href=\"".$row->url."\"><img src=\"".$imagef."\" alt=\"".$row->url."\" width=\"16\" height=\"16\"></a> <br /></td>";


if ($i % $nocolumns == $nocolumns - 1) {
//end this row
$ppeergroup .= "</tr>";
}

$i += 1;
}

//print blank columns

if ($i % $nocolumns != 0)  {

while ($i++ % $nocolumns != 0) {
$ppeergroup .= "<td width = ".$colwidth."'%'>&nbsp;</td>";
}
$ppeergroup .= "</tr>";
}
$ppeergroup .= "</table>";


}  // closes if

}  // closes else


}  // closes function




// class to find list of personalized peergroups
class Steps {
  
    public $all;
    private $count;
    private $curr;
  
    public function __construct () {
    
      $this->count = 0;
    
    }
  
    public function add ($step) {
    
      $this->count++;
      $this->all[$this->count] = $step;
    
    }
  
    public function setCurrent ($step) {
    
      reset($this->all);
      for ($i=1; $i<=$this->count; $i++) {
        if ($this->all[$i]==$step) break;
        next($this->all);
      }
      $this->curr = current($this->all);
    
    }
  
    public function getCurrent () {
    
      return $this->curr;
    
    }
  
    public function getNext () {
          self::setCurrent($this->curr);
          return next($this->all);
    
    }
   
    public function getPrev () {
    
      self::setCurrent($this->curr);
      return prev($this->all);
    
    }
      
  }  // closes class




// what feeds and lifestyle ids need melife data array for?
function prepmelife ($meprdata)  {
//echo 'heelo';
global $lifestylemell;
global $meuqni;
global $perfeed;

$lifestylemell = array();


foreach ($meprdata as $fmam)  {


// now have toplife table, top5 lifestyles foreach feed, let preprocess all those to have per group on tap.
$db->query ="SELECT * FROM ".RSSDATA.".toplife WHERE ".RSSDATA.".toplife.feedid = '$fmam' "; 
//echo $db->query;
$resultlstyles = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlstyles) >  0 )  {

while ($row = mysql_fetch_object($resultlstyles))  {

// these lifestyle id need convert idlifestart? Probably
$lifestylemell[] = idconvert ($row->lifestyleid);
$toperfeed[$fmam][] = idconvert ($row->lifestyleid); 
}  
}  // closes if

}

// probable duplicate lifestyle id to limit to unique ids (most probably all ids)
//print_r($toperfeed);
//echo 'uni coming <br /><br />';
$meuqni = array_unique($lifestylemell);
sort($meuqni);
//print_r($meuqni);


// turn $toperfeed array and list per feed has each lifestyle def ie.  lifestyle 1 has feed x y and z in them

// foreach lifestyleid 
//$meuqni = Array ( '0' => '1', '1' => '7');//, '2' => '8', '3' => '16', '4' => '20', '5' => '28', '6' => '66' );

foreach ($meuqni as $lifeid)  {

foreach ($toperfeed as $fedkey=>$lifefive )  {

//echo 'lifestyleidwuqals'.$lifeid;
//print_r($lifefive);

$intop = '';

$intop = array_search($lifeid, $lifefive);
//echo 'intop'.$intop.'<br /><br />';

if ($intop > 0  || $intop === 0 )  {

$perfeed[$lifeid][] = $fedkey; 

}


}  // closes foreach lip
}  // close foreach loop lifestyle
//echo 'perfeedarray <br /><br />';
//print_r($perfeed);


}  // closes function




// forms melife array(s), accepts an array and creates melifedata
function melifearray ($newmedata, $newadf, $feedid)  {
//echo 'ere';
global $feedids;
global $melifeavglist;

$melifeavglist = array();

// need a list of the lifestyles and a list of the feeds (assume all for now)

//foreach ($newmedata as $mell)  {
//echo $mell;

//first need to find out each individuals melife avg (this will be for the last date they were scored, probably need to change this as all melifestats should be at same time-processing energy issue)
// first find out last date of week melife stats taken, 2. then can adjust diffavg value for particular individual if updated more recentlty (not implemented) 3. sort order for each.
$db->query="SELECT * FROM ".RSSDATA.".weeklydate ORDER BY ".RSSDATA.".weeklydate.wdate DESC LIMIT 1";
//echo $db->query;
$resultwdate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultwdate) >  0 )  {

$row = mysql_fetch_object($resultwdate);
$wedate = $row->wdate;

}

$db->query="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.date = '$wedate' AND ".RSSDATA.".melife.idlifestart = '$newmedata' ";
//echo $db->query;

$resultmeavga = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultmeavga) >  0 )  {

while ($row = mysql_fetch_object($resultmeavga))  {

$melifeavglist[$newmedata][$row->feed_id] = $row->diffavg;

}  
}  // closes if


// when a new feed just added, that melife will NOT be in the above array, need to find the new data and add it to the array.
// if firsttime url also add the new feed melife data 
if ($newadf == 1)  {

$db->query="SELECT * FROM ".RSSDATA.".melife WHERE ".RSSDATA.".melife.feed_id = $feedid AND ".RSSDATA.".melife.idlifestart = '$newmedata' ORDER BY ".RSSDATA.".melife.date DESC LIMIT 1";
//echo $db->query;
$resultmeavgab = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultmeavgab) == 1 )  {

$row = mysql_fetch_object($resultmeavgab);
$melifeavglist[$newmedata][$row->feed_id] = $row->diffavg;

}
}

asort($melifeavglist[$newmedata]);

//print_r($melifeavglist);
return $melifeavglist;

}  // closes function






////////////////// current code for peer groups

function mostlikelifestyle ($lifestylelid)  {

global $peergroup;
//global $flid;

$db->query ="SELECT * FROM ".RSSDATA.".lifestyle WHERE ".RSSDATA.".lifestyle.idlifestyle = '$lifestylelid' "; 

$resultlifeid = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlifeid) == 1)  {

$rowlife = mysql_fetch_object($resultlifeid);


$flid = $rowlife->idlifestart;
//echo $flid;
}


// need to get latest individual lifestyle rankings date wise
$db->query ="SELECT ".RSSDATA.".dailypeers.date FROM ".RSSDATA.".dailypeers ORDER BY date DESC LIMIT 1 ";

$resultldate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultldate) == 1)  {

$rowdate = mysql_fetch_object($resultldate);


$ldate = $rowdate->date;
//echo $flid;
}




$db->query ="SELECT * FROM ".RSSDATA.".dailypeers LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".dailypeers.peerid  WHERE  ".RSSDATA.".dailypeers.idlifestart = $lifestylelid AND ".RSSDATA.".dailypeers.date = $ldate";
//echo $db->query;

$resultlikelife = mysql_query($db->query) or die(mysql_error());


$peergroup = '';

if (mysql_num_rows($resultlikelife))  {

$peergroup .=  "<img src=\"/lifestylelinking/images/peergroup.gif\">";
// display search results
$peergroup .=  "<table width=\"100\" border=\"1\" style='table-layout:fixed' >";
$nocolumns = 5;

//column width
//$colwidth = (int)(100/$nocolumns);
$colwidth = 25;

$i = 0;

while ($row = mysql_fetch_object($resultlikelife))  {

       if ($i % $nocolumns == 0)  {
// start a new row
$peergroup .= "<tr>";
}

$noproducts = mysql_num_rows($resultlikelife);
$norows = ceil($noproducts/$nocolumns);
//echo $norows;

if ($noproducts > 1 )  {

$imagef = $row->image;

}

//$imagef = "/images/meico.gif";

if ( strlen($imagef) == 0 )  {

$imagef = "/lifestylelinking/images/meico.gif";

}


$peergroup .= "<td width = ".$colwidth." ><a href=\"".$row->url."\"><img src=\"".$imagef."\" alt=\"".$row->url."\" width=\"16\" height=\"16\"></a> <br /></td>";


if ($i % $nocolumns == $nocolumns - 1) {
//end this row
$peergroup .= "</tr>";
}

$i += 1;
}

//print blank columns

if ($i % $nocolumns != 0)  {

while ($i++ % $nocolumns != 0) {
$peergroup .= "<td width = ".$colwidth."'%'>&nbsp;</td>";
}
$peergroup .= "</tr>";
}
$peergroup .= "</table>";

}


}  // closes function




//  display all lifestyles on homepage
function grouplistsin ()  {

global $grouplist;


$db->query ="SELECT * FROM  ".LIFEDATA.".lifestylegroups ORDER BY ".LIFEDATA.".lifestylegroups.groupname ASC";

$resultgroups = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;

if (mysql_num_rows($resultgroups)  > 0  )  {

$grouplists = '';

while ($row=mysql_fetch_object($resultgroups))  {

$db->query ="SELECT * FROM  ".LIFEDATA.".lifestylegroups LEFT JOIN ".LIFEDATA.".grouplink ON ".LIFEDATA.".grouplink.groupid = ".LIFEDATA.".lifestylegroups.groupid LEFT JOIN ".LIFEDATA.".lifestyle ON ".LIFEDATA.".lifestyle.idlifestyle = ".LIFEDATA.".grouplink.idlifestyle WHERE ".LIFEDATA.".lifestylegroups.groupid = $row->groupid  ORDER BY ".LIFEDATA.".lifestyle.name ASC  ";

$resultglist = mysql_query($db->query) or die ("Error in query: $db->query. ".mysql_error());
//echo $db->query;

if (mysql_num_rows($resultglist)  > 0  )  {

$grouplist .="<div class=\"grouplist\">";
$grouplist .="<b>$row->groupname</b><br />";

while ($row=mysql_fetch_object($resultglist))  {

if ($row->idlifestyle)  {

//$grouplist .="<input type=\"checkbox\"  name=\"lifestylesid[]\" value=\"".$row->idlifestyle."\"> <a href=\"/lifestylelinking/me/mein.php?lifestyleid=".$row->idlifestyle."\"> ".$row->name."</a><br />";
$grouplist .="<input type=\"checkbox\"  name=\"lifestylesid[]\" value=\"".$row->idlifestyle."\"> ".$row->name."</a><br />";

}

else {

$grouplist .="<i>coming soon</i>";

}

}  // closes while loop

$grouplist .="</div>";

}


}  // closes loop
}


}  // closes function



// produce list of peers for a lifestyle atoz
function lifestyleblogs ($letterlist)  {

global $llist;

$db->query ="SELECT * FROM ".RSSDATA.".lifestyle WHERE ".RSSDATA.".lifestyle.name LIKE '$letterlist%' ORDER BY ".RSSDATA.".lifestyle.name ASC";
//echo $db->query;
$resultlifestylelet = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlifestylelet) > 0 )  {

while ($row = mysql_fetch_object($resultlifestylelet))  {

$lstylelist[$row->idlifestart] = $row->name;

}
}


$peerlist = '';

$peerlist .="<div class=\"peerlisttop\">";


foreach ($lstylelist as $lskey=>$llist)  {

// need to get latest individual lifestyle rankings date wise
$db->query ="SELECT ".RSSDATA.".dailypeers.date FROM ".RSSDATA.".dailypeers ORDER BY date DESC LIMIT 1 ";

$resultldate = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultldate) == 1)  {

$rowdate = mysql_fetch_object($resultldate);
$letdate = $rowdate->date;
}

$db->query ="SELECT * FROM ".RSSDATA.".dailypeers LEFT JOIN ".RSSDATA.".feeds ON ".RSSDATA.".feeds.id = ".RSSDATA.".dailypeers.peerid  WHERE  ".RSSDATA.".dailypeers.idlifestart = $lskey AND ".RSSDATA.".dailypeers.date = $letdate ORDER BY ".RSSDATA.".dailypeers.rank ASC LIMIT 10 ";
//echo $db->query;

$resultlist = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultlist) > 0 )  {


$peerlist .="<div class=\"peerlist\">";

$peerlist .= "<b>".$llist."</b><br>";

while ($row = mysql_fetch_object($resultlist))  {

// need a presention
// pass the info. on to a display function
$peerlist .= "<a href=\"".$row->url."\">".$row->title."</a><br>";

}
$peerlist .= '</div>';
}

}  // closes foreach

$peerlist .= '</div>';

echo $peerlist;

}  // closes function


// html code to display atoz peer list
function lifestylebloggerpres ($resultlist)  {

global $llist;

$peerlist = '';

if (mysql_num_rows($resultlist) > 0 )  {

$peerlist .="<div class=\"peerlisttop\">";

$peerlist .="<div class=\"peerlist\">";

$peerlist .= "<b>".$llist."</b><br>";

while ($row = mysql_fetch_object($resultlist))  {

// need a presention
// pass the info. on to a display function
$peerlist .= "<a href=\"".$row->url."\">".$row->title."</a><br>";



}
$peerlist .= '</div>';
$peerlist .= '</div>';
$peerlist .= '</div>';
}

echo $peerlist;

}  // closes functions




// create atoz list of letters
function letteratoz ($getletter)  {
?>
    <div class="atoznav">
<?php
// array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i');
foreach (range('A', 'Z') as $letter) {
?>
    <a href="index.php?letid=<?php echo $letter ?>"><?php echo $letter ?></a> &nbsp
<?php
}
?>
    </div> <!-- closes magazinenav-->
<?php


//$letterlist = escapeinteger($_GET['letid']);
$letterlist = escapestring($getletter);

?>

<div class="topbox">

<div class="rightlist">

<?php
if ($letterlist)  {

lifestyleblogs ($letterlist);

}
?>
</div></div>

<?php
}  // closes function



///////////////  per individual basis   should have one code base with logic to set between individual and batch ///////////////////////////

// based on the individual find those closest to them in 'average', over all relevant lifestyle lifestyle or ability to switch on and off the lifestylelinking and see how the results disply change
function indivmostlikeme ($feedid)  {

global $feedids;
global $peergroup;
global $meuqni;
global $melifeavglist;
global $perfeed;

// NEED TO SETUP UPFRONT LOGIC IS BATCH OR REFRESH FOR PARTICUALR INDIVIDUAL?

// what is feed id of this individual
//$feedindv  = 2;  // need to GET_(feed_id) or something
$date = time();

// need list of all feeds
feedarray ();
//$feedids = Array ( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20');//, '21' => '21', '22' => '23', '23' => '24', '24' => '25', '25' => '26', '26' => '27', '27' => '28', '29' => '29', '30' => '31', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35', '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40');
//print_r($feedids);

//prepmelife ($feedids);
$lifelisttop = toplifefivearray($feedid);

//print_r($lifelisttop);
// call melifedata array with list of feed ids mepath needs data on


//how many individual, count feedarray
$indpeers = count($feedids);
//echo $indpeers.'nofeeds<br />';
//80-20 rule
$peertwenty = $indpeers * 0.1 ;
//echo $peertwenty.'20% feeds<br />';


// batch per lifestlyeid  save memory getting over maxed
foreach ($lifelisttop as $unid)  {

$perdiffarr = '';
$newadf = 1;
$perdiffarr = melifearray ($unid, $newadf, $feedid);
//print_r($melifeavglist);
//print_r($perdiffarr);
//echo 'break<br /><br />';
// now look up the individuals melife stat and then order all indivials stas and then use 80-20 rules, ie take20% of indivudals clostest to that individual.  Then display results, have any post been published from this peergroup of in the last 24hrs?

// new list of feeds that have lifestyle in top5 $perfeed;
//foreach ($feedids as $fma)  {

//foreach ($perfeed[$unid] as $pfma)  {

$steps = '';
// form an array for all the lifestyleids, count no. feeds find out 20% of that number, find out individuals average then identify feeds 10% plus this indivudals avarage and -10% from their average
//print_r($melifeavglist[$mell]);
$mecou = count($perdiffarr[$unid]);
//echo $mecou;
if ( $mecou  > 0  )  {

//gives us array containing the individuals average
// next need to find -10 and +10% either side of that position

//  now need to count back and forward along the array to pick out peer list.
$steps = new Steps();
$poslist = array();

foreach ($perdiffarr[$unid] as $key=>$reindex)  {

 $steps->add($key);  
 
 }
 //echo 'listorder';
 //print_r($steps->all);
 $feedindv = $feedid;
 //echo 'indiv feedid'.$feedindv.'<br /><br />';
// set this individuals avg. position
 $steps->setCurrent($feedindv);
 //print_r($steps);

for ($i = 1; $i <= $peertwenty; $i++) {
  
//echo $i;
//echo $steps->getNext().'<br>';
$newset = $steps->getNext();
//echo $newset.'newno<br /><br >';
$indivset = $steps->setCurrent($newset);
//echo $indivset.'indvid set';
if (strlen($newset) > 0)  {

$poslist[] = $newset;

}

}
//print_r($poslist);
$steps->setCurrent($feedindv);

for ($i = 1; $i <= $peertwenty; $i++) {
  
//echo $i;
//echo $steps->getPrev().'<br>';
$newset = $steps->getPrev();
$indivset = $steps->setCurrent($newset);

if (strlen($newset) > 0)  {

$poslist[] = $newset;

}
}

//echo 'postlist';
//print_r($poslist);
//echo 'postlist';

$arraycou = count($poslist);
//echo $arraycou.'numcou';
// save to perpeers table to have on demand quickly and update on a per individual basis
if ($arraycou > 0 )  {

//print_r($poslist);
//echo 'heelo';
$peerinslist = '';
$rank = '';

foreach ($poslist as $peerid)   { 

$rank++;

$peerinslist .="('$feedid', '$rank', '$date', '$unid', '$peerid' ), ";

}

$peerinslist =substr($peerinslist,0,(strLen($peerinslist)-2)); //this will eat the last comma
//echo $peerinslist.'insertcode';

if (strLen($peerinslist) > 0 )  {

$db->query =" INSERT INTO ".RSSDATA.".perpeers (feedid, rank, date, idlifestart, peerid) VALUES ";

$db->query .= $peerinslist;
//echo $db->query;
$resultinsqual = mysql_query($db->query) or die(mysql_error());

} //closes if 
} // closes if anything to insert

}  // closes if no melifedata

//}  // closes foreach loop ie eachfeed.


}  // closes for each unqiue lifestyle definition


}  // closes function




// individual top5 lifestyles
function indivtopfivelife ($feedid)  {

$topmenu = array();
$rank = '';
$date = time();

$standmenu = standardtopmenutwo ();

$memenure = memenu ($feedid);
//echo '<br /><br />new feed';
//print_r($lifequalify);
//echo '<br /><br />';
$topmenure = compareusermenutwo ($standmenu, $memenure);
//echo '<br /><br />top 5 feed';
//print_r($topmenure);

$db->query =" INSERT INTO ".RSSDATA.".toplife (feedid, rank, date, lifestyleid) VALUES ";


foreach ($topmenure as $tpf)  {

$rank++;
$convtpf = idconvert($tpf);

$db->query .="('$feedid', '$rank', '$date', '$convtpf'), ";

}

$db->query =substr($db->query,0,(strLen($db->query)-2)); //this will eat the last comma
//echo $db->query;
$resultmelifet = mysql_query($db->query) or die(mysql_error());

}  // closes function




// form array of top5 lifestyles
function toplifefivearray ($feedid)  {


$db->query ="SELECT * FROM ".RSSDATA.".toplife WHERE ".RSSDATA.".toplife.feedid = $feedid ";
//echo $db->query;

$resulttoplif = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resulttoplif) > 0 )  {

while ($row = mysql_fetch_object($resulttoplif))  {

$toplifear[$row->rank] = $row->lifestyleid;

}
}

return $toplifear;

}  // closes function



?>
