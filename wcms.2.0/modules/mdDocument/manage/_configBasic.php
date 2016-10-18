<?php
require_once "../../../_config.php";
require_once __PATH__."/_Admin/include/commonHeader.php";

if($_POST['type'] == "cateModPost")
{
	$func->checkRefer("POST");

	//넘어온 값과 변수 동기화 및 validCheck
	foreach($_POST AS $key=>$val)
	{
		${$key} = trim($val);
	}

	//콘텐츠 공유설정
	if($_POST['share'])
	{
		//타겟 문서함의 환경설정(게시물 수량) 취득
		$Rows = $db->queryFetch(" SELECT articled,articleTrashed FROM `mdDocument__` WHERE cate='".$_POST['share']."' ");
		$db->data['articled'] = $Rows['articled'];
		$db->data['trashed'] = $Rows['trashed'];
	}

	$db->data['skin']                       = $skin;
	$db->data['cate']                       = $cate;
	$db->data['share']                      = $share;
	$db->data['listUnion']                  = $listUnion;
	$db->data['boardType']                  = $boardType;
	$db->data['config']['list']             = $list;
	$db->data['config']['listView']         = $listView;
	$db->data['config']['listHcount']       = $listHcount;
	$db->data['config']['listVcount']       = $listVcount;
	$db->data['config']['cutSubject']       = $cutSubject;
	$db->data['config']['cutContent']       = $cutContent;
	$db->data['config']['uploadCount']      = $uploadCount;
	$db->data['config']['uploadType']       = $uploadType;
	$db->data['config']['recommand']        = $recommand;
	$db->data['config']['writerInfo']       = $writerInfo;
	$db->data['config']['readCount']        = $readCount;
	$db->data['config']['writer']           = $writer;
	$db->data['config']['regDate']          = $regDate;
	$db->data['config']['comment']          = $comment;
	$db->data['config']['download']         = $download;
	$db->data['config']['secret']           = $secret;
	$db->data['config']['sms']              = $sms;
	$db->data['config']['replyMail']        = $replyMail;
	$db->data['config']['replyImage']       = $replyImage;
	$db->data['config']['thumbIsFix']       = $thumbIsFix;
	$db->data['config']['thumbType']        = $thumbType;
	$db->data['config']['thumbSsize']       = $thumbSsize;
	$db->data['config']['thumbSsizeHeight'] = $thumbSsizeHeight;
	$db->data['config']['thumbMsize']       = $thumbMsize;
	$db->data['config']['thumbMsizeHeight'] = $thumbMsizeHeight;
	$db->data['config']['thumbBsize']       = $thumbBsize;
	$db->data['config']['thumbBsizeHeight'] = $thumbBsizeHeight;
	$db->data['config']['viewCategory']     = $viewCategory;
	$db->data['config']['category']         = $category;
	$db->data['config']['division']         = $division;
	$db->data['config']['addContent']       = $addContent;
	$db->data['config']['defaultContent']   = $defaultContent;
	$db->data['config']['sortType']					= $sortType;
	$db->data['config']                     = serialize($db->data['config']);
	$db->data['contentAdd'] = 'a:6:{s:12:"opt_division";s:1:"N";s:11:"opt_subject";s:1:"M";s:10:"opt_writer";s:1:"Y";s:9:"opt_email";s:1:"Y";s:9:"opt_phone";s:1:"N";s:13:"opt_agreement";s:1:"N";}';

	if($seted > 0)
	{
		$db->sqlUpdate("mdDocument__", "cate='".$db->data['cate']."'", array('cate','skin','contentAdd'), 0);
	}
	else
	{
		$db->sqlInsert("mdDocument__", "REPLACE", 0);
	}

	//그룹에 따라 게시판 테이블 생성
	if($_POST['prefix'])
	{
		include __PATH__."/modules/mdDocument/manage/_sql.php";
		foreach($sql['mdDocument'] AS $val)
		{
			$val = str_replace("_prefix_", substr($db->data['cate'], 0, 3), $val);
			$db->queryForce(trim($val));
		}
	}

	//동일 카테고리 일괄적용
	if($_POST['sameUnder'] == 'Y')
	{
		//$db->sqlUpdate("mdDocument__", "skin='".$skin."' AND config like '%".$list."%'", array('skin','cate','name'), 0);
	}

	//스타일 적용
	//$display->cacheCss($config['skin']);
	if(!preg_match('/\/_Admin\//', $_SERVER['HTTP_REFERER']))
	{
		$func->errCfm("문서·게시물 모듈 (환경설정)이 정상적으로 적용되었습니다.", "parent.$.dialogRemove();");
	}
	else
	{
		$func->err("문서·게시물 모듈 (환경설정)이 정상적으로 적용되었습니다.", "parent.$.tabMenu('tabDoc02','#tabBodyDoc02','../modules/mdDocument/manage/_configForm.php?cate=".__CATE__."&amp;skin=".$skin."',null,200);");
	}

}
else
{
	$func->checkRefer("GET");

	if(__CATE__)
	{
		$Rows = $db->queryFetch(" SELECT * FROM `mdDocument__` WHERE skin='".$_GET['skin']."' AND cate='".__CATE__."' ");
		$config = unserialize($Rows['config']);
		$config['cate'] = $Rows['cate'];
		$config['skin'] = ($Rows['skin']) ? $Rows['skin'] : $_GET['skin'];
		$config['listUnion'] = $Rows['listUnion'];
		$config['boardType'] = $Rows['boardType'];
	}
	else
	{
		$func->ajaxMsg("[".__CATE__."]카테고리 정보가 존재하지 않습니다.","", 100);
	}
}
?>
<div id="messageBox"><span><img src="<?php echo($cfg['droot']);?>common/image/icon/delete.gif" /></spna>&nbsp;</div>
<form id="frmCate" name="frmCate" method="post" enctype="multipart/form-data" target="hdFrame" action="<?php echo($_SERVER['PHP_SELF']);?>">
<input type="hidden" name="cate" value="<?php echo(__CATE__);?>" />
<input type="hidden" name="skin" value="<?php echo($config['skin']);?>" />
<input type="hidden" name="seted" value="<?php echo($db->getNumRows());?>" />
<input type="hidden" name="type" value="cateModPost" />

