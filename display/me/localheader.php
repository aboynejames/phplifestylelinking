<div class="header-wrapper">

	<div class="top-nav">
  
		<div class="logo">

			<a href="/lifestylelinking/me/index.php"><img src="/lifestylelinking/images/mepath.png" title="mepath" alt="mepath" height="35px" /></a>
		</div>  
  
  
  
  
 <?php
// need to replace welcome base on openID or FB connect (can have a pic)
prevent_cache_headers();
// checks that fb key, secret, callback url as they should be 
 if (is_fbconnect_enabled()) {
    ensure_loaded_on_correct_url();
  }
$fb_uid = facebook_client()->get_loggedin_user();
if ($fb_uid && !$_SESSION[fbname])  {

$user_params = array();
      $user_params['fb_uid'] = $fb_uid;
      //$user_params['name'] = $;
      
$fbname = new User($user_params);
 $_SESSION[fbname] = $fbname->getName();
 $_SESSION[fbpic] = $fbname->getProfilePic(true);
}
//print_r($_SESSION);

if ($fb_uid && $_SESSION[fbname]) {

 echo 'Welcome <b> ';
 echo $_SESSION[fbname];
 echo '</b>';
 echo $_SESSION[fbpic];
  }


  else  {

     echo "Welcome <b>$_SESSION[username]</b> to lifestyle linking.";

           }
?>  
     <a href="/lifestylelinking/me/index.php" id="current">home</a> &middot;    
     <a href="/lifestylelinking/me/memprofile.php">profile</a> &middot;
		<a href="/lifestylelinking/me/memprivacy.php">privacy</a> &middot;
		<!--<a href="" ">settings</a> &middot; -->

<?php
// need to replace logout based on openID or FBconnect
  if ($fb_uid)  {
  
  //($user->is_facebook_user()) {
  
//echo sprintf('<a href="#" onclick="FB.Connect.logout(function() { refresh_page(); })">'
  echo '<a href="/lifestylelinking/me/logout.php">Logout of Facebook</a>';
  }
  
  else {
?>
  <a href="/lifestylelinking/me/logout.php">sign out </a> 	
<?php
  }
  ?>
  
  </div>
  
  <div class="header">
		<!--<div class="logo">

			<a href="/lifestylelinking/me/mein.php"><img src="/lifestylelinking/images/mepath.png" title="mepath" alt="mepath" height="35px" /></a>
		</div>  -->
		
		<div class="navigation">
       <div class="navigationme">
           <ul>
					<li class="current"><a href="/lifestylelinking/me/index.php?metext=1"><b>me</b></a></li>
            <li><a href="/lifestylelinking/me/addme.php">me data</a></li>
					<li><a href="/lifestylelinking/me/help.php">Help</a></li>
          </ul>
       </div>
  
     <div class="navigationmake">       
          <ul> 
            <li class="makegreen"><a href="/lifestylelinking/make/make.php?metext=5">make</a></li>
				</ul>
		</div>  
    
	 </div>  <!--  closes navigation  -->
  
  </div>
</div>