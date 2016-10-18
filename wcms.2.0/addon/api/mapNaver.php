<?php
$_GET['info'] = ($_GET['info']) ? $_GET['info'] : "현재위치";
$mapWidth  = $_GET['width'] ? $_GET['width'] : '1000' ;
$mapHeight = $_GET['height'] ? $_GET['height'] : '800' ;
$map_key = $_GET['height'];

$address = $_GET['address'] ? $_GET['address'] : $cfg['site']['address'];

if($address != null) {
	$out = "GET /api/geocode.php?key=".$_GET['apiKey']."&query=".urlencode($address);
	$fp = fsockopen("openapi.map.naver.com", 80);

	$mapbody = "";
	$mapPointX = 0;
	$mapPointY = 0;

	if (!$fp) {
		echo "connection fail...<br />\n";
	} else {
		$out .= " HTTP/1.1\r\n";
		$out .= "Host:openapi.map.naver.com\r\r\n";
		$out .= "Connection: Close\r\n\r\n";

		$rel = fwrite($fp, $out);
		while (!feof($fp)) {
			$mapbody .= fgets($fp, 128);
		}

		fclose($fp);
		$mapPointX_1 = explode("<x>", $mapbody);
		$mapPointX_2 = explode("</x>", $mapPointX_1[1]);
		$mapPointX = $mapPointX_2[0]; 
		
		$mapPointY_1 = explode("<y>", $mapbody);
		$mapPointY_2 = explode("</y>", $mapPointY_1[1]);
		$mapPointY = $mapPointY_2[0];  
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en_US" xml:lang="en_US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="/common/css/default.css" type="text/css" charset="utf-8" media="all" />
<title>네이버 지도API</title>
<script type="text/javascript" src="http://openapi.map.naver.com/openapi/naverMap.naver?ver=2.0&key=<?php echo($_GET['apiKey']);?>"></script>
<!-- prevent IE6 flickering -->
<script type="text/javascript">
	try {document.execCommand('BackgroundImageCache', false, true);} catch(e) {}
</script>
</head>
<body>
<div style="width:100%; height:<?php echo($_GET['height']);?>px; border:1px solid #d2d2d2">
<div id="map" style="width:100%;height:<?php echo($mapHeight);?>px; margin:auto"></div>
</div>
<script type="text/javascript">
	var mapPointX = <?php echo($mapPointX);?>;
	var mapPointY = <?php echo($mapPointY);?>;
	var mapWidth  = <?php echo($mapWidth);?>;
	var mapHeight = <?php echo($mapHeight);?>;

	var oPoint = new nhn.api.map.TM128(mapPointX, mapPointY);
	var defaultLevel = 11;
	var oMap = new nhn.api.map.Map(document.getElementById('map'), { 
		point : oPoint,
		zoom : defaultLevel,
		enableWheelZoom : true,
		enableDragPan : true,
		enableDblClickZoom : false,
		mapMode : 0,
		activateTrafficMap : false,
		activateBicycleMap : false,
		minMaxLevel : [ 1, 14 ],
		size : new nhn.api.map.Size(mapWidth, mapHeight)
	});

	var oSlider = new nhn.api.map.ZoomControl();
	oMap.addControl(oSlider);
	oSlider.setPosition({
		top : 10,
		left : 10
	});

	var oMapTypeBtn = new nhn.api.map.MapTypeBtn();
	oMap.addControl(oMapTypeBtn);
	oMapTypeBtn.setPosition({
		bottom : 10,
		right : 80
	});

	var markerCount = 0;
	
	var oSize = new nhn.api.map.Size(28, 37);
	var oOffset = new nhn.api.map.Size(14, 37);
	var oIcon = new nhn.api.map.Icon('http://static.naver.com/maps2/icons/pin_spot2.png', oSize, oOffset);

	var oMarker = new nhn.api.map.Marker(oIcon, { title : '<?php echo($_GET['info']);?>' });  //마커를 생성한다 
	oMarker.setPoint(oPoint); //마커의 좌표를 oPoint 에 저장된 좌표로 지정한다

	oMap.addOverlay(oMarker); //마커를 네이버 지도위에 표시한다
	 

	var oLabel = new nhn.api.map.MarkerLabel(); // 마커 라벨를 선언한다. 
	oMap.addOverlay(oLabel); // - 마커의 라벨을 지도에 추가한다. 
	oLabel.setVisible(true, oMarker); // 마커의 라벨을 보이게 설정한다.
</script>
</body>
</html>