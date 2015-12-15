<?php
	
  function checkLogin($levels)
	{
		if(!$_SESSION['logged_in'])
		{
			$access = FALSE;
		}
		else {
			$kt = split(' ', $levels);
			
			$query = mysql_query('SELECT Level_access FROM users WHERE ID = "'.mysql_real_escape_string($_SESSION['user_id']).'"');
			$row = mysql_fetch_assoc($query);
			
			$access = FALSE;
			
			while(list($key,$val)=each($kt))
			{
				if($val==$row['Level_access'])
				{//if the user level matches one of the allowed levels
					$access = TRUE;
				}
			}
		}
		if($access==FALSE)
		{
			header("Location: lifestylelinking/index.php");
		}
		else {
		//do nothing: continue
		}
		
	}
	
  
  
  
  
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
  
  
?>