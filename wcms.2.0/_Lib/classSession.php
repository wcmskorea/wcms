<?php
class Sess
{
	var $base64_chars;
	var $getKey;
	var $code;

	public function __construct(){
		//set handle to override SESSION
		//$session_id = session_id();
		//$session_name = session_name();
		//$session_path = session_save_path();
		//echo "in class";
	}

	function Sess()
	{
		session_cache_limiter('nocache, must_revalidate, private_no_expire');
		session_set_cookie_params(0,"/");
		session_start();

		$this->base64_chars = '+/0123456789=ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$this->getKey = $this->base64_getkey();
		$this->code = "CSS";
		//로그 저장에 제외시킬 Bots IP
		$this->robot = "112.222.92.138|125.60.5.|116.125.143.|211.212.39.|61.247.221.|61.247.204.|117.53.97."; //10억홈피|구글|개인정보점검센터|다음|다음|네이버|네이트
		$this->robot .= "|121.156.121.|121.189.38.|121.156.125.|183.107.123.|211.113.50.|211.113.65.|61.111.247."; //KT|온세
		$this->robot .= "|66.249.|207.46.|65.52.|157.55.|69.64.|208.115.|209.85.238."; //US
	}

	function base64_getkey()
	{
		if ($_SESSION['KEY'] == '') {
			$array = array('(', ')', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '^',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
			'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
			);
			shuffle($array);
			$_SESSION['KEY'] = join('', $array);
		}
		return $_SESSION['KEY'];
	}

	function encode($code)
	{
		$this->code = ($code) ? $code : $this->code;
		return strtr(base64_encode($this->code), $this->base64_chars, $this->getKey);
	}

	function decode($Rcode)
	{
		$this->code = ($Rcode) ? $Rcode : $this->code;
		return base64_decode(strtr($this->code, $this->getKey, $this->base64_chars));
	}

	function sessEdit($passwd)
	{
		$_SESSION["passwd"] = $passwd;
	}

