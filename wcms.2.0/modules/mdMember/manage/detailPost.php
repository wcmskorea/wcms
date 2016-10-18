<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("['경고']정상적인 접근이 아닙니다."); }
if($_SERVER['REQUEST_METHOD'] == 'GET' ) { $func->err("['경고']정상적인 접근이 아닙니다."); }

if($_POST['type'] == "post")
{
	// 넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		//${$key} = trim($val);
		$db->data[$key] = trim($val);
		// $func->validCheck(체크할 값, 항목제목, 항목명, 체크타입)
		if($key == "id" 		&& !$_POST['userid'])	$func->vaildCheck($val, "회원 아이디", "userid", "M", "pass");
		if($key == "name"   	&& !$_POST['userid'])	$func->vaildCheck($val, "회원 이름", "korean", "M", "pass");
		//if($key == "passwd" 	&& !$_POST['userid'])	$func->vaildCheck($val, "회원 비밀번호" ,"passwd", "M", "pass");
		if($key == "idcode" 	&& $val)				$func->vaildCheck($val, "회원 주민번호", "idcode", "M", "pass");
		if($key == "email"  	&& $val)				$func->vaildCheck($val, "회원 이메일", "email", "M", "pass");
		if($key == "mobile" 	&& $val)				$func->vaildCheck($val, "회원 휴대전화", "mobile", "M", "pass");
		if($key == "phone"  	&& $val)				$func->vaildCheck($val, "회원 전화번호", "phone", "M", "pass");
		if($key == "fax"    	&& $val)				$func->vaildCheck($val, "회원 팩스번호", "homephone", "M", "pass");
		if($key == "groupno"	&& $val)				$func->vaildCheck($val, "사업자번호", "bizno", "M", "pass");
	}
	/*$db->data['birthyear']  = $db->data['birthyear'] ? $db->data['birthyear'] : date("Y");
	$db->data['birthmonth'] = $db->data['birthmonth'] ? $db->data['birthmonth'] : date("m");
	$db->data['birthday']   = $db->data['birthday'] ? $db->data['birthday'] : date("d");*/

	$Rows									= $member->memberInfo($db->data['userid']);

	if($Rows['id']) //수정
	{
		$db->data['seq']			= $Rows['seq'];
		$db->data['sort']			= ($db->data['sort']) ? $db->data['sort'] : $Rows['sort'];
		$db->data['id']				= $Rows['id'];
		$db->data['nicked']		= $Rows['nick'];

		if($db->data['passwd'])
		{
			$db->data['passwd']		= $db->passType($cfg['site']['encrypt'], $db->data['passwd']);
			$db->data['passwdModify']   = time();                                             //비밀번호변경일시
		}
		else
		{
			$db->data['passwd']         = $Rows['passwd'];
			$db->data['passwdModify']   = $Rows['passwdModify'];
		}
		if($db->data['idcode'])
		{
			$db->data['sex']		= substr($db->data['idcode'], 6, 1);                          //성별
			$age								= substr($db->data['idcode'], 0, 4);                          //나이계산
			$centry							= (substr($db->data['idcode'], 6, 1) > 2) ? 2000 : 1900;      //2000년 이후 출생자 체크
			$age								= substr($db->data['idcode'], 0, 2) + $centry;                //나이 계
			$db->data['age']		= date("Y") - $age;																						//나이 계
			$db->data['birth']	= strtotime($age."-".substr($db->data['idcode'], 2, 2)."-".substr($db->data['idcode'], 4, 2));	//주민등록증 : 생년월일
			$db->data['idcode']	= $db->passType($cfg['site']['encrypt'], $db->data['idcode']);
		} else
		{
			$db->data['idcode']	= $Rows['idcode'];
			$db->data['age']		= ($db->data['birthyear']) ? date("Y")-$db->data['birthyear'] : 0; 								//나이 계산
			$db->data['birth']	= strtotime($db->data['birthyear']."-".$db->data['birthmonth']."-".$db->data['birthday']); 		//생일
			$db->data['age']	= ($db->data['birth'] == 0) ? 0 : $db->data['age'];
		}

		$db->data['week']				= $Rows['week']; //등록요일
		$db->data['dateReg']		= ($Rows['dateReg']) ? $Rows['dateReg'] : time();
		$db->data['dateModify']	= time();
		$msg										= "변경";

		$sql = " SELECT COUNT(*) FROM `mdMember__account` WHERE id<>'".$Rows['id']."' AND LOWER(nick)='".$db->data['nick']."' ";
		if($nick && $db->queryFetchOne($sql) > 0) { $func->err("['".$db->data['nick']."']은(는) 이미 사용중인 닉네임입니다."); }

	} 
	else //신규등록
	{
		$db->data['seq']		= '';
		$db->data['sort']		= ($db->data['sort']) ? $db->data['sort'] : $db->queryFetchOne(" SELECT MAX(A.sort) FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE B.team='".$db->data['team']."' ") + 1;
		$db->data['passwd']	= ($db->data['passwd']) ? $db->passType($cfg['site']['encrypt'], $db->data['passwd']) : "";					//비밀번호
		if($db->data['idcode'])
		{
			$db->data['sex']	= substr($db->data['idcode'], 6, 1);                                							//성별
			$age							= substr($db->data['idcode'], 0, 4);                                							//나이계산
			$centry						= (substr($db->data['idcode'], 6, 1) > 2) ? 2000 : 1900;            							//2000년 이후 출생자 체크
			$age							= substr($db->data['idcode'], 0, 2) + $centry;                      							//나이 계
			$db->data['age']	= date("Y") - $age;																				//나이 계
			$db->data['birth']= strtotime($age."-".substr($db->data['idcode'], 2, 2)."-".substr($db->data['idcode'], 4, 2));	//주민등록증 : 생년월일
		} else
		{
			$db->data['age']	= ($db->data['birthyear']) ? date("Y")-$db->data['birthyear'] : 0; 								//나이 계산
			$db->data['birth']= strtotime($db->data['birthyear']."-".$db->data['birthmonth']."-".$db->data['birthday']); 		//생일
			$db->data['age']	= ($db->data['birth'] == 0) ? 0 : $db->data['age'];
		}
		$db->data['idcode']			= ($db->data['idcode']) ? $db->passType($cfg['site']['encrypt'], $db->data['idcode']) : "";		//주민번호
		$db->data['week']				= date("D"); //등록요일
		$db->data['dateReg']		= time();
		$db->data['dateModify']	= 0;
		$db->data['passwdModify']		= time();
		$msg										= "등록";

		if($db->queryFetchOne(" SELECT COUNT(*) FROM `mdMember__account` WHERE LOWER(id)='".strtolower($db->data['id'])."' ") > 0) { $func->err("이미 사용중인 ['아이디']입니다."); }
		if($nick && $db->queryFetchOne(" SELECT COUNT(*) FROM `mdMember__account` WHERE LOWER(nick)='".strtolower($nick)."' ") > 0) { $func->err("이미 사용중인 ['닉네임']입니다."); }
	}

	if(is_array($_POST['department']))
	{
		$db->data['department'] = '';
		foreach($_POST['department'] AS $val)
		{
			$db->data['department'] .= $val.",";
		}
		$db->data['department'] = preg_replace('/,$/', null, $db->data['department']);
	}

	// 공통
	$db->data['division']		= ($db->data['division']) ? $db->data['division'] : "P";
	$db->data['name']				= $db->data['name'];
	$db->data['email']			= $db->data['email'];
	$db->data['memory']			= strtotime($db->data['memoryyear']."-".$db->data['memorymonth']."-".$db->data['memoryday']); 	//기념일
	$db->data['dateExpire']	= 0;
	$db->data['info']				= $cfg['timeip'];

	// 회원 기본정보 등록
	if($db->sqlInsert("mdMember__account", "REPLACE"))
	{
		// 회원 부가정보 등록
		$db->sqlInsert("mdMember__info", "REPLACE");

		// 회원 닉네임 변경내역
		if($db->data['nicked'] != $db->data['nick'])
		{
			$db->sqlInsert("mdMember__nick", "INSERT");
		}

		$msg = "정상적으로 회원".$msg." 되었습니다.";

		$db->unLock();
		unset($db->data);

		$func->setLog(__FILE__, "회원등록 성공");

		$func->err($msg, "
		parent.$.insert('#left_mdMember','../modules/mdMember/manage/_left.php');
		parent.$.dialogRemove();");
		/*
		$func->err($msg, "
		parent.$.insert('#left_mdMember','../modules/mdMember/manage/_left.php');
		parent.$.insert('#module', '../modules/mdMember/manage/_controll.php?lev=".$db->data['level']."&amp;type=memList',300);
		parent.$.dialogRemove();");
		*/

	} else
	{
		$db->query(" DELECT FROM `mdMember__account` WHERE id='".$db->data['id']."' ");
		$db->query(" OPTIMIZE TABLE `mdMember__account` ");
		$func->setLog(__FILE__, "회원등록 실패");
		$func->err("회원정보 입력실패 입니다. 관리자에게 문의바랍니다.");
	}
} else
{
	$func->err("잘못된 접근입니다.");
}
?>
