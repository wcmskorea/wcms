<?php
require_once "../_config.php";

if(!$_POST['uid']) { $func->err("[변경할 비밀번호] 항목은 반드시 작성하셔야합니다.", "parent.$('input[typename=uid]').select()"); }
if(!$_POST['upw']) { $func->err("[변경할 비밀번호 확인] 항목은 반드시 작성하셔야합니다.", "parent.$('input[typename=upw]').select()"); }
if($_POST['uid'] != $_POST['upw']) { $func->err("[변경할 비밀번호]가 일치하지 않습니다", "parent.$('input[typename=upw]').select()"); }

if($cfg['site']['encrypt'] == 'crypt')
{
	$Rows = $db->queryFetch(" SELECT * FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE A.id='".strtolower(mysql_real_escape_string($_SESSION['uid']))."' AND A.level<='".$cfg['operator']."' AND A.level>'0' ");
	if(crypt($_POST['upw'], $Rows['passwd']) == $Rows['passwd'])
	{
		$func->err("이전 비밀번호와 동일하게 설정할 수 없습니다.");
		exit(0);
	}
}
else
{
	$query = " SELECT * FROM `mdMember__account` AS A LEFT JOIN `mdMember__info` AS B ON A.id=B.id WHERE A.id='".strtolower(mysql_real_escape_string($_SESSION['uid']))."' AND A.passwd='".$db->passType($cfg['site']['encrypt'], mysql_real_escape_string($_POST['upw']))."' AND A.level<='".$cfg['operator']."' AND A.level>'0' ";
	$Rows = $db->queryFetch($query);
	if($db->getnumRows() > 0)
	{
		$func->err("이전 비밀번호와 동일하게 설정할 수 없습니다.");
		exit(0);
	}
}

$db->query(" UPDATE `mdMember__account` SET passwd='".$db->passType($cfg['site']['encrypt'], mysql_real_escape_string($_POST['upw']))."', passwdModify='".time()."' WHERE id='".$_SESSION['uid']."' ");

$msg = "정상적으로 변경 되었습니다.";
$func->setLog(__FILE__, "비밀번호 변경");
$func->err($msg, "parent.location.replace('".$cfg['droot']."_Admin/')");
exit(0);
?>
