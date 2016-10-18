<?php
header("Content-Type: text/html; charset=UTF-8");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");

session_start();

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
    <table>
        <tr>
            <td style="width: 186px">
                TEST_이름</td>
            <td style="width: 100px">
                <?= $_SESSION["gpinName"] ?><br>
				<?= mb_convert_encoding($_SESSION["gpinName"], "UTF-8", "EUC-KR") ?>
            </td>
        </tr>
        <tr>
            <td style="width: 186px">
                식별번호</td>
            <td style="width: 100px">
                <?= $_SESSION[ "gpinVIDN"] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 186px">
                중복확인 정보</td>
            <td style="width: 100px">
                <?= $_SESSION["gpinDupInfo"] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 186px">
                실 주민등록번호</td>
            <td style="width: 100px">
                <?= $_SESSION[ "gpinIDN"] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 186px">
                성별</td>
            <td style="width: 100px">
                <?= $_SESSION["gpinSex"] ?>
            </td>
        </tr>
        <tr>
            <td style="width: 186px">
                생년월일</td>
            <td style="width: 100px">
                <?= $_SESSION[ "gpinBirth"] ?>
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
