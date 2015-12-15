<?php

function tablesetup ()
{

set_time_limit(0);

$tables = dirname(__FILE__) . '/lifestyletables.sql';

//$tablesw = dirname(__FILE__) . '/words.sql';
// setting up all the database tables, tables and words
$tablesstart = file_get_contents($tables, true);

$arr_sql =  preg_split('/;[\n\r]+/', $tablesstart);
//print_r($arr_sql);
//echo count($arr_sql);
//reset($arr_sql);


foreach ($arr_sql as $ars=>$sql) 
{

$db->query ="$sql";
echo $db->query;
$resultbaseurl = mysql_query($db->query) or die(mysql_error());
echo '<br /><br />';

}  // closes foreach


/*
$arr_success=array();
$arr_failure=array();


while (list($k,$v)=each($arr_sql))
 {
 if (trim($v)!="")
  {?><li><?=$v?><?
 
  if (!$db->query($v))
   echo "<b>"  . $db->Errno  . "</b> : ". $db->Error ;
  ob_flush();
  flush();
  ?></li><? }
 }

*/
}  // closes function

?>