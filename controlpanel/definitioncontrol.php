<?php session_start();
define( 'ABSPATH', dirname(dirname(__FILE__)) .'/' );
include_once(ABSPATH . 'controlpanel/controlhead.php'); 
include_once(ABSPATH . 'wikipediaapi/botclasses.php');

// list current magazine section and lifestyles
$start = $_SESSION[_token];

livelifemags ();

$t = preventdform ();
$_SESSION[_token] = $t;

$tests = $_SESSION[_token];
$testp = $_POST[_token];


if (isset($_POST[magsubmit]))
{
        if ($_POST[magname])  
        {

        if ($start == $testp) 
            {

              $magname = empty($_POST['magname']) ? die ("<br />Please enter a magazine section name.") : mysql_real_escape_string($_POST['magname']);

              maginsert ($magname);
              
              formmag();
            }

            else {
            formmag();

            }
        }  // close if

        else {

        formmag();

        } // closes else
}  // closes if form been set?

else {
//set the form
formmag();

} // closes else




// setup lifestyle categor to a magazine section
if (isset($_POST[startdef]))
{
        if ($_POST[startdefurl])  
        {

        if ($start == $testp) 
            {
            //$catname = empty($_POST['catname']) ? die ("<br />Please type lifestyle cat.") : mysql_escape_string($_POST['catname']);
            $startdefurl = empty($_POST['startdefurl']) ? die ("<br />Please type startdefurl") : mysql_escape_string($_POST['startdefurl']);

            $pieces = explode("http://en.wikipedia.org/wiki/", $startdefurl);

            $page = $pieces[1];
            
            dblifestyleinsert ($page, $startdefurl);
            callwikipedia ($page, $objid); 
 
            formlifestyle();
            }

            else {
            formlifestyle();

            }
        }  // close if

        else {

        formlifestyle();

        } // closes else
}  // closes if form been set?

else {
//set the form
formlifestyle();

} // closes else
?>

<?php include_once (ABSPATH . "controlpanel/controlfooter.php"); ?> 

