<?php
$_GET['info'] = ($_GET['info']) ? $_GET['info'] : "현재위치";
$mapHeight = ($_GET['roadView'] == 'no') ? $_GET['height'] : $_GET['height']/2;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="/common/css/default.css" type="text/css" charset="utf-8" media="all" />
<title>다음 지도API</title>
<script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=<?php echo($_GET['apiKey']);?>"></script>
<script type="text/javascript">
	var map;
	function road(z) {
			map = new daum.maps.Map(document.getElementById('map'), {
			center: new daum.maps.LatLng(z.channel.item[0].lat, z.channel.item[0].lng),
			level: 4
		});
		var marker = new daum.maps.Marker({
			position: new daum.maps.LatLng(z.channel.item[0].lat, z.channel.item[0].lng)
		});
		marker.setMap(map);

		var infowindow = new daum.maps.InfoWindow({
			content: '<p style="margin:7px 0px; font:12px/1.5 sans-serif;text-align:center"><strong style="color:#ff3300"><?php echo($_GET['info']);?></strong><br /><?php echo($_GET['address']);?></p>',
			removable : true
			});
		infowindow.open(map, marker);

		var zoomControl = new daum.maps.ZoomControl();
		map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);
		var mapTypeControl = new daum.maps.MapTypeControl();
		map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);

		var p = new daum.maps.LatLng(z.channel.item[0].lat, z.channel.item[0].lng);
		var rc = new daum.maps.RoadviewClient();
		var rv = new daum.maps.Roadview(document.getElementById("roadview"));

		rc.getNearestPanoId(p, 50, function(panoid) {
			rv.setPanoId(panoid);
		});

	}

	function setCenter(z) {
		map.setCenter(new daum.maps.LatLng(z.channel.item[0].lat, z.channel.item[0].lng));
	}

	function panTo(z) {
		map.panTo(new daum.maps.LatLng(z.channel.item[0].lat, z.channel.item[0].lng));
	}

	var obj = {
		init : function()
		{
			obj.r = document.getElementById('map');
			obj.s = document.createElement('script');
			obj.s.type ='text/javascript';
			obj.s.charset ='utf-8';
			obj.s.src = 'http://apis.daum.net/local/geo/addr2coord?apikey=78ca267207739d49ce4e6d217559ae9e9a5b4e4d&output=json&callback=road&q=' + encodeURI('<?php echo($_GET['address']);?>');
			document.getElementsByTagName('head')[0].appendChild(obj.s);
		}
	};
	window.onload = function(){	obj.init(); };
</script>
</head>
<body>

<div style="width:100%; height:<?php echo($_GET['height']);?>px; border:1px solid #d2d2d2">
<div id="map" style="width:100%;height:<?php echo($mapHeight);?>px; margin:auto"></div>
<?php if($_GET['roadView'] != 'no') { ?>
<div id="roadview" style="width:100%;height:<?php echo($mapHeight);?>px; margin:auto"></div>
<?php } ?>
</div>
</body>
</html>