<?php

class Member
{
    var $uid;
    var $ulevel;
    var $uposition;
	var $perm;
	var $permLimit;
	var $permCheck;
	var $dedicate;
	var $userPosition;
	var $query;
	var $rows;
	var $rst;

	/*------------------------------------------------------------------------------------
	 * 회원관련 Class
	 */
	function Member($perm, $permLimit)
	{
		/**
		 * 권한설정
		 */
		//Functions::showArray($perm);
		$this->perm 		= explode(",", $perm); //['0']관리, ['1']접근, ['2']열람권한, ['3']작성, ['4']답변, ['5']댓글
		$this->permLimit 	= explode(",", $permLimit); //['0']관리, ['1']접근, ['2']열람권한, ['3']작성, ['4']답변, ['5']댓글
		$this->user 		= (isset($_SESSION['uid'])) ? $_SESSION['uid'] : null;
		$this->userLevel 	= (isset($_SESSION['ulevel'])) ? $_SESSION['ulevel'] : null;
		$this->userPosition	= (isset($_SESSION['uposition'])) ? $_SESSION['uposition'] : null;
	}

	/**------------------------------------------------------------------------------------
	 * 모듈별 퀀한체크
	 *--------------------------------------------------------------------------------------
	 * $perm : 제한 레
	 * $limit : 제한 형
	 */
	function checkPerm($permCheck)
	{
		if($permCheck == 's')
		{
			if($this->userLevel && $this->userLevel == 1) { return true; }
			return false;
		}
		else
		{
			switch($this->permLimit[$permCheck])
			{
				case 'E' :
					if($this->userLevel && $this->userLevel <= $GLOBALS['cfg']['operator']) { return true; }
					if($this->userLevel != $this->perm[$permCheck]) { return false; }
					return true;
					break;
				case 'U' :
					if($this->userLevel && $this->userLevel <= $GLOBALS['cfg']['operator']) { return true; }
					if($this->userLevel > $this->perm[$permCheck]) { return false; }
					if(!$this->userLevel && $this->perm[$permCheck] != '99') { return false; }
					return true;
					break;
				case 'G' :
					if($this->userLevel && $this->userLevel <= $GLOBALS['cfg']['operator']) { return true; }
					if($this->userLevel > $this->perm[$permCheck]) { return false; }
					if(!$this->userLevel && $this->perm[$permCheck] != '99') { return false; }
					if($this->dedicate && $this->userPosition != $this->dedicate) { return false; }
					return true;
					break;
				case 'P' :
					if($this->userLevel && $this->userLevel <= $GLOBALS['cfg']['operator']) { return true; }
					if($this->userLevel > $GLOBALS['cfg']['operator']) { return false; }
					if($this->user != $this->perm[$permCheck]) { return false; }
					return true;
					break;
				case 'A' :
					if($this->userLevel && $this->userLevel <= $GLOBALS['cfg']['operator']) { return true; }
					if($_SESSION['uage'] >= 19) { return true; } else { return false; }
					return true;
					break;
				default :
					return false;
					break;
			}
		}
	}

	/*----------------------------------------
	 * 회원정보 취득(아이디)
	 */
	function memberInfo($idx, $field=null)
	{
		if($idx == 'master' || $idx == '99999999')	//seq로 검색 추가(2013-01-29)
		{
			$this->rst = array('id'=>'master','name'=>'관리자','email'=>'css@aceoa.com','phone'=>'062-374-4242','mobile'=>'010-2984-0407','zipcode'=>'500-480','address01'=>'광주광역시 북구 오룡동','address02'=>'1110-7번지 광주디자인센터 5층 501호','seq'=>'99999999');
		}
		else {
			$this->query	= " SELECT ";
			$this->query	.= ($field) ? $field : "*,A.info AS info";
			$this->query	.= " FROM `mdMember__account` AS A INNER JOIN `mdMember__info` AS B ON A.id=B.id ";
			$this->query	.= (is_numeric($idx)) ? "WHERE A.seq='".strtolower($idx)."'" : "WHERE A.id='".strtolower($idx)."'";
			$this->rst 		= @mysql_fetch_array(@mysql_query($this->query));
		}
		return $this->rst;
	}

