<div class="rightbox">
<div class="metext">
<?php
// include the page header for signed in
include_once ("lifestylelinking/me/menav.php");
?>

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


//  get lifestyleid then display activity stream for that lifestyle
elseif (($_GET['lifestyleid']  && !$_GET['feedid']) || ($_SESSION[chartreturn] == 1))  {

// reset chartreturn setting
$_SESSION[chartreturn] = null;

if ($_GET['lifestyleid'] )  {
$lifestylelid = escapeinteger($_GET['lifestyleid']);
}
/*
flickrfour ($lifestylelid); 
echo $imagedisplay;
?>
<div class="sumphoto">
<img src="/lifestylelinking/images/video.gif"><br />
<?php
youtubekeywords($lifestylelid);
searchAndPrint ($searchTerms);
?>
</div>
<?php
*/
timerankresults ($lifestylelid);      
inpostresults ($lifestylelid, $lifestylesubid);  
?>
<img src="/lifestylelinking/images/blogposts.gif"><br />
<?php
echo $llresults;
?>

<br /><br />

<?php

echo $llpaging;

?>

</div>  <!-- closes postbox-->

</div>  <!-- closes contenttab-->

<div class="contenttabright">  

<div class="photocol">
<?php
flickrfour ($lifestylelid); 
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
mostlikelifestyle ($lifestylelid);

echo $peergroup;


?>
</div>  <!-- closes contenttabright-->

<?php

}


//  lifestyleid and feedid  results for that blog author
elseif ($_GET['lifestyleid'] && $_GET['feedid'] )  {

$lifestylelid = escapeinteger($_GET['lifestyleid']);

authorposts ($lifestylelid);    
inpostresults ($lifestylelid, $lifestylesubid);  

echo $llresults;

?>

<br /><br />

<?php

echo $llpaging;

?>

</div>  <!-- closes postbox-->

</div>  <!-- closes contenttab-->

<div class="contenttabpeer">
<br /><br /><br />

<div class="peergroup">

<?php
mostlikelifestyle ($lifestylelid);

echo $peergroup;

}  // closes last elseif

?>

</div>    <!-- closes peergroup -->

</div>  <!-- closes contettabpeer -->


<br />
<br /> 

</div> <!-- closes right box-->

