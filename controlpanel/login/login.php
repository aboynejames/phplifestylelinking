<?php session_start();
define( 'ABSPATH', dirname(dirname(dirname(__FILE__))) .'/' );
include_once (ABSPATH . "addlogic.php");
//print_r($_SESSION);

// query db to find base folder url
$db->query ="SELECT * FROM ".RSSDATA.".siteinfo";
//echo $db->query;
$resultsetf = mysql_query($db->query) or die(mysql_error());

if (mysql_num_rows($resultsetf)  > 0 )  {

while ($row = mysql_fetch_object($resultsetf))  {

$siteinfo[$row->type] = $row->siteinfo;

} 
}

$basefolderurl = $siteinfo[baseurl];
$abspath = $siteinfo[absolpath];

define( 'LLFOLDER', $basefolderurl );



if(isset($_POST['Login']))
	{
		if($_POST['username']!='' && $_POST['password']!='')  		{

      //Use the input username and password and check against 'users' table
			$query = mysql_query('SELECT ID, Username, Active FROM users WHERE Username = "'.mysql_real_escape_string($_POST['username']).'" AND Password = "'.mysql_real_escape_string(md5($_POST['password'])).'"');
			
			if(mysql_num_rows($query) == 1)  			{
      
				$row = mysql_fetch_assoc($query);
				if($row['Active'] == 1)  				{
        
					session_start();
					$_SESSION['user_id'] = $row['ID'];
          $_SESSION['username'] = $row['Username'];
					$_SESSION['logged_in'] = TRUE;
          setcookie("username", $_POST['Username'], time()+(84600*30));
          
          if ($_SESSION[logged_in] == 1 )  {
        
        $loginloc = makeurla('controlpanel/index.php');
        header("Location:  ".$loginloc." ");
				  
          }
          
          else {
					
          header("Location:  login.php ");
				        }
                }
else {
					$error = 'Your membership is not activated. Please open the email that was sent and click on the activation link';
				}
			}
			else {		
				$error = 'Login failed !';		
			}
		}
		else {
			$error = 'Please use both your username and password to access your account';
		}
	}
  
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
		<link href="<?php makeurl('controlpanel/css/admin.css') ?>" type="text/css" rel="stylesheet" id="stylesheet" />
		
	</head>


<body>

<?php
// include the page header
include_once (ABSPATH . 'header.php');
?>  

<div class="mid-wrapper">
	<div class="sub-nav">

	</div>
	<div class="mid">
	<div class="content">

<?php if(isset($error)){ echo $error;}?>
<br />
<br />
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
	Username:<input type="text" id="username" name="username" size="32" value="" />
	Password:<input type="password" id="password" name="password" size="32" value="" />
	<input type="submit" name="Login" value="Login" />
</form>
<br />

<br />
</div>
	</div>
	
	<br />

</div>


<?php include_once (ABSPATH . "controlpanel/controlfooter.php"); ?> 