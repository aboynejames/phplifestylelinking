<?php
require_once( dirname(__FILE__) . '/mysqlstart2.php' );

// firsttime install of lifestylelinking //

// mysql database install  (too come)
//$db->query ="CREATE DATABASE `aboyn0_gadgets` " ;
//$resultdb = mysql_query($db->query) or die(mysql_error());


	if(isset($_POST['register']))	
{

$db->query ="CREATE TABLE  siteinfo (
  `infoid` int(11) NOT NULL auto_increment,
  `type` varchar(100) NOT NULL,
  `siteinfo` varchar(255) NOT NULL,
  PRIMARY KEY  (`infoid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
//echo $db->query;
$resultinfo = mysql_query($db->query) or die(mysql_error());

// has the baseurl been set?
// query db to find base folder url
$db->query ="SELECT * FROM ".RSSDATA.".siteinfo";
//echo $db->query;
$resultsetf = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultsetf)  > 0 )  {

//echo 'zero';

while ($row = mysql_fetch_object($resultsetf))  {

$siteinfo[$row->type] = $row->siteinfo;

} 
//print_r($siteinfo);
$basefolderurl = $siteinfo[baseurl];
$abspath = $siteinfo[absolpath];

define( 'LLFOLDER', $basefolderurl );
define( 'ABSPATH', $abspath );

}  // closes if

else 
{
//echo 'else';
// need to find out url path
$host = 'http://'.$_SERVER[HTTP_HOST];
$controlleruri = $_SERVER[REQUEST_URI];

// tidy to find start folder depth
$starturl = substr($controlleruri,0,(strLen($controlleruri)-11));
$baseurl = $starturl;
//$baseurl = $controlleruri;


$abspath = $_SERVER[DOCUMENT_ROOT].$baseurl;

$startinfo[host] = $host;
$startinfo[baseurl] = $baseurl;
$startinfo[absolpath] = $abspath;

$db->query =" INSERT INTO ".RSSDATA.".siteinfo (type, siteinfo) VALUES ";

foreach ($startinfo as $type=>$sinfo)  {

$db->query .= "( '$type', '$sinfo' ),";

}  // closes foreach

$db->query=substr($db->query,0,(strLen($db->query)-1));

$resultbaseurl = mysql_query($db->query) or die(mysql_error());

define( 'LLFOLDER', $baseurl );
define( 'ABSPATH',  $abspath);


} // closes else

// setting up all the database tables, tables and words


function tablesetup ()
{

set_time_limit(0);

$tables = dirname(__FILE__) . '/lifestyletables.sql';
$tablesb = dirname(__FILE__) . '/words.sql';

$tablesarr[] = $tables;
$tablesarr[] = $tablesb;

foreach ($tablesarr as $tabsql)
{
$arr_sql = '';
// setting up all the database tables, tables and words
$tablesstart = file_get_contents($tabsql, true);

$arr_sql =  preg_split('/;[\n\r]+/', $tablesstart);

  foreach ($arr_sql as $ars=>$sql) 
  {
  if (strlen($sql) > 5 ) 
    {
    $db->query ="$sql";
    //echo $db->query;
    $resultbaseurl = mysql_query($db->query) or die(mysql_error());
    
    } // closes if 

  }  // closes foreach
}  // closes foreach


}  // closes function

tablesetup ();


function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}
	
	function checkUnique($field, $compared)
	{
		$query = mysql_query("SELECT `".mysql_real_escape_string($field)."` FROM `users` WHERE `".mysql_real_escape_string($field)."` = '".mysql_real_escape_string($compared)."'");
		if(mysql_num_rows($query)==0)
		{
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
  
  
  
  
	function numeric($str)
	{
		return ( ! ereg("^[0-9\.]+$", $str)) ? FALSE : TRUE;
	}
	
  
  
  
	function alpha_numeric($str)
	{
		return ( ! preg_match("/^([-a-z0-9])+$/i", $str)) ? FALSE : TRUE;
	}
	
  
  
  
	function random_string($type = 'alnum', $len = 8)
	{					
		switch($type)
		{
			case 'alnum'	:
			case 'numeric'	:
			case 'nozero'	:
			
					switch ($type)
					{
						case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'numeric'	:	$pool = '0123456789';
							break;
						case 'nozero'	:	$pool = '123456789';
							break;
					}
	
					$str = '';
					for ($i=0; $i < $len; $i++)
					{
						$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
					}
					return $str;
			  break;
			case 'unique' : return md5(uniqid(mt_rand()));
			  break;
		}
	}
  
  

   //create userprofile url
   $nospacename = $_POST['username'];

   // need to create singleword for user  profileurl
   $createprofileurl = str_replace (" ", "", $nospacename); 


  
		if($_POST['username']!='' && $_POST['password']!='' && $_POST['password']==$_POST['password_confirmed'] && $_POST['email']!='' && valid_email($_POST['email'])==TRUE && checkUnique('Username', $_POST['username'])==TRUE && checkUnique('Email', $_POST['email'])==TRUE) 
		{
		    
			$db->query = "INSERT INTO users (Username , Password, Email, Active, Level_access, Random_key, userprofile) VALUES ('".mysql_real_escape_string($_POST['username'])."', '".mysql_real_escape_string(md5($_POST['password']))."', '".mysql_real_escape_string($_POST['email'])."', '1', '3', '".random_string('alnum', 32)."', '$createprofileurl') ";
    
    $resultbaseurl = mysql_query($db->query) or die(mysql_error());


	}  // closes if		
		else {		
			$error = 'There is an error with the registration data.  Either the username and/or email address are already taken or the password comfirmation is different from the password entered.';	
		}
    
    echo '<h1>Setup is complete.</h1>';
    echo 'Go to '?><a href="<?php echo LLFOLDER ?>controlpanel/login/login.php">Control Panel Login</a><?php


}  // closes if installed pressed


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
	<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
	   <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		
		<title>open - lifestyle linking</title>
		<meta name="description" content="Main Page" />
		<meta name="page-topic" content="Homepage" />

		
		<meta name="robots" content="all" />
		<meta name="author" content="" />
		<meta name="author" content="" />
		<meta name="Keywords" content="" /> 	
		
		<!--Main Style Sheet-->
		<!--session selector to pick which stylesheet based on context if this header is in a php function-->
<style>

#container 
{
    margin: auto;
/*    min-width: 800px;*/
    width: 940px;
    
    padding: 0.5em;    
    background-color: #ffffff;
}

</style>
	
	
<body>

	<div id="container"> <!--is closed in footer.php-->
<h1>Welcome to the lifesylelinking auto install.</h1>
 <br />
<?php if(isset($error)){ echo $error;}?>
<?php if(isset($msg)){ echo $msg;}  ?>
<br />
Set the Administrator Control Panel Username and Password:
<br /> 
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
  <table>
	<tr>
  <td>Username:</td>
  <td><input type="text" id="username" name="username" size="32" value="<?php if(isset($_POST['username'])){echo $_POST['username'];}?>" /></td>
	</tr>
  <tr>
  <td>Password:</td>
  <td><input type="password" id="password" name="password" size="32" value="" /></td>
	</tr>
   <tr>
   <td>Re-password:</td>
   <td><input type="password" id="password_confirmed" name="password_confirmed" size="32" value="" /></td>
	 </tr>
   <tr>
   <td>Email:</td>
   <td><input type="text" id="email" name="email" size="32" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}?>" /><td>
	</tr>
  <tr>
  <td> </td>
  <td><input type="submit" name="register" value="register" /></td>
  </tr>
 </table>
</form>  
  
  
</div>  <!-- closes container -->

</body>

</html> 