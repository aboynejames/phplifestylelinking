<?php session_start();
define( 'ABSPATH', dirname(dirname(__FILE__)) .'/' );
include_once(ABSPATH . 'controlpanel/controlhead.php'); 

$start = $_SESSION[_token];
$t = preventdform ();
$_SESSION[_token] = $t;

$tests = $_SESSION[_token];
$testp = $_POST[_token];

$ddate = dailyupdatedate();


?>
<div id="controlsection">  <!-- results date and set -->
<?php
if (isset($_POST[resultdateid]))
{
        if ($_POST[resultnewd])  
        {

        if ($start == $testp) 
            {

              $newresdate = mysql_real_escape_string($_POST['resultnewd']);
              //echo $newresdate;
              resulttime($newresdate);    // set date from current 
              dateresultform ();
            }

            else {
            dateresultform ();

            }
        }  // close if

        else {

        dateresultform ();

        } // closes else
}  // closes if form been set?

else {
//set the form
dateresultform ();

} // closes else

?>
</div>


<div id="controlsection"> <!-- daily update of feeds -->
<?php
if (isset($_POST[dailyqualid]))
{
        if ($_POST[dailyqualid])  
        {

        if ($start == $testp) 
            {

              // set arrays for speical words and lifestyle words
              specialwords();
              lifestylearray();

              // sets no. of past items processed
              $firstupdateid = itemmangement ();

              // produce list of feeds to check for new post items
              $feedstoday = livefeeds();

              newdailyitems($feedstoday, $firstupdateid); 
              echo 'complete';

            dailyupdatefeeds ();
            }

            else {
            dailyupdatefeeds ();

            }
        }  // close if

        else {

        dailyupdatefeeds ();

        } // closes else
}  // closes if form been set?

else {
//set the form
dailyupdatefeeds ();

} // closes else

?>

</div>


<div id="controlsection">  <!-- daily mestats update -->
<?php
if (isset($_POST[mestatcal]))
{
        if ($_POST[mestatcal])  
        {

        if ($start == $testp) 
            {

                // me avg stats
                feeditems ();
                echo 'complete';
               dailymestatsform();
            }

            else {
            dailymestatsform();

            }
        }  // close if

        else {

        dailymestatsform();

        } // closes else
}  // closes if form been set?

else {
//set the form
dailymestatsform();

} // closes else
?>
</div>


<div id="controlsection">  <!-- daily melife normalization -->
<?php
if (isset($_POST[mecalstats]))
{
        if ($_POST[mecalstats])  
        {

        if ($start == $testp) 
            {

              // melife normalization
              avglifestats ();
              melifecal ();
              echo 'complete';

              melifenormform();
            }

            else {
            melifenormform();

            }
        }  // close if

        else {

        melifenormform();

        } // closes else
}  // closes if form been set?

else {
//set the form
melifenormform();

} // closes else

?>


</div>

<div id="controlsection">  <!-- daily results calculations-->
<?php
if (isset($_POST[dailyresults]))
{
        if ($_POST[finishdate])  
        {

        if ($start == $testp) 
            {

            $finishtime = mysql_real_escape_string($_POST['finishdate']);

            dailylifestlyeposts ($finishtime);
            // need to update results date by 24 hrs
            setnextrestime ();
            echo 'complete';   
            dailyresultform();

              }

            else {
            dailyresultform();

            }
        }  // close if

        else {

        dailyresultform();

        } // closes else
}  // closes if form been set?

else {
//set the form
dailyresultform();

} // closes else

?>

</div>


<?php include_once (ABSPATH . "controlpanel/controlfooter.php"); ?> 