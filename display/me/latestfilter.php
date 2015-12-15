<div class="rightbox">
<div class="metext">
<?php
// include the page header for signed in
include_once ("lifestylelinking/me/menav.php");
?>
<!--<div align="right" ><a href="/lifestylelinking/rsscode/index.php?lifestyleid=<?php echo $lifestylelid ?>"><img src="/lifestylelinking/images/rssicon.gif"></a></div>-->

</div>  <!-- closes metext -->

<div class="contenttab">

<div class="postbox">
<div class="magtext">
<?php lifestylesummary();
echo '<b>'.$celname.'</b> ';
?>
</div>
<div class="magtextright">
<?php
echo '<b>--- Last 24 hrs</b>';
?>
</div>

<?php

// logic for main content dispaly
// if 'me' select
if ($lifestylelid == 46)   {

melatestsummary ();

echo $mesummary;

?>

<br /><br />

<?php

//echo $llpaging;

?>

</div>  <!-- closes postbox-->

</div>  <!-- closes contenttab-->

<div class="contenttabpeer">
<br /><br /><br />

<div class="peergroup">

<?php
//mostlikelifestyle ($lifestylelid);

//echo $peergroup;


}  // closes if 'me' menu 



//  NEW SIGNED IN LATEST & FLITERED
elseif ( ($_GET['filtext'] ==  1 || $_SESSION['filtext'] ==  1 ) && $_SESSION[metext] == 1)  {

// latest from Personalized peer group (also can have publics peer groups)
//echo 'hello';
//$lifestylelid = escapeinteger($_GET['lifestyleid']);
//$feedid = escapeinteger($_GET['feedid']);
//$filtext = escapeinteger($_GET['filtext']);
$lifestylelid = $_SESSION[mecid];
//echo $lifestylelid.' lifestlyeid??';
$lifestylelidaa = idstartconvert($_SESSION[mecid]);
//echo $lifestylelidaa.' lifestlyeid??';

$feedid = $_SESSION[mefid];

if (isset($_GET['filtext']) )  {

$filtext = escapeinteger($_GET['filtext']);
}
else  {
$filtext = $_SESSION['filtext'];
}

pergrpposts ($feedid, $lifestylelidaa, $filtext);

?>

<br /><br />

<?php

//echo $llpaging;

?>

</div>  <!-- closes postbox-->

</div>  <!-- closes contenttab-->


<div class="contenttabright">  

<div class="photocol">
<?php
flickrfour ($lifestylelidaa); 
echo $imagedisplay;
?>
</div>  <!-- closes photo list -->

<div class="videocol">
<img src="/lifestylelinking/images/video.gif"><br />
<?php
youtubekeywords($lifestylelid);
searchAndPrint ($searchTerms);
?>
</div>  <!-- closes video list -->


<div class="contenttabpeer">
<br /><br /><br />

<div class="peergroup">

<?php
//mostlikelifestyle ($lifestylelid);
pergroupdplay ($lifestylelid, $feedid);
echo $ppeergroup;

}  // closes last elseif


elseif ( ($_GET['filtext'] ==  2 || $_SESSION['filtext'] ==  2 ) && $_SESSION[metext] == 1 )  {

// latest from personalized peer group and filter to pick out the best from the posts. (also can have publics peer groups)
//echo 'filtered';
$lifestylelid = $_SESSION[mecid];
//echo $lifestylelid.' lifestlyeid??';
$lifestylelidaa = idstartconvert($_SESSION[mecid]);
//echo $lifestylelidaa.' lifestlyeid??';
$feedid = $_SESSION[mefid];



if (isset($_GET['filtext']) )  {

$filtext = escapeinteger($_GET['filtext']);
}
else  {
$filtext = $_SESSION['filtext'];
}

pergrpposts ($feedid, $lifestylelidaa, $filtext);

?>

<br /><br />

<?php

//echo $llpaging;

?>

</div>  <!-- closes postbox-->

</div>  <!-- closes contenttab-->

<div class="contenttabright">  

<div class="photocol">
<?php
flickrfour ($lifestylelidaa); 
echo $imagedisplay;
?>
</div>  <!-- closes photo list -->

<div class="videocol">
<img src="/lifestylelinking/images/video.gif"><br />
<?php
youtubekeywords($lifestylelid);
searchAndPrint ($searchTerms);
?>
</div>  <!-- closes video list -->


<div class="contenttabpeer">
<br /><br /><br />

<div class="peergroup">

<?php
pergroupdplay ($lifestylelid, $feedid);
echo $ppeergroup;

}  // closes last elseif



?>

</div>    <!-- closes peergroup -->

</div>  <!-- closes contettabpeer -->


<br />
<br /> 

</div> <!-- closes right box-->

