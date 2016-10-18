<?php

global $member;
global $db;

// 당일이전까지의 내용 삭제
$db->query(" DELETE FROM `mdOrder__today` WHERE SUBSTRING(regdate,1,10) < '".date("Y-m-d")."' ");


$this->result .= '<form name="quickForm">';
$this->result .= '<input type="hidden" name="sh_UpNo" id="sh_UpNo"  value="0">';
$this->result .= '<input type="hidden" name="sh_DownNo" id="sh_DownNo" value="2">';

$this->result .= '<div id="rightQuick">';
$this->result .= '<ul>';
$this->result .= '<li><span id="upBtn" onclick="f_QuickImgUp()"><img src="/user/default/image/button/btn_up.gif" alt="위" style="cursor:pointer" /></span></li>';

// 관련상품 정보 호출
if ($_SESSION['uid']) { 
	$memberInfo = $member->memberInfo($_SESSION['uid']);
	$toQuery = ' SELECT * FROM `mdOrder__today` WHERE memberSeq = "'.$memberInfo['seq'].'" AND SUBSTRING(regdate,1,10) = "'.date("Y-m-d").'" ORDER BY regdate DESC'; 
}else{ 
	$timeip     = explode("|",$GLOBALS['cfg']['timeip']);
	$ip         = $timeip[1];
	$toQuery = ' SELECT * FROM `mdOrder__today` WHERE todaySess = "'.$ip.'" AND SUBSTRING(regdate,1,10) = "'.date("Y-m-d").'" ORDER BY regdate DESC'; 
}

$db->query($toQuery);
for( $loop=0; $loop<3; $loop++ ) {
	$qRows = $db->Fetch();

	switch( $loop ) {
		case 0 : $spanName = "first";  break;
		case 1 : $spanName = "second"; break;
		case 2 : $spanName = "third";  break;
	}

	//썸네일 이미지
	$thumb = $db->queryFetch("SELECT * FROM `mdProduct__file` WHERE parent='".$qRows['productSeq']."' AND LOWER(extName) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1", 2);
	$dir   = __SKIN__."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/s-".$thumb['fileName'];
	$cate  = $db->queryFetchOne("SELECT cateCode FROM `mdProduct__product` WHERE seq='".$qRows['productSeq']."'", 2);
	$url   = '<a href="'.$_SERVER['PHP_SELF'].'?cate='.$cate.'&amp;type=view&amp;num='.$qRows['productSeq'].'#module">';

	$this->result .= '<li><span id="'.$spanName.'FloatingGoodsimg">'.$url.'<img src="'.$dir.'" title="상품이미지" alt="상품이미지" width="35" height="40"  onerror="this.src=\'/common/image/noimg_m.gif\'" class="thumbNail"/></a></span></li>';
}

$this->result .= '<li><span id="downBtn" onclick="f_QuickImgDown()"><img src="/user/default/image/button/btn_down.gif" alt="아래" style="cursor:pointer" /></span></li>';
$this->result .= '</ul>';
$this->result .= '</div>';
$this->result .= '</form>';
?>

<script type="text/javascript">

// 퀵메뉴 상품 설정
var quickUrl  = new Array();
var quickPath = new Array();

<?php
$db->query($toQuery);
$listCount=0;
while( $sRows = $db->Fetch() ) {

	//썸네일 이미지
	$thumb = $db->queryFetch("SELECT * FROM `mdProduct__file` WHERE parent='".$sRows['productSeq']."' AND LOWER(extName) IN ('jpg','gif','png','bmp') ORDER BY seq ASC Limit 1", 2);
	$dir   = __SKIN__."data/".date("Y",$thumb['regDate'])."/".date("m",$thumb['regDate'])."/s-".$thumb['fileName'];
	$url   = '<a href="'.$_SERVER['PHP_SELF'].'?cate='.$cate.'&amp;type=view&amp;num='.$qRows['productSeq'].'#module">';

	echo ("
		quickPath[$listCount] = '$dir';
		quickUrl[$listCount]  = '$url';
	");
	$listCount++;
}
?>

function f_QuickImgUp() {
	var nFirstCnt = parseInt($('#sh_UpNo').val());

	if (nFirstCnt == -1)     { alert("오늘 본 상품이 존재하지 않습니다.");}
	else if (nFirstCnt <= 0) { alert("오늘 본 마지막 상품입니다.");}
	else {

		var strText1 = quickUrl[nFirstCnt-1]+'<img src="'+quickPath[nFirstCnt-1]+'" title="상품이미지" alt="상품이미지" width="35" height="40"  onerror="this.src=\'/common/image/noimg_m.gif\'" class="thumbNail"/></a>';
		var strText2 = quickUrl[nFirstCnt]  +'<img src="'+quickPath[nFirstCnt]+'" title="상품이미지" alt="상품이미지" width="35" height="40"  onerror="this.src=\'/common/image/noimg_m.gif\'" class="thumbNail"/></a>';
		var strText3 = quickUrl[nFirstCnt+1]+'<img src="'+quickPath[nFirstCnt+1]+'" title="상품이미지" alt="상품이미지" width="35" height="40"  onerror="this.src=\'/common/image/noimg_m.gif\'" class="thumbNail"/></a>';

		$('#firstFloatingGoodsimg').html(strText1);
		$('#secondFloatingGoodsimg').html(strText2);
		$('#thirdFloatingGoodsimg').html(strText3);

		$('#sh_UpNo').val(nFirstCnt-1);
		$('#sh_DownNo').val(nFirstCnt+1);
	}
}

function f_QuickImgDown()
{
	var nLastCnt = parseInt($('#sh_DownNo').val());
	var nMaxCnt = <?=$listCount-1?>;

	if (nLastCnt == -1) {
		alert("오늘 본 상품이 존재하지 않습니다.");
	}
	else if (nLastCnt >= nMaxCnt) {
		alert("오늘 본 처음 상품입니다.");
	}
	else {

		var strText1 = quickUrl[nLastCnt-1]+'<img src="'+quickPath[nLastCnt-1]+'" title="상품이미지" alt="상품이미지" width="35" height="40"  onerror="this.src=\'/common/image/noimg_m.gif\'" class="thumbNail"/></a>';
		var strText2 = quickUrl[nLastCnt-1]+'<img src="'+quickPath[nLastCnt]+'" title="상품이미지" alt="상품이미지" width="35" height="40"  onerror="this.src=\'/common/image/noimg_m.gif\'" class="thumbNail"/></a>';
		var strText3 = quickUrl[nLastCnt-1]+'<img src="'+quickPath[nLastCnt+1]+'" title="상품이미지" alt="상품이미지" width="35" height="40"  onerror="this.src=\'/common/image/noimg_m.gif\'" class="thumbNail"/></a>';

		$('#firstFloatingGoodsimg').html(strText1);
		$('#secondFloatingGoodsimg').html(strText2);
		$('#thirdFloatingGoodsimg').html(strText3);

		$('#sh_UpNo').val(nLastCnt-1);
		$('#sh_DownNo').val(nLastCnt+1);
	}
}
</script>