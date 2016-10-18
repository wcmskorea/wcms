<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); };

//['0']관리, ['1']접근, ['2']열람권한, ['3']작성(등록)
if($member->checkPerm(3) === false) { $func->err("회원등록 권한이 없습니다"); }

//리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("['경고']정상적인 접근이 아닙니다."); }
if($_SERVER['REQUEST_METHOD'] == 'GET' ) { $func->err("['경고']정상적인 접근이 아닙니다."); }

//넘어온 값과 변수 동기화 및 validCheck
foreach($_POST AS $key=>$val)
{
	$db->data[$key] = trim($val);
	$db->data['mobile'] = ($_SESSION['mobile']) ? $_SESSION['mobile'] : $db->data['mobile'];

	//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입, 필수항목
	if($key == "oldpasswd") { $func->vaildCheck($val, "현재 비밀번호", "trim", "M"); }
	if($key == "nick") 		{ $func->vaildCheck($val, "회원 닉네임", "nick", $cfg['module']['opt_nick']); }
	if($key == "email") 	{ $func->vaildCheck($val, "회원 이메일", "email", $cfg['module']['opt_email']); }
	if($key == "zipcode") 	{ $func->vaildCheck($val, "우편번호", "trim", $cfg['module']['opt_address']); }
	if($key == "address01") { $func->vaildCheck($val, "주소", "trim", $cfg['module']['opt_addres']); }
	if($key == "address02") { $func->vaildCheck($val, "나머지 주소", "trim", $cfg['module']['opt_address']); }
	if($key == "authcode") 	{ $func->vaildCheck($val, "인증코드", "num", $cfg['module']['opt_mobileAuth']); }
	if($key == "mobile") 	{ $func->vaildCheck($val, "휴대전화 번호", "mobile", $cfg['module']['opt_mobile']); }
	if($key == "phone") 	{ $func->vaildCheck($val, "전화번호", "phone", $cfg['module']['opt_phone']); }
	if($key == "fax") 		{ $func->vaildCheck($val, "팩스번호", "phone", $cfg['module']['opt_fax']); }
	if($key == "receive") 	{ $func->vaildCheck($val, "수신동의", "trim", $cfg['module']['opt_receive']); }
	if($key == "groupName") { $func->vaildCheck($val, "회사(단체)명", "trim", $cfg['module']['opt_groupName']); }
	if($key == "groupNo") 	{ $func->vaildCheck($val, "등록(사업자)번호", "bizno", $cfg['module']['opt_groupNo']); }
	if($key == "ceo") 		{ $func->vaildCheck($val, "대표(담당)자", "trim", $cfg['module']['opt_ceo']); }
	if($key == "status") 	{ $func->vaildCheck($val, "업태", "trim", $cfg['module']['opt_status']); }
	if($key == "class") 	{ $func->vaildCheck($val, "업종", "trim", $cfg['module']['opt_class']); }
	if($key == "religion") 	{ $func->vaildCheck($val, "종교", "trim", $cfg['module']['opt_religion']); }
	if($key == "content") 	{ $func->vaildCheck($val, "기타", "trim", $cfg['module']['opt_content']); }
}

//본인인증 체크
if($cfg['module']['opt_mobileAuth'] != 'N' && $_SESSION['mobileCode'] != $db->data['authcode']) { $func->err("휴대폰 인증코드가 일치하지 않습니다. 다시 확인 후 시도해 주십시오.", "back"); }
//비밀번호 체크
if($db->data['passwd'] && $_POST['repasswd'] && $passwd != $repasswd) { $func->err("입력하신 비밀번호가 일치하지 않습니다.", "back"); }
//닉네임 체크
if($db->data['nick'] && $member->memberRegCheck('nick', $db->data['nick']) === true) { $func->err("(".$db->data['nick'].")은 이미 등록된 닉네임입니다.", "back"); }
//이메일 체크
if($db->data['email'] && $member->memberRegCheck('re_email', $db->data['email']) === true) { $func->err("(".$db->data['email'].")은 이미 등록된 Email입니다.", "back"); }
//사업자번호 체크
if($db->data['bizNo'] && $member->memberRegCheck('bizno', $db->data['groupNo']) === true) { $func->err("(".$db->data['groupNo'].")은 이미 등록된 사업자번호입니다.", "back"); }
//비밀번호 변수할당
if($db->data['repasswd'])
{
	$db->data['passwd']	= $db->data['repasswd'];
	$db->data['passwdModify'] = time();
}
else
{
	$db->data['passwd']	= $db->data['oldpasswd'];
	$memberInfo = $member->memberInfo($_SESSION['uid']);
	$db->data['passwdModify'] = $memberInfo['passwdModify'];
}
$db->data['passwd']	= ($db->data['repasswd']) ? $db->data['repasswd'] : $db->data['oldpasswd'];
//수신동의 변수할당
$db->data['receive'] = ($db->data['receive']) ? 'Y' : 'N';
//수정일자 변수할당
$db->data['dateModify']	= time();
//생일 변수할당
if($db->data['birthyear']) 
{
	$db->data['birth'] = strtotime(date($db->data['birthyear']."-".$db->data['birthmonth']."-".$db->data['birthday'])); //생일
}
//기념일 변수할당
if($db->data['memoryyear'])
{
	$db->data['memory']	= strtotime(date($db->data['memoryyear']."-".$db->data['memorymonth']."-".$db->data['memoryday'])); //기념일
}