<table class="table_list" style="width:100%;">
<col width="140">
<col width="320">
<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span class="mg2">항 목</span></p></th>
			<th><p class="center"><span class="mg2">기본정보 설정</span></p></th>
			<th><p class="center"><span class="mg2">도움말</span></p></th>
		</tr>
	</thead>
	<tbody>
	<tr><th><label for="link2"><strong>사이트 유형</strong></label></th>
		<td><ol><li class="opt"><?php echo($config['skin']);?></li></ol></td>
		<td class="small_gray bg_gray"><ol><li class="opt"></li></ol></td>
	</tr>
	<tr><th><label for="link2"><strong>콘텐츠 공유</strong></label></th>
		<td><ol><li class="opt"><select id="share" class="bg_gray" name="share" style="width:290px" title="콘텐츠 공유">
			<option value="">연결 안함</option>
			<option value="">-------------------------------------------------------</option>
			<?php
			$db->query(" SELECT cate,name FROM `site__` WHERE skin='default' AND SUBSTRING(cate,1,3)<>'000' AND mode='mdDocument' ORDER BY cate ASC ");
			while($sRows = $db->fetch())
			{
				for($i=2;$i<=strlen($sRows['cate'])/3;$i++) { $blank .= "　"; }
				echo('<option value="'.$sRows['cate'].'"');
				if($Rows['share'] === $sRows['cate']) { echo(' selected="selected" class="colorRed"'); }
				echo('>'.$blank.' ('.$sRows['cate'].')'.$sRows['name'].'</option>');
				unset($blank);
			}
			?>
			</select></li>
			</ol>
		</td>
		<td class="small_gray bg_gray"><ol><li class="opt">다른 카테고리의 문서·게시물의 콘텐츠를 동일하게 노출</li></ol></td>
	</tr>
	<?php
	$form = new Form('table');

	$form->addStart('리스트 형태', 'list', 1, 0, 'M');
	$form->name = array('Page'=>'문서·페이지','List'=>'목록·리스트','ListMobile'=>'목록·리스트(모바일)','View'=>'뷰·리스트','Tabs'=>'탭·리스트','Gallery'=>'갤러리·앨범','GalleryTab'=>'갤러리·앨범(TAB)','Faq'=>'FAQ·자주하는질문','Cal'=>'일정·캘린더','Zine'=>'웹진·뉴스');
	$form->add('select', $form->name, $config['list'], 'width:290px;', '', 'onChange="return setDefaultDocument(this.value);"');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">콘텐츠 페이지 형태 설정</td>');
	$form->addEnd(1);

	$form->addStart('뷰페이지 목록형태', 'listView', 1, 0, 'M');
	$form->add('radio', array('N'=>'노출안함', 'Recent'=>'이전/다음글', 'List'=>'전체목록', 'Gallery'=>'사진목록'), $config['listView'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">뷰페이지 하단 리스트 설정</td>');
	$form->addEnd(1);

	$form->addStart('게시판 이용형태', 'boardType', 1);
	$form->add('radio', array('BASIC'=>'일반형', 'QNA'=>'상품문의', 'HUGI'=>'포토 후기', 'HUGIBASIC'=>'일반 후기'), $config['boardType'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">첨부 가능갯수 설정 (자료실의 용도일 경우만 선택)</td>');
	$form->addEnd(1);

	$form->addStart('게시물 노출순서', 'sortType', 1);
	$form->add('radio', array('idx'=>'등록순', 'regDate'=>'등록일'), $config['sortType'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">게시물의 노출 순서설정</td>');
	$form->addEnd(1);

	$form->addStart('첨부 파일형태', 'uploadType', 1);
	$form->add('radio', array('Basic'=>'일반형', 'Multi'=>'멀티형'), $config['uploadType'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">첨부 가능갯수 설정 (자료실의 용도일 경우만 선택)</td>');
	$form->addEnd(1);

	$form->addStart('첨부 파일갯수', 'uploadCount', 1);
	$form->add('select', array('0'=>'0개','1'=>'1개','3'=>'3개','5'=>'5개','10'=>'10개'), $config['uploadCount'], 'width:60px;');
	$form->addHtml('<li class="opt gray">개</li>');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">첨부 가능갯수 설정 (자료실의 용도일 경우만 선택)</td>');
	$form->addEnd(1);

	$form->addStart('추천 점수부여', 'recommand', 1);
	$form->add("select", array('0'=>'0점','5'=>'5점','10'=>'10점'), $config['recommand'], 'width:60px;');
	$form->addHtml('<li class="opt gray">점 (댓글허용시 연동)</li>');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">추천 가능점수 설정 (\'0\'점은 노출되지 않음)</td>');
	$form->addEnd(1);
?>
	</tbody>
</table>

<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>

<table class="table_list" style="width:100%;">
<col width="140">
<col width="320">
<col>
	<thead>
		<tr>
			<th class="first"><p class="center"><span class="mg2">항 목</span></p></th>
			<th><p class="center"><span class="mg2">기타정보 설정</span></p></th>
			<th><p class="center"><span class="mg2">도움말</span></p></th>
		</tr>
	</thead>
<?php
	$form->addStart('게시물 글자수', 'cutSubject', 1, 0);
	$form->addHtml('<li class="opt"><span>제목</span></li>');
	$form->add('input', 'cutSubject', $config['cutSubject'], 'width:40px; text-align:center;','digits="true" maxlength="3"');
	$form->addHtml('<li class="opt gray">자</li><li class="opt"><span>, 요약</span></li>');
	$form->id = 'cutContent';
	$form->add('input', 'cutContent', $config['cutContent'], 'width:40px; text-align:center;','digits="true" maxlength="3"');
	$form->addHtml('<li class="opt gray">자</li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>게시 목록의 제목과 요약내용의 글자 수</span>');
	$form->addEnd(1);

	$form->addStart('게시물 출력수', 'listHcount', 1, 0, 'M');
	$form->addHtml('<li class="opt"><span>가로</span></li>');
	$form->add('input', 'listHcount', $config['listHcount'], 'width:40px; text-align:center;','digits="true" maxlength="1"');
	$form->addHtml('<li class="opt gray">개</li><li class="opt"><span>, 세로</span></li>');
	$form->id = 'listVcount';
	$form->add('input', 'listVcount', $config['listVcount'], 'width:40px; text-align:center;','digits="true" maxlength="2"');
	$form->addHtml('<li class="opt gray">개</li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>게시 목록의 가로와 세로 노출 갯수 설정 (가로:열, 세로:행)</span>');
	$form->addEnd(1);

	$form->addStart('손톱이미지 비율', 'thumbIsFix', 1, 0, 'M');
	$form->add('radio', array('R'=>'비율형','F'=>'고정형'), $config['thumbIsFix'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">이미지 저장시 고정형인지 비율형인지 설정</td>');
	$form->addEnd(1);

	$form->addStart('손톱이미지 설정', 'thumbType', 1, 0, 'M');
	$form->add('radio', array('4,3'=>'가로형', '0'=>'가로형(배경)', '3,4'=>'세로형', '1,1'=>'정사각형'), $config['thumbType'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">이미지 저장시 자동 저장될 썸네일(손톱 이미지) 설정</td>');
	$form->addEnd(1);

	//$thumbSsizeDisabled = ($config['thumbIsFix'] == "F") ? "" : "disabled=disabled";
	$form->addStart('손톱이미지 크기[소]', 'thumbSsize', 1, 0);
	$form->addHtml('<li class="opt"><span>넓이</span></li>');
	$form->add('input', 'thumbSsize', $config['thumbSsize'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt gray">px</li><li class="opt"><span>, 높이</span></li>');
	$form->id = 'thumbSsizeHeight';
	$form->add('input', 'thumbSsizeHeight', $config['thumbSsizeHeight'], 'width:40px; text-align:center;','digits="true" maxlength="4"',$thumbSsizeDisabled);
	$form->addHtml('<li class="opt gray">px</li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>썸네일 크기[소]</span>');
	$form->addEnd(1);

	//$thumbMsizeDisabled = ($config['thumbIsFix'] == "F") ? "" : "disabled=disabled";
	$form->addStart('손톱이미지 크기[중]', 'thumbMsize', 1, 0);
	$form->addHtml('<li class="opt"><span>넓이</span></li>');
	$form->add('input', 'thumbMsize', $config['thumbMsize'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt gray">px</li><li class="opt"><span>, 높이</span></li>');
	$form->id = 'thumbMsizeHeight';
	$form->add('input', 'thumbMsizeHeight', $config['thumbMsizeHeight'], 'width:40px; text-align:center;','digits="true" maxlength="4"',$thumbMsizeDisabled);
	$form->addHtml('<li class="opt gray">px</li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>썸네일 크기[중]</span>');
	$form->addEnd(1);

	//$thumbBsizeDisabled = ($config['thumbIsFix'] == "F") ? "" : "disabled=disabled";
	$form->addStart('손톱이미지 크기[대]', 'thumbBsize', 1, 0);
	$form->addHtml('<li class="opt"><span>넓이</span></li>');
	$form->add('input', 'thumbBsize', $config['thumbBsize'], 'width:40px; text-align:center;','digits="true" maxlength="4"');
	$form->addHtml('<li class="opt gray">px</li><li class="opt"><span>, 높이</span></li>');
	$form->id = 'thumbBsizeHeight';
	$form->add('input', 'thumbBsizeHeight', $config['thumbBsizeHeight'], 'width:40px; text-align:center;','digits="true" maxlength="4"',$thumbBsizeDisabled);
	$form->addHtml('<li class="opt gray">px</li>');
	$form->addEnd(0);
	$form->addHtml('<td class="small_gray bg_gray"><ol><li><span>썸네일 크기[대]</span>');
	$form->addEnd(1);

	$form->addStart('등록정보 공개', 'writerInfo', 1, 0, 'M');
	$form->add('radio', array('N'=>'노출안함', 'M'=>'일부노출', 'Y'=>'전체노출'), $config['writerInfo'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">등록자 ID,이름,아이피,이메일,등록일 정보공개 여부</td>');
	$form->addEnd(1);

	$form->addStart('조회수 공개', 'readCount', 1, 0, 'M');
	$form->add('radio', array('Y'=>'노출함', 'N'=>'노출안함'), $config['readCount'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">조회수 공개 여부</td>');
	$form->addEnd(1);

	$form->addStart('등록자 공개', 'writer', 1, 0, 'M');
	$form->add('radio', array('Y'=>'노출함', 'N'=>'노출안함'), $config['writer'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">등록자 공개 여부</td>');
	$form->addEnd(1);

	$form->addStart('등록일 공개', 'regDate', 1, 0, 'M');
	$form->add('radio', array('Y'=>'노출함', 'N'=>'노출안함'), $config['regDate'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">등록일 공개 여부</td>');
	$form->addEnd(1);

	$form->addStart('비밀글 사용', 'secret', 1, 0, 'M');
	$form->add('radio', array('Y'=>'사용(선택)', 'M'=>'사용(필수)','N'=>'사용안함'), $config['secret'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">비밀글 작성 여부 설정</td>');
	$form->addEnd(1);

	$form->addStart('댓글달기 허용', 'comment', 1, 0, 'M');
	$form->add('radio', array('N'=>'허용안함', 'Y'=>'허용함'), $config['comment'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">댓글작성 가능 여부 설정</td>');
	$form->addEnd(1);

	$form->addStart('다운로드 허용', 'download', 1, 0, 'M');
	$form->add('radio', array('N'=>'허용안함', 'Y'=>'허용함'), $config['download'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">첨부된 파일의 다운로드 가능 여부</td>');
	$form->addEnd(1);

	$form->addStart('SMS발송 허용', 'sms', 1, 0, 'M');
	$form->add('radio', array('N'=>'발송안함','Y'=>'발송함'), $config['sms'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">게시글 등록시 운영자에게 알림메세지 문자발송 여부 설정</td>');
	$form->addEnd(1);

	$form->addStart('답글영역 노출', 'replyImage', 1, 0, 'M');
	$form->add('radio', array('N'=>'노출안함','Y'=>'노출함'), $config['replyImage'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">뷰페이지에 답글내용영역 노출 여부 설정</td>');
	$form->addEnd(1);

	$form->addStart('답변메일발송 허용', 'replyMail', 1, 0, 'M');
	$form->add('radio', array('N'=>'발송안함','Y'=>'발송함'), $config['replyMail'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">답변글 등록시 원글 작성에게 답변메일발송 여부 설정</td>');
	$form->addEnd(1);

	$form->addStart('데이터 형태', 'listUnion', 1, 0, 'M');
	$form->add('radio', array('N'=>'본 카테고리만', 'Y'=>'하위 카테고리 포함'), $config['listUnion'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">하위 카테고리 게시물을 포함시킬지 설정</td>');
	$form->addEnd(1);

	$form->addStart('카테고리명 노출', 'viewCategory', 1, 0, 'M');
	$form->add('radio', array('N'=>'노출안함','Y'=>'노출함'), $config['viewCategory'], 'color:black;');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray">리스트 및 뷰페이지에 카테고리명 노출 여부 설정</td>');
	$form->addEnd(1);

	$form->addStart('카테고리(구분) 설정', 'category', 1);
	$form->add('textarea', 'category', $config['category'], 'width:290px; height:32px;','maxlength="100"');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray"><span>게시물 카테고리 설정</span><span class="small_blue">( 콤마[ , ]구분입력 )</span><br /><span>( ex : 질문, 답변 )</span></td>');
	$form->addEnd(1);

	$form->addStart('말머리(구분) 설정', 'division', 1);
	$form->add('textarea', 'division', $config['division'], 'width:290px; height:32px;','maxlength="100"');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray"><span>게시물 머릿말 설정</span><span class="small_blue">( 콤마[ , ]구분입력 )</span><br /><span>( ex : 질문, 답변 )</span></td>');
	$form->addEnd(1);

	$form->addStart('추가입력 사항', 'addContent', 1);
	$form->add('textarea', 'addContent', $config['addContent'], 'width:290px; height:32px;','maxlength="100"');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray"><span>게시물 머릿말 설정</span><span class="small_blue">( 콤마[ , ]구분입력 )</span><br /><span>( ex : 질문, 답변 )</span></td>');
	$form->addEnd(1);

	$form->addStart('기본내용 설정', 'defaultContent', 1);
	$form->add('textarea', 'defaultContent', $config['defaultContent'], 'width:290px; height:32px;','maxlength="100"');
	$form->addEnd();
	$form->addHtml('<td class="small_gray bg_gray"><span>게시글 신규 등록시 내용에 들어갈 문구 설정</span><br /><span>( ex : 출석합니다. )</span></td>');
	$form->addEnd(1);

	if($config['list'] != 'Page')
	{
		$form->addStart('일괄적용', 'sameUnder', 1);
		$form->add('checkbox', 'sameUnder', 'N', 'color:red;');
		$form->addEnd();
		$form->addHtml('<td class="small_gray bg_gray">같은 모듈 동일한 리스트 형태의 환경설정 일괄 적용</td>');
		$form->addEnd(1);
	}
	?>
		<!--<tr><th><label for="upfile"><strong>답글파일첨부</strong></label></th>
		<td><ul>
<?php
	echo('<li class="opt"><input type="file" name="upfile" class="input_gray" style="width:200px;height:18px;">'.$config['replyImage'].'</li>');
?>
		</ul></td>
		<td class="small_gray bg_gray"><ol><li class="opt">답글 파일 업로드</li></ol></td>
		</tr>-->
	</tbody>
</table>
<div class="pd5 right"><span class="btnPack medium icon strong"><span class="check"></span><button type="submit" onclick="return $.submit(this.form)">위 설정으로 적용</button></span>&nbsp;<span class="btnPack black medium"><a href="javascript:;" onclick="$.dialogRemove();">취소</a></span></div>
</form>
<script type="text/javascript">
function setDefaultDocument(val)
{
	if(!val) return false;
	switch(val)
	{
		case "Page":
			$("#cutSubject").val('50');
			$("#cutContent").val('');
			$("#listHcount").val('1');
			$("#listVcount").val('1');
			$("#thumbSsize").val('');
			$("#thumbMsize").val('');
			$("#thumbBsize").val('');
		break;
		case "List": default:
			$("#cutSubject").val('50');
			$("#cutContent").val('');
			$("#listHcount").val('1');
			$("#listVcount").val('10');
			$("#thumbSsize").val('80');
			$("#thumbMsize").val('150');
			$("#thumbBsize").val('350');
		break;
		case "Tabs":
			$("#cutSubject").val('100');
			$("#cutContent").val('300');
			$("#listHcount").val('1');
			$("#listVcount").val('6');
			$("#thumbSsize").val('80');
			$("#thumbMsize").val('150');
			$("#thumbBsize").val('350');
		break;
		case "Faq":
			$("#cutSubject").val('100');
			$("#cutContent").val('300');
			$("#listHcount").val('1');
			$("#listVcount").val('6');
			$("#thumbSsize").val('80');
			$("#thumbMsize").val('150');
			$("#thumbBsize").val('350');
		break;
		case "Cal":
			$("#cutSubject").val('100');
			$("#cutContent").val('');
			$("#listHcount").val('1');
			$("#listVcount").val('1');
			$("#thumbSsize").val('80');
			$("#thumbMsize").val('140');
			$("#thumbBsize").val('350');
		break;
		case "Zine":
			$("#cutSubject").val('100');
			$("#cutContent").val('400');
			$("#listHcount").val('1');
			$("#listVcount").val('5');
			$("#thumbSsize").val('80');
			$("#thumbMsize").val('120');
			$("#thumbBsize").val('350');
		break;
		case "Gallery": case "Case": case "GalleryTab":
			$("#cutSubject").val('10');
			$("#cutContent").val('');
			$("#listHcount").val('4');
			$("#listVcount").val('2');
			$("#thumbSsize").val('80');
			$("#thumbMsize").val('150');
			$("#thumbBsize").val('350');
		break;
	}
	return true;
}
</script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('#frmCate').validate({onkeyup:function(element){$(element).valid();}});
	$("input[type=text],input[type=password],textarea").keyup(function(){var maxlen = parseInt($(this).attr('maxlength'));if($(this).val().length == maxlen) alert('최대 ' + maxlen + '자까지만 입력하실 수 있습니다!');});
});
//]]>
</script>
<?php
require_once __PATH__."/_Admin/include/commonScript.php";
?>

