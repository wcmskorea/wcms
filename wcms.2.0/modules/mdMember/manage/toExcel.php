<?php
/*---------------------------------------------------------------------------------------
| 회원모듈 : 회원목록 엑셀다운로드
|----------------------------------------------------------------------------------------
| Last (2009-08-29 : 이성준)
*/
require_once "../../../_config.php";

# 리퍼러 체크
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
if(!preg_match('/mdMember/', $_SESSION['udepartment']) && $_SESSION['ulevel'] == $cfg['operator']) { $func->err("권한이 없습니다"); }

usleep($cfg[sleep]);

header("Content-Type: application/vnd.ms-excel; charset=".$cfg['charset']);
header("Content-Disposition: attachment; filename=member_list.xls");
header("Content-Description: PHP5 Generated Data" );
?>
<table id="cell" summary="회원목록">
  <caption>회원목록</caption>
  <thead>
  <tr><th style="border:1px solid #999; background:#eee;">연번</th>
      <th style="border:1px solid #999; background:#eee;">등급</th>
      <th style="border:1px solid #999; background:#eee;">그룹</th>
      <th style="border:1px solid #999; background:#eee;">구분</th>
      <th style="border:1px solid #999; background:#eee;">부서명</th>
      <th style="border:1px solid #999; background:#eee;">팀명</th>
      <th style="border:1px solid #999; background:#eee;">담당업무</th>
      <th style="border:1px solid #999; background:#eee;">아이디</th>
      <th style="border:1px solid #999; background:#eee;">이름</th>
      <th style="border:1px solid #999; background:#eee;">이메일</th>
      <th style="border:1px solid #999; background:#eee;">생일</th>
      <th style="border:1px solid #999; background:#eee;">기념일</th>
      <th style="border:1px solid #999; background:#eee;">전화번호</th>
      <th style="border:1px solid #999; background:#eee;">휴대폰</th>
      <th style="border:1px solid #999; background:#eee;">우편번호</th>
      <th style="border:1px solid #999; background:#eee;">주소</th>
      <th style="border:1px solid #999; background:#eee;">기타(업무분장)</th>
      <th style="border:1px solid #999; background:#eee;">학력</th>
      <th style="border:1px solid #999; background:#eee;">경력</th>
      <th style="border:1px solid #999; background:#eee;">자격</th>
      <?php if($cfg['module']['opt_department'] != 'N') {?><th style="border:1px solid #999; background:#eee;">부서명</th><?php } ?>
      <?php if($cfg['module']['opt_team'] != 'N') {?><th style="border:1px solid #999; background:#eee;">직함</th><?php } ?>
      <?php if($cfg['module']['opt_function'] != 'N') {?><th style="border:1px solid #999; background:#eee;">직함</th><?php } ?>
      <?php if($cfg['module']['opt_certification'] != 'N') {?><th style="border:1px solid #999; background:#eee;">자격증</th><?php } ?>
  </tr>
  </thead>
  <tbody>
  <?php
  $n = 1;
  $sq = ($_GET[lev]) ? "mdMember__account.level='".$_GET[lev]."'" : "1";
  $db->query("SELECT * FROM `mdMember__account` LEFT JOIN `mdMember__info` ON mdMember__account.id=mdMember__info.id WHERE ".$sq." ORDER BY mdMember__account.seq ASC ");

  if($db->getNumRows() < 1) {
    echo '<tr><td class="blank" colspan="24">등록된 회원이 존재하지 않습니다.</td></tr>';
  }
  else {
    while($Rows = $db->Fetch()) {
      $level = $db->QueryFetchOne(" SELECT position FROM `mdMember__level` WHERE level='".$Rows[level]."' ", 2);
?>
    <tr>
      <td style="border:1px solid #999; background:#eee;"><?php echo($n);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($level);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[group]);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[division]);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[department]);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[team]);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[dedicate]);?></td>
      <td style="border:1px solid #999;" title="아이디"><?php echo($Rows[id]);?></td>
      <td style="border:1px solid #999;" title="이름"><?php echo($Rows[name]);?></td>
      <td style="border:1px solid #999;" title="이메일"><?php echo($Rows[email]);?></td>
      <td style="border:1px solid #999;" title="생일"><?php echo(($Rows[birth] ? date('Y-m-d',$Rows[birth]) : ''));?></td>
      <td style="border:1px solid #999;" title="기념일"><?php echo(($Rows[memory] ? date('Y-m-d',$Rows[memory]) : ''));?></td>
      <td style="mso-number-format:\@; border:1px solid #999;" title="전화번호"><div class="left"><?php echo($Rows[phone]);?></div></td>
      <td style="mso-number-format:\@; border:1px solid #999;" title="휴대전화번호"><div class="left"><?php echo($Rows[mobile]);?></div></td>
      <td style="border:1px solid #999;" title="우편번호"><?php echo($Rows[zipcode]);?></td>
      <td style="border:1px solid #999;" title="주소"><div class="left"><?php echo($Rows[address01]);?>&nbsp;<?php echo($Rows[address02]);?></div></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[memo]);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[education]);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[career]);?></td>
      <td style="border:1px solid #999; background:#eee;"><?php echo($Rows[qualifications]);?></td>
      <?php if($cfg['module']['opt_department'] != 'N') {?><td style="border:1px solid #999; background:#eee;"><?php echo($Rows['department']);?></td><?php } ?>
      <?php if($cfg['module']['opt_team'] != 'N') {?><td style="border:1px solid #999; background:#eee;"><?php echo($Rows['team']);?></td><?php } ?>
      <?php if($cfg['module']['opt_function'] != 'N') {?><td style="border:1px solid #999; background:#eee;"><?php echo($Rows['function']);?></td><?php } ?>
      <?php if($cfg['module']['opt_certification'] != 'N') {?><td style="border:1px solid #999; background:#eee;"><?php echo($Rows['certification']);?></td><?php } ?>
    </tr>
<?php
    $n++;
    }
  }
  ?>
  </tbody>
</table>
