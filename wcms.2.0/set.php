<?php
/**
 * Web Contents Management System
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * @Certification Keywords : manage,admin,Admin,login
 */

/**
 * Configration
 */
require_once "./_config.php";

/**
 * 빌더 셋팅 시작
 */
$display->mode = "setup";

if(!$_POST['type'] || $sess->decode($_POST['type']) != 'postSetting' & $sess->decode($_POST['type']) != 'postSeted')
{
	/**
	 * Header 출력
	 */
	$display->title = 'WCMS 신규 사이트 생성하기';
	$display->loadHeader("setup");

	/**
	 * Layout 출력
	 */
	include "./addon/system/setForm.php";

	/**
	 * Footer 출력
	 */
	$display->loadFooter();

}
else if($sess->decode($_POST['type']) == 'postSeted' || $sess->decode($_POST['type']) == 'postSetting')
{
	$sock		= new Syssock($_POST['userid'], $_POST['authCode']);
	$sock->type	= "memberCheck";
	$socket		= $sock->memCheck($_POST['userid'], $_POST['userpasswd']);
	if($socket['code'] == '00' && $sess->decode($_POST['type']) == 'postSetting')
	{
		//넘어온 값과 변수 동기화 및 validCheck
		foreach($_POST AS $key=>$val)
		{
			$db->data[$key] = trim($val);
			//$func->validCheck(체크할 값, 항목제목, 항목명, 체크타입, 필수항목
			if($key == "name") 		$func->vaildCheck($val, "회원 이름", "username", "korean", "M");
			if($key == "id")   		$func->vaildCheck($val, "회원 아이디", "userid", "userid", "M");
			if($key == "passwd")    $func->vaildCheck($val, "비밀번호" ,"passwd", "trim", "M");
			if($key == "repasswd")  $func->vaildCheck($val, "비밀번호 확인" ,"repasswd", "trim", "M");
			if($key == "idcode")    $func->vaildCheck($val, "회원 주민번호", "idcode", "idcode", "M");
			if($key == "email")     $func->vaildCheck($val, "회원 이메일", "email", "email", "M");
			if($key == "zipcode")   $func->vaildCheck($val, "우편번호", "zipcode", "trim");
			if($key == "address01") $func->vaildCheck($val, "주소", "address01", "trim");
			if($key == "address02") $func->vaildCheck($val, "나머지 주소", "address02", "trim");
			if($key == "mobile")    $func->vaildCheck($val, "휴대전화 번호", "mobile", "mobile", "M");
			if($key == "phone")     $func->vaildCheck($val, "전화번호", "phone", "phone");
			if($key == "company" && $val)  $func->vaildCheck($val, "회사명", "company", "trim");
			if($key == "bizno"   && $val)  $func->vaildCheck($val, "사업자번호", "bizno", "bizno");
		}
		if(!$_POST['id']) { $func->err("[아이디] 필수항목은 꼭 작성하셔야합니다.","parent.$('input['@name=id']').select()"); }
		if(!$_POST['passwd']) { $func->err("[비밀번호] 필수항목은 꼭 작성하셔야합니다.","parent.$('input['@name=passwd']').select()"); }
		if(!$_POST['idcode']) { $func->err("[주민번호] 필수항목은 꼭 작성하셔야합니다.","parent.$('input['@name=idcode']').select()"); }
		if(!$_POST['email']) { $func->err("[이메일] 필수항목은 꼭 작성하셔야합니다.","parent.$('input['@name=email']').select()"); }

		$db->data['date']		= time(); //등록일자
		$db->data['week']		= date("D"); //등록요일
		$db->data['birth']		= substr($db->data['idcode'], 0, 6); //생일
		$db->data['sex']		= substr($db->data['idcode'], 6, 1); //성별
		$centry					= (substr($db->data['idcode'], 6, 1) > 2) ? 2000 : 1900; //2000년 이후 출생자를 위한
		$age					= substr($db->data['idcode'], 0, 2) + $centry; //나이 계
		$db->data['age']		= ($db->data['idcode']) ? date("Y") - $age : 0; //나이 계
		$db->data['receive']	= 'Y'; //수신동의
		$db->data['url']		= 'http://www.'.__HOST__;

		$sock->memRegist($db->data);
		/* 10억홈피 회원등록 : End */
	}
	else if($socket['code'] == '01' && $sess->decode($_POST['type']) == 'postSeted')
	{
		/* 회원가입 입력항목 옵션 체크  */
		if(!$_POST['userid']) { $func->err("[아이디] 필수항목은 꼭 작성하셔야합니다.","parent.$('input['@name=userid']').select()"); }
		if(!$_POST['userpasswd']) { $func->err("[비밀번호] 필수항목은 꼭 작성하셔야합니다.","parent.$('input['@name=userpasswd']').select()"); }

		$sock->type			= "memberInfo";
		$db->data			= $sock->memCheck($_POST['userid']);
		if(!$db->data['id'])
		{
			$func->err("[1]존재하지 않는 회원이거나, 비밀번호가 일치하지 않습니다.", "back");
		}
		else
		{
			foreach($db->data AS $key=>$val)
			{
				$db->data[$key] = iconv('CP949', 'UTF-8//IGNORE', $val);
			}
			$db->data['authCode'] = trim($_POST['authCode']);
		}
	}
	else
	{
		$func->err("[".$socket['code']."] ".iconv('CP949', 'UTF-8//IGNORE', $socket['msg'])." 입니다.", "back");
	}

    /**
	 * 공통 데이터베이스 설정
	 */
    if($db->data['id'] == 'wcmskorea')
    {
        $db->query("CREATE DATABASE IF NOT EXISTS `commonSql`");
	    $db->selectDB('commonSql');
        $db->query("CREATE TABLE IF NOT EXISTS `site__zipcode` (
            `seq` int(10) unsigned NOT NULL auto_increment,
            `zipcode` varchar(7) NOT NULL default '',
            `sido` varchar(4) NOT NULL default '',
            `gugun` varchar(13) NOT NULL default '',
            `dong` varchar(44) NOT NULL default '',
            `bunji` varchar(30) NOT NULL default '',
        PRIMARY KEY  (`seq`),
        KEY `sido` (`sido`),
        KEY `gugun` (`gugun`),
        KEY `dong` (`dong`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $db->query("load data local infile '".$_SERVER['DOCUMENT_ROOT']."_Admin/sql/zipcode.sql' into table `site__zipcode`");

        $db->query("CREATE TABLE IF NOT EXISTS `site__calendar` (
          `num` int(11) NOT NULL AUTO_INCREMENT,
          `lunar_date` date NOT NULL DEFAULT '0000-00-00',
          `solar_date` date NOT NULL DEFAULT '0000-00-00',
          `yun` tinyint(1) NOT NULL DEFAULT '0',
          `ganji` varchar(5) NOT NULL DEFAULT '',
          `memo` varchar(50) NOT NULL DEFAULT '',
          PRIMARY KEY (`num`),
          KEY `lunar_date` (`lunar_date`),
          KEY `solar_date` (`solar_date`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $db->query("load data local infile '".$_SERVER['DOCUMENT_ROOT']."_Admin/sql/calendar.sql' into table `site__calendar`");
    }

	/**
	 * 디렉토리 복사 및 권한설정
	 */
	@mkdir(__PATH__."/_Site/".__DB__);
	$func->dirCopy("./_Site/".$cfg['default'], "./_Site/".__DB__, true);
	shell_exec(" ./_Lib/modch.sh 707 ./_Site/".__DB__."/default/data ");
	@shell_exec(" ./_Lib/modch.sh 707 ./_Site/".__DB__."/default/inc ");
	@shell_exec(" ./_Lib/modch.sh 707 ./_Site/".__DB__."/mobile/data ");
	@shell_exec(" ./_Lib/modch.sh 707 ./_Site/".__DB__."/mobile/inc ");
	@shell_exec(" ./_Lib/find.sh ./_Site/".__DB__." -name '*.php' -exec ./_Lib/ownch.sh acehost.acehost {} \; ");
	@shell_exec(" ./_Lib/find.sh ./_Site/".__DB__." -name '*.php' -exec ./_Lib/modch.sh 644 {} \; ");
	@shell_exec(" ./_Lib/find.sh ./_Site/".__DB__."/default/cache -name '*.php' -exec ./_Lib/ownch.sh daemon.daemon {} \; ");
	@shell_exec(" ./_Lib/find.sh ./_Site/".__DB__."/mobile/cache -name '*.php' -exec ./_Lib/ownch.sh daemon.daemon {} \; ");
	$cfg = array_merge($cfg, (array)parse_ini_file(__PATH__.'/_Site/'.$cfg['default'].'/'.$cfg['skin'].'/cache/config.ini.php', true));

	/**
	 * 사이트 정보 등록
	 */
	$cfg['site']['domain']		= __HOST__;
	$cfg['site']['id']			= $db->data['id'];
	$cfg['site']['authCode']	= $db->data['authCode'];
	$cfg['site']['siteName']	= $db->data['groupName'];
	$cfg['site']['ceo']			= $db->data['ceo'];
	$cfg['site']['groupName']	= $db->data['groupName'];
	$cfg['site']['groupNo']		= $db->data['groupNo'];
	$cfg['site']['status']		= $db->data['status'];
	$cfg['site']['class']		= $db->data['class'];
	$cfg['site']['ecommerceNo']	= $db->data['econo'];
	$cfg['site']['phone']		= $db->data['phone'];
	$cfg['site']['fax']			= $db->data['fax'];
	$cfg['site']['address']		= '('.$db->data['zipcode'].') '.$db->data['address01'].' '.$db->data['address02'];
	$cfg['site']['operator']	= $db->data['name'];
	$cfg['site']['mobile']		= $db->data['mobile'];
	$cfg['site']['email']		= $db->data['email'];
	$cfg['site']['dateReg']		= time();
	$cfg['site']['info']		= $cfg['timeip'];

	require_once __PATH__."_Lib/classConfig.php";
	$config = new Config($cfg);
	$config->configMake();
	$config->configSave(__PATH__.'_Site/'.__DB__.'/'.$cfg['skin'].'/cache/config.ini.php');

    /**
	 * 데이터베이스 생성
	 */
	$db->query("CREATE DATABASE IF NOT EXISTS `".__DB__."`");
	$db->selectDB(__DB__);

	/**
	 * 사이트 정보 테이블 생성
	 */
	include "./_Admin/sql/default.sql.php";
	foreach($sql['site'] AS $val) { $db->query(trim($val)); }

	/**
	 * 회원 테이블 생성
	 */
	//include "./modules/mdMember/manage/_sql.php";
	//foreach($sql['mdMember'] AS $val) { $db->query(trim($val)); }

	/**
	 * 기본 지원 솔루션 자동 셋팅
	 */
	foreach ($cfg['solutionFree'] as $val)
	{
		if(is_file(__PATH__."/modules/".$val."/manage/_sql.php")) //mdSitemap 등 DB가 없는 모듈은 제외
		{
			include __PATH__."/modules/".$val."/manage/_sql.php";
			foreach($sql[$val] AS $value)
			{
				$value = str_replace('="/', '="'.$cfg['droot'], $value); //상위폴더 설정시
				$value = str_replace("_prefix_", null, $value);
				$db->query(trim($value));
			}
		}
	}

	/**
	 * 회원 기본정보 등록
	 */
	$db->data['seq']		= 1;
	$db->data['sort']		= 1;
	$db->data['level']		= 2;
	$db->data['nick'] 		= $db->data['name'];
	$db->data['passwd']		= $db->passType($cfg['site']['encrypt'], $_POST['userpasswd']);
	$db->data['passwdModify']		= time();
	if($db->sqlInsert("mdMember__account", "REPLACE", 0))
	{
		//회원 부가정보 등록
		$db->sqlInsert("mdMember__info", "REPLACE", 0);
		Member::sendMail('ceo@wcmskorea.com', 'WCMS v2.0<ceo@wcmskorea.com>', $cfg['site']['domain']." - 셋팅완료!", $db->data['groupName'].' : 셋팅완료!', $db->data['groupName']);

		//$func->setLog(__FILE__, "신규 사이트(".__DB__.") 설치 성공");
		$func->err("WCMS 솔루션이 성공적으로 설치 되었습니다.", "location.replace('/_Admin/login.php');");
	}
	else
	{
		$db->query(" DELECT FROM `mdMember__account` WHERE id='".$id."' ");
		$db->query(" OPTIMIZE TABLE `mdMember__account` ");
		$func->err("회원정보 입력실패 입니다. 관리자에게 문의바랍니다.");
	}
/*
 * 빌더 사이트 셋팅 : End
 */

exit(0);
}
?>
