<?php
header("Content-Type: text/html; charset=euc-kr");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>
<?php

    /**
     * 이 서비스는 공공I-PIN인증정보수신 페이지입니다.
     * 사용자인증 정보, 사이트가입확인요청 등의 요청서비스에 대해서 공공I-PIN에서 응답을 주는 페이지이므로
     * 1) 공공I-PIN 공무원창구를 통해 정확하게 URL이 등록되어야하며
     * 2) 서비스와 관련된 내용을 수정하시면 안됩니다.
     *
     */

	include("gpin_func.php");

	$_SESSION["myIP"] = $_SERVER["REMOTE_ADDR"];


	if($_REQUEST["versionRequest"] != null and $_REQUEST["versionRequest"] == "versionRequest" ){
		$ret = version(0);
		GPIN_ERROR_CHECK(TRUE);
		echo "$ret";
		exit;

	}


	$result="false";

	// 요청한 사용자 IP와 응답받는 사용자 IP를 비교한다.
	if($_SESSION["myIP"] == $_SESSION["gpinUserIP"]){
		$result="true";
	}

	if($result=="true"){

		$samlResponse=$_POST['SAMLResponse'];
		$attrNames = array("dupInfo", "virtualNo", "realName", "sex", "age", "birthDate", "nationalInfo", "authInfo", "GPIN_AQ_SERVICE_SITE_USER_CONFIRM");

		//GPinProxy을 통해 사용자정보를 G-PIN센터(SPStub)로부터 가져옴.
		$ret = parseSAMLResponse($samlResponse, $attrNames);

		// 에러인 경우 메세지표시 후 종료함.	
		GPIN_ERROR_CHECK(TRUE);
		
		$_SESSION["dupInfo"]		= $ret[0];
		$_SESSION["virtualNo"]		= $ret[1];
		$_SESSION["realName"]		= $ret[2];
		$_SESSION["sex"]			= $ret[3];
		$_SESSION["age"]			= $ret[4];
		$_SESSION["birthDate"]		= $ret[5];
		$_SESSION["nationalInfo"]	= $ret[6];
		$_SESSION["authInfo"]		= $ret[7];
		$_SESSION["GPIN_AQ_SERVICE_SITE_USER_CONFIRM"]	= $ret[8];
	}



?>
	<script type="text/javascript">
<?	if($result == "true"){ ?> 
		window.opener.location.href = '<?= $_SESSION["gpinAuthRetPage"] ?> ';
		window.close();
<?	}else if($result == "false"){ ?>
		alert('요청이 금지된 IP입니다. <?= $_SESSION["myIP"] ?>');
<?	}else{ ?>
		alert("수신받은 인증값이 없습니다.");
<?  } ?>
	</script>
<?php 
	$_SESSION["gpinAuthRetPage"] = null;
?>


