<?php
/**
 * 회원별 적립 포인트 관리
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 1. 28.
 */
require_once "../../../_Admin/include/commonHeader.php";

if($_POST[type] == 'logPost')
{
  $db->Query(" UPDATE `mdMember__info` SET content ='".$_POST['content']."' WHERE id='".$_POST['user']."' ");
}
?>

<div class="menu_violet">
  <p title="드래그하여 이동하실 수 있습니다"><strong>회원 접속내역 및 관리 [ 회원ID : <?=$_GET['user']?> ]</strong></p>
</div>

<table class="table_basic">
<col width="350">
<col>
<tr>
  <td style="vertical-align:top; padding:8px;" class="bg_gray">
    <form name="frmMember" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" target="hdFrame">
    <input type="hidden" name="type" value="logPost" />
    <input type="hidden" name="user" value="<?php echo($_GET['user']); ?>" />
    <?php
    	include "./detailView.php";
    ?>
		<div style="border-top:1px dashed #999; padding:5px 0;">
			<p class="pd5 center">
			<?php if($_GET['user'] && (preg_match('/mdMember/', $_SESSION['udepartment']) || $_SESSION['ulevel'] < $cfg['operator'])) { ?><span class="btnPack red medium strong"><button type="submit">정보 수정하기</button></span><?php } ?>
			</p>
			<p class="pd5"><strong>관리자 메모</strong> 100자이내</p>
			<p><textarea name="content" style="width:99%;height:120px;" class="input_white_line"><?php echo($Rows['content']);?></textarea></p>
		</div>
    </form>
  </td>
  <td style="vertical-align:top;">
    <div class="cube"><div class="line">
    <table class="table_list" style="width:100%;">
    <col width="150">
    <col>
		<col>
    <col width="100">
    <thead>
      <th style="text-align:center" class="first"><p class="center">접속IP</p></th>
      <th style="text-align:center">로그인 시간</th>
			<th style="text-align:center">로그아웃 시간</th>
      <th style="text-align:center">로그인수</th>
    </thead>
    <tbody>
    <?php
    #--- 게시물 리스트 및 페이징 설정
    $rows				= 5;
		$memberInfo = $member->memberInfo($user);
		//$totalRec		= $db->getTotalRows(" SELECT substring(login,1,10) as login_date, ip, count(seq) as login_cnt FROM `mdMember__log` WHERE id='".$user."' GROUP BY login_date, ip LIMIT 5 ");
    $i = 0;
		$db->query(" SELECT substring(login,1,10) as login_date, ip, count(seq) as login_cnt  FROM `mdMember__log` WHERE id='".$user."' GROUP BY login_date, ip ORDER BY login_date ASC LIMIT ".$rows );
    while($sRows = $db->Fetch())
    {
    ?>
    <tr>
      <th><p class="center"><?php echo($sRows['ip']);?></p></th>
			<td><p class="center"><?php echo($sRows['login_date']);?></p></td>
			<td><p class="center"><?php echo($sRows['logout_date']);?></p></td>
			<td><p class="center"><?php echo($sRows['login_cnt']);?></p></td>
    </tr>
    <?php
			$userip = $sRows['ip'];
      $i++;
    }
		//로그인 기록이 없다면 최근 회원정보에서 색출
		$userip = ($userip) ? $userip : $info['1'];
    ?>
    </tbody>
    </table>
		<br />
		<table class="table_list" style="width:100%;">
    <col width="150">
    <col>
    <thead>
      <th style="text-align:center" class="first"><p class="center">접속경로</p></th>
      <th style="text-align:center">접속 키워드</th>
    </thead>
    <tbody>
    <?php
    #--- 게시물 리스트 및 페이징 설정
    $rows	= 10;
    $i		= 0;
		$db->query("SELECT * FROM `mdAnalytic__refer` WHERE ip='".$userip."' GROUP BY ip,referer ORDER BY date DESC");
    while($sRows = $db->Fetch())
    {
			$refer			= explode("/", $sRows['referer']);
			$match			= (preg_match("/\bnaver\b/i", $sRows['referer'])) ? "query=" : "q=";
			$keyword		= explode($match, urldecode($sRows['referer']));
			$keyword['1']	= (mb_detect_encoding($keyword['1']) != "UTF-8") ? iconv("CP949", "UTF-8", $keyword['1']) : $keyword['1'];
			$keyword		= explode("&", $keyword['1']);
			//$keyword		= $func->cutStr($keyword['0'], 14);

			echo('<tr>
			<th><a href="http://'.$sRows['referer'].'" class="actUnder" target="_blank">'.$refer['0'].'</a></th>
			<td><ol><li class="opt colorRed">'.$keyword['0'].'</li><li class="opt colorGray">('.$sRows['time'].')</li></ol></td>
			</tr>');

      $i++;
    }
    ?>
    </tbody>
    </table>
		<br />
		<table class="table_list" style="width:100%;">
		<col width="150">
		<col>
		<thead>
			<tr>
				<th class="first"><p class="center"><span>이동 시간</span></p></th>
				<th><p class="center"><span>이동 경로</span></p></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$track = $db->queryFetchOne(" SELECT info FROM `mdAnalytic__track` WHERE skin='default' AND ip='".$userip."' ORDER BY date DESC ");
		if($db->getNumRows() < 1)
		{
			echo '<tr><td class="blank" colspan="2">내역이 없습니다.</td></tr>';
		} else
		{
			$track = explode(">", $track);
			foreach($track AS $key=>$val)
			{
				$value = explode(":", $val);
				if(is_numeric($value['0']))
				{
					$delay = ($pageDelay) ? $value['0'] - $pageDelay : 0;
					$pageDelay = $value['0'];
					$pageTime = date('m/d H:i:s', $value['0']);
					$pageName = ($value['1'] == 'main') ? "첫페이지 (".date('i:s', $delay).")" : $db->queryFetchOne(" SELECT name FROM `site__` WHERE cate = '".$value['1']."' AND skin='default' ", 2)." (".date('i:s', $delay).")";
			?>
					<tr>
						<th><?php echo($pageTime); ?></th>
						<td class="darkgray">
						<ol>
							<li style="width:300px"><?php echo($pageName); ?></li>
						</ol>
						</td>
					</tr>
			<?php
				}
			}
		}
		?>
		</tbody>
		</table>
    </div></div>
  </td>
</tr>
</table>
<?php
require_once "../../../_Admin/include/commonScript.php";
?>