//현재 비밀번호 매칭
if($cfg['site']['encrypt'] == 'crypt')
{
	$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` WHERE id='".$_SESSION['uid']."'");
	if(crypt($db->data['oldpasswd'], $Rows['passwd']) != $Rows['passwd']) {
		$func->err("비밀번호가 다르거나 일치하는 회원정보가 존재하지 않습니다.", "back");
	}
}
else
{
	$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` WHERE id='".$_SESSION['uid']."' AND passwd='".$db->passType($cfg['site']['encrypt'], $db->data['oldpasswd'])."'");
	if($db->getNumRows() < 1) { $func->err("현재 비밀번호가 일치하지 않습니다.", "back"); }
}	

//회원 탈퇴 처리
if($db->data['secede'] == 'Y') 
{
	$db->query(" UPDATE `mdMember__account` SET level='0',dateExpire='".time()."' WHERE id='".$Rows['id']."' ");
	if($db->getAffectedRows() > 0) 
	{
		$func->err("회원탈퇴 신청이 정상적으로 완료되었습니다.", "window.location.replace('./');");
	} 
	else {
		$func->err("입력하신 정보가 일치하지 않습니다.", "back");
	}
} 
else 
{
	//종교 변수할당
	if($cfg['module']['opt_religion'] != 'N') {
		$db->data['religion'] = $db->data['religion'].','.$db->data['religion_etc'];
	}
	//전공 변경으로 인한 권한해제
	$db->data['level'] 		= (($Rows['type'] == 'S' || $Rows['type'] == 'F') && $Rows['function'] != $db->data['function']) ? $member->memberBasic() : $Rows['level'];

	//비밀번호 변수할당
	$db->data['passwd']		= $db->passType($cfg['site']['encrypt'], $db->data['passwd']);

	//시간/IP정보 변수할당
	$db->data['info']		= $cfg['timeip'];
	
	//회원 기본정보 변경
	if($db->sqlUpdate("mdMember__account", "id='".$Rows['id']."'", array(), 0)) 
	{
		//회원 부가정보 변경
		$db->sqlUpdate("mdMember__info", "id='".$Rows['id']."'", array(), 0);
		
		/**
		 * 첨부파일 처리
		 */
		require_once		__PATH__."_Lib/classUpLoad.php";
		$up 						= new upLoad($cfg['upload']['dir'], $_FILES, explode(",", $cfg['module']['thumbType'])); //썸네일 생성비율 ['3']['4']=>비율
		$up->count			= $cfg['module']['uploadCount'];
		$up->table			= $cfg['cate']['mode']."__file".$prefix;
		$up->upLoaded		= ($db->data['fileName']) ? array_reverse(explode("|:|", $db->data['fileName'])) : null;
		$up->insertFile	= $db->data['insertFile'];
		$up->insertDb		= 'Y';
		$up->seq				= $_SESSION['useq'];
		$up->upCount		= $up->upCount + $db->data['fileCount'];
		$up->upFiles($_SESSION['uid']);

		//닉네임 변경으로 인한 로그기록
		if($_SESSION['uname'] != $db->data['nick']) 
		{
			$db->data['id']			= $_SESSION['uid'];
			$db->data['nicked']		= $_SESSION['uname'];
			if($db->sqlInsert("mdMember__nick", "INSERT"))
			{
				$_SESSION['uname']	= $db->data['nick'];
			}
		}
		
		//담당업무 변경으로 인한 레벨 조정
		if($db->data['level'] == $Rows['level']) 
		{
			$func->setLog(__FILE__, "회원정보 변경 성공");
			$func->err("정상적으로 변경 되었습니다.", "window.location.replace('./index.php');");
		} else 
		{
			$func->setLog(__FILE__, "회원정보 변경 성공");
			$sess->sessKill();
			$func->err("정보변경으로 승인해제 되었습니다. 재승인 요청하셔야 이용하실 수 있습니다.", "./");
		}
	} else 
	{
		$func->setLog(__FILE__, "회원정보 변경 실패");
		$func->err("현재 비밀번호가 일치하지 않거나, 변경된 회원정보가 없습니다.");
	}
}
?>
