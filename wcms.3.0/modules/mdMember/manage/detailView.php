<?php
/**
 * 회원정보 상세보기
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 28.
 */
$Rows	= $member->memberInfo($_GET['user']);
$Logs = $db->queryFetch(" SELECT * FROM `mdMember__log` WHERE id='".$Rows['id']."' ORDER BY Login DESC ");

$department		= explode(",", $Rows['department']);
$birth				= explode("-", $Rows['birth']);
$memory				= explode("-", $Rows['memory']);
$sex					= ($Rows['sex']) ? ($Rows['sex']=='1' || $Rows['sex']=='3') ? "남" : "여" : "-";
$info					= explode("|", $Rows['info']);

if($Rows['seq'] && $Rows['id']) $func->setLog(__FILE__, "회원정보(".$Rows['id'].") 열람");
?>
<div style="padding-bottom:10px;">
	<span><a href="javascript:;" onclick="$.memberInfo('<?php echo($Rows['id']);?>');" class="btnPack<?php if($_GET['type']=='detail'){print(' violet');}else{print(' gray');}?> small"><span>회원정보</span></a>
	<a href="javascript:;" onclick="$.dialog('../modules/mdMember/manage/_controll.php', '&amp;type=log&amp;user=<?php echo($Rows['id']);?>',1000,500)" class="btnPack<?php if($_GET['type']=='log'){print(' violet');}else{print(' gray');}?> small"><span>접속내역</span></a>
</div>
<div style="border-top:1px dashed #999; padding:5px 0; line-height:20px;">
	<?php if($_GET['user']) { ?>
	-. <strong><?php echo($Rows['name']);?></strong>님 ( <span class="red"><?php echo($member->memberPosition($Rows['level']));?></span> )<!-- <strong><?php echo($Rows['id']);?>--></strong><br />
	<?php
		$func->setLog(__FILE__, "회원(".$Rows['id'].") 정보 조회");
	} else {
	?>
	<strong>신규 회원을 등록합니다.</strong><br />
	<?php } ?>
</div>
<div style="border-top:1px dashed #999; padding:10px 0; line-height:20px;">
	-. 성　별 : <strong><?php echo($sex);?></strong><br />
	-. 나　이 : <strong><?php echo($Rows['age']);?></strong> 세<br />
	-. 연락처 : (<strong>T</strong>) <?php echo($Rows['phone']);?>, (<strong>M</strong>) <?php echo($Rows['mobile']);?>
</div>
<div style="border-top:1px dashed #999; padding:10px 0; line-height:20px;">
	-. 최근 로그인 : <?php echo($Logs['login']); ?> (IP: <?php echo($Logs['ip']);?>)<br />
	-. 정보  변경일 : <?php echo(($Rows['dateModify'] == 0)?"-":date("Y-m-d H:i",$Rows['dateModify']));?><br />
	-. 회원 등록일 : <?php echo(date("Y-m-d H:i",$Rows['dateReg']));?><br />
	-. 비밀번호 변경일 : <?php echo(($Rows['passwdModify'] == 0)?"-":date("Y-m-d H:i",$Rows['passwdModify']));?><br />
	-. 회원 탈퇴일 : <?php echo(($Rows['dateExpire'] == 0)?"-":date("Y-m-d H:i",$Rows['dateExpire']));?>
</div>
