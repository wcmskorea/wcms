<?php
$sess->sessKill();
$db->query(" UPDATE `mdMember__log` SET logout=CURDATE() ORDER BY login DESC LIMIT 1 ");
//Header("Location: /index.php");
$func->err("정상적으로 로그아웃되었습니다.", "./");
?>

