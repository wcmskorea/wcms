<?php
header("Content-Type: text/html; charset=UTF-8");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");

session_start();

?>

<?
    $_SESSION["dupInfo"] = null;
    $_SESSION["virtualNo"] = null;
    $_SESSION["realName"] = null;
    $_SESSION["sex"] = null;
    $_SESSION["age"] = null;
    $_SESSION["birthDate"] = null;        
    $_SESSION["nationalInfo"] = null;
	$_SESSION["authInfo"] = null;
    $_SESSION["GPIN_AQ_SERVICE_SITE_USER_CONFIRM"] = null;

?>
<script>
	location.href = "Sample-AuthResponse.php";
</script>

