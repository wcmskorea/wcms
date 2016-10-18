<?php
header("Content-Type: text/html; charset=euc-kr");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");

session_start();
?>

<?
	// ##############################################################################
	// FILE_NAME: GPinSample-siteUserConfirmSend.php
	// DESC: 가입자 등록 시, 본인확인 후 센터에 이용기관에서의 사용자 id를 포함한 확인요청 메시지를 전송하는 페이지
	// ##############################################################################
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=euc-kr" http-equiv="content-type" />
    <title>GPIN SP 샘플 페이지 - Site User Confirm Result</title>
</head>
<body>
    <p>
    G-Pin SP 샘플 페이지 - 본인확인 결과<br />
    <br />
    <table>
        <tr>
            <td style="width: 186px">
                사용자 등록 확인 결과</td>
            <td style="width: 100px">
                <?= $_SESSION["GPIN_AQ_SERVICE_SITE_USER_CONFIRM"] ?>
            </td>
        </tr>
    </table>
    </p>
    <br />
    <a href="javascript:history.back(-2)">뒤로가기</a>
    <br />
    <a href="Sample-_SessionClear.php">세션정보 지우기</a>
</body>
</html>
