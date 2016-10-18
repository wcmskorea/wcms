<?php
/*---------------------------------------------------------------------------------------
| 회원모듈 : 회원목록 엑셀다운로드
|----------------------------------------------------------------------------------------
| Last (2009-08-29 : 이성준)
*/
require_once "../../../_config.php";

# 리퍼러 체크
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
usleep($cfg[sleep]);

//카테고리명
$category		= $db->QueryFetchOne(" SELECT name FROM `site__` WHERE cate='".__CATE__."' ", 2);

//엑셀 파일명은 UTF-8 일경우 깨지게 되므로 강제로 EUC-KR로 변경처리 한다.
$fileName = ($cfg['charset'] == "utf-8") ? iconv("UTF-8", "EUC-KR", $category."_내역_") : $category."_내역_";

//환경설정
$config			= $db->queryFetch("SELECT config,contentAdd FROM `mdApp01__` WHERE cate='".__CATE__."'");
$cfg['module']	= @array_merge((array)unserialize($config['config']), (array)unserialize($config['contentAdd']));
$division		= explode(",", $cfg['module']['division']);
$result			= ($cfg['module']['result']) ? explode(",", $cfg['module']['result']) : "";

$sql = "cate='".__CATE__."'";
if($_GET['state'] == 0 || $_GET['state']) { $sql .= " AND state='".$_GET['state']."'"; }

//헤더정보
header("Content-Type: application/vnd.ms-excel; charset=".$cfg['charset']);
header("Content-Disposition: attachment; filename=".$fileName.date('Ymd').".xls");
header("Content-Description: PHP5 Generated Data" );
?>

<table id="cell" summary="요청목록">
	<caption><?php echo($category);?>_내역</caption>
	<thead>
	<tr>
		<th style="background:#eee;">연번</th>
		<th style="background:#eee;">카테고리</th>
		<th style="background:#eee;">상태</th>
		<th style="background:#eee;">구분</th>
		<th style="background:#eee;">신청일</th>
		<th style="background:#eee;">아이디</th>
		<th style="background:#eee;">이름</th>
		<th style="background:#eee;">주민번호</th>
		<th style="background:#eee;">이메일</th>
		<th style="background:#eee;">연락처</th>
		<th style="background:#eee;">휴대폰</th>
		<th style="background:#eee;">우편번호</th>
		<th style="background:#eee;">주소</th>
		<th style="background:#eee;">예약일정</th>
		<th style="background:#eee;">요청내용</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$n = 1;
	$db->Query(" SELECT * FROM `mdApp01__content` WHERE ".$sql." ORDER BY dateReg ASC ");

	if($db->getNumRows() < 1)
	{
		echo '<tr><td class="blank" colspan="14">등록된 요청건이 존재하지 않습니다.</td></tr>';
	} else
	{
		while($Rows = $db->Fetch())
		{
			$contentAdd = (array)unserialize($Rows['conentAdd']);
			$category   = $db->QueryFetchOne(" SELECT name FROM `site__` WHERE cate='".$Rows[cate]."' ", 2);
			$Rows['division'] = ($division && $Rows['division']) ? $division[$Rows['division']] : $division['0'];

			echo('<tr>
			<td title="연번">'.$n.'</td>
			<td title="카테고리">'.$category.'</td>
			<td title="상태">'.$result[$Rows['state']].'</td>
			<td title="구분">'.$Rows['division'].'</td>
			<td title="">'.date('Y-m-d H:i:s',$Rows['dateReg']).'</td>
			<td title="아이디">'.$Rows[id].'</td>
			<td title="이름">'.$Rows[name].'</td>
			<td title="주민번호" style="mso-number-format:\'@\';">'.$Rows[idcode].'</td>
			<td title="이메일">'.$Rows[email].'</td>
			<td title="전화번호" style="mso-number-format:\'@\';">'.$Rows[phone].'</td>
			<td title="휴대전화" style="mso-number-format:\'@\';">'.$Rows[mobile].'</td>
			<td title="우편번호">'.$Rows[zipcode].'</td>
			<td title="주소">'.$Rows[address01].' '.$Rows[address02].'</td>
			<td title="예약일정">'.$contentAdd['scheduleyear'].'년 '.$contentAdd['schedulemonth'].'월 '.$contentAdd['scheduleday'].'일 '.$contentAdd['schedulehour'].'시 '.$contentAdd['schedulemin'].'분</td>
			<td title="요청내용">'.stripslashes($Rows['content']).'</td>
			</tr>');
			$n++;
		}
	}
?>
</tbody>
</table>