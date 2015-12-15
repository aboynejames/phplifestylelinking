<?php

//  start error handling class

// catch all types of error a page may experience and display an apology message
function myErrorHandler($type, $message, $file, $line) {

$sorry = '<html>

<body>

<div align="center">
<img src="/lifestylelinking/images/mepath.png"> <br /><br />mepath has broken.  Please accept mepath\'s apology.  Try reloading the page again or return to the <a href="http://www.mepath.com">homepage</a>.
</div>

</body>
</html>
';

    switch ($type) {



        // warnings
        case E_USER_WARNING:
            // report error
           echo $sorry;
            //print "Non-fatal error on line $line of $file: $msg <br />";
            //echo 'friendly message and maybe an image sayingsorry.';
          //die($sorry);  
          break;

        // fatals
        case E_USER_ERROR:
            // report error and die()
            //die("Fatal error on line $line of $file: $msg <br />");
          die($sorry); 
          break;

        // fatals
        case E_ERROR:
            // report error and die()
            //die("Fatal E_ERROR caught  where about   error on line $line of $file: $msg <br />");
          die($sorry);  
          break;

        // notices
        default:
            // do nothing
            break;
    }

}




// catch fatal e_ERRORS
function fatalErrorShutdownHandler()
{

//echo 'being called';

  $last_error = error_get_last();
//print_r($last_error);

if ($last_error['type'] === E_ERROR) {
    // fatal error
    myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  }
}


// start error handling

//set_error_handler('myErrorHandler');
register_shutdown_function('fatalErrorShutdownHandler');
error_reporting(5);



?>