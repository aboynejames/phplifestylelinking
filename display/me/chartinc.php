

<div class="rightbox">           
<div class="metext">
<?php
// include the page header for signed in
include_once ("lifestylelinking/me/menav.php");
?>
<div align="right" ><a href="/lifestylelinking/rsscode/index.php?lifestyleid=<?php echo $lifestylelid ?>"><img src="/lifestylelinking/images/rssicon.gif"></a></div>

</div>  <!-- closes metext -->

<?php

if ($lifestylelid == 46 && $_SESSION[mefid] != 598)  { 
  //converts the data into proper XML form and finally 
  //relays XML data document to the chart
  $strDataURL = "/lifestylelinking/charts/melifestyle.php";

}

// no personalization, give lifestyle averages
elseif ($lifestylelid == 46 && $_SESSION[mefid] == 598)  {

$strDataURL = "/lifestylelinking/charts/meaverage.php";

}

elseif ($_REQUEST['lifestyleid'] && $_REQUEST['feedid'] )  {


$_SESSION[chartlid] = escapeinteger($_REQUEST['lifestyleid']);
$_SESSION[chartfeed] = escapeinteger($_REQUEST['feedid']);

  //relays XML data document to the chart
  $strDataURL = "/lifestylelinking/charts/melifedata.php";

}

else {

$strDataURL = "/lifestylelinking/charts/mevpeers.php";

}

  //Create the chart - Pie 3D Chart with dataURL as strDataURL
  echo renderChart("/fusioncharts/Charts/FCF_MSLine.swf", $strDataURL, "", "reltime", 550, 400, false, false);

?>



       </div> <!-- closes rightbox-->
