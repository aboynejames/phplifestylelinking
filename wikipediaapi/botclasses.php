<?php
/** botclasses.php - Bot classes for interacting with mediawiki.
 *  (c) 2008 Chris G - http://en.wikipedia.org/wiki/User:Chris_G
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *   
 *  Developers (add your self here if you worked on the code):
 *	Cobi  - [[User:Cobi]]    - Wrote the http class and some of the wikipedia class
 *	Chris - [[User:Chris_G]] - Wrote the most of the wikipedia class
 **/

/**
 * This class is designed to provide a simplified interface to cURL which maintains cookies.
 * @author Cobi
 **/
class http {
		private $ch;
		private $uid;
		public $cookie_jar;
		public $postfollowredirs;
		public $getfollowredirs;

		function data_encode ($data, $keyprefix = "", $keypostfix = "") {
			assert( is_array($data) );
			$vars=null;
			foreach($data as $key=>$value) {
				if(is_array($value)) $vars .= $this->data_encode($value, $keyprefix.$key.$keypostfix.urlencode("["), urlencode("]"));
				else $vars .= $keyprefix.$key.$keypostfix."=".urlencode($value)."&";
			}
			return $vars;
		}

		function __construct () {
			$this->ch = curl_init();
			$this->uid = dechex(rand(0,99999999));
			curl_setopt($this->ch,CURLOPT_COOKIEJAR,'/tmp/cluewikibot.cookies.'.$this->uid.'.dat');
			curl_setopt($this->ch,CURLOPT_COOKIEFILE,'/tmp/cluewikibot.cookies.'.$this->uid.'.dat');
			curl_setopt($this->ch,CURLOPT_MAXCONNECTS,100);
			curl_setopt($this->ch,CURLOPT_CLOSEPOLICY,CURLCLOSEPOLICY_LEAST_RECENTLY_USED);
			$this->postfollowredirs = 0;
			$this->getfollowredirs = 1;
			$this->cookie_jar = array();
		}

		function post ($url,$data) {
//			echo 'POST: '.$url."\n";
			$time = microtime(1);
			curl_setopt($this->ch,CURLOPT_URL,$url);
			curl_setopt($this->ch,CURLOPT_USERAGENT,'php wikibot classes');
			/* Crappy hack to add extra cookies, should be cleaned up */
			$cookies = null;
			foreach ($this->cookie_jar as $name => $value) {
				if (empty($cookies)) {
					$cookies = "$name=$value";
				} else {
					$cookies .= "; $name=$value";
				}
			}
			if ($cookies != null) {
				curl_setopt($this->ch,CURLOPT_COOKIE,$cookies);
			}
			curl_setopt($this->ch,CURLOPT_FOLLOWLOCATION,$this->postfollowredirs);
			curl_setopt($this->ch,CURLOPT_MAXREDIRS,10);
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Expect:'));
			curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($this->ch,CURLOPT_TIMEOUT,30);
			curl_setopt($this->ch,CURLOPT_CONNECTTIMEOUT,10);
			curl_setopt($this->ch,CURLOPT_POST,1);
			//curl_setopt($this->ch,CURLOPT_FAILONERROR,1);
//			curl_setopt($this->ch,CURLOPT_POSTFIELDS, substr($this->data_encode($data), 0, -1) );
			curl_setopt($this->ch,CURLOPT_POSTFIELDS, $data);
			$data = curl_exec($this->ch);
			//echo "Error: ".curl_error($this->ch);
			//var_dump($data);
			global $logfd; if (!is_resource($logfd)) $logfd = fopen('php://stderr','w'); fwrite($logfd,'POST: '.$url.' ('.(microtime(1) - $time).' s) ('.strlen($data)." b)\n");
			return $data;
		}

		function get ($url) {
			//echo 'GET: '.$url."\n";
			$time = microtime(1);
			curl_setopt($this->ch,CURLOPT_URL,$url);
			curl_setopt($this->ch,CURLOPT_USERAGENT,'php wikibot classes');
			/* Crappy hack to add extra cookies, should be cleaned up */
			$cookies = null;
			foreach ($this->cookie_jar as $name => $value) {
				if (empty($cookies)) {
					$cookies = "$name=$value";
				} else {
					$cookies .= "; $name=$value";
				}
			}
			if ($cookies != null) {
				curl_setopt($this->ch,CURLOPT_COOKIE,$cookies);
			}
			curl_setopt($this->ch,CURLOPT_FOLLOWLOCATION,$this->getfollowredirs);
			curl_setopt($this->ch,CURLOPT_MAXREDIRS,10);
			curl_setopt($this->ch,CURLOPT_HEADER,0);
			curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($this->ch,CURLOPT_TIMEOUT,30);
			curl_setopt($this->ch,CURLOPT_CONNECTTIMEOUT,10);
			curl_setopt($this->ch,CURLOPT_HTTPGET,1);
			//curl_setopt($this->ch,CURLOPT_FAILONERROR,1);
			$data = curl_exec($this->ch);
			//echo "Error: ".curl_error($this->ch);
			//var_dump($data);
			global $logfd; if (!is_resource($logfd)) $logfd = fopen('php://stderr','w'); fwrite($logfd,'GET: '.$url.' ('.(microtime(1) - $time).' s) ('.strlen($data)." b)\n");
			
//     print_r($data); 
      return $data;
		}

