<?php
/* 환경설정 파일 */
require_once "../../../_config.php";
/* 공통헤더 파일 */
require_once __PATH__."/_Admin/include/commonHeader.php";

if($_POST['type'] == "cateModPost")
{
    $func->checkRefer("POST");

    //넘어온 값과 변수 동기화 및 validCheck
    foreach($_POST AS $key=>$val)
    {
        ${$key} = trim($val);
    }

    $db->data['cate'] = $cate;
    $db->data['config']['form']  = $form;
    $db->data['config']['cert']  = $cert;
    $db->data['config']['find']  = $find;
    $db->data['config']['sms']   = $sms;
    $db->data['config']['group'] = $group;
    /*$db->data['config']['uploadCount']      = $uploadCount;
    $db->data['config']['uploadType']       = $uploadType;
    $db->data['config']['thumbIsFix']       = $thumbIsFix;
    $db->data['config']['thumbType']        = $thumbType;
    $db->data['config']['thumbSsize']       = $thumbSsize;
    $db->data['config']['thumbSsizeHeight'] = $thumbSsizeHeight;
    $db->data['config']['thumbMsize']       = $thumbMsize;
    $db->data['config']['thumbMsizeHeight'] = $thumbMsizeHeight;
    $db->data['config']['thumbBsize']       = $thumbBsize;
    $db->data['config']['thumbBsizeHeight'] = $thumbBsizeHeight;*/
    $db->data['config'] = serialize($db->data['config']);

    $db->sqlUpdate("mdMember__", "cate IN ('000002001','000002002','000002003')", array('cate','contentAdd'), 0);

    $func->setLog(__FILE__, "고객·회원 모듈의 환경설정 변경", true);
    $func->err("고객·회원 모듈 (환경설정)이 정상적으로 적용되었습니다.");
}
else
{
    $func->checkRefer("GET");
    if(defined('__CATE__'))
    {
        $Rows = $db->queryFetch(" SELECT * FROM `mdMember__` WHERE cate='000002001' ");
        $config = unserialize($Rows['config']);
    }
    else
    {
        $func->ajaxMsg("[".__CATE__."]카테고리 정보가 존재하지 않습니다.","", 100);
    }
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;<strong>Message :</strong>&nbsp;&nbsp;&nbsp;</div>
<fieldset id="help">
<legend> < TIP's ></legend>
<ul>
	<li>본 페이지는 회원모듈의 유형 및 환경설정을 관리합니다.</li>
    <li>메뉴얼 및 우측 도움말을 확인하시어 신중히 설정하시기 바랍니다.</li>
</ul>
</fieldset>
<form id="frmCate" name="frmCate" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data" target="hdFrame">
<input type="hidden" name="cate" value="000002001" />
<input type="hidden" name="type" value="cateModPost" />

<table class="table_list" style="width:100%;">
<colgroup>
    <col width="140">
    <col width="400">
    <col>
</colgroup>
    <thead>
        <tr>
            <th class="first"><p class="center"><span class="mg2">항 목</span></p></th>
            <th><p class="center"><span class="mg2">기본정보 설정</span></p></th>
            <th><p class="center"><span class="mg2">도움말</span></p></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $form = new Form('table');

    $form->addStart('회원가입 형태', 'form', 1, 0, 'M');
    $form->add('radio', array('Basic'=>'일반용'), $config['form'], 'color:black;');
    $form->addEnd(0);
    $form->addHtml('<td class="small_gray bg_gray">회원가입 양식 설정</td>');
    $form->addEnd(1);

    $form->addStart('실명인증 서비스', 'cert', 1, 0, 'M');
    $form->add('radio', array('Pass'=>'사용안함','Rname'=>'실명인증'), $config['cert'], 'color:black;');
    $form->addEnd(0);
    $form->addHtml('<td class="small_gray bg_gray">회원가입 및 변경시 본인인증 절차 사용여부</td>');
    $form->addEnd(1);

    $form->addStart('임시 비밀번호', 'find', 1, 0, 'M');
    $form->add('radio', array('email'=>'email 전송','sms'=>'email + SMS 전송'), $config['find'], 'color:black;');
    $form->addEnd(0);
    $form->addHtml('<td class="small_gray bg_gray">아이디/비밀번호 찾기시 임시비밀번호 발송방법</td>');
    $form->addEnd(1);

    $form->addStart('가입안내 문자발송', 'sms', 1, 0, 'M');
    $form->add('radio', array('N'=>'발송안함','M'=>'회원만','O'=>'운영자만','B'=>'둘다발송'), $config['sms'], 'color:black;');
    $form->addEnd(0);
    $form->addHtml('<td class="small_gray bg_gray">신규 회원등록시 문자발송 여부 선택</td>');
    $form->addEnd(1);

    $form->addStart('회원그룹 설정', 'group', 1);
    $form->add('textarea', 'group', $config['group'], 'width:384px; height:50px;','maxlength="100"');
    $form->addEnd(0);
    $form->addHtml('<td class="small_gray bg_gray"><ol><li><span>회원 그룹별 구분을 위한 설정</span><span class="small_blue">( 콤마[ , ]구분입력 )</span></li></ol></td>');
    $form->addEnd(1);
    ?>
</table>
<div class="pd5 center"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span></div>
</form>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
/* 공통 스크립트 */
require_once __PATH__."_Admin/include/commonScript.php";
?>