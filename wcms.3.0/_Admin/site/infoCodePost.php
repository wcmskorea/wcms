<?php
require "../include/commonHeader.php";
require	__PATH__."_Lib/classConfig.php";

	/**
	 * 등록처리
	 */
if( "infoCodePost" == trim($_POST['type']).trim($_GET['type']))
{

	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		$db->data[$key] = trim($val);
		#--- $func->validCheck(체크할 값, 항목제목, 항목명(타겟), 체크타입)
		if($key == "cate")     $func->vaildCheck($val, "구분코드", "cate", "num");
		if($key == "cateName") $func->vaildCheck($val, "구분명", "cateName", "trim");
		if($key == "cateViewSeq")  $func->vaildCheck($val, "표시순서", "cateViewSeq");
		if($key == "code")     $func->vaildCheck($val, "코드", "code", "num");
		if($key == "name")     $func->vaildCheck($val, "코드명", "name", "trim");
		if($key == "codeViewSeq")  $func->vaildCheck($val, "표시순서", "codeViewSeq");
	}
	$db->data['modifier'] = $_SESSION['uid'];

	if("" == trim($_POST['stype']).trim($_GET['stype']))
	{
		$db->data['useYn'] = ($_POST['useYn'] == "Y")  ? "Y" : "N";

		if($db->sqlInsert("mdCommon__code","INSERT", 0))
		{
			$func->setLog(__FILE__, "구분코드 등록", true);
			$func->err("구분코드 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove(); parent.$.insert('#tabBody01','./site/infoCode.php',null,200)");
		}
		else
		{
			$func->err("구분코드 정보가 변경된 내용이 없거나, 적용 실패입니다.","parent.$.dialogRemove(); ");
		}
	}
	/**
	 * 구분 수정처리
	 */
	else if("cateModify" == trim($_POST['stype']).trim($_GET['stype']))
	{
		unset($db->data['type'], $db->data['stype']);

		if($db->sqlUpdate("mdCommon__code","cate='".$db->data['oldCate']."'", array(), 0))
		{
			$func->setLog(__FILE__, "구분코드 수정", true);
			$func->err("구분코드 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove(); parent.$.insert('#tabBody01','./site/infoCode.php',null,200)");
		}
		else
		{
			$func->err("구분코드 정보가 변경된 내용이 없거나, 적용 실패입니다.","parent.$.dialogRemove(); ");
		}
	}
	/**
	 * 코드 수정처리
	 */
	else if("codeModify" == trim($_POST['stype']).trim($_GET['stype']))
	{
		unset($db->data['type'], $db->data['stype']);
		if($db->sqlUpdate("mdCommon__code","cate='".$db->data['oldCate']."' AND code='".$db->data['oldCode']."'", array(), 0))
		{
			$func->setLog(__FILE__, "코드정보 수정", true);
			$func->err("구분코드 정보가 정상적으로 적용되었습니다.", "parent.$.dialogRemove(); parent.$.insert('#tabBody01','./site/infoCode.php',null,200)");
		}
		else
		{
			$func->err("구분코드 정보가 변경된 내용이 없거나, 적용 실패입니다.","parent.$.dialogRemove(); ");
		}
	}
}
	/**
	 * 구분목록표시
	 */
else if( "codeLeft" == trim($_POST['type']).trim($_GET['type']))
{
?>
	<table class="table_basic" style="width:100%;">
	<col width="30">
	<col>
	<col width="70">
	<tbody>
	<?php
	$cateCnt=0;
	$db->Query(" SELECT cate,cateName,cateViewSeq FROM mdCommon__code GROUP BY cate,cateName ORDER BY cate ");
	while($Rows = $db->fetch())
	{
		$cateCnt++;
	?>
	<tr class='listOut' onMouseOver="this.className='listOver'" onMouseOut="this.className='listOut'" style="line-height:20px">
		<td style="text-align:center;cursor:pointer" onClick='f_setCate("<?=$Rows['cate']?>","codeRight","ALL")'><?=$Rows['cate']?></td>
		<td style="cursor:pointer" onClick='f_setCate("<?=$Rows['cate']?>","codeRight","ALL")'><?=$Rows['cateName']?></td>
		<td style="text-align:center">
			<img src="/common/image/icon/add.gif" onclick="$.dialog('./site/index.php?type=dialog&cate=<?=$Rows['cate']?>&cateName=<?=$Rows['cateName']?>&cateViewSeq=<?=$Rows['cateViewSeq']?>',null,400,310)" style="cursor:pointer" title="추가" />
			<img src="/common/image/icon/cog.gif" onclick="$.dialog('./site/index.php?type=dialog&stype=modify&cate=<?=$Rows['cate']?>&cateName=<?=$Rows['cateName']?>&cateViewSeq=<?=$Rows['cateViewSeq']?>',null,400,310)" style="cursor:pointer" title="수정" />
			<img src="/common/image/icon/delete.gif" onClick="f_cateDelete('<?=$Rows['cate']?>','<?=$Rows['cateName']?>','cateDelete')" style="cursor:pointer" title="삭제" />
		</td>
	</tr>
	<?
	}

	if ( !$cateCnt )
	{
			echo "<tr class='listOut'><td colspan='3' style='text-align:center'>구분코드가 존재하지 않습니다.</td></tr>";
	  $cateCnt++;
	}

	for($loop=$cateCnt; $loop < 16; $loop++ )
	{
	?>
	  <tr class='listOut' style="line-height:20px">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	<?php
	}
	?>
	</tbody>
	</table>
 <?php
}
	/**
	 * 코드목록표시
	 */
