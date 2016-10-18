<?php
if(!defined('__CSS__')) { header("HTTP/1.0 404 Not found"); die(); }
if(!$cfg['module']['contentAdd'] && $cfg['module']['list'] != 'Page') { $func->err("입력항목이 설정되지 않았습니다. 환경설정에서 입력항목을 셋팅해주세요"); }

/**
 * 입력 옵션 환경설정 병합
 */
$cfg['module'] = @array_merge($cfg['module'], (array)unserialize($cfg['module']['contentAdd']));

/**
 * 답변글 여부
 */
if($_GET['num'])
{
    if(!$member->checkPerm(4))
    {
        $func->err($lang['doc']['notperm'], $errorScript);
    }
    $Rows = $db->queryFetch(" SELECT * FROM `".$cfg['cate']['mode']."__content".$prefix."` WHERE seq='".$_GET['num']."'" );
    if($db->getNumRows() < 1)
    {
        $func->err($lang['doc']['notfound'], $errorScript);
    }
    $Rows['subject']     = stripslashes($Rows['subject']);
    $Rows['subject']     = ($cfg['module']['list'] != 'Case') ? "(RE) ".htmlspecialchars($Rows['subject']) : htmlspecialchars($Rows['subject']);
    if($cfg['module']['division'])
    {
        @preg_match_all("#\[(.*?)\]#si", $Rows['subject'] ,$division);
        $Rows['subject'] = @trim(preg_replace("#\[(.*?)\]#si","", $Rows['subject']));
    }
    $writer = ($cfg['operator'] >= $_SESSION['ulevel']) ? $_SESSION['uname'] : $Rows['writer'];
    $Rows['content']     = stripslashes($Rows['content']);
    $Rows['content']     = ($cfg['module']['list'] != 'Case') ? '<p>&nbsp;</p><p>&nbsp;</p><p class="gray" style="text-align:center;">--- 아래의 내용은 <strong>'.$Rows['writer'].'</strong> 님께서 ( '.date('Y년m월d일 H시i분s초',$Rows['regDate']).' )에 작성하신 내용입니다 ---</p><p>&nbsp;</p>'.htmlspecialchars($Rows['content']) : htmlspecialchars($Rows['content']);
    $productSeq = ($Rows['productSeq']) ? $Rows['productSeq'] : "0";

} else
{
    $productSeq = ($_GET['productSeq']) ? $_GET['productSeq'] : "0";
}

$Rows['writer']     = ($Rows['writer']) ? stripslashes($Rows['writer']) : $_SESSION['uname'];
$Rows['content']    = ($Rows['content']) ? $Rows['content'] : "<p>&nbsp;</p>";
$Rows['content']    = ($cfg['module']['defaultContent']) ? str_replace('\r\n','<br/>',$cfg['module']['defaultContent']) : $Rows['content'];
$Rows['addContent'] = stripslashes($Rows['addContent']);
$Rows['addContent'] = htmlspecialchars($Rows['addContent']);
$addCcontent        = explode("|", $Rows['addContent']);
?>
<div class="docInput">
<form id="bbsform" name="bbsform" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
<input type="hidden" name="type" value="<?php echo($sess->encode('inputPost'));?>" />
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="mode" value="<?php echo($_GET['mode']);?>" />
<input type="hidden" name="parent" value="<?php echo($Rows['seq']);?>" />
<input type="hidden" name="fileName" value="" />
<input type="hidden" name="fileCount" value="<?php echo($Rows['file']);?>" />
<input type="hidden" name="currentPage" value="<?php echo($currentPage);?>" />
<input type="hidden" name="replyMail" value="<?php echo($Rows['email']);?>" />

