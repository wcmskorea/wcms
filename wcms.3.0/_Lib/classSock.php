<?php
class Syssock
{
	var $errno;
	var $errstr;

	var $server;
	var $port;
	var $timeout;
	var $socket;
	var $query;
	var $result = array();
	var $spamcount;
	var $logs;
	var $user;
	var $pass;
	var $send;
	var $date;
	var $sendType;
	var $tempMode;
	var $tempArray;

	/* 소켓 생성 */
	function Syssock($user=null, $pass=null, $sender=null)
	{
		$this->server		= "";
		$this->port 		= "";
		$this->timeout 		= 5;
		$this->spamcount	= 3;
		$this->logs			= 1;			//발송 로그 사용여부 (1 or 0)
		$this->user			= $user;		//계정아이디
		$this->pass			= $pass;		//계정비번
		$this->sender 		= $sender;		//보내는 사람
		$this->date			= time();		//발송시간
	}

	/* 소켓 생성 */
	function connect()
	{
		if($this->socket = @fsockopen($this->server, $this->port, $this->errno, $this->errstr, $this->timeout))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/* 결과값 받기 */
	function read()
	{
		$buffer = "";	// read buffer

		while(!feof($this->socket))
		{
			$buffer = fgets($this->socket, 256);

			if(!$buffer) break;
			if(strcmp($buffer, "\n") == 0) break;

			$key = trim( substr ( $buffer, 0, strpos($buffer, "|^|") ) );
			$val = trim( substr ( $buffer, strpos($buffer, "|^|") + 3 ) );

			if ( !empty($key) )
			{
				//$this->result[$key] = iconv("CP949", "UTF-8", $val);
				$this->result[$key] = $val;
			}
		}
		$this->disconnect();
	}

	/* SMS 발송가능 수량체크 */
	function smsCheck()
	{
		$this->type		= "count";
		if(!$this->smsWrite())
		{
			$this->result[code] = 99;
			$this->result[msg]	= "SMS서버와 통신장애(1)입니다.";
		}
		else
		{
			$this->read();
		}
		return $this->result;
	}

	/* 문자 전송 */
	function smsSend($receiver, $mesg, $test='Y')
	{
		$this->type		= "send";
		$this->recv		= preg_replace("/||$/", "", $receiver);

		if($mesg != 'member' && $mesg != 'admin' && $mesg != 'temp01' && $mesg != 'temp02' && $mesg != 'temp03' && $mesg != 'temp04' && $mesg != 'temp05')
		{
			$this->mesg = $mesg;
		}
		else
		{
			$this->smsTemplet($mesg);
		}

		$this->seq		= ($this->logs) ? $this->smsHistory() : 0;

		if(!$_SESSION['ulevel'] && $this->smsDelay() >= $this->spamcount)
		{
			$this->result[code] = 95;
			$this->result[msg]	= "비회원 발송은 하루에 {".$this->spamcount."건}으로 제한되어 있습니다";
		}
		else if(!$this->recv || !preg_match('/^(0[2-8][0-5]?|01[016789])-?([1-9]{1}[0-9]{2,3})-?([0-9]{4})$/', $this->recv))
		{
			$this->result[code] = 96;
			$this->result[msg]	= "수신번호가 잘못된 형식입니다.";
		}
		else if(!$this->seq || !$this->smsWrite())
		{
			$this->result[code] = 99;
			$this->result[msg]	= "SMS서버와 통신장애(2)입니다.";
		}
		else
		{
			$this->read();
			if($this->logs) $this->smsHistory();
		}
		return $this->result;
	}

	/* SMS 명령어 와 값전달 */
	function smsWrite()
	{
		if(!$this->connect())
		{
			return false;
		}
		else
		{
			$this->mesg	= htmlspecialchars($this->mesg);
			$this->mesg	= ($GLOBALS['cfg']['charset']=='utf-8') ? iconv("UTF-8", "CP949", $this->mesg) : $this->mesg;
			$str  = "userid|^|".	$this->user		."|+|";
			$str .= "userpw|^|".	$this->pass		."|+|";
			$str .= "type|^|".		$this->type		."|+|";
			$str .= "send|^|".		$this->sender	."|+|";
			$str .= "recv|^|".		$this->recv		."|+|";
			$str .= "mesg|^|".		$this->mesg		."|+|";
			$str .= "date|^|".		$this->date		."|+|";
			$str .= "seq|^|".		$this->seq		."|+|";
			$str .= "host|^|".		$_SERVER[HTTP_HOST];
			$this->query = $str;
			fputs($this->socket, $this->query);
			return true;
		}
	}

	/* SMS 발송내역 */
	function smsHistory()
	{
		//global $_SESSION;
		$this->userid = $_SESSION[id];
		if(!$this->result[seq])
		{
			$rst = @mysql_query("INSERT INTO `mdSms__history` (sendDate,regDate,receiver,sender,mesg,rst,user,userip,sendType,count) VALUES ('".$this->date."','".time()."','".$this->recv."','".$this->sender."','".$this->mesg."','01','".$this->userid."','".$_SERVER[REMOTE_ADDR]."','".$this->sendType."','".count(explode("||", $this->recv))."')");
			if(@mysql_num_rows($rst) < 1)
			{
				return @mysql_insert_id();
			}
			else
			{
				return 0;
			}
		}
		else
		{
			@mysql_query("UPDATE `mdSms__history` SET rst='".$this->result[code]."' WHERE seq='".$this->result[seq]."'");
		}
	}

	/* SMS 발송내역 */
	function smsDelay()
	{
		$rst = @mysql_fetch_array(@mysql_query("SELECT COUNT(*) FROM `mdSms__history` WHERE FROM_UNIXTIME(sendDate,'%Y%m%d')='".date("Ymd")."' AND rst='01' AND userip='".$_SERVER[REMOTE_ADDR]."'"));
		if($rst[0] >= $this->spamcount) @mysql_query("DELETE FROM `mdSms__history` WHERE seq='".$this->seq."'");
		return $rst[0];
	}

	/* SMS 텥플리설정 */
	function smsTemplet($temp)
	{
		$rst = @mysql_fetch_array(@mysql_query("SELECT ".$temp." FROM `mdSms__` WHERE mode='".$this->tempMode."'"));
		$count = preg_match_all("#\{(.*?)\}#si", $rst[0], $matche);
		for ($i = 0; $i < $count; $i++)
		{
			$rst[0] = str_replace($matche[0][$i], $this->tempArray[$i], $rst[0]);
		}
		$this->mesg = $rst[0];
		return true;
	}

	/* 10억홈피 회원여부 */
	function memCheck($id, $passwd=null, $checktype=null)
	{
		$this->mesg		 = $id."|+|";
		$this->mesg		.= "passwd|^|".$passwd."|+|";
		$this->mesg		.= "checktype|^|".$checktype."|+|";
		if(!$this->memWrite())
		{
			$this->result[code] = 99;
			$this->result[msg]	= "회원인증실패";
		}
		else
		{
			$this->read();
		}
		return $this->result;
	}

	/* 10억홈피 회원등록 */
	function memRegist($data)
	{
		//Functions::showArray($data);
		//die();
		$this->type		= "memberRegist";
		$this->mesg		= $data['id']."|+|";
		$this->mesg		.= "name|^|".$data['name']."|+|";
		$this->mesg		.= "ename|^|".$data['ename']."|+|";
		$this->mesg		.= "passwd|^|".$data['passwd']."|+|";
		$this->mesg		.= "idcode|^|".$data['idcode']."|+|";
		$this->mesg		.= "email|^|".$data['email']."|+|";
		$this->mesg		.= "zip|^|".$data['zipcode']."|+|";
		$this->mesg		.= "addr01|^|".$data['addrress01']."|+|";
		$this->mesg		.= "addr02|^|".$data['addesss02']."|+|";
		$this->mesg		.= "addr03|^|".$data['address03']."|+|";
		$this->mesg		.= "addr04|^|".$data['address04']."|+|";
		$this->mesg		.= "city|^|".$data['city']."|+|";
		$this->mesg		.= "country|^|".$data['country']."|+|";
		$this->mesg		.= "phone|^|".$data['phone']."|+|";
		$this->mesg		.= "fax|^|".$data['fax']."|+|";
		$this->mesg		.= "mobile|^|".$data['mobile']."|+|";
		$this->mesg		.= "agree|^|".$data['division']."|+|";
		$this->mesg		.= "week|^|".$data['week']."|+|";
		$this->mesg		.= "sex|^|".$data['sex']."|+|";
		$this->mesg		.= "age|^|".$data['age']."|+|";
		$this->mesg		.= "birth|^|".$data['birth']."|+|";
		$this->mesg		.= "company|^|".$data['company']."|+|";
		$this->mesg		.= "ceo|^|".$data['ceo']."|+|";
		$this->mesg		.= "status|^|".$data['status']."|+|";
		$this->mesg		.= "class|^|".$data['class']."|+|";
		$this->mesg		.= "url|^|".$data['url'];
		$this->mesg		= iconv("UTF-8", "CP949", $this->mesg);
		if(!$this->memWrite())
		{
			$this->result[code] = 99;
			$this->result[msg]	= "통신장애";
		}
		else
		{
			$this->read();
		}
		return $this->result;
	}

	/* 10억홈피 회원정보변경 */
	function memUpdate($id, $name, $passwd)
	{
		$this->type	   = "memberUpdate";
		$this->mesg		 = $id."|+|";
		$this->mesg		.= "name|^|".$name."|+|";
		$this->mesg		.= "passwd|^|".$passwd;
		$this->mesg		 = iconv("UTF-8", "CP949", $this->mesg);
		if(!$this->memWrite())
		{
			$this->result[code] = 99;
			$this->result[msg]	= "통신장애";
		}
		else
		{
			$this->read();
		}
		return $this->result;
	}

	/* 10억홈피 값전달 */
	function memWrite()
	{
		if(!$this->connect())
		{
			return false;
		}
		else
		{
			$str  = "userid|^|".	$this->user		."|+|"; //필수전달값 : 사이트 아이디
			$str .= "userpw|^|".	$this->pass		."|+|"; //필수전달값 : 사이트 인증키
			$str .= "type|^|".		$this->type		."|+|"; //필수전달값 : 데이터
			$str .= "memid|^|".		$this->mesg		;
			$this->query = $str;
			fputs($this->socket, $this->query);
			return true;
		}
	}

	/* 변수 초기화 */
	function varReset()
	{
		unset($this->query);
		unset($this->result);
	}

	/* 접속 종료 */
	function disconnect()
	{
		fclose($this->socket);
	}
}
?>