else if( "codeRight" == trim($_POST['type']).trim($_GET['type']) )
{
	$cate = $_GET['cate'];
  if ( "" == $_GET['cate'] )
  {
    $cate = $db->QueryFetchOne(" SELECT cate FROM mdCommon__code ORDER BY cate LIMIT 1 ");
  }
  ?>
  <table class="table_basic" style="width:100%;">
    <col width="30">
	<col>
	<col width="70">
    <tbody>
    <?php
    $codeCnt=0;
		$codeQuery = ($_GET['code']) ? " AND code LIKE '".$_GET['code']."%'":"";

    $db->Query("SELECT * FROM mdCommon__code WHERE cate = '".$cate."'".$codeQuery." AND LENGTH(code) = ".$_GET['step']." ORDER BY code ");
		while( $Rows = $db->Fetch() )
    {
      $codeCnt++;
      if ( "Y" == $Rows['useYn'] ) { $useYn = "사용"; }
      else                         { $useYn = "<span class='colorRed'>미사용</span>"; }
    ?>
      <tr class='listOut' onMouseOver="this.className='listOver'" onMouseOut="this.className='listOut'" style="line-height:20px">
        <td style="text-align:center;cursor:pointer" onClick='f_getCode("codeRight","<?=$Rows['cate']?>","<?=strlen($Rows['code'])?>","<?=$Rows['code']?>")' title="<?php echo($Rows['code']);?>"><?=substr($Rows['code'],-3)?></td>
        <td style="cursor:pointer" onClick='f_getCode("codeRight","<?=$Rows['cate']?>","<?=strlen($Rows['code'])?>","<?=$Rows['code']?>")' title="<?php echo($Rows['code']);?>"><?=$Rows['name']?></td>
        <td style="text-align:center">
		<?php
			if(strlen($Rows['code']) < 9) {
		?>
					<img src="/common/image/icon/add.gif" onclick="$.dialog('./site/index.php?type=dialog&cate=<?=$Rows['cate']?>&code=<?=$Rows['code']?>&cateName=<?=$Rows['cateName']?>&cateViewSeq=<?=$Rows['cateViewSeq']?>',null,400,310)" style="cursor:pointer" title="추가" />
		<?php
			}
		?>
					<img src="/common/image/icon/cog.gif" onclick="$.dialog('./site/index.php?type=dialog&cate=<?=$Rows['cate']?>&code=<?=$Rows['code']?>&cateName=<?=$Rows['cateName']?>&cateViewSeq=<?=$Rows['cateViewSeq']?>&stype=modify',null,400,310)" style="cursor:pointer" title="수정" />
					<img src="/common/image/icon/delete.gif" onClick="f_codeDelete('<?=$Rows['cate']?>','<?=$Rows['code']?>','<?=$Rows['name']?>','codeDelete')" style="cursor:pointer" title="삭제" />
				</td>
			</tr>
    <?php
    }

    if ( !$codeCnt )
    {
      echo "<tr class='listOut'><td colspan='3' style='text-align:center'>코드가 존재하지 않습니다.</td></tr>";
      $codeCnt++;
    }

    for($loop=$codeCnt; $loop < 16; $loop++ )
    {
    ?>
    <tr class='listOut' style="line-height:20px">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php
    }
    ?>
    </tbody>
  </table>
<?php
}
	/**
	 * 코드삭제처리
	 */
else if( "codeDelete" == trim($_POST['type']).trim($_GET['type']) )
{
	$db->query(" DELETE FROM `mdCommon__code` WHERE cate='".$_GET['cate']."' AND code = '".$_GET['code']."' ");
	if($db->getAffectedRows() > 0)
	{
		$db->query(" OPTIMIZE TABLE `mdCommon__code` ");
		$func->setLog(__FILE__, "코드 삭제", true);
		$func->ajaxMsg("코드가 삭제 되었습니다.", "$.insert('#tabBody01','./site/infoCode.php',null,200)", 20);
	}
	else
	{
		$func->ajaxMsg("코드 정보가 변경된 내용이 없거나, 적용 실패입니다.", "", 20);
	}
}
	/**
	 * 구분삭제처리
	 */
else if( "cateDelete" == trim($_POST['type']).trim($_GET['type']) )
{
	$db->query(" DELETE FROM `mdCommon__code` WHERE cate='".$_GET['cate']."' ");
	if($db->getAffectedRows() > 0)
	{
		$db->query(" OPTIMIZE TABLE `mdCommon__code` ");
		$func->setLog(__FILE__, "구분코드 삭제", true);
		$func->ajaxMsg("구분코드가 삭제 되었습니다.", "$.insert('#tabBody01','./site/infoCode.php',null,200)", 20);
	}
	else
	{
		$func->ajaxMsg("구분코드 정보가 변경된 내용이 없거나, 적용 실패입니다.", "", 20);
	}
}
?>
