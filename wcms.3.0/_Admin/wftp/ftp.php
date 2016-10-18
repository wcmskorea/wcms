<?php
require_once "../../_config.php";
if(!isset($_SESSION['uid'])) { $func->err("관리자만 이용할 수 있습니다.","window.self.close()"); }
if($_SESSION['ulevel'] > $cfg['operator']) { $func->err("관리자만 이용할 수 있습니다.", "window.self.close()"); }
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo($cfg['site']['siteName']);?> :: 파일관리 시스템 [v<?php echo($cfg['version']);?>]</title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?php echo($cfg['charset']);?>" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta name="description" content="홈페이지,웹마스터,웹프로그래밍,웹디자인,유지보수,웹호스팅" />
	<meta name="keywords" content="자바스크립트,html,플래쉬,플래시,홈페이지,웹마스터,웹프로그래밍,웹디자인,유지보수" />
	<link rel="stylesheet" href="<?php echo($cfg['droot']);?>common/css/admin.css" type="text/css" charset="<?php echo($cfg['charset']);?>" media="all" />
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/common.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.ui.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.facebox.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/ajax.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/validation.js"></script>
	<script type="text/javascript" src="<?php echo($cfg['droot']);?>common/js/jquery.validate.js"></script>
<script type="text/javascript">
//<![CDATA[
	function order(a)
	{
		var frm = document.ftpFrm;
		var cnt = -1;
		for(var i=0; i<frm.elements.length; i++)
		{
			var e = frm.elements[i];
			if(e.type == "checkbox")
			{
				if(e.checked == true)
				{
					cnt++;
				}
			}
		}
		if(cnt < 1)
		{
			alert('선택된 폴더나 파일이 없습니다.');
			return false;
		}
		else
		{
			if(a == 1)
			{
				if(confirm(cnt + "개의 폴더(파일)을 백업하시겠습니까?") == true){
					frm.action = "<?php echo($cfg['droot']);?>_Site/backup.php";
					frm.method = 'POST';
					frm.rtotal.value = cnt;
					frm.submit();
				}
			}
			else if(a == 2)
			{
				if(confirm(cnt + "개의 폴더(파일)을 삭제하시겠습니까?") == true){
					frm.type.value = "delete";
					frm.rtotal.value = cnt;
					return $.checkFarm(frm,'./ftpProcess.php',300,100);
				}
			}
			else if(a == 3)
			{
				if(cnt > 1)
				{
					alert('이름 변경은 하나만 선택 가능합니다.');
					return false;
				}
				else
				{
					if(confirm("해당 폴더(파일)의 이름을 변경하시겠습니까?") == true){
						frm.type.value = "rename";
						return $.checkFarm(frm,'./ftpUpload.php','dialog','',400,120);
					}
				}
			}
			else
			{
				alert('잘못된 코드입니다.');
				return false;
			}
		}
	}

	function swfUpload()
	{
		arrMovie = new Array()
		var arr_num = 0;
		var objectTags = document.getElementsByTagName('object');
		var movie;
		for (i = 0; i < objectTags.length; i++ )
		{
			if(objectTags[i].getAttribute("method")=="single_upload" || objectTags[i].getAttribute("method")=="multi_upload")
			{
				if(document.getElementsByName(objectTags[i].getAttribute("id"))[0])
				{
					movie = document.getElementsByName(objectTags[i].getAttribute("id"))[0];
				}
				else
				{
					movie = document.getElementById(objectTags[i].getAttribute("id"));
				}
				if(movie.GetVariable("totalSize") > 0)
				{
					arrMovie[arr_num] = movie;
					arr_num++;
				}
			}
		}
		if(arrMovie.length>0)
		{
			if(arrMovie[0].getAttribute("method")=="single_upload" || arrMovie[0].parentNode.getAttribute("method")=="single_upload") arrMovie[0].height = 70;
			if(arrMovie[0].getAttribute("method")=="multi_upload" || arrMovie[0].parentNode.getAttribute("method")=="multi_upload") arrMovie[0].height = parseInt(20*arrMovie[0].GetVariable("listRows")+25+45,10);
			arrMovie[0].SetVariable( "startUpload", "" );
		}
		else
		{
			$.dialogRemove();
		}
	}
	function swfUploadComplete()
	{
		toGetElementById("ftpUploadButton1").className = "center pd5 hide";
		toGetElementById("ftpUploadButton2").className = "center pd5 show";
	}
    function fileTypeError( notAllowFileType ){ //허용하지 않은 형식의 파일일 경우 에러 메시지 출력
     alert("확장자가 " + notAllowFileType + "인 파일들은 업로드 할 수 없습니다.");
    }
    function overSize( limitSize ){ //허용하지 않은 형식의 파일일 경우 에러 메시지 출력
     alert("선택한 파일의 크기가 " + limitSize + "를 초과하여 업로드 할 수 없습니다.");
    }
    function version_error(){ //플래쉬 버전이 8 미만일 경우 에러 메시지 출력
     alert("플래쉬 버전이 8.0 이상인지 확인하세요.\n이미 설치하신 경우는 브라우저를 전부 닫고 다시 시작하세요.");
    }
    function httpError(){ //http 에러 발생시 출력 메시지
     alert("네트워크 에러가 발생하였습니다. 관리자에게 문의하세요.");
    }
    function ioError(){ //파일 입출력 에러 발생시 출력 메시지
     alert("입출력 에러가 발생하였습니다.\n 다른 프로그램에서 이 파일을 사용중인지 확인하세요.");
    }
    function onSecurityError(){ //파일 입출력 에러 발생시 출력 메시지
     alert("보안 에러가 발생하였습니다. 관리자에게 문의하세요.");
    }
//]]>
</script>
</head>
<body style="background:url(); background-color:#f7f7f7">
<div id="ftp">
<div class="head_violet" style="float: left; width: 150px;">
	<h1 class="show" style="margin-top: 9px; font-size: 14px; letter-spacing: 1px;"><strong>WEB·FTP</strong></h1>
</div>
<div class="head_black">
<div style="float: right;">
	<p style="text-align: right; font: 11px normal; color: #d2d2d2; margin: 2px;">★주의사항★ 폴더나 파일을 삭제시에는 신중하게 결정하시기 바랍니다.</p>
</div>
</div>
<div class="clear" style="border-top: 1px solid #000"></div>
<div id="dir">
<div id="dirSub">
<table class="table_basic" style="width:100%;">
	<tr>
		<td><p class="pd10 center">폴더나 파일이 존재하지 않습니다.<br />[사이트 환경 설정]에서 셋팅완료가 되어야 관리 가능합니다.</p></td>
	</tr>
</table>
</div>
</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
{
	$.insert('#dirSub','./ftpData.php?dir=<?php echo($_GET['dir']);?>',null,300);
	$("input[type=text],input[type=password],textarea").focus(function(){$(this).toggleClass("input_active");}).blur(function(){$(this).toggleClass("input_active"); });
	$("a[rel*=facebox]").facebox();
});
$(document).keypress(function(e){if(e.which == 27) $.dialogRemove();});
//]]>
</script>
</body>
</html>