<?php
$boardType = $db->queryFetchOne(" SELECT boardType FROM `mdDocument__` WHERE cate='".$cfg['module']['cate']."' limit 1 ");
?>
<input type="hidden" name="boardType" value="<?php echo($boardType);?>" />
<?php
if($productSeq != 0){
?>
<input type="hidden" name="productSeq" value="<?php echo($productSeq);?>" />
<input type="hidden" name="orderCode" value="<?php echo($_GET['orderCode']);?>" />
<?php
}
?>
<?php
if($cfg['module']['list'] != 'Page')
{
    echo('<table class="table_basic" summary="게시물 입력양식 입니다." style="width:100%;">
    <caption>게시물 등록하기</caption>
    <colgroup>
        <col width="17%">
        <col width="33%">
        <col width="17%">
        <col width="33%">
    <colgroup>
    <tbody>');

    //카테고리 선택
    if($cfg['module']['listUnion'] == 'Y')
    {
        echo('<tr>
            <th scop="row"><label for="subject" class="required"><span class="red" title="필수입력항목">*</span><strong>등 록 위 치</strong></label></th>
            <td colspan="3"><ol><li class="opt"><select name="move" class="bg_gray">');
            $db->query(" SELECT A.cate AS cate,B.name AS name FROM `".$cfg['cate']['mode']."__` AS A INNER JOIN `site__` AS B ON A.cate=B.cate AND SUBSTRING(A.cate,1,".strlen($cfg['module']['cate']).")='".$cfg['module']['cate']."' AND A.listUnion<>'Y' ORDER BY A.cate ASC ");
            while($sRows = $db->fetch())
            {
                echo('<option value="'.$sRows['cate'].'"');
                if($cfg['module']['cate'] == $sRows['cate']) echo(' selected="selected" style="color:#990000;"');
                echo('>'.$sRows['name'].'</option>');
            }
        echo('</select></li></ol></td></tr>');
    }

    $form = new Form('table');

    //카테고리 설정
    if($cfg['module']['category'] && $cfg['module']['opt_category'] != 'N' && !$_GET['num'])
    {
        $cateClass = explode(",", $cfg['module']['category']);
        $form->addStart('카테고리', 'category', 3, 0, $cfg['module']['opt_category']);
        $form->add('selectValue', $cateClass, $Rows['category'], 'color:black;');
        $form->addEnd(1);
    }
    //말머리 설정
    if($cfg['module']['division'] && $cfg['module']['opt_division'] != 'N' && !$_GET['num'])
    {
        $class = explode(",", $cfg['module']['division']);
        $form->addStart('구 분', 'division', 3, 0, $cfg['module']['opt_division']);
        $form->add('radio', $class, $division[1][0], 'color:black;');
        $form->addEnd(1);
    }
    if($cfg['module']['opt_subject'] != 'N')
    {
        $form->addStart('제 목', 'subject', 3, 0, $cfg['module']['opt_subject']);
        $form->add('input', 'subject', $Rows['subject'], 'width:99%;','maxlength="60"');
        $form->addEnd(1);
    }
    if($cfg['module']['opt_writer'] != 'N')
    {
        $form->addStart('작 성 자', 'writer', 1, 0, $cfg['module']['opt_writer']);
        $form->add('input', 'writer', $writer, 'width:170px; ime-mode:active','maxlength="10"');
        $form->addEnd(0);
    }
    if($cfg['module']['opt_email'] != 'N')
    {
        $form->addStart('이 메 일', 'email', 0, 0, $cfg['module']['opt_email']);
        $form->add('input', 'email', $Rows['email'], 'width:170px; ime-mode:disabled', 'email="true" maxlength="40"');
        $form->addEnd(1);
    }
    if($cfg['module']['opt_phone'] != 'N')
    {
        if(!$_SESSION['uid'])
        {
            $form->addStart('전 화 번 호', 'contact', 1, 0, $cfg['module']['opt_phone']);
            $form->add('input', 'contact', $Rows['phone'], 'width:170px;', 'phone="true" maxlength="14"');
            $form->addEnd(0);
        }
        else
        {
            $form->addStart('전 화 번 호', 'contact', 3, 0, $cfg['module']['opt_phone']);
            $form->add('input', 'contact', $Rows['phone'], 'width:170px;', 'phone="true" maxlength="14"');
            $form->addEnd(1);
        }
    }
    if($cfg['module']['opt_url'] != 'N')
    {
        $form->addStart('링크 URL', 'url', 3, 0, $cfg['module']['opt_url']);
        $form->add('input', 'url', $Rows['url'], 'width:99%;','maxlength="100"');
        $form->addEnd(1);
    }

    if(!$_SESSION['uid'] || ($cfg['module']['opt_passwd'] && $cfg['module']['opt_passwd'] != 'N')) //운영자 패스워드 입력여부 업데이트 : 이성준 - 2013년 2월 28일 목요일
    {
        $cfg['module']['opt_passwd'] = ($cfg['module']['opt_passwd']) ? $cfg['module']['opt_passwd'] : 'M';
        if($cfg['module']['opt_phone'] != 'N') { //전화번호 입력시 colspan 조절
            $form->addStart('비 밀 번 호', 'passwd', 0, 0, $cfg['module']['opt_passwd']);
        } else {
            $form->addStart('비 밀 번 호', 'passwd', 3, 0, $cfg['module']['opt_passwd']);
        }
        $form->add('input', 'passwd', "", 'width:170px;');
        $form->addEnd(1);
    }

    /* 추가입력사항 : addContent */
    if($cfg['module']['addContent'])
    {
        $addOpt = explode(",", $cfg['module']['addContent']);
        foreach($addOpt AS $key=>$val)
        {
            $form->addStart($val, 'addopt['.$key.']', 3);
            $form->add('input', 'addopt['.$key.']', $Rows['subject'], 'width:99%;');
            $form->addEnd(1);
        }
    }
    echo('</tbody></table>').PHP_EOL;


    //table -> element로 형태변환
    $form->type = 'element';

    switch($cfg['module']['list'])
    {
        case "List"    : default    : $secret = "비밀글";     $notice = "공지글";        break;
        case "Cal"                : $secret = "비공개일정";    $notice = "중요일정";     break;
        case "Faq"                : $secret = "비공개질문";    $notice = "공통질문";        break;
    }
    //공지 및 비밀글 옵션
    echo('<div class="docOpt">');

    echo('<div class="agree">');
    $form->addStart("개인정보취급방침 동의", 'useAgree', 1, 0, 'M');
    $form->add('checkbox', 'useAgree', ($member->checkPerm('0')) ? 'Y' : null, 'font-weight:bold;');
    $form->addEnd();
    echo('</div>');

    if($cfg['module']['secret']!='N')
    {
        $Rows['useSecret'] = $Rows['useSecret'] ? $Rows['useSecret'] : ($cfg['module']['secret']=='M' ? 'Y': '');
        echo('<div class="secret">');
        $form->addStart($secret, 'useSecret', 1, 0, $cfg['module']['secret']);
        $form->add('checkbox', 'useSecret', $Rows['useSecret'], 'font-weight:bold;');
        $form->addEnd();
        echo('</div>');
    }
    if($member->checkPerm('0'))
    {
        echo('<div class="notice">');
        $form->addStart($notice, 'useNotice', 1, 0, 'Y');
        $form->add('checkbox', 'useNotice', 'N', 'font-weight:bold;');
        $form->addEnd();
        echo('</div>');
    }
    echo('<div class="clear"></div></div>').PHP_EOL;
}

//상세내용 : 구형 에디터
//if($cfg['module']['opt_content'] != 'N') {
//    $form->addStart('게시물 내용', 'contents', 1, 0, 'Y');
//    $form->add('editor', 'content', $Rows['content'], 'width:99%; height:200px;');
//    $form->addEnd();
//}

//상세내용 : 다음 에디터
if($cfg['module']['opt_content'] != 'N') { include __PATH__."addon/editor/editor.php"; }

//날짜선택
if($member->checkPerm('0') === true)
{
    if($cfg['module']['list'] == 'Page') { $form = new Form('element'); }
    echo('<div class="cube" id="dateSelector"><div class="line">');

    if(preg_match('/Cal|Forum/', $cfg['module']['list']))
    {
        //날짜를 클릭하고 작성할 경우 해당 날짜 자동삽입
        if($_GET['year'] & $_GET['month'] & $_GET['day'])
        {
            $redate = strtotime($_GET['year'].'-'.$_GET['month'].'-'. $_GET['day'].' 00:00:00');
            $endate = strtotime($_GET['year'].'-'.$_GET['month'].'-'. $_GET['day'].' 23:59:59');
            $checked = ' checked="checked"';
        } else {
            $redate = null;
            $endate = null;
            $checked = null;
        }
        $form->addStart('일정 시작일', 'reyear', 3, 0, "Y");
        $form->addHtml('<span class="keeping"><input type="checkbox" id="redate" name="redate" class="input_check active"'.$checked.' value="Y" /><label for="redate" class="bold">시작일 변경</label></span>&nbsp;');
        $form->add('datemin', 're', $redate);
        $form->addHtml('&nbsp;<span>부터</span>&nbsp;<span class="small_gray">(미선택시 금일 날짜로 등록)</span><br />');
        $form->addEnd();
        $form->addStart('일정 종료일', 'reyear', 3, 0, "Y");
        $form->addHtml('<span class="keeping"><input type="checkbox" id="endate" name="endate" class="input_check active"'.$checked.' value="Y" /><label for="endate" class="bold">종료일 변경</label></span>&nbsp;');
        $form->add('datemin', 'en', $endate);
        $form->addHtml('&nbsp;<span>까지</span>&nbsp;<span class="small_gray">(미선택시 시작일 날짜로 등록)</span>');
        $form->addEnd();
    }
    else
    {
        $form->addStart('작　성　일', 'reyear', 3, 0, "Y");
        $form->addHtml('<span class="keeping"><input type="checkbox" id="redate" name="redate" class="input_check active" value="Y" /><label for="redate" class="bold">작성일 변경</label></span>&nbsp;');
        $form->add('datetime', 're', null);
        $form->addHtml('&nbsp;<span class="small_gray">(앞쪽 체크박스를 선택하면 자동 적용됩니다)</span>');
        $form->addEnd();
    }

    echo('</div></div>');
}

//첨부파일
include __PATH__."addon/system/upLoadFile.php";
?>

<?php
if($_GET['num'])
{
?>
<div class="center pd5"><span class="btnPack black medium strong"><button type="submit"<?php if($cfg['module']['uploadCount'] > 0 && $cfg['module']['uploadType'] == 'Multi'){echo(' onclick="javascript:NfUpload.FileUpload();"');}else{echo(' onclick="return submitForm(this.form);"');}?> class="red"><?php echo($lang['doc']['submit']);?></button>
</span>&nbsp;<span class="btnPack gray medium"><a href="<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>&type=view&num=<?php echo($_GET['num']);?>" ><?php echo($lang['doc']['cancel']);?></a></span></div>
<?php
}
else
{
?>
<div class="center pd5"><span class="btnPack black medium strong"><button type="submit"<?php if($cfg['module']['uploadCount'] > 0 && $cfg['module']['uploadType'] == 'Multi'){echo(' onclick="javascript:NfUpload.FileUpload();"');}else{echo(' onclick="submitForm(this.form);"');}?> class="red"><?php echo($lang['doc']['submit']);?></button>
</span>&nbsp;<span class="btnPack gray medium"><a href="<?php echo($_SERVER['PHP_SELF']);?>?<?php echo(__PARM__);?>"><?php echo($lang['doc']['cancel']);?></a></span></div>
<?php
}
?>
</div>
</form>

<script language="javascript" type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
jQuery(function($)
{
    $("#redate").click(function()
    {
        if($("#redate:checked").length > 0)
        {
            var thisDate = new Date();
            $("#reyear").val(thisDate.getFullYear()).css('color','red');
            $("#remonth").val(thisDate.getMonth()+1).css('color','red');
            $("#reday").val(thisDate.getDate()).css('color','red');
            $("#rehour").val(thisDate.getHours()).css('color','red');
            $("#remin").val('00').css('color','red');
            $("#resec").val('0').css('color','red');
        }
        else
        {
            $("#reyear option:first").attr("selected", true).css('color','black');
            $("#remonth option:first").attr("selected", true).css('color','black');
            $("#reday option:first").attr("selected", true).css('color','black');
            $("#rehour option:first").attr("selected", true).css('color','black');
            $("#remin option:first").attr("selected", true).css('color','black');
            $("#resec option:first").attr("selected", true).css('color','black');
        }
    });
    $("#endate").click(function()
    {
        if($("#endate:checked").length > 0)
        {
            var thisDate = new Date();

            $("#enyear").val(thisDate.getFullYear()).css('color','red');
            $("#enmonth").val(thisDate.getMonth()+1).css('color','red');
            $("#enday").val(thisDate.getDate()).css('color','red');
            $("#enhour").val('11').css('color','red');
            $("#enmin").val('59').css('color','red');
            $("#ensec").val('59').css('color','red');
        }
        else
        {
            $("#enyear option:first").attr("selected", true).css('color','black');
            $("#enmonth option:first").attr("selected", true).css('color','black');
            $("#enday option:first").attr("selected", true).css('color','black');
            $("#enhour option:first").attr("selected", true).css('color','black');
            $("#enmin option:first").attr("selected", true).css('color','black');
            $("#ensec option:first").attr("selected", true).css('color','black');
        }
    });
});
//]]>
</script>
