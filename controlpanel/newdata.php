<?php session_start();
define( 'ABSPATH', dirname(dirname(__FILE__)) .'/' );
include_once(ABSPATH . 'controlpanel/controlhead.php'); 

// list current magazine section and lifestyles
$start = $_SESSION[_token];

$t = preventdform ();
$_SESSION[_token] = $t;

$tests = $_SESSION[_token];
$testp = $_POST[_token];


if (isset($_POST[startrss]))
{
        if ($_POST[jprssid])  
        {

        if ($start == $testp) 
            {

                      // first test to see a url has been added
                      $defprep = definitionspresent ();
                      if ($defprep == 1) 
                      {
                      $meurla = mysql_real_escape_string($_POST['jprssid']);

                      feedurllogic ($meurla);
                      newdataform();
                      echo 'complete';
                      }
                      else { echo 'Please start by adding a definition.';  }
              
            }

            else {
            newdataform();

            }
        }  // close if

        else {

        newdataform();

        } // closes else
}  // closes if form been set?

else {
//set the form
newdataform();

} // closes else

?>


<?php include_once (ABSPATH . "controlpanel/controlfooter.php"); ?> 