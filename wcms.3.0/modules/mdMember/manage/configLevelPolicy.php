<?php
/* 환경설정 파일 */
require_once "../../../_config.php";
/* 공통헤더 파일 */
require_once __PATH__."/_Admin/include/commonHeader.php";

if($_POST['type'] == 'configLevelPolicy')
{
    if(!$db->checkTable('`mdShop__grades`')) $func->err("쇼핑몰 관련 모듈이 설치되지 않았습니다.");

    //넘어온 값과 변수 동기화 및 validCheck
    foreach($_POST AS $key=>$val)
    {
        $db->data[$key] = trim($val);
        #$func->validCheck(체크할 값, 항목제목, 체크타입, 필수항목, 항목명(타겟))
        if($key == "level")       { $func->vaildCheck($val, "등급순위", "trim");}
        if($key == "addPointRate"){ $func->vaildCheck($val, "추가적립", "trim");}
        if($key == "discountRate"){ $func->vaildCheck($val, "추가할인", "trim");}
        if($key == "summary")     { $func->vaildCheck($val, "관련설명", "trim");}
        if($key == "startPayment"){ $func->vaildCheck($val, "실적범위[최소금액]", "trim");}
        if($key == "endPayment")  { $func->vaildCheck($val, "실적범위[최대금액]", "trim");}
    }

    $db->data['seq']    = ($_POST['idx']) ? $_POST['idx'] : NULL;
    $db->data['modifier']     = $_SESSION['uid'];

    if($db->sqlInsert("`mdShop__grades`","REPLACE", false))
    {
        $func->setLog(__FILE__, "회원등급별 정책정보 변경", true);
        $func->err("회원등급 정보가 정상적으로 적용되었습니다.", "parent.$.tabMenu('tab04','#tabBody04','../modules/mdMember/manage/configLevelPolicy.php',null,200)");
    }
    else
    {
        $func->err("회원등급 정보가 변경된 내용이 없거나, 적용 실패입니다.");
    }
}
else
{
    if($_GET['del'] == "yes")
    {
        $db->query(" DELETE FROM `mdShop__grades` WHERE seq='".$_GET['idx']."' ");
        if($db->getAffectedRows() > 0)
        {
            $db->query(" OPTIMIZE TABLE `mdShop__grades` ");
            $func->setLog(__FILE__, "회원등급별 정책정보 삭제", true);
            $func->err("회원등급별 정책정보가 삭제되었습니다.", "parent.$.tabMenu('tab04','#tabBody04','../modules/mdMember/manage/configLevelPolicy.php',null,200)", 20);
        }
        else
        {
            $func->err("회원등급별 정책정보가 변경된 내용이 없거나, 적용 실패입니다.", "", 20);
        }
    } 
    else if($_GET['idx']) 
    { 
        $Rows = $db->queryFetch(" SELECT * FROM `mdShop__grades` WHERE seq='".$_GET['idx']."' "); 
    }
}

?>
<fieldset id="help">
<legend> < TIP's > </legend>
<ul>
    <li>본 페이지는 회원등급별 차등 할인 및 적립혜택 설정을 관리합니다.</li>
    <li>추가할인 또는 추가적립은 100 이하일 경우 %(비율) 적용이 되며 100 이상일 경우 금액으로 계산됩니다.</li>
</ul>
</fieldset>

<form name="frmCate" method="post" target="hdFrame" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="configLevelPolicy" />
<input type="hidden" name="idx" value="<?php echo($Rows['seq']);?>" />

