<?php
require_once "../../../_config.php";

$Rows		= $db->queryFetch(" SELECT * FROM `mdApp01__content` WHERE seq='".$_GET[idx]."' ");
$configQuery = $db->queryFetch("SELECT * FROM `mdApp01__` WHERE cate='".$Rows['cate']."'");
$config = @array_merge((array)unserialize($configQuery['config']), (array)unserialize($configQuery['contentAdd']));

$division	= explode(",", $config['division']);
$division = ($Rows['division'] == '100') ? "문자상담" : $division[$Rows['division']];
$result		= explode(",", $config['result']);
$contentAdd = (array)unserialize($Rows['contentAdd']);
?>

<!DOCTYPE html>
<html>
<head>
<title>{<?php echo($cfg['version']);?>} <?php echo($cfg['site']['siteName']);?> > 관리시스템 [WCMS-<?php echo(strtoupper($cfg['site']['lang']));?>]</title>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?php echo($cfg['charset']);?>" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="content-language" content="kr" />
<meta name="robots" content="ALL" />
<meta name="publisher" content="10억홈피" />
<meta name="description" content="홈페이지,웹마스터,웹프로그래밍,웹디자인,유지보수,웹호스팅" />
<meta name="keywords" content="자바스크립트,html,플래쉬,플래시,홈페이지,웹마스터,웹프로그래밍,웹디자인,유지보수" />
<link rel="shortcut icon" href="<?php echo($cfg['droot']);?>image/favicon.ico" type="image/ico">
<link rel="stylesheet" href="<?php echo($cfg['droot']);?>common/css/admin.css" type="text/css" media="all" />
</head>
<body style="background:url();" onload="printMe();" onunload="rollBack();">

<div class="pd3 right"><?php foreach($result AS $key=>$val)
{
	echo('&nbsp;<span>');
	if($Rows['state'] == $key || ($Rows['state'] == '0' && $key == '0'))
	{
		echo('▣');
	} else
	{
		echo('□');
	}
	echo('&nbsp;'.$val.'<span>&nbsp;');
}
?></div>

<table class="table_basic" style="width:100%">
	<col>
	<thead>
		<tr>
			<th class="first pd10"><p class="center pd5"><span style="font-size:16px;font-weight:bold;color:#000">( <?php echo($config['division']);?> ) 접 수 내 역</span></p></th>
		</tr>
	</thead>
</table>