		function __destruct () {
			curl_close($this->ch);
			@unlink('/tmp/cluewikibot.cookies.'.$this->uid.'.dat');
		}
	}

/**
 * This class is interacts with wikipedia using api.php
 * @author Chris G and Cobi
 **/
class wikipedia {
	private $http;
	private $token;
	public $url = 'http://en.wikipedia.org/w/api.php';

	/**
	 * This is our constructor.
	 * @return void
	 **/
	function __construct () {
		$this->http = new http;
		$this->token = null;
	}
	
	/**
	 * Sends a query to the api.
	 * @param $query The query string. 
	 * @param $post POST data if its a post request (optional).
	 * @return The api result.
	 **/
	function query ($query,$post=null) {
		
    if ($post==null) {
			$ret = $this->http->get($this->url.$query);
		} 
    else {
			$ret = $this->http->post($this->url.$query,$post);
		}
		
    return unserialize($ret);
	}
	
	/**
	 * Gets the content of a page. Returns false on error.
	 * @param $page The wikipedia page to fetch.
	 * @param $revid The revision id to fetch (optional)
	 * @return The wikitext for the page.
	 **/
	function getpage ($page,$revid=null) {
	
global $wwords;

$append = '';
		if ($revid!=null) {
			$append = '&rvstartid='.$revid;
		}
    
		$x = $this->query('?action=query&format=php&prop=revisions&titles='.urlencode($page).'&rvlimit=1&rvprop=content'.$append);

//		$x = $this->query('?action=query&meta=siteinfo&siprop=namespaces&format=php');

//echo $x;
//print_r($x);

foreach ($x['query']['pages'] as $ret) {
    
			if (isset($ret['revisions'][0]['*'])) {
	//	echo 'two';
//    print_r($ret['revisions'][0]['*']);

$wwords = $ret['revisions'][0]['*'];
//echo $wwords;

return $ret['revisions'][0]['*'];

    } 
    
    else {

return false;

}
}


}
	
	/**
	 * Gets the page id for a page.
	 * @param $page The wikipedia page to get the id for.
	 * @return The page id of the page.
	 **/
	function getpageid ($page) {
		$x = $this->query('?action=query&format=php&prop=revisions&titles='.urlencode($page).'&rvlimit=1&rvprop=content');
		foreach ($x['query']['pages'] as $ret) {
			return $ret['pageid'];
		}
	}
	
	/**
	 * Gets the number of contributions a user has.
	 * @param $user The username for which to get the edit count.
	 * @return The number of contributions the user has.
	 **/
	function contribcount ($user) {
		$x = $this->query('?action=query&list=allusers&format=php&auprop=editcount&aulimit=1&aufrom='.urlencode($user));
		return $x['query']['allusers'][0]['editcount'];
	}
	
	/**
	 * Returns an array with all the members of $category
	 * @param $category The category to use.
	 * @param $subcat (bool) Go into sub categories?
	 * @return array
	 **/
	 function categorymembers ($category,$subcat=false) {
	 	$continue = '';
	 	$pages = array();
	 	while (true) {
	 		$res = $this->query('?action=query&list=categorymembers&cmtitle='.urlencode($category).'&format=php&cmlimit=500'.$continue);
	 		if (isset($x['error'])) {
	 			return false;
	 		}
	 		foreach ($res['query']['categorymembers'] as $x) {
	 			$pages[] = $x['title'];
	 		}
	 		if (empty($res['query-continue']['categorymembers']['cmcontinue'])) {
	 			if ($subcat) {
	 				foreach ($pages as $p) {
	 					if (substr($p,0,9)=='Category:') {
	 						$pages2 = $this->categorymembers($p,true);
	 						$pages = array_merge($pages,$pages2);
	 					}
	 				}
	 			}
	 			return $pages;
	 		} else {
	 			$continue = '&cmcontinue='.urlencode($res['query-continue']['categorymembers']['cmcontinue']);
	 		}
	 	}
	 }
	 
