<?php
require_once "../_config.php";
$sess = new Sess();
$sess->sessKill();
Header("Location: ./login.php");
exit;
?>
