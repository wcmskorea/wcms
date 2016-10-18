<!-- 헤드라인 -->
<?php
$n = 1;
$thumbNail = array();
$db->Query("SELECT * FROM `".$Rows[module]."__content` WHERE cate='001006' ORDER BY recom DESC LIMIT 4");
?>
<script type="text/javascript">
var article_list = [<?php
while($Rows = $db->Fetch())
{
	$Rows[title] = $func->cutStr($Rows[title], 15);
	$check = $db->QueryFetch("SELECT COUNT(*) AS count,AVG(recom) AS avg FROM `".$Rows[module]."__reply` WHERE parent='".$Rows[seq]."'", 2);
	$thumb = $db->QueryFetchOne("SELECT `filename` FROM `".$Rows[module]."__file` WHERE parent='".$Rows[seq]."' AND (LOWER(SUBSTRING(filename,-3))='jpg' OR LOWER(SUBSTRING(filename,-3))='gif' OR LOWER(SUBSTRING(filename,-3))='png' OR LOWER(SUBSTRING(filename,-3))='bmp') ORDER BY seq ASC Limit 1", 3);
	$file = "/user/data/".date("Y",$Rows[date])."/".date("m",$Rows[date])."/"."s-".$thumb;

	echo '[{"title":"'.$Rows[title].' <span style=\"color:yellow\">★★★★★ <i>'.sprintf("%.1f",$check[avg]).'</i></span>&nbsp;<span style=\"font-size:12px;\">( '.$check[count].'명 참가 )","url":"/'.$Rows[cate].'/view/'.$Rows[seq].'","image":"/user/data/'.date("Y",$Rows[date]).'/'.date("m",$Rows[date]).'/'.$thumb.'","thumbnail":"/user/data/'.date("Y",$Rows[date]).'/'.date("m",$Rows[date]).'/s-'.$thumb.'"}';

		$db->Query("SELECT * FROM `".$Rows[module]."__reply` WHERE parent='".$Rows[seq]."' ORDER BY date DESC LIMIT 2", 2);
		while($sRows = $db->Fetch(2))
		{
			$sRows[body] = $func->cutStr($sRows[body], 20);
			echo ',{"title":"'.$sRows[body].'","url":"/'.$Rows[cate].'/view/'.$Rows[seq].'","image":"","thumbnail":""}';
		}

	echo ($n == 4) ? ']' : '],';
	array_push($thumbNail, $file);
	$n++;
}
?>];
var external_list = [[],[],[],[]];
var rolling_pause = false;
var curr_hd_num = 0;

change_hd = function(num)
{
	for(var i=0; i<4; i++)
	{
		var thumbnail = document.getElementById('thumbnail_'+i);
		if( num == i)
		{
			thumbnail.className = 'on';
			update_hd(num);
		}
		else
		{
			thumbnail.className = '';
		}
	}
    curr_hd_num = num;
}
update_hd = function(num)
{
	var article_data = article_list[num];
  var external_data = external_list[num];
  var hd_url = article_data[0]['url'].replace(/[\"]/g,"");

	document.getElementById('hd_img').src = article_data[0]['image'];
	document.getElementById('hd_img').alt = article_data[0]['title'];
  document.getElementById('hd_img_url').href = hd_url;
  document.getElementById('hd_title').href = hd_url;
	document.getElementById('hd_title').innerHTML= article_data[0]['title'];

	//중계, tv, 일반기사 3가지 경우에 따라 헤드라인 타입이 달라진다.
	var related_data = "";

	var article_cnt = (article_data.length > 5) ? 5 : article_data.length;
	related_data = "<ul class='type_article'>";
	for (var i=1 ; i<article_cnt ; i++)
	{
	if (article_data[i] != null)
	{
		    related_data += "<li><a href="+article_data[i]['url']+">"+article_data[i]['title']+"</a></li>";
		}
	}
	document.getElementById('play_img_id').innerHTML = "";
	related_data += "</ul>";
	document.getElementById('related').innerHTML= related_data;
}
roll_headline = function()
{
	if(rolling_pause)
	{
			setTimeout("roll_headline()", 1000);
	}
	else
	{
		if(curr_hd_num > 3) curr_hd_num = 0;
		change_hd(curr_hd_num);
		curr_hd_num++;
		setTimeout("roll_headline()", 5000);
	}
}
</script>
<!-- 헤드라인 -->
<div class="headline_wrap" style="width:620px; height:378px; margin-left:<?=$cfg[padding]?>px; margin-bottom:<?=$cfg[padding]?>px;">
<h3 class="blind"><a id="h_headline" name="h_headline">헤드라인 뉴스</a></h3>
<dl class="headline">
	<dt class="title" id="hd_title">
		<a href="#none">홍길동<a>&nbsp;<span style="color:yellow">★★★★★ <i>9.5</i></span>
	</dt>
	<dd style="padding:5px;">
		<span class="bg_txt"></span><a href="#none" id="hd_img_url"><img id="hd_img" src="/user/data/2009/03/DSC04874.JPG" width="492" height="368" alt="'훈련 결산' 김인식 "일본서 최대한 끌어올린다"" onMouseOut="rolling_pause = false;"  onMouseOver="rolling_pause = true;"><div id="play_img_id"></div></a></span>
	</dd>
	<dd id="related" class="news_list">
		<ul class="type_article">
			<li><a href="#none">'韓 좌투수 vs 日 좌타자' 흥미진진</a></li>
			<li><a href="#none">대표팀 경기, 공중파 중계 안한다</a></li>
		</ul>
	</dd>
</dl>
<ul class="headline_nav">
	<?php
	foreach($thumbNail as $key=>$value) {
		echo '<li class="on" id="thumbnail_'.$key.'" style="cursor:pointer;" onMouseOut="rolling_pause = false;"  onMouseOver="rolling_pause = true;" onclick="javascript:change_hd('.$key.');"><img src="'.$value.'" alt="'.$key.'" width="110" height="83" /></li>';
	}
	?>
</ul>
</div>
<!-- //헤드라인 -->
<script language="javascript">
var parsed_url = window.location.search.substring(1).split("&");
for (var i=0;i<parsed_url.length ;i++ )
{
	var parsed_param = parsed_url[i].split("=");
	if (parsed_param[0] == 'hd')
	{
			curr_hd_num = parsed_param[1];
			//change_hd( parsed_param[1] );
	}
}
roll_headline();
</script>
