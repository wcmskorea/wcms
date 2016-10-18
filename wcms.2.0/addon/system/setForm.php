<?php
/**
 * Author : Sung-Jun, Lee
 * Lastest : 2010. 4. 17.
 */
?>
<div id="setup_layout" style="background: url(<?php echo($cfg['droot']);?>common/image/background/back.jpg) repeat-x;">
<div id="setup_wrap">
<div id="setup_header">
<div class="line_2_gray">
<div class="head_violet">
<h1>『 WCMS <?php echo($cfg['version']);?> 』로 새로운 사이트를 구성합니다!</h1>
</div>
</div>
</div>

<div id="setup_container">

	<div class="tabMenu2">
	<ul class="tabBox">
		<li class="tab on" id="tab01" onclick="activeTab(this)" style="margin-left:0;"><p><a href="javascript:;" style="width:280px;">기계약 고객</a></p></li>
		<!--<li class="tab" id="tab02" onclick="activeTab(this)"><p><a href="javascript:;" style="width:280px;">신규 회원등록</a></p></li>-->
		<li class="tab" id="tab02" onclick="alert('(주)10억홈피와 별도의 계약을 통해 이용하실 수 있습니다!');"><p><a href="javascript:;" style="width:280px;">미계약 고객</a></p></li>
	</ul>
	
	<!-- TAB 1번 : Start -->
	<div class="tabBody show" id="tabBody01">
    <br />
	<form name="regist" method="post" action="https://<?php echo($_SERVER['HTTP_HOST']);?>:<?php echo($cfg['site']['ssl']);?><?php echo($cfg['droot']);?>set.php" enctype="multipart/form-data">
	<input type="hidden" name="type" value="<?php echo($sess->encode('postSeted'));?>" />
		<p class="right small_gray pd5">(<span class="colorRed" title="필수입력항목">*</span>)나 하늘색 입력항목은 필수 입력항목입니다.</p>
		<table class="table_basic" summary="회원가입을 위한 개인정보 입력 항목" style="width: 100%;">
			<caption>회원가입 항목</caption>
			<col width="130">
			<col>
			<thead>
				<tr>
					<th colspan="2" class="first"><p class="center pd7">계 정 정 보</p></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$form = new Form('table');
			
			$form->addStart('아 이 디', 'userid', 1, 0 ,'M');
			$form->add('input', 'userid', $Rows['id'], 'width:120px;', '');
			$form->addHtml('<li class="opt"><span class="colorGray">(10억홈피에 등록된 아이디)</span></li>');
			$form->addEnd(1);
			
			$form->addStart('비밀번호', 'userpasswd', 1, 0 ,'M');
			$form->add('input', 'userpasswd', null, 'width:120px;', '');
			$form->addHtml('<li class="opt"><span class="colorGray">(10억홈피에 등록된 비밀번호)</span></li>');
			$form->addEnd(1);

			$form->addStart('인증KEY', 'authCode', 1, 0 ,'M');
			$form->add('input', 'authCode', null, 'width:120px;', '');
			$form->addHtml('<li class="opt"><span class="colorGray">(등록한 이메일로 발급받은 인증키)</span></li>');
			$form->addEnd(1);
			?>
			</tbody>
		</table>
		<div class="pd10 center"><span class="btnPack black medium icon strong"><span class="add"></span><button type="submit" onclick="return $.submit(this.form);">신규 생성 하기</button></span></div>
	</form>
	</div>
	<!-- TAB 1번 : End -->

	<!-- TAB 2번 : Start -->
	<div class="tabBody hide" id="tabBody02">
    <?php
		$privacy  = @file_get_contents(__HOME__."/cache/document/000999003001.html");
		echo('<div class="agreement">
		<h3 class="hide">개인정보취급방침 안내</h3>
		<div class="frame textContent pd10 bg_gray" style="height:100px; font-size:11px;">'.stripslashes(str_replace("본 웹사이트", "<strong>".$cfg['site']['siteName']."</strong>", $privacy)).'</div>
		<div class="pd5"><span class="colorRed"><input type="checkbox" id="agree2" name="agree2" class="input_check required" title="개인정보 수집 및 취급방침에 동의는 필수선택 입니다." value="Y" />&nbsp;<label for="agree2">개인정보 수집 및 취급방침에 동의 합니다.</label></span></div>
		</div><!-- .agreement end -->').PHP_EOL;
	?>
	<br />
	<form name="setup" method="post" action="<?php echo($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
	<input type="hidden" name="type" value="<?php echo($sess->encode('postSetting'));?>" />
	<input type="hidden" name="checkuserid" id="checkuserid" title="아이디 중복체크" value="" />
	<input type="hidden" name="checkuseremail" id="checkuseremail" title="이메일 중복체크" value="" />
	
		<p class="right small_gray pd5">(<span class="colorRed" title="필수입력항목">*</span>)나 하늘색 입력항목은 필수 입력항목입니다.</p>
		<table class="table_basic" summary="회원가입을 위한 개인정보 입력 항목" style="width:100%;">
			<caption>회원가입 항목</caption>
			<col width="130">
			<col>
			<thead>
				<tr>
					<th colspan="2" class="first"><p class="center pd7">회 원 정 보</p></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$form->addStart('이 름', 'name', 1, 0, 'M');
			$form->add('input', 'name', $Rows['name'], 'width:120px;');
			$form->addEnd(1);
			
			$form->addStart('아이디', 'id', 1, 0 ,'M');
			$form->add('input', 'id', $Rows['id'], 'width:120px;', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserId").'\',\'Tid\');"');
			$form->addHtml('<li class="opt"><span id="checkId" class="small_orange"></span></li>');
			$form->addEnd(1);
			
			$form->addStart('비밀번호', 'passwd', 1, 0 ,'M');
			$form->add('input', 'passwd', null, 'width:120px;');
			$form->addHtml('<li class="opt"></li>');
			$form->addEnd(1);
			
			$form->addStart('비밀번호 확인', 'repasswd', 1, 0 ,'M');
			$form->add('input', 'repasswd', null, 'width:120px;', 'match="passwd"');
			$form->addHtml('<li class="opt"><span class="small_gray">입력한 비밀번호와 동일하게 입력하세요.</span></li>');
			$form->addEnd(1);
			
			$form->addStart('이메일', 'email', 1, 0 , 'M');
			$form->add('input', 'email', $Rows['email'], 'width:185px;', 'onblur="$.checkOverLap(\''.$sess->encode("checkUserEmail").'\',\'Email\');"');
			$form->addHtml('<li class="opt"><span id="checkEmail" class="small_orange"></span></li>');
			$form->addEnd(1);
    		?>
			<thead>
				<tr>
					<th colspan="2" class="first"><p class="center pd7">기 업 정 보</p></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$form->addStart('회사명', 'groupName', 1, 0 , 'Y');
			$form->add('input', 'groupName', $Rows['groupName'], 'width:330px;');
			$form->addEnd(1);
		
			$form->addStart('등록(사업자)번호', 'groupNo', 1, 0 , 'Y');
			$form->add('input', 'groupNo', $Rows['groupNo'], 'width:330px;');
			$form->addEnd(1);
		
			$form->addStart('대표(담당)자명', 'ceo', 1, 0 , 'Y');
			$form->add('input', 'ceo', $Rows['ceo'], 'width:330px;');
			$form->addEnd(1);
		
			$form->addStart('업태', 'status', 1, 0 , 'Y');
			$form->add('input', 'status', $Rows['status'], 'width:330px;');
			$form->addEnd(1);
		
			$form->addStart('업종', 'class', 1, 0 , 'Y');
			$form->add('input', 'class', $Rows['class'], 'width:330px;');
			$form->addEnd(1);
		
			$form->addStart('통신판매신고번호', 'econo', 1, 0 , 'Y');
			$form->add('input', 'class', $Rows['class'], 'width:330px;');
			$form->addEnd(1);

            $form->addStart('사무실 전화번호', 'office', 1, 0 , 'Y');
			$form->add('input', 'office', $Rows['office'], 'width:120px;', 'opt="phone"');
			$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 062-000-0000)</span></li>');
			$form->addEnd(1);
		
			$form->addStart('사무실 팩스번호', 'fax', 1, 0 , 'Y');
			$form->add('input', 'fax', $Rows['fax'], 'width:120px;', 'opt="phone"');
			$form->addHtml('<li class="opt"><span class="small_gray"> (예 : 062-000-0000)</span></li>');
			$form->addEnd(1);
			?>
			<tbody>
		</table>
		<div class="pd10 center"><span class="btnPack black medium icon strong"><span class="add"></span><button type="submit" onclick="return $.submit(this.form);">신규 생성 하기</button></span></div>
	</form>
	</div>
	<!-- TAB 2번 : End -->
	</div>
<div id="setup_footer">
<ul>
	<li>본 서비스에 등록하시기 전에 <u>이용약관</u>을 충분히 숙지하시기 바랍니다. [약관보기]</li>
	<li>본 서비스에 신규 등록함과 동시에 비회원의 경우 <u>10억홈피 사이트의 회원</u>으로 <u>자동등록</u> 됩니다.</li>
	<li class="accent">고객센터 : <strong>062)374-4242~4</strong> (오전 09시00분~
	오후 7시까지)</li>
</ul>
</div>

</div><!-- container -->
</div><!-- wrap -->
</div><!-- layout --> 
