<?php
include_once (ABSPATH . 'mysqlstart2.php');

include_once( ABSPATH . 'display/context/errorhandle.php' );
include_once( ABSPATH . 'display/context/functions.php' );
include_once( ABSPATH . 'display/context/melogic.php' );
include_once( ABSPATH . 'display/context/startpage.php' );
include_once( ABSPATH . 'display/context/addform.php' );
include_once( ABSPATH . 'display/context/resultsdisplay.php' );
include_once( ABSPATH . 'display/context/youtubedaily.php' );

include_once( ABSPATH . 'widgets/widgetapi.php' );
//include_once( ABSPATH . 'facebookapp/init.php' );

include_once( ABSPATH . 'core/logic/wordprep.php' );
include_once( ABSPATH . 'core/logic/scorematrix.php' );
include_once( ABSPATH . 'core/logic/mestats.php' );
include_once( ABSPATH . 'core/logic/melife.php' );
include_once( ABSPATH . 'core/logic/keepfeed.php' );
include_once( ABSPATH . 'core/logic/dailyresults.php' );
include_once( ABSPATH . 'core/logic/siteinfo.php' );
include_once( ABSPATH . 'core/logic/tests.php' );

include_once( ABSPATH . 'core/social/social.php' );
include_once( ABSPATH . 'core/social/pergrpstream.php' );
include_once( ABSPATH . 'feedreader/init.php' );
include_once( ABSPATH . 'feedreader/fof-main.php' );

include_once( ABSPATH . 'controlpanel/forms/defform.php' );


//We've included ../Includes/FusionCharts.php, which contains functions
  //to help us easily embed the charts.
 // include_once("lifestylelinking/charts/FusionCharts.php");

?>
