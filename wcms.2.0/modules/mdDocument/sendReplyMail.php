<?php
//=================================================================================================
// 프로젝트 : 10억홈피 WCMS
// 처리내용 : 게시판 답변글 등록시 이메일발송
//=================================================================================================
if(!defined('__CSS__')) header("HTTP/1.0 404 Not found");

$mailText = '
<link rel="stylesheet" href="/common/css/default.css" type="text/css" charset="utf-8" media="all" />
<link rel="stylesheet" href="/user/default/cache/stylesheet.css" type="text/css" charset="utf-8" media="all" />
<div id="content" style="background-color:#FFFFFF;">
<div class="document">
	<div class="docRead">
		<div class="readHeader">
			<div class="titleAndUser">
				<div class="title"><h4>'.$_POST['subject'].'</h4></div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>

		<!-- Content : Start -->
		<div class="contentBody textContent">'.$db->data['content'].'</div>
		<div class="clear"></div>
		<div class="replyContent"></div>			
	</div>
</div>    
</div>';

//echo $mailText;
// 메일송신여부확인(결제자) -------------------------------
$member->sendMail($_POST['replyMail'], $cfg['site']['email'], $_POST['subject'], $mailText, $cfg['site']['siteName']);
?>