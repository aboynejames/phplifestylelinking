<div class="contenttab">
<div class="postbox">
<div class="magtext">

<b> <?php lifestylesummary(); ?> </b>
</div>
<div class="magtextright">
<?php
echo '<b>- Last 24 hrs</b>';
?>
<a href="http://75.101.138.19/lifestylelinking/rsscode/index.php?lifestyleid=<?php echo $lifestylelid ?>"><img src="/lifestylelinking/display/images/rssicon.gif"></a>

</div>
<?php

if(!$lifestylelid)  {

$lifestylelid = 1;

}


?>

<img src="/lifestylelinking/display/images/blogposts.gif"><br />
<?php
timerankresults ($lifestylelid);      
inpostresults ($lifestylelid, $lifestylesubid);
?>

<br /><br />

<?php

echo $llpaging;

?>



</div>  <!-- closes postbox-->

</div>  <!-- closes contenttab -->

<div class="photocol">
<?php flickrfour ($lifestylelid); ?>
</div>  <!-- closes photo list -->

<div class="videocol">
<img src="/lifestylelinking/display/images/video.gif"><br />
<?php
//youtubekeywords($lifestylelid);
//searchAndPrint ($searchTerms);
?>
</div>  <!-- closes video list -->


