<?php
header("Content-Type: text/html; charset=UTF-8");
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

?>

<?
	include("gpin_func.php");

	
	if ($_REQUEST["regNo"] != null)
		$rtnDupValue = getUserDupValue($_REQUEST["regNo"], $_REQUEST["siteId"]);
	else
		$rtnDupValue = "";


	echo $rtnDupValue;

?>