	 /**
	  * Returns a list of pages that link to $page.
	  * @param $page
	  * @param $extra (defaults to null)
	  * @return array
	  **/
	 function whatlinkshere ($page,$extra=null) {
	 	$continue = '';
	 	$pages = array();
	 	while (true) {
	 		$res = $this->query('?action=query&list=backlinks&bltitle='.urlencode($page).'&bllimit=500&format=php'.$continue.$extra);
	 		if (isset($res['error'])) {
	 			return false;
	 		}
	 		foreach ($res['query']['backlinks'] as $x) {
	 			$pages[] = $x['title'];
	 		}
	 		if (empty($res['query-continue']['backlinks']['blcontinue'])) {
	 			return $pages;
	 		} else {
	 			$continue = '&blcontinue='.urlencode($res['query-continue']['backlinks']['blcontinue']);
	 		}
	 	}
	 }
	 
	 /**
	 * Returns an array with all the subpages of $page
	 * @param $page
	 * @return array
	 **/
	 function subpages ($page) {
	 	/* Calculate all the namespace codes */
		$ret = $this->query('?action=query&meta=siteinfo&siprop=namespaces&format=php');
		foreach ($ret['query']['namespaces'] as $x) {
			$namespaces[$x['*']] = $x['id'];
		}
		$temp = explode(':',$page,2);
		$namespace = $namespaces[$temp[0]];
		$title = $temp[1];
		$continue = '';
		$subpages = array();
		while (true) {
			$res = $this->query('?action=query&format=php&list=allpages&apprefix='.urlencode($title).'&aplimit=500&apnamespace='.$namespace.$continue);
			if (isset($x[error])) {
	 			return false;
	 		}
			foreach ($res['query']['allpages'] as $p) {
				$subpages[] = $p['title'];
			}
			if (empty($res['query-continue']['allpages']['apfrom'])) {
	 			return $subpages;
	 		} else {
	 			$continue = '&apfrom='.urlencode($res['query-continue']['allpages']['apfrom']);
	 		}
		}
	 }
	
	/**
	 * This function takes a username and password and logs you into wikipedia.
	 * @param $user Username to login as.
	 * @param $pass Password that corrisponds to the username.
	 * @return array
	 **/
	function login ($user,$pass) {
		$ret = $this->query('?action=login&format=php',array('lgname' => $user, 'lgpassword' => $pass));
		if ($ret['login']['result'] != 'Success') {
			echo "Login error: \n";
			print_r($ret);
			die();
		} else {
			return $ret;
		}
	}
	
	/* crappy hack to allow users to use cookies from old sessions */
	function setLogin($data) {
		$this->http->cookie_jar = array(
					$data['cookieprefix'].'UserName' => $data['lgusername'],
					$data['cookieprefix'].'UserID' => $data['lguserid'],
					$data['cookieprefix'].'Token' => $data['lgtoken'],
					$data['cookieprefix'].'_session' => $data['sessionid'],
				);

	}
	
	/**
	 * Check if we're allowed to edit $page.
	 * See http://en.wikipedia.org/wiki/Template:Bots
	 * for more info.
	 * @param $page The page we want to edit.
	 * @param $user The bot's username.
	 * @return bool
	 **/
	function nobots ($page,$user=null,$text=null) {
		if ($text == null) {
			$text = $this->getpage($page);
		}
		if ($user != null) {
			if (preg_match('/\{\{(nobots|bots\|allow=none|bots\|deny=all|bots\|optout=all|bots\|deny=.*?'.preg_quote($user,'/').'.*?)\}\}/iS',$text)) { 
				return false; 
			}
		} else {
			if (preg_match('/\{\{(nobots|bots\|allow=none|bots\|deny=all|bots\|optout=all)\}\}/iS',$text)) { 
				return false; 
			}
		}
		return true;
	}
	
	/**
	 * This function returns the edit token for the current user.
	 * @return edit token.
	 **/
	function getedittoken () {
		$x = $this->query('?action=query&prop=info&intoken=edit&titles=Main%20Page&format=php');
		foreach ($x['query']['pages'] as $ret) {
			return $ret['edittoken'];
		}
	}
	
	/**
	 * Purges the cache of $page.
	 * @param $page The page to purge.
	 * @return Api result.
	 **/
	function purgeCache($page) {
		return $this->query('?action=purge&titles='.urlencode($page).'&format=php');
	}
	
