<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
$type = ($_GET['type']) ? $_GET['type'] : $_POST['type'];

switch(__CATE__) {
	//로그인 & 로그아웃
	case '000002001' :
		switch($sess->decode($type))
		{
			case 'loginAccess' :
				@ob_end_clean();
				require __PATH__."modules/mdMember/logAccess.php";
				break;
			case 'loginAjax' :
				if($_SESSION['uid'])
				{
					@ob_end_clean();
					$func->err("이미 로그인 상태입니다.");
				}
				require __PATH__."modules/mdMember/loginAjax".($cfg['skin']=="mobile" ? "Mobile":"").".php";
				break;
			default :
				if($_SESSION['uid'])
				{
					@ob_end_clean();
					require __PATH__."modules/mdMember/logOut.php";
				} else if($type == 'loginAjax')
				{
					require __PATH__."modules/mdMember/loginAjax.php";
				} else
				{
					if($_SESSION['uid']) { $func->err("이미 로그인된 상태입니다.", "location.replace('/index.php')"); }
					require __PATH__."modules/mdMember/login.php";
				}
				break;
		}
		break;
	//회원등록 & 정보수정
	case '000002002' :
		switch($sess->decode($type)) {
			case 'registAgree' :
				require __PATH__."modules/mdMember/registCheckAgree.php";
				break;
			case 'registForm' ://cmVnaXN0Rm9ybQ==
				if($_SESSION['uid'] && !$_SESSION['author']) {
					require __PATH__."modules/mdMember/modifyForm".($cfg['skin']=="mobile" ? "Mobile":"").".php";
				} else {
					require __PATH__."modules/mdMember/registForm".($cfg['skin']=="mobile" ? "Mobile":"").".php";
				}
				break;
			case 'registCheck' : case 'registPost' :
				@ob_end_clean();
				require __PATH__."modules/mdMember/registPost.php";
				break;
			case 'modifyPost' :
				@ob_end_clean();
				require __PATH__."modules/mdMember/modifyPost.php";
				break;
			case 'passwdExpire' :
				require __PATH__."modules/mdMember/passwdExpire.php";
				break;
			case 'passwdChangePost' :
				require __PATH__."modules/mdMember/passwdChangePost.php";
				break;

			default:
				//회원정보 수정
				if($_SESSION['uid'] && !$_SESSION['author']) {
					require __PATH__."modules/mdMember/modifyForm".($cfg['skin']=="mobile" ? "Mobile":"").".php";
				} else {
					//회원가입 - 본인인
					if($_SESSION['virtualNo'] && $_SESSION['realName'] && !$_SESSION['author']) {
						require __PATH__."modules/mdMember/registCheckAgree.php";
					}
					//회원 로그인 - 본인인증
					else if($_SESSION['virtualNo'] && $_SESSION['realName'] && $_SESSION['author']) {
						if($member->memberRegCheck('idcode', $_SESSION['virtualNo']) == true) {
							@ob_end_clean();
							session_destroy();
							$func->err("이미 등록된 개인식별코드 번호입니다.");
						} else {
							$db->query(" UPDATE `mdMember__account` SET name='".$_SESSION['realName']."',idcode='".$_SESSION['virtualNo']."' WHERE id='".$_SESSION['author']."' ");
							$func->err("본인 인증이 완료되었습니다.", "./?cate=000002001");
						}
					} else {
						require __PATH__."modules/mdMember/regist".($cfg['skin']=="mobile" ? "Mobile":"").".php";
					}
				}
				break;
			}
		break;
	//아이디&비밀번호 찾기
	case '000002003' :
		if($cfg['module']['auth'] == 'email') {
			require __PATH__."modules/mdMember/findEmail.php";
		} else if($cfg['module']['auth'] == 'bizno') {
			require __PATH__."modules/mdMember/findBiz.php";
		} else {
			require __PATH__."modules/mdMember/findBasic.php";
		}
		break;

	default:
		$func->err("해당 모듈은 존재하지 않습니다.", "history.back()");
		break;
}
?>
