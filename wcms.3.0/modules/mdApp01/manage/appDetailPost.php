<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
//리퍼러 체크
if(!preg_match("/".$_SERVER['HTTP_HOST']."/",$_SERVER['HTTP_REFERER'])) { $func->err("['경고']정상적인 접근이 아닙니다."); }

if($_POST['type'] == 'mpost')
{
	$config = $db->queryFetch("SELECT * FROM `mdApp01__` WHERE cate='".__CATE__."'");
	$contentAdd = (array)unserialize($config['contentAdd']);

	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		if(is_array($val)) { $val = implode("|",$val);}
		${$key} = trim($val);

		#--- $func->vaildCheck(체크할 값, 항목제목, 항목명, 체크타입, 필수항목)
		if($key == "name")      $func->vaildCheck($val, "신청자명", "trim", $contentAdd['opt_name']);
		if($key == "email")     $func->vaildCheck($val, "회원 이메일", "email", $contentAdd['opt_email']);
		if($key == "mobile")    $func->vaildCheck($val, "휴대전화 번호", "mobile", $contentAdd['opt_mobile']);
		if($key == "phone")     $func->vaildCheck($val, "전화번호", "phone", $contentAdd['opt_phone']);
		if($key == "division")  $func->vaildCheck($val, "상담구분", "trim", $contentAdd['opt_division']);
		if($key == "content")   $func->vaildCheck($val, "상세내용(기타)", "trim", $contentAdd['opt_content']);

		$db->data[$key] = trim($val);
		$db->data['contentAdd'][$key] = trim($val);
	}

	$currentPage = $db->data['currentPage'];

	unset($db->data['contentAdd']['cate'], $db->data['contentAdd']['type'],$db->data['contentAdd']['idx'], $db->data['contentAdd']['dateReg'], $db->data['contentAdd']['fileName'],$db->data['contentAdd']['division'],$db->data['contentAdd']['state'],$db->data['contentAdd']['content'],$db->data['contentAdd']['answers'],$db->data['currentPage']);

	$db->data['seq']  = ($_POST['idx']) ? $_POST['idx'] : null;
	$db->data['cate'] = __CATE__;
	$db->data['id']   = ($_POST['id']) ? $_POST['id'] : '';		//예약수정시 관리자 아이디로 업데이트 방지(20120508)
	$db->data['division'] = $_POST['division'];
	if($db->data['schedulemonth'] || $db->data['scheduleday'] || $db->data['scheduleyear'])		//상담내역 변경시 schedule값 있을때만 변경(20120517)
		$db->data['schedule'] = mktime($db->data['schedulehour'], $db->data['schedulemin'], $db->data['schedulesec'], $db->data['schedulemonth'], $db->data['scheduleday'], $db->data['scheduleyear']);
	$db->data['contentAdd']  = serialize($db->data['contentAdd']);
	$db->data['dateModify']  = time();
	$db->data['state'] = $state;
	$db->data['info'] = $cfg['timeip'];
	$db->data['state'] = $state;

	$db->data['content'] = mysql_real_escape_string($_POST['content']);
	$db->data['contentAnswers'] = mysql_real_escape_string($_POST['answers']);

	// search 필드는 검색에 필요한 필드로 검색항목에 따라 변경이 되어야 한다.
	$db->data['search'] = $name."|".str_replace("-","",$mobile)."|".str_replace("-","",$phone)."|".$email."|".str_replace("-","",$idcode);

	$db->sqlUpdate("mdApp01__content", "seq='".$_POST[idx]."'", array('cate','seq'), 0);


	$result		= explode(",", $config['result']);
	foreach($result AS $key=>$val)
	{
		if($key == $state)
			$stateName = $val; //처리구분
	}
	$stateName = $stateName ? $stateName : "완료";

	$cateInfo = $category->getCategoryInfo(__CATE__,$config['skin']);
	$cateName  = $cateInfo['name'] ? $cateInfo['name'] : '상담.문의';

	if($func->checkModule('mdSms') && $_POST['sendSMS'] == 'send')
	{
		if($_POST['answers'])//답변내용이 있을때
		{
			$sock->sender		= $cfg['site']['phone'];
			$sock->smsSend($db->data['mobile'], $_POST['answers']);
			$sock->varReset();
		}
		else
		{
			$sock->tempMode = "mdApp01";
			$sock->tempArray	= array($db->data['name'], $cateName, $stateName,$cfg['site']['siteName']); //{회원명},{카테고리},{처리구분명},{사이트}
			$sock->smsSend($db->data['mobile'], "temp03");
			$sock->varReset();
		}
	}

	$func->err("정상적으로 변경 되었습니다.", "
	parent.$.dialogRemove();
	parent.$.insert('#module', '../modules/mdApp01/manage/_controll.php', '&type=list&cate=".__CATE__."&state=".$state."&currentPage=".$currentPage."',300);
	parent.$.insert('#left_mdApp01','../modules/mdApp01/manage/_left.php');
	");

} else if($_GET['type'] == 'dpost') {

	$Rows = $db->queryFetch(" SELECT * FROM `mdApp01__content` WHERE seq='".$_GET['idx']."' ");
	if($Rows['seq'])
	{
		//연관 첨부파일 삭제
		$db->query(" SELECT * FROM `mdApp01__file` WHERE parent='".$Rows['seq']."' ORDER BY date ASC ");
		$n = 1;
		while($sRows = $db->fetch())
		{
			@unlink($cfg['upload']['dir'].date("Y",$sRows['regDate'])."/".date("m",$sRows['regDate'])."/".$sRows['fileName']);
			if($n == 1) { $autoNum = $sRows['seq']; }
			$n++;
		}
		$db->query(" DELETE FROM `mdApp01__file` WHERE parent='".$Rows['seq']."' ");
		if($autoNum) { $db->query(" ALTER TABLE `mdApp01__file` AUTO_INCREMENT=".$autoNum); }
    	$db->query(" DELETE FROM `mdApp01__content` WHERE seq='".$Rows['seq']."' ");
    	$db->query(" UPDATE `mdApp01__content` SET seq=seq-1 WHERE seq>'".$Rows['seq']."' ");
    	$db->query(" OPTIMIZE TABLES `mdApp01__content` ");
    	$func->ajaxMsg("정상적으로 삭제되었습니다.", "
    	$.dialogRemove();
    	$.insert('#module', '../modules/mdApp01/manage/_controll.php?type=list&cate=".$Rows['cate']."&state=".$Rows['state']."&currentPage=".$currentPage."',null,300);
    	$.insert('#left_mdApp01','../modules/mdApp01/manage/_left.php');
    	", 20);
	}
}
?>