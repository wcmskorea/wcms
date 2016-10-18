<?php
/* 환경설정 파일 */
require_once __PATH__."_Admin/include/commonHeader.php";
?>
<h2><span class="arrow">▶</span>회원.고객 모듈 환경설정</h2>
<div class="tabMenu2">
	<ul class="tabBox">
		<li class="tab on" id="tab01" style="margin-left:0;"><p><a href="javascript:;" onclick="$.tabMenu('tab01','#tabBody01','../modules/mdMember/manage/configBasic.php',null,200)" class="actgray" style="width:100px;">기본설정</a></p></li>
		<li class="tab" id="tab02"><p><a href="javascript:;" onclick="$.tabMenu('tab02','#tabBody02','../modules/mdMember/manage/configForm.php',null,200)" class="actgray" style="width:100px;">입력항목 설정</a></p></li>
        <li class="tab" id="tab03"><p><a href="javascript:;" onclick="$.tabMenu('tab03','#tabBody03','../modules/mdMember/manage/configLevel.php',null,200)" class="actgray" style="width:100px;">회원등급 설정</a></p></li>
        <?php if($func->checkModule('mdMileage')) { ?>
        <li class="tab" id="tab04"><p><a href="javascript:;" onclick="$.tabMenu('tab04','#tabBody04','../modules/mdMember/manage/configLevelPolicy.php',null,200)" class="actgray" style="width:100px;">회원등급별 정책</a></p></li>
        <?php } ?>
	</ul>
	<div class="tabBody show" id="tabBody01"></div>
	<div class="tabBody hide" id="tabBody02"></div>
    <div class="tabBody hide" id="tabBody03"></div>
    <div class="tabBody hide" id="tabBody04"></div>
    <script type="text/javascript">
    //<![CDATA[
        $(document).ready(function(){$.insert("#tabBody01","../modules/mdMember/manage/configBasic.php",null,200);});
    //]]>
    </script>
</div>
<?php
/* 공통 스크립트 */
require_once __PATH__."_Admin/include/commonScript.php";
?>