<table class="table_basic" style="width:100%">
	<col width="120">
	<col>
	<tbody>
	<?php if($Rows['id']) { ?>
	<tr><th>회원ID</th>
	    <td><?php echo($Rows['id']);?></td>
	</tr>
	<?php } ?>
	<?php if($config['opt_name'] != 'N') { ?>
	<tr><th>신청자명</th>
	    <td><?php echo($Rows['name']);?></td>
	</tr>
	<?php } ?>
	<?php if($config['opt_email'] != 'N') { ?>
	<tr><th>E-mail</th>
	    <td><?php echo($Rows['email']);?></td>
	</tr>
	<?php } ?>
	<?php if($config['opt_mobile'] != 'N') { ?>
	<tr><th>휴대폰</th>
	    <td><?php echo($Rows['mobile']);?></td>
	</tr>
	<?php } ?>
	<?php if($config['opt_phone'] != 'N') { ?>
	<tr><th>전화번호</th>
	    <td><?php echo($Rows['phone']);?></td>
	</tr>
	<?php } ?>
	<?php if($config['opt_schedule'] != 'N') { ?>
	<tr><th>예약시간</th>
	    <td><?php echo($contentAdd['scheduleyear'].'년 '.$contentAdd['schedulemonth'].'월 '.$contentAdd['scheduleday'].'일 '.$contentAdd['schedulehour'].'시 '.$contentAdd['schedulemin'].'분');?></td>
	</tr>
	<?php } ?>
	<?php if($config['opt_content'] != 'N') { ?>
	<tr><th>상세내용</th>
	    <td><?php echo(nl2br(stripslashes($Rows['content'])));?></td>
	</tr>
	<?php }
	for($i=1; $i<=20; $i++)
	{
		if($config['opt_add'.$i] != 'N')
		{
			$opt = $db->queryFetch(" SELECT * FROM `mdApp01__opt` WHERE cate='".$Rows['cate']."' AND sort='".$i."' ");
			$addValue = ($opt['addType'] != 'input') ? explode("|", $opt['addContent']) : 'addContent'.$i;
			$value		= ($opt['addType'] != 'input') ? $addValue[$contentAdd['addContent'.$i]] : $contentAdd['addContent'.$i];
			echo('<tr><th>'.$opt['addName'].'</td>
				<td>'.$value.'</td>
			</tr>');
		}
	}
	?>
</table>
<div class="pd10 colorGray"><strong>&lt;ㆍ답변 및 처리내용ㆍ&gt;</strong></div>
<div class="pd10"><?php echo(nl2br(stripslashes($Rows['contentAnswers'])));?></div>

<!--- 인쇄관련 --->
<object id="IEPageSetupX" classid="clsid:41C5BC45-1BE8-42C5-AD9F-495D6C8D7586" codebase="<?php echo($cfg['droot']);?>common/js/IEPageSetupX.cab#version=1,0,20,4" style="width:0px;height:0px;">
	<param name="copyright" value="http://isulnara.com">
	<div style="position:absolute;top:276;left:320;width:300;height:68;border:solid 1 #99B3A0;background:#D8D7C4;overflow:hidden;z-index:1;visibility:visible;"><FONT style='font-family: "굴림", "Verdana"; font-size: 9pt; font-style: normal;'><br />&nbsp;&nbsp;인쇄 여백제어 컨트롤이 설치되지 않았습니다.&nbsp;&nbsp;<BR>&nbsp;&nbsp;<a href="<?php echo($cfg['droot']);?>common/js/IEPageSetupX.exe"><font color=red>이곳</font></a>을 클릭하여 수동으로 설치하시기 바랍니다.&nbsp;&nbsp;</FONT></div>
</object>
<script type="text/javascript">
<!--
function printMe()
{
  /*
  header  머리글 설정
  footer  바닥글 설정
  leftMargin  왼쪽 여백(단위: mm)
  rightMargin  오른쪽 여백(단위: mm)
  topMargin  위쪽 여백(단위: mm)
  bottomMargin  아래쪽 여백(단위: mm)
  RollBack  수정 이전 값으로 되돌림(한 단계 이전만 지원)
  Clear  여백은 0으로, 머리글/바닥글은 모두 제거, 배경색 및 이미지 인쇄 안함, 크기에 맞게 축소 안함
  SetDefault  기본값으로 복원(여백 모두: 19.05mm, 머리글:&w&b페이지 &p / &P, 바닥글:&u&b&d, 배경색 및 이미지 인쇄: 안함, 크기에 맞게 축소: 안함)
  Preview  미리보기
  SetupPage  페이지 설정 창 띄우기
  CloseIE  웹브라우즈 닫기
  PrintBackground  배경색 및 이미지 인쇄
  ShrinkToFit  크기에 맞게 축소(IE8만 지원)
  Orientation  인쇄 방향 설정 - 가로
  Orientation  인쇄 방향 설정 - 세로
  Print  인쇄
  Print(true)  인쇄(인쇄 대화상자 표시)
  컨트롤 설치 여부  컨트롤 설치 여부 검사
  PaperSize  인쇄 용지 설정(PaperSize = 'B4')
  Printer  프린터 설정: pdfFactory Pro
  GetPrinters()  프린터 목록 구하기
  GetDefaultPrinter()  기본 프린터 구하기
  */
  IEPageSetupX.Orientation = 1;//인쇄 방향 설정 - 가로0, 세로1
  IEPageSetupX.header='<?php echo($cfg[site][siteName]);?>';//머리글
  IEPageSetupX.footer='';//바닥글
  IEPageSetupX.leftMargin=15;//왼쪽여백
  IEPageSetupX.rightMargin=15;//오른쪽여백
  IEPageSetupX.topMargin=20;//위쪽여백
  IEPageSetupX.bottomMargin=11;//아래쪽여백
  IEPageSetupX.PrintBackground = true;
  IEPageSetupX.ShrinkToFit = true;
  IEPageSetupX.Preview();
  //IEPageSetupX.Print(true);//인쇄(인쇄 대화상자 표시)
  self.close();
}
function rollBack()            // 브라우즈 닫을 때 설정값 원래 값으로 돌리기
{
  IEPageSetupX.RollBack();            // 이전에 설정된 값으로 돌리기
}
//-->
</script>
</body>
</html>