	/**
	 * Checks if $user has email enabled.
	 * Uses index.php.
	 * @param $user The user to check.
	 * @return bool.
	 **/
	function checkEmail($user) {
		$x = $this->query('?action=query&meta=allmessages&ammessages=noemailtext|notargettext&amlang=en&format=php');
		$messages[0] = $x['query']['allmessages'][0]['*'];
		$messages[1] = $x['query']['allmessages'][1]['*'];
		$page = $this->http->get(str_replace('api.php','index.php',$this->url).'?title=Special:EmailUser&target='.urlencode($user));
		if (preg_match('/('.preg_quote($messages[0],'/').'|'.preg_quote($messages[1],'/').')/i',$page)) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Returns all the pages $page is transcluded on.
	 * @param $page The page to get the transclusions from.
	 * @param $sleep The time to sleep between requets (set to null to disable).
	 * @return array.
	 **/
	function getTransclusions($page,$sleep=null,$extra=null) {
		$continue = '';
		$pages = array();
		while (true) {
			$ret = $this->query('?action=query&list=embeddedin&eititle='.urlencode($page).$continue.$extra.'&eilimit=500&format=php');
			if ($sleep != null) {
				sleep($sleep);
			}
			foreach ($ret['query']['embeddedin'] as $x) {
				$pages[] = $x['title'];
			}
			if (isset($ret['query-continue']['embeddedin']['eicontinue'])) {
				$continue = '&eicontinue='.$ret['query-continue']['embeddedin']['eicontinue'];
			} else {
				return $pages;
			}
		}
	}
	
	/**
	 * Edits a page.
	 * @param $page Page name to edit.
	 * @param $data Data to post to page.
	 * @param $summary Edit summary to use.
	 * @param $minor Whether or not to mark edit as minor.  (Default false)
	 * @param $bot Whether or not to mark edit as a bot edit.  (Default true)
	 * @return api result
	 **/
	function edit ($page,$data,$summary = '',$minor = false,$bot = true,$section = null) {
		if ($this->token==null) {
			$this->token = $this->getedittoken();
		}
		$params = array(
			'title' => $page,
			'text' => $data,
			'token' => $this->token,
			'summary' => $summary,
			($minor?'minor':'notminor') => '1',
			($bot?'bot':'notbot') => '1'
		);
		if ($section != null) {
			$params['section'] = $section;
		}
		return $this->query('?action=edit&format=php',$params);
	}
	
	/**
	 * Moves a page.
	 * @param $old Name of page to move.
	 * @param $new New page title.
	 * @param $reason Move summary to use.
	 * @param $movetalk Move the page's talkpage as well.
	 * @return api result
	 **/
	function move ($old,$new,$reason,$options=null) {
		if ($this->token==null) {
			$this->token = $this->getedittoken();
		}
		$params = array(
			'from' => $old,
			'to' => $new,
			'token' => $this->token,
			'reason' => $reason
		);
		if ($options != null) {
			$option = explode('|',$options);
			foreach ($option as $o) {
				$params[$o] = true;
			}
		}
		return $this->query('?action=move&format=php',$params);
	}
	
	/**
	 * Rollback an edit.
	 * @param $title Title of page to rollback.
	 * @param $user Username of last edit to the page to rollback.
	 * @param $reason Edit summary to use for rollback.
	 * @param $bot mark the rollback as bot.
	 * @return api result
	 **/
	function rollback ($title,$user,$reason=null,$bot=false) {
		$ret = $this->query('?action=query&prop=revisions&rvtoken=rollback&titles='.urlencode($title).'&format=php');
		foreach ($ret['query']['pages'] as $x) {
			$token = $x['revisions'][0]['rollbacktoken'];
			break;
		}
		$params = array(
			'title' => $title,
			'user' => $user,
			'token' => $token
		);
		if ($bot) {
			$params['markbot'] = true;
		}
		if ($reason != null) { $params['summary'] = $reason; }
		return $this->query('?action=rollback&format=php',$params);
	}
		
	/**
	 * Blocks a user.
	 * @param $user The user to block.
	 * @param $reason The block reason.
	 * @param $expiry The block expiry.
	 * @param $options a piped string containing the block options.
	 * @return api result
	 **/	
	function block ($user,$reason='vand',$expiry='infinite',$options=null,$retry=true) {
		if ($this->token==null) {
			$this->token = $this->getedittoken();
		}
		$params = array(
			'expiry' => $expiry,
			'user' => $user,
			'reason' => $reason,
			'token' => $this->token
		);
		if ($options != null) {
			$option = explode('|',$options);
			foreach ($option as $o) {
				$params[$o] = true;
			}
		}
		$ret = $this->query('?action=block&format=php',$params);
		/* Retry on a failed token. */
		if ($retry and $ret['error']['code']=='badtoken') {
			$this->token = $this->getedittoken();
			return $this->block($user,$reason,$expiry,$options,false);
		}
		return $ret;
	}
	
	/**
	 * Unblocks a user.
	 * @param $user The user to unblock.
	 * @param $reason The unblock reason.
	 * @return api result
	 **/	
	function unblock ($user,$reason) {
		if ($this->token==null) {
			$this->token = $this->getedittoken();
		}
		$params = array(
			'user' => $user,
			'reason' => $reason,
			'token' => $this->token
		);
		return $this->query('?action=unblock&format=php',$params);
	}
	
	/**
	 * Emails a user.
	 * @param $target The user to email.
	 * @param $subject The email subject.
	 * @param $text The body of the email.
	 * @param $ccme Send a copy of the email to the user logged in.
	 * @return api result
	 **/	
	function email ($target,$subject,$text,$ccme=false) {
		if ($this->token==null) {
			$this->token = $this->getedittoken();
		}
		$params = array(
			'target' => $target,
			'subject' => $subject,
			'text' => $text,
			'token' => $this->token
		);
		if ($ccme) {
			$params['ccme'] = true;
		}
		return $this->query('?action=emailuser&format=php',$params);
	}
	
	/**
	 * Deletes a page.
	 * @param $title The page to delete.
	 * @param $reason The delete reason.
	 * @return api result
	 **/	
	function delete ($title,$reason) {
		if ($this->token==null) {
			$this->token = $this->getedittoken();
		}
		$params = array(
			'title' => $title,
			'reason' => $reason,
			'token' => $this->token
		);
		return $this->query('?action=delete&format=php',$params);
	}
	
	/**
	 * Undeletes a page.
	 * @param $title The page to undelete.
	 * @param $reason The undelete reason.
	 * @return api result
	 **/	
	function undelete ($title,$reason) {
		if ($this->token==null) {
			$this->token = $this->getedittoken();
		}
		$params = array(
			'title' => $title,
			'reason' => $reason,
			'token' => $this->token
		);
		return $this->query('?action=undelete&format=php',$params);
	}
	
	/**
	 * (Un)Protects a page.
	 * @param $title The page to (un)protect.
	 * @param $protections The protection levels (e.g. 'edit=autoconfirmed|move=sysop')
	 * @param $expiry When the protection should expire (e.g. '1 day|infinite')
	 * @param $reason The (un)protect reason.
	 * @param $cascade Enable cascading protection? (defaults to false)
	 * @return api result
	 **/	
	function protect ($title,$protections,$expiry,$reason,$cascade=false) {
		if ($this->token==null) {
			$this->token = $this->getedittoken();
		}
		$params = array(
			'title' => $title,
			'protections' => $protections,
			'expiry' => $expiry,
			'reason' => $reason,
			'token' => $this->token
		);
		if ($cascade) {
			$params['cascade'] = true;
		}
		return $this->query('?action=protect&format=php',$params);
	}
	
	/**
	 * Uploads an image.
	 * Uses index.php as there is no api image uploading yet :(
	 * @param $page The destination file name.
	 * @param $file The local file path.
	 * @param $desc The upload discrption (defaults to '').
	 **/
	 function upload ($page,$file,$desc='') {
		$post = array(
			'wpUploadFile'		=> '@'.$file,
			'wpSourceType'		=> 'file',
			'wpDestFile'		=> $page,
			'wpUploadDescription'	=> $desc,
			'wpLicense'		=> '',
			'wpWatchthis'		=> '0',
			'wpIgnoreWarning'	=> '1',
			'wpUpload'		=> 'Upload file'
		);
		return $this->http->post(str_replace('api.php','index.php',$this->url).'?title=Special:Upload&action=submit',$post);
	}
	
	/**
	 * Changes a users rights.
	 * @param $user   The user we're working with.
	 * @param $add    A pipe-separated list of groups you want to add.
	 * @param $remove A pipe-separated list of groups you want to remove.
	 * @param $reason The reason for the change (defaults to '').
	 **/
	function userrights ($user,$add,$remove,$reason='') {
		// get the userrights token
		$token = $this->query('?action=query&list=users&ususers='.urlencode($user).'&ustoken=userrights&format=php');
		$token = $token['query']['users'][0]['userrightstoken'];
		$params = array(
				'user' => $user,
				'token' => $token,
				'add' => $add,
				'remove' => $remove,
				'reason' => $reason
			);
		return $this->query('?action=userrights&format=php',$params);
	}
}

?>