	/*----------------------------------------
	 * 회원정보 취득(고유번호)
	 */
	function memberInfoSeq($idx, $field=null)
	{
		$this->query	= " SELECT ";
		$this->query	.= ($field) ? $field : "*,A.level AS level";
		$this->query	.= " FROM `mdMember__account` AS A INNER JOIN `mdMember__info` AS B ON A.id=B.id LEFT JOIN `mdMember__level` AS C ON A.level=C.level WHERE A.seq='".$idx."' ";
		$this->rst 		= @mysql_fetch_array(@mysql_query($this->query));
		return $this->rst;
	}

	/*----------------------------------------
	 * 회원등록 체크
	 */
	function memberRegCheck($idtype, $code)
	{
		switch($idtype)
		{
			case 'id':
				$this->query = "SELECT COUNT(*) FROM `mdMember__account` WHERE id='".strtolower($code)."' AND level>'0'";
				break;
			case 'email':
				$this->query = "SELECT COUNT(*) FROM `mdMember__account` WHERE email='".$code."' AND level>'0'";
				break;
			case 're_email':
				$this->query = "SELECT COUNT(*) FROM `mdMember__account` WHERE id<>'".$_SESSION['uid']."' AND email='".$code."' AND level>'0'";
				break;
			case 'idcode':
				$this->query = "SELECT COUNT(*) FROM `mdMember__account` WHERE idcode=PASSWORD('".$code."') AND level>'0'";
				break;
			case 'bizno':
				$this->query = "SELECT COUNT(*) FROM `mdMember__info` WHERE bizNo='".$code."'";
				break;
			case 'new_nick':
				$this->query = "SELECT COUNT(*) FROM `mdMember__account` WHERE nick='".$code."' AND level>'0'";
				break;
			case 'nick':
				$this->query = "SELECT COUNT(*) FROM `mdMember__account` WHERE id<>'".$_SESSION['uid']."' AND nick='".$code."' AND level>'0'";
				break;
		}
		$this->rst = @mysql_fetch_array(@mysql_query($this->query));
		return ($this->rst['0'] > 0) ? true : false;
	}

	/*----------------------------------------
	 * 회원 신규 쪽지 조회
	 */
	function memberGetMessages($user)
	{
		$this->rst = @mysql_fetch_array(@mysql_query(" SELECT COUNT(*) FROM `mdMessage__box` WHERE receiver='".$user."' AND receiveDate<'1' "));
		return $this->rst['0'];
	}

	/*----------------------------------------
	 * 회원 포인트 조회 [현재 사용하지 않은 함수]
	 */
	function memberGetMileages($user)
	{
		$this->rst = @mysql_fetch_array(@mysql_query(" SELECT SUM(point) FROM `mdMileage__history` WHERE id='".$user."' "));
		return $this->rst['0'];
	}

	/*----------------------------------------
	 * 회원 예치금 조회
	 */
	function memberGetMoneys($user)
	{
		$this->rst = @mysql_fetch_array(@mysql_query(" SELECT SUM(money) FROM `mdPayment__moneys` WHERE id='".$user."' "));
		return $this->rst['0'];
	}

