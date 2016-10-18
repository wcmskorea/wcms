<?php
header("Content-Type: text/html; charset=euc-kr");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");

session_start();
header("Location: /?cate=000002002");
die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=euc-kr" http-equiv="content-type" />
    <title>GPIN SP 샘플 페이지</title>
</head>
<body>
    <p>
    G-Pin SP 샘플 페이지 - 본인확인 결과<br />
    <br />
<?
	// 요청한 사용자 IP와 응답받는 사용자 IP를 비교한다.
	if ($_SERVER['REMOTE_ADDR'] == $_SESSION["gpinUserIP"])
	{
?>
    <table>

        <tr>
            <td>
               중복확인코드(dupInfo)</td>
            <td>
                <?//= mb_convert_encoding($_SESSION["dupInfo"], "UTF-8", "EUC-KR") ?>
                <?= $_SESSION["dupInfo"] ?>
            </td>
        </tr>
        <tr>
            <td>
                개인식별번호(virtualNo)</td>
            <td>
                <?//= mb_convert_encoding($_SESSION["virtualNo"], "UTF-8", "EUC-KR") ?>
                <?= $_SESSION["virtualNo"] ?>
            </td>
        </tr>
        <tr>
            <td>
                이름(realName)</td>
            <td>
                <?//= mb_convert_encoding($_SESSION["realName"], "UTF-8", "EUC-KR") ?>
                <?= $_SESSION["realName"] ?>
            </td>
        </tr>
        <tr>
            <td>
                성별(sex)</td>
            <td>
                <?//= mb_convert_encoding($_SESSION["sex"], "UTF-8", "EUC-KR") ?>
                <?= $_SESSION["sex"] ?>
            </td>
        </tr>
        <tr>
            <td>
               나이(age)</td>
            <td>
                <?//= mb_convert_encoding($_SESSION["age"], "UTF-8", "EUC-KR") ?>
                <?= $_SESSION["age"] ?>
            </td>
        </tr>
        <tr>
            <td>
               생년월일(birthDate)</td>
            <td>
                <?//= mb_convert_encoding($_SESSION["birthDate"], "UTF-8", "EUC-KR") ?>
                <?= $_SESSION["birthDate"] ?>
            </td>
        </tr>
        <tr>
            <td>
               국적(nationalInfo)</td>
            <td>
                <?//= mb_convert_encoding($_SESSION["nationalInfo"], "UTF-8", "EUC-KR") ?>
                <?= $_SESSION["nationalInfo"] ?>
            </td>
        </tr>
        <tr>
            <td>
               본인인증방법(authInfo)</td>
            <td>
                <?//= mb_convert_encoding($_SESSION["authInfo"], "UTF-8", "EUC-KR") ?>
                <?= $_SESSION["authInfo"] ?>
            </td>
    </table>
<?
	}
	else
	{
?>
		<table>
		<tr><td>세션값을 받지 못했습니다.</td></tr>
		</table>
<?
	}
?>
    </p>

    <br />
    <a href="Sample-index.html">뒤로가기</a>
    <br />
    <a href="Sample-_SessionClear.php">세션정보 지우기</a>
</body>
</html>
