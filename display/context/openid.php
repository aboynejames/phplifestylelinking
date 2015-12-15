<?php
function GetUserId($openid_url)  {

global $oneresult;
global $userid;

$db->query="SELECT ".RSSDATA.".user_openids.user_id FROM ".RSSDATA.".user_openids WHERE ".RSSDATA.".user_openids.openid_url = '$openid_url' ";

$db->result = mysql_query($db->query) or die(mysql_error());

$oneresult = mysql_num_rows ($db->result);

$row = mysql_fetch_object($db->result);

$userid = $row->user_id;

} // closes function


function GetOpenIDsByUser($user_id)  {

global $result;

$db->query="SELECT * FROM ".RSSDATA.".user_openids WHERE ".RSSDATA.".user_openids.user_id = '$user_id' ";
//echo $db->query;

$result = mysql_query($db->query) or die(mysql_error());




}  // closes function


function AttachOpenID($openid_url, $user_id)  {

$db->query="INSERT INTO ".RSSDATA.".user_openids (openid_url, user_id) VALUES ('$openid_url', '$user_id') ";
//echo $db->query;

$db->result = mysql_query($db->query) or die(mysql_error());

}  // closes function


function DetachOpenID($openid_url, $user_id)  {

global $delopenid;

$db->query="DELETE FROM ".RSSDATA.".user_openids WHERE ".RSSDATA.".user_openids.openid_url = '$openid_url' AND ".RSSDATA.".user_openids.user_id = '$user_id' ";
//echo $db->query;

$delopenid = mysql_query($db->query) or die(mysql_error());


}


function DetachOpenIDsByUser($user_id)  {

$db->query="DELETE FROM ".RSSDATA.".user_openids WHERE ".RSSDATA.".user_openids.user_id = '$user_id' ";

$db->result = mysql_query($db->query) or die(mysql_error());

}  // closes function


?>