	function sessChk()
	{
		if(session_is_registered($_SESSION["uid"]))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function sessKill()
	{
		if($GLOBALS['cfg']['site']['sessionType'] == 'sessiondb')
		{
			$this->sessionDbDestroy(session_id());
		}
		else if($GLOBALS['cfg']['site']['sessionType'] == 'sessionfile')
		{
			$this->sessionFileDestroy(session_id());
		}
		session_destroy();
	}

	function sessDel()
	{
		$i=0;
		$path = __PATH__."/Session";
		$directory = dir($path);
		while($entry = $directory->read()) {
			if ($entry != "." && $entry != "..") {
				if(!preg_match(session_id(), $entry) && !preg_match($HTTP_COOKIE_VARS['ZBSESSIONID'], $entry)) {
					@chmod($path."/".$entry,0777);
					@unlink($path."/".$entry);
					$i++;
					if($i%100==0) print(".");
					flush();
				}
			}
		}
	}
	# 카운트
	function counting($type, $domain=null)
	{
		if($type == "count"){
			if(preg_match('/'.$this->robot.'/', $_SERVER['REMOTE_ADDR'])) { return false; }
			if($_SESSION['ulevel'] && $_SESSION['ulevel'] <= $GLOBALS['cfg']['operator']) { return false; }
		}

		switch($type)
		{
			case "count" :
				if($_COOKIE['CNT'] != $GLOBALS['cfg']['skin'] && $_SERVER['SERVER_PROTOCOL'] == "HTTP/1.1")
				{
					@mysql_query(" UPDATE `mdAnalytic__track` SET counting=counting+1 WHERE ip='".$_SERVER['REMOTE_ADDR']."' AND skin='".$GLOBALS['cfg']['skin']."' AND date=CURDATE() ");
					if(!$GLOBALS['db']->getAffectedRows())
					{
						@mysql_query(" INSERT INTO `mdAnalytic__track` (counting,ip,skin,date) VALUES ('1','".$_SERVER['REMOTE_ADDR']."','".$GLOBALS['cfg']['skin']."',CURDATE()) ");
					}
					setCookie("CNT", $GLOBALS['cfg']['skin'], time()+86400, "/", ".".$domain."", 0);
				}
				break;
			case "today" :
				return number_format($GLOBALS['db']->queryFetchOne(" SELECT COUNT(*) AS count FROM `mdAnalytic__track` WHERE date=CURDATE() "));
				break;
			case "all" :
				return number_format($GLOBALS['db']->queryFetchOne(" SELECT COUNT(*) AS count FROM `mdAnalytic__track`"));
				break;
		}
	}

	# Referer 체크
	function countReferer($ref)
	{
		//if(preg_match('/'.$this->robot.'/', $_SERVER['REMOTE_ADDR'])) { return false; }
		if($_SESSION['ulevel'] && $_SESSION['ulevel'] <= $GLOBALS['cfg']['operator']) { return false; }
		if(preg_match("/http/i",$_SERVER['HTTP_REFERER']))
		{
			if(!preg_match('/'.$_SERVER['HTTP_HOST'].'/i', $_SERVER['HTTP_REFERER']) && !preg_match('/\admin/i', $_SERVER['HTTP_REFERER']))
			{
				$refer = preg_replace('/http:\/\//i', null, $_SERVER['HTTP_REFERER']);
				$refer = preg_replace('/https:\/\//i', null, $refer);
				if($refer && !$_COOKIE['RFR']) 
				{ 
					@mysql_query(" INSERT INTO `mdAnalytic__refer` (ip,referer,date) VALUES ('".$_SERVER['REMOTE_ADDR']."','".$refer."',CURDATE()) "); 
					setCookie("RFR", $_SERVER['REMOTE_ADDR'], 0, "/", ".".$domain."", 0);
				}
				return true;
			}
			else
			{
				return false;
			}
		}
		return false;
	}

	function tracking($page='main', $limit=null)
	{
		if(preg_match('/'.$this->robot.'/', $_SERVER['REMOTE_ADDR'])) { return false; }
		if(!$limit && $_SESSION['ulevel'] && $_SESSION['ulevel'] <= $GLOBALS['cfg']['operator']) { return false; }
		$query = " UPDATE `mdAnalytic__track` SET info = CONCAT(info,'>".time().":".$page."') WHERE ip='".$_SERVER['REMOTE_ADDR']."' AND skin='".$GLOBALS['cfg']['skin']."' AND SUBSTRING(info,-".strlen($page).")<>'".$page."' AND date=CURDATE() ";
		@mysql_query($query);
		return true;
	}

	#세션 DB
	function sessionDbOpen($save_path, $session_name)
	{
		global $sess_save_path;

		$sess_save_path = $save_path;
		return(true);
	}

	function sessionDbClose()
	{
		return(true);
	}

	function sessionDbRead($id)
	{
		$id = mysql_real_escape_string($id);

		$query = "SELECT ssData FROM `site__session` WHERE id = '$id'";
		$row = $GLOBALS['db']->queryFetch($query);

		return $row['ssData']; 
	}

	function sessionDbWrite($id, $data)
	{
		$id = mysql_real_escape_string($id);
		$ssData = mysql_real_escape_string($data);
		$ssDatetime = date("Y-m-d H:i:s", time());

		$query = "REPLACE INTO `site__session` VALUES ('$id', '$ssDatetime', '$ssData','$_SESSION[uid]','$_SERVER[REMOTE_ADDR]')";
		$result = mysql_query($query);

		return $result; 
	}

	function sessionDbDestroy($id)
	{
		$id = mysql_real_escape_string($id);

		$query = "DELETE FROM `site__session` WHERE id = '$id'";
		$result = mysql_query($query);

		return $result;
	}

	function sessionDbClean($max)
	{
		$old = time() - $max;
		$old = date("Y-m-d H:i:s", $old);

		$query = "DELETE FROM `site__session` WHERE ssDatetime < '$old'";
		$result = mysql_query($query);

		return $result;
	}

	function sessionLoginCheck($userid)
	{
		$result = true;

		if($GLOBALS['cfg']['site']['sessionType'] == 'sessiondb')
		{
			$loginTime = date("Y-m-d H:i:s", time() - 60 * $GLOBALS['cfg']['site']['sessionTime']);
			$row = @mysql_fetch_array(@mysql_query("SELECT * FROM `site__session` WHERE userid='".$userid."' AND ssIp != '".$_SERVER['REMOTE_ADDR']."' AND ssDatetime > '".$loginTime."' "));

			if($row['id']) {
				//기존 로그인 세션 삭제
				@mysql_query("DELETE FROM `site__session` WHERE id='".$row['id']."' ");
				$result = false;
			}
			return $result;
		}
		else if($GLOBALS['cfg']['site']['sessionType'] == 'sessionfile')
		{
			$sess_save_path = session_save_path();
			$d = dir($sess_save_path);

			while (false != ($entry = $d->read())) { 
				$temp = file($sess_save_path . '/' . $entry); 
				if (preg_match("`uid\|[^;]*\"" . $userid . "\";`", $temp[0])) { 
					//기존 로그인 세션 삭제
					@unlink($sess_save_path . '/' . $entry);
					$result = false;
					exit;
				}
			}
			return $result;
		}
		return true;
	}

	function sessionFileOpen($save_path, $session_name)
	{
		global $sess_save_path;

		$sess_save_path = $save_path;
		return(true);
	}

	function sessionFileClose()
	{
		return(true);
	}

	function sessionFileRead($id)
	{
		global $sess_save_path;

		$sess_file = "$sess_save_path/sess_$id";
		return (string) @file_get_contents($sess_file);
	}

	function sessionFileWrite($id, $data)
	{
		global $sess_save_path;

		$sess_file = "$sess_save_path/sess_$id";
		if ($fp = @fopen($sess_file, "w")) {
			$return = fwrite($fp, $data);
			fclose($fp);
			return $return;
		} else {
			return(false);
		}
	}

	function sessionFileDestroy($id)
	{
		global $sess_save_path;

		$sess_file = "$sess_save_path/sess_$id";
		return(@unlink($sess_file));
	}

	function sessionFileClean($max)
	{
		global $sess_save_path;

		foreach (glob("$sess_save_path/sess_*") as $filename) {
			if (filemtime($filename) + $maxlifetime < time()) {
				@unlink($filename);
			}
		}
		return true;
	}

}
?>