	/*----------------------------------------
	 * 회원 포인트 적립 및 차감
	 */
	function memberMileages($id, $funce, $point=0, $mesg=null)
	{
		$this->rst = ($point) ? $point : @mysql_fetch_array(@mysql_query(" SELECT `".$funce."` FROM `mdMileage__` WHERE seq='1' "));
		switch($funce) {
			case 'mileageLog' :
				$this->rst = @mysql_fetch_array(@mysql_query(" SELECT COUNT(*) FROM `mdMileage__history` WHERE id='".$id."' AND mode='mileageLog' AND DATE_FORMAT(FROM_UNIXTIME(date),'%Y-%m-%d')='".date("Y-m-d")."' "));
				if($this->rst['0'] > 0) {	return 0;	}
				break;
			case 'switch' :
				$mesg  = str_replace("{{mileage}}", $this->rste['0'], $mesg);
				$mesg .= "를 예치금 전환";
				@mysql_fetch_array(@mysql_query(" INSERT INTO `mdMileage__history` (id, mode, mesg, point, date, ip) VALUES ('".$id."','".$funce."','".$mesg."','".$this->rst['0']."','".time()."','".$_SERVER['REMOTE_ADDR']."') "));
				$this->rows = @mysql_insert_id();
				@mysql_fetch_array(@mysql_query(" INSERT INTO `mdPayment__moneys` (id, mileages, mode, mesg, money, date, ip) VALUES ('".$id."','".$this->rows."','".$funce."','".$mesg."','".str_replace("-",null,$this->rst['0'])."','".time()."','".$_SERVER['REMOTE_ADDR']."') "));
				return $this->rst['0'];
				break;
			case 'default' :
				return 0;
				break;
		}
		if($mileage['0']) {
			$mesg  = str_replace("{{mileage}}", $this->rst['0'], $mesg);
			$mesg .= ($this->rst['0'] < 0) ? " 삭감" : " 적립";
			@mysql_fetch_array(@mysql_query(" INSERT INTO `mdMileage__history` (id, mode, mesg, point, date, ip) VALUES ('".$id."','".$funce."','".$mesg."','".$$this->rst['0']."','".time()."','".$_SERVER['REMOTE_ADDR']."') "));
			return $this->rst['0'];
		} else {
			return 0;
		}
	}

	/*----------------------------------------
	 * 회원 예치금 적립 및 차감
	 */
	function memberMoneys($id, $funce, $money=0, $mesg=null)
	{
		switch($funce) {
			case 'switch' :
				$mesg  = str_replace("{{money}}", $money, $mesg);
				$mesg .= "을 포인트 전환";
				@mysql_fetch_array(@mysql_query(" INSERT INTO `mdPayment__moneys` (id, mode, mesg, money, date, ip) VALUES ('".$id."','".$funce."','".$mesg."','".$money."','".time()."','".$_SERVER['REMOTE_ADDR']."') "));
				@mysql_fetch_array(@mysql_query(" INSERT INTO `mdMileage__history` (id, moneys, mode, mesg, point, date, ip) VALUES ('".$id."','".$db->GetLastID()."','".$funce."','".$mesg."','".str_replace("-",null,$money)."','".time()."','".$_SERVER['REMOTE_ADDR']."') "));
				return $money;
				break;
		}
		if($money) {
			$mesg  = str_replace("{{money}}", $money, $mesg);
			$mesg .= ($money < 0) ? " 삭감" : " 예치";
			@mysql_fetch_array(@mysql_query(" INSERT INTO `mdPayment__moneys` (id, mode, mesg, money, date, ip) VALUES ('".$id."','".$funce."','".$mesg."','".$money."','".time()."','".$_SERVER['REMOTE_ADDR']."') "));
			return $money;
		} else {
			return 0;
		}
	}

	/*----------------------------------------
	 * 회원수 조회(레벨별)
	 */
	function memberRegRows($type=0)
	{
		$rst = @mysql_fetch_array(@mysql_query("SELECT COUNT(*) FROM `mdMember__account` WHERE level='".$type."'"));
		return $rst['0'];
	}

	/*----------------------------------------
	 * 회원 등급 조회(레벨별)
	 */
	function memberPosition($level)
	{
		$rst = ($level == 1) ? array("관리자") : @mysql_fetch_array(@mysql_query(" SELECT position FROM `mdMember__level` WHERE level='".$level."' "));
		return ($rst['0']) ? $rst['0'] : "탈퇴회원";
	}

	/*----------------------------------------
	 * 기본 레벨 조회
	 */
	function memberBasic()
	{
		$rst = @mysql_fetch_array(@mysql_query("SELECT level FROM `mdMember__level` WHERE `default`='Y'"));
		return $rst['0'];
	}

	/*----------------------------------------
	 * 추천 레벨 조회
	 */
	function memberRecom()
	{
		$rst = @mysql_fetch_array(@mysql_query("SELECT level FROM `mdMember__level` WHERE `recom`='Y'"));
		return $rst['0'];
	}