<table class="table_basic" style="width:100%;">
    <caption>회원등급 설정</caption>
    <col width="140">
    <col>
    <thead>
        <tr><th colspan="4" class="first"><p class="center"><span>회원등급 설정</span></p></th></tr>
    </thead>
    <tbody>
    <?php
        $memberLevel = $db->query(" SELECT level,position FROM `mdMember__level` WHERE position !='' && level != 99");
        while($levelRows = $db->fetch())
        {
            $levelInfo[$levelRows['level']] = $levelRows['position'];
        }


        $form = new Form('table');
        
        /*
        $form->addStart('회원등급명', 'gradeName', 1);
        $form->add('input', 'gradeName', $Rows['gradeName'], 'width:100px;');
        $form->addEnd(0);
        */
        $form->addStart('회원등급', 'level', 1, 0, 'M');
        $form->add('radio', $levelInfo, $Rows['level'], 'width:100px;');
        $form->addEnd(1);
        
        $form->addStart('적립정책', 'addPointRate', 1);
        $form->add('input', 'addPointRate', $Rows['addPointRate'], 'width:50px;');
        $form->addHtml('<li class="opt"><span>%</span> 추가 적립적용</li>');
        $form->addEnd(1);

        $form->addStart('할인정책', 'discountRate', 1);
        $form->add('input', 'discountRate', $Rows['discountRate'], 'width:50px;');
        $form->addHtml('<li class="opt"><span>%</span> 추가 할인적용</li>');
        $form->addEnd(1);

        $form->addStart('실적범위', 'startPayment', 1, 0, 'M');
        $form->addHtml('<li class="opt"><span>총 누적 구매액이</span></li>');
        $form->add('input', 'startPayment', $Rows['startPayment'], 'width:50px; text-align:center;');
        $form->addHtml('<li class="opt"><span>원 이상 ~ </span></li>');
        $form->add('input', 'endPayment', $Rows['endPayment'], 'width:50px; text-align:center;');
        $form->addHtml('<li class="opt"><span>원 미만</span></li>');
        $form->addEnd(1);

        $form->addStart('관련설명', 'summary', 1);
        $form->add('input', 'summary', $Rows['summary'], 'width:310px;');
        $form->addEnd(1);
    ?>
    <tr>
        <td colspan="4"><div class="pd5 center">
        <?php
        if($_GET['idx'])
        {
          echo('<p style="vertical-align:middle"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 등급별 정책을 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="#none" onclick="$.insert(\'#tabBody03\',\'./site/shopBank.php\',null,200)">취소</a></span></p></div>');
        } else
        {
          echo('<span class="btnPack medium icon strong"><span class="check"></span><button type="submit">위 등급별 정책을 적용</button></span></div>');
        }
        ?>
        </div></td>
    </tr>
    </tbody>
</table>
</form>
<div class="table">
<table class="table_basic" style="width:100%;">
    <caption>회원등급 설정</caption>
    <col width="60">
    <col width="150">
    <col width="150">
    <col width="150">
    <col>
    <col width="150">
    <col width="95">
    <thead>
    <tr><th class="first"><div class="center">등급순위</div></th>
        <th><div class="center">회원등급명</div></th>
        <th><div class="center">추가 할인</div></th>
        <th><div class="center">추가 적립</div></th>
        <th><div class="center">실적 범위</div></th>
        <th><div class="center">관련 설명</div></th>
        <th><div class="center">관 리</div></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $db->query(" SELECT * FROM `mdShop__grades` WHERE 1 ORDER BY level ASC ");
    if($db->getNumRows() < 1)
    {
        echo('<tr><td class="blank" colspan=6">등록된 회원등급별 정책 정보가 없습니다.</td></tr>');
    } 
    else
    {
        while($Rows = $db->fetch())
        { 
            $ratePrint = ($Rows['discountRate'] <= 100) ? $Rows['discountRate'].' %' : $Rows['discountRate'].' 원';
            $pointPrint = ($Rows['addPointRate'] <= 100) ? $Rows['addPointRate'].' %' : $Rows['addPointRate'].' 원';

            echo('<tr class="active">
                <th><p class="center">'.$Rows['level'].'</p></th>
                <td><p class="center">'.$levelInfo[$Rows['level']].'</p></td>
                <td><p class="center">'.$ratePrint.'</p></td>
                <td><p class="center">'.$pointPrint.'</p></td>
                <td><p class="center">'.number_format($Rows['startPayment']).'원 이상 ~ '.number_format($Rows['endPayment']).'원 미만</p></td>
                <td><p class="center">'.$Rows['summary'].'</p></td>
                <td><p class="center"><span class="btnPack small"><a href="#none" onclick="$.insert(\'#tabBody07\',\'./site/shopGrade.php\',\'&amp;idx='.$Rows['seq'].'\',200);">수정</a></span>&nbsp;<span class="btnPack black small"><a href="#none" onclick="if(delThis()){$.message(\'./site/index.php\', \'&amp;type=shopGradePost&amp;idx='.$Rows['seq'].'&amp;del=yes\')}">삭제</a></span></p></td>
            </tr>');
        }
    }
    ?>
    </tbody>
</table>
<?php
/* 공통 스크립트 */
require_once __PATH__."_Admin/include/commonScript.php";
?>
