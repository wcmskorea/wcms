<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); };

//[0]관리, [1]접근, [2]열람권한, [3]작성(등록)
if($member->checkPerm(3) === false) { $func->err("회원등록 권한이 없습니다"); }

//리퍼러 체크
$func->checkRefer("POST");

//입력 옵션 설정병합
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

//실명인증 처리
if($sess->decode($type) == 'registCheck')
{

	if(!$_POST['agree1']) { $func->err("회원이용약관 동의를 하셔야합니다.", "back"); }
	if(!$_POST['agree2']) { $func->err("개인정보취급방침 동의를 하셔야합니다.", "back"); }

	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
		//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입, 필수항목
		if($key == "username") { $func->vaildCheck($val, "이름", "korean", "M"); }
		if($key == "usercode") { $func->vaildCheck($val, "주민번호", "jumin", "M"); }
	}

	//주민번호 체크
	if($_POST['usercode'] && $member->memberRegCheck('idcode', $usercode) == true) { $func->err("이미 등록된 개인식별코드 번호입니다.", "back"); }
	$_SESSION["virtualNo"] = $usercode;
	$_SESSION["realName"] = $username;
	$func->err("('".$_SESSION['realName']."')님 실명확인 되었습니다. 회원가입 서식작성 페이지로 이동합니다.", "window.location.replace(".$_SERVER['PHP_SELF']."?cate=".__CATE__."&type=".$sess->encode('registAgree').");");

}
else {

	//회원가입 등록
	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		$db->data[$key] 		= trim($val);
		$db->data['name'] 		= ($_SESSION['realName']) ? $_SESSION['realName'] : $db->data['name'];
		$db->data['idcode'] 	= ($_SESSION['virtualNo']) ? $_SESSION['virtualNo'] : $db->data['idcode'];
		$db->data['mobile'] 	= ($_SESSION['mobile']) ? $_SESSION['mobile'] : $db->data['mobile'];

		//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입, 필수항목)
		if($key == "division") 		{ $func->vaildCheck($val, "회원구분", "trim",  $cfg['module']['opt_division']); }
		if($key == "name") 			{ $func->vaildCheck($val, "회원 이름", "korean",  $cfg['module']['opt_name']); }
		if($key == "idcode" && $_SESSION['virtualNo']) { $func->vaildCheck($val, "개인식별코드", "trim",  $cfg['module']['opt_idcode']); }
		if($key == "idcode" && !$_SESSION['virtualNo']) { $func->vaildCheck($val, "주민번호", "trim", $cfg['module']['opt_idcode']); }
		if($key == "userid") 		{ $func->vaildCheck($val, "회원 아이디", "userid", "M"); }
		if($key == "passwd") 		{ $func->vaildCheck($val, "비밀번호", "trim", "M"); }
		if($key == "repasswd") 		{ $func->vaildCheck($val, "비밀번호 확인", "trim", "M"); }
		if($key == "nick") 			{ $func->vaildCheck($val, "회원 닉네임", "nick", $cfg['module']['opt_nick']); }
		if($key == "email") 		{ $func->vaildCheck($val, "회원 이메일", "email", $cfg['module']['opt_email']); }
		if($key == "zipcode") 		{ $func->vaildCheck($val, "우편번호", "trim", $cfg['module']['opt_address']); }
		if($key == "address01") 	{ $func->vaildCheck($val, "주소", "trim", $cfg['module']['opt_address']); }
		if($key == "address02") 	{ $func->vaildCheck($val, "나머지 주소", "trim", $cfg['module']['opt_address']); }
		if($key == "authcode") 		{ $func->vaildCheck($val, "인증코드", "num", $cfg['module']['opt_mobileAuth']); }
		if($key == "phone") 		{ $func->vaildCheck($val, "전화번호", "phone", $cfg['module']['opt_phone']); }
		if($key == "fax") 			{ $func->vaildCheck($val, "팩스번호", "phone", $cfg['module']['opt_fax']); }
		if($key == "mobile") 		{ $func->vaildCheck($val, "휴대전화 번호", "mobile", $cfg['module']['opt_mobile']); }
		if($key == "receive") 		{ $func->vaildCheck($val, "수신동의", "trim", $cfg['module']['opt_receive']); }
		if($key == "groupName") 	{ $func->vaildCheck($val, "회사(단체)명", "trim", $cfg['module']['opt_groupName']); }
		if($key == "groupNo") 		{ $func->vaildCheck($val, "등록(사업자)번호", "bizno", $cfg['module']['opt_groupNo']); }
		if($key == "ceo") 			{ $func->vaildCheck($val, "대표(담당)자", "trim", $cfg['module']['opt_ceo']); }
		if($key == "status") 		{ $func->vaildCheck($val, "업태", "trim", $cfg['module']['opt_status']); }
		if($key == "class") 		{ $func->vaildCheck($val, "업종", "trim", $cfg['module']['opt_class']); }
		if($key == "religion") 	{ $func->vaildCheck($val, "종교", "trim", $cfg['module']['opt_religion']); }
		if($key == "content") 		{ $func->vaildCheck($val, "자기소개", "trim", $cfg['module']['opt_content']); }
	}

	//본인인증 체크
	if($cfg['module']['opt_mobileAuth'] != 'N' && $_SESSION['mobileCode'] != $_POST['authcode']) { $func->err("휴대폰 인증코드가 일치하지 않습니다. 다시 확인 후 시도해 주십시오.", "back"); }
	//비밀번호 체크
	if($db->data['passwd'] != $db->data['repasswd']) { $func->err("입력하신 비밀번호가 일치하지 않습니다.", "back"); }
	//주민번호 체크
	if($_SESSION['virtualNo'] && $member->memberRegCheck('idcode', $_SESSION['virtualNo']) == true) { $func->err("이미 등록된 개인식별코드입니다.", "back"); }
	//if(!$_SESSION['virtualNo'] && $cfg['module']['opt_nick'] != 'N' && $member->memberRegCheck('idcode', $db->data['idcode']) == true) { $func->err("이미 등록된 주민번호입니다.", "back"); }
	//아이디 체크
	if($member->memberRegCheck('id', $db->data['id']) == true) {$func->err("이미 등록된 아이디입니다.", "back"); }
	//닉네임 체크
	if($db->data['nick'] && $member->memberRegCheck('nick', $db->data['nick']) == true) { $func->err("이미 등록된 닉네임입니다.", "back"); }
	//이메일 체크
	if($db->data['email'] && $member->memberRegCheck('email', $db->data['email']) == true) { $func->err("이미 등록된 Email입니다.", "back"); }
	//사업자번호 체크
	if($db->data['groupNo'] && $member->memberRegCheck('bizno', $db->data['groupNo']) == true) { $func->err("이미 등록된 사업자번호입니다.", "back"); }
	//고유번호 변수할당
	$db->data['seq']		= '';
	//회원분류 변수할당
	$db->data['division']	= ($db->data['division']) ? $db->data['division'] : "P";
	//회원등급 변수할당
	$db->data['level']		= $member->memberBasic();
	//회원암호 변수할당
	$db->data['passwd']		= $db->passType($cfg['site']['encrypt'], $db->data['passwd']);
	if($db->data['idcode'])
	{
		//회원성별 변수할당
		$db->data['sex']	= substr($db->data['idcode'], 6, 1);
		$age							= substr($db->data['idcode'], 0, 4);
		$centry						= (substr($db->data['idcode'], 6, 1) > 2) ? 2000 : 1900;
		$age							= substr($db->data['idcode'], 0, 2) + $centry;
		//회원나이 변수할당
		$db->data['age']	= date("Y") - $age;
		//회원생년월일 변수할당
		$db->data['birth']	= strtotime($age."-".substr($db->data['idcode'], 2, 2)."-".substr($db->data['idcode'], 4, 2));
		//회원주민번호 변수할당
		$db->data['idcode']	= $db->passType($cfg['site']['encrypt'], $db->data['idcode']);
	}
	if($db->data['birthyear'])
	{
		//회원식별코드 변수할당
		$db->data['idcode']	= $Rows['idcode'];
		//회원나이 변수할당
		$db->data['age']	= ($db->data['birthyear']) ? date("Y")-$db->data['birthyear'] : 0;
		//회원생일 변수할당
		$db->data['birth']	= strtotime($db->data['birthyear']."-".$db->data['birthmonth']."-".$db->data['birthday']);
	}
	//회원가입요일 변수할당
	$db->data['week']		= date("D");
	//회원등록일 변수할당
	$db->data['dateReg']		= time();
	$db->data['dateModify']	= time();
	$db->data['passwdModify']	= time();
	//회원기념일 변수할당
	$db->data['memory']		= strtotime($db->data['memoryyear']."-".$db->data['memorymonth']."-".$db->data['memoryday']);
	//회원수신동의 변수할당
	$db->data['receive']	= ($db->data['receive']) ? 'Y' : 'N';
	//시간/IP정보 변수할당
	$db->data['info']		= $cfg['timeip'];
	//종교 변수할당
	if($cfg['module']['opt_religion'] != 'N') 
	{
		$db->data['religion'] = $db->data['religion'].','.$db->data['religion_etc'];
	}

	/**
	 * 첨부파일 처리
	 */
	require_once	__PATH__."_Lib/classUpLoad.php";
	$up             = new upLoad($cfg['upload']['dir'], $_FILES, explode(",", $cfg['module']['thumbType'])); //썸네일 생성비율 ['3']['4']=>비율
	$up->count      = $cfg['module']['uploadCount'];
	$up->table      = $cfg['cate']['mode']."__file".$prefix;
	$up->upLoaded   = ($db->data['fileName']) ? array_reverse(explode("|:|", $db->data['fileName'])) : null;
	$up->insertFile = $db->data['insertFile'];
	$up->insertDb   = 'Y';
	$up->seq        = $db->data['seq'];

	//썸네일 비율처리 2012-01-17 강인
	if($cfg['module']['thumbIsFix']){ $up->thumbIsFix = $cfg['module']['thumbIsFix'];}

	$up->small				= $cfg['module']['thumbSsize'];
	$up->middle				= $cfg['module']['thumbMsize'];
	$up->large				= $cfg['module']['thumbBsize'];
	$up->smallHeight	= $cfg['module']['thumbSsizeHeight'];
	$up->middleHeight	= $cfg['module']['thumbMsizeHeight'];
	$up->largeHeight	= $cfg['module']['thumbBsizeHeight'];

	$up->upFiles();

	//회원 기본정보 등록
	if($db->sqlInsert("mdMember__account", "INSERT"))
	{
		//등록된 seq(고유번호) 색출
		$seq = $db->getLastID();

		//회원 부가정보 등록
		$db->sqlInsert("mdMember__info", "INSERT");
		$db->sqlInsert("mdMember__nick", "INSERT");

		//SMS(Socket) 문자 발송
		if($func->checkModule('mdSms') && $cfg['module']['sms'] != 'N')
		{
			$sock->tempMode = "mdMember";
			switch($cfg['module']['sms'])
			{
				case "M" : /*신청자만*/
					$sock->tempArray	= array($db->data['name'], $cfg['site']['domain']);
					$sock->smsSend($db->data['mobile'], "temp02");
					break;
				case "O" : /*운영자만*/
					$sock->sender	    = $db->data['mobile'];
					$sock->tempArray	= array($db->data['name'], $db->data['id'], $cfg['cate']['domain']);
					$sock->smsSend($cfg['site']['mobile'], "temp03");
					break;
				case "B" : /*둘다전송*/
					$sock->tempArray	= array($db->data['name'], $cfg['site']['domain']);
					$sock->smsSend($db->data['mobile'], "temp02");
					$sock->sender	    = $db->data['mobile'];
					$sock->tempArray	= array($db->data['name'], $db->data['id'], $cfg['cate']['domain']);
					$sock->smsSend($cfg['site']['mobile'], "temp03");
					break;
			}
		}

		//회원 적립금 적립여부(2013-08-29)
		if($func->checkModule('mdMileage') && $cfg['mileage']['mileageUse'] == 'Y')
		{
			if($cfg['mileage']['mileageReg'] > 0)
			{
				$data = array("mileageType"=>"A", "mileage"=>$cfg['mileage']['mileageReg'], "code"=>"007002", "memo"=>"회원가입 축하 적립금 지급", "id"=>$db->data['id']);
				if($mileage->MileageInsert($data))
				{
					$msg = "정상적으로 회원등록 되었습니다.\\n\\n 가입축하『".number_format($data['mileage'])."』적립금 적립!";
				}
			}

			//회원 추천시 적립여부
			if($cfg['mileage']['mileageRec'] > 0 && $db->data['recom'])
			{
				$data = array("mileageType"=>"A", "mileage"=>$cfg['mileage']['mileageRec'], "code"=>"007002", "memo"=>"신규회원 추천자 등록시 적립금 지급", "id"=>$db->data['recom']);
				if($mileage->MileageInsert($data))
				{
					$msg .= "\\n\\n 추천인에게 『".number_format($data['mileage'])."』적립금 적립!";
				}
			}
		}
		else
		{
			$msg = ($db->data['memType'] == 'C') ? "회원등록 되었습니다. 교사 및 교내직원은 별도의 승인전까지 일반회원 등급입니다." : "정상적으로 회원등록 되었습니다.";
		}

		//자동 로그인 처리 : 2012년 7월 12일 목요일
		$_SESSION['useq']				= $Rows = $db->queryFetchOne(" SELECT seq FROM `mdMember__account` WHERE id='".$db->data['id']."' ");
		$_SESSION['uid'] 				= $db->data['id'];
		$_SESSION['uname'] 			= $db->data['name'];
		$_SESSION['ulevel'] 		= $db->data['level'];
		$_SESSION['uposition'] 	= $Rows = $db->queryFetchOne(" SELECT position FROM `mdMember__level` WHERE level='".$db->data['level']."' ");
		$_SESSION['utype'] 			= $db->data['division'];
		$_SESSION['urecom']			= $db->data['recom'];

		//접속 로그 등록
		$db->query(" INSERT INTO `mdMember__log` (id,login,ip) VALUES ('".$db->data['id']."',NOW(),'".$_SERVER['REMOTE_ADDR']."') ");

		$func->setLog(__FILE__, "회원가입 성공");
		/*if(__URI__)
		{
			$func->err($msg."\\n\\n 자동으로 로그인 되었습니다. 감사합니다!", "window.location.replace('".urldecode(__URI__)."');");
		}
		else {*/
			$func->err($msg."\\n\\n 자동으로 로그인 되었습니다. 감사합니다!", "window.location.replace('./index.php');");
		//}

	}
	else {

		$db->query(" DELECT FROM `mdMember__account` WHERE id='".$userid."' ");
		$db->query(" OPTIMIZE TABLE `mdMember__account` ");

		$func->setLog(__FILE__, "회원가입 실패");

		$func->err("회원정보 입력실패 입니다. 관리자에게 문의바랍니다.", "back");

	}
}
?>
