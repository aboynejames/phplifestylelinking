<?php

// register functions


function openidreg ()  {

if(isset($_POST['register']))	{

   $openIDurl = $_POST['openid_url'];

   //create userprofile url
   $nospacename = $_POST['username'];

   // need to create singleword for user  profileurl
   $createprofileurl = str_replace (" ", "", $nospacename); 


		    
			$query = mysql_query("INSERT INTO users (`Username` , `Password`, `Email`, `Random_key`, `userprofile`) VALUES ('".mysql_real_escape_string($_POST['username'])."', '".mysql_real_escape_string(md5('openid'))."', '".mysql_real_escape_string($_POST['email'])."', '".random_string('alnum', 32)."', '$createprofileurl') ") or die(mysql_error());
        
      
			$getUser = mysql_query("SELECT ID, Username, Email, Random_key FROM users WHERE Username = '".mysql_real_escape_string($_POST['username'])."'") or die(mysql_error());
	  
			if(mysql_num_rows($getUser) == 1 )  {//make sure their is one user
			
			  //  now also need to add info to user_openID to show they are using OPENID

      $row = mysql_fetch_assoc($getUser);

      AttachOpenID($openIDurl, $row['ID']);	


        $update = mysql_query("UPDATE users SET Active=1 WHERE ID='".mysql_real_escape_string($row['ID'])."'") or die(mysql_error());
				$msg = 'Congratulations !  You are now registerd, you can now login.';
        
        $setprivacysaved=$db->query("INSERT INTO privacy (ID, privstatusid) VALUES ('".mysql_real_escape_string($row['ID'])."', '1')");
       


       $msg = 'Registration is complete, welcome to mepath.com.';
	 
//   	header("Location: /lifestylelinking/me/mein.php");
        
      			}  //closes mail if  
      
			else {
				$error = 'There has been an error. Sorry. Please try again.';
			}
							
}  // closes if form submitted




}  // closes function


?>