	/*----------------------------------------
	 * 디스크 용량 및 트래픽 용량 체크
	 */
	function checkDisk($server, $disk, $traffic, $user)
	{
		$uses 		= @file_get_contents("http://service".$server.".aceoa.com/serviceSize.php?ids=".$user);
		$uses		= explode("|", $uses);
		$uses['3']	= ($disk >= 1024000) ? number_format(($disk/1024)/1000,1)."GB" : number_format($disk/1024)."MB";
		$uses['4']	= ($traffic >= 1024000) ? number_format(($traffic/1024)/1000,1)."GB" : number_format($traffic/1024)."MB";
		if($uses['1'] < 50) {
			$uses['5'] = '#3366FF';
		} else if($uses['1'] > 50 && $uses['1'] < 80) {
			$uses['5'] = '#FF9900';
		} else if($uses['1'] > 80) {
			$uses['5'] = '#FF0000';
		}
		if($uses['2'] < 50) {
			$uses['6'] = '#3366FF';
		} else if($uses['2'] > 50 && $uses['2'] < 80) {
			$uses['6'] = '#FF9900';
		} else if($uses['2'] > 80) {
			$uses['6'] = '#FF0000';
		}
		return $uses;
	}

	/*----------------------------------------
	 * 메일발송
	 */
	 /*
	function sendMail($to,$from,$subject,$body,$name,$html='N')
	{
		$boundary = '----=='.uniqid(rand(),true);
		$eol = "\n";
		$headers = sprintf(
			"From: %s <%s>".$eol.
			"MIME-Version: 1.0".$eol.
			"Content-Type: multipart/alternative;".$eol."\tboundary=\"%s\"".$eol.$eol.
			"",
		sprintf("%s <%s>", '=?euc-kr?b?'.base64_encode($name).'?=', $to),
		$from,
		$boundary
		);
		$subject = '=?euc-kr?b?'.base64_encode($subject).'?=';
		$text = chunk_split(base64_encode(str_replace(array("<",">","&"), array("&lt;","&gt;","&amp;"), $body)));
		$html = chunk_split(base64_encode($html=='Y' ? nl2br($body) : $body));
		$body = sprintf(
			"--%s".$eol.
			"Content-Type: text/plain; charset=euc-kr; format=flowed".$eol.
			"Content-Transfer-Encoding: base64".$eol.
			"Content-Disposition: inline".$eol.$eol.
			"%s".
			"--%s".$eol.
			"Content-Type: text/html; charset=euc-kr".$eol.
			"Content-Transfer-Encoding: base64".$eol.
			"Content-Disposition: inline".$eol.$eol.
			"%s".
			"--%s--".
			"",
		$boundary,
		$text,
		$boundary,
		$html,
		$boundary
		);
		return mail($to, $subject, $body, $headers);
	}

	*/
		function sendMail($to,$from,$subject,$body,$name,$html='N'){
		$boundary = '----=='.uniqid(rand(),true);
		$eol = "\n";
		$headers = sprintf(
			"From: %s <%s>".$eol.
			"MIME-Version: 1.0".$eol.
			"Content-Type: multipart/alternative;".$eol."\tboundary=\"%s\"".$eol.$eol.
			"",
			sprintf("%s <%s>", '=?utf-8?b?'.base64_encode($name).'?=', $to),
			$from,
			$boundary
		);
		$subject = '=?utf-8?b?'.base64_encode($subject).'?=';
		$text = chunk_split(base64_encode(str_replace(array("<",">","&"), array("&lt;","&gt;","&amp;"), $body)));
		$html = chunk_split(base64_encode($html=='Y' ? nl2br($body) : $body));
		$body = sprintf(
			"--%s".$eol.
			"Content-Type: text/plain; charset=utf-8; format=flowed".$eol.
			"Content-Transfer-Encoding: base64".$eol.
			"Content-Disposition: inline".$eol.$eol.
			"%s".
			"--%s".$eol.
			"Content-Type: text/html; charset=utf-8".$eol.
			"Content-Transfer-Encoding: base64".$eol.
			"Content-Disposition: inline".$eol.$eol.
			"%s".
			"--%s--".
			"",
			$boundary,
			$text,
			$boundary,
			$html,
			$boundary
		);
		return @mail($to, $subject, $body, $headers);
	}
}
?>
