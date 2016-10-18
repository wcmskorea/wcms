<?php
class Calendar
{
	var $Cate;
	var $Year;
	var $Month;
	var $Today;
	var $weekTitleKr			= array('일','월','화','수','목','금','토');
	var $weekTitleEn			= array('SUN','MON','TUE','WED','THU','PRI','SAT');
	var $weekTitleCn			= array('日','月','火','水','木','金','土');
	var $weekColorArray		= array('#ff6600','#666','#666','#666','#666','#666','#3399ff');
	var $weekColorReserv	= array('#ff6600','#666','#666','#666','#666','#3399ff','#3399ff');
	var $weekColorReserv01= array('#ff6600','#666','#666','#666','#666','#666','#3399ff');
	var $statusColor			= array("0"=>"#440e62","1"=>"#44b34a","2"=>"#0080ff","3"=>"#0080ff","4"=>"#f26c00","5"=>"#ff0000");
	var $thisMonthOneDayTime	= 0; //해당달 1일 time()값
	var $thisMonthLastDay		= 0; //해당달의 마지막 날
	var $startWeek				= 0; //해당달의 1일 요일
	var $beforeMonthOneDayTime	= 0; //지난달 마지막일 time()
	var $beforeMonthLastDay		= 0; //지난달의 마지막 날
	var $result;

	function Calendar($table=null)
	{
		//$this->Table				= $table;
		//해당달 1일 time()값
		$this->configSetting();
	}

	function configSetting()
	{
		//해당달 1일 time()값
		$this->thisMonthOneDayTime	= mktime(0,0,0,$this->Month,1,$this->Year);
		//해당달의 마지막 날
		$this->thisMonthLastDay		= date('t', $this->thisMonthOneDayTime);
		//해당달의 1일 요일
		$this->startWeek			= date('w', $this->thisMonthOneDayTime);
		//지난달 마지막일 time()
		$this->beforeMonthOneDayTime= $this->thisMonthOneDayTime - 1;
		//지난달의 마지막 날
		$this->beforeMonthLastDay	= date('t', $this->beforeMonthOneDayTime);
		//현재일의 주차수
		$this->week					= date("W", mktime(0,0,0,$this->Month,$this->Today,$this->Year));
	}

	# 큰 네비게이션
	function setNavi($monthly=false)
	{
		$nmonth		= ($this->Month == 12) ? 1 : $this->Month + 1;
		$pmonth		= ($this->Month == 1) ? 12 : $this->Month - 1;
		$nyear		= ($this->Month == 12) ? $this->Year + 1 : $this->Year;
		$pyear		= ($this->Month == 1) ? $this->Year - 1 : $this->Year;
		$prev		= '<span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$pyear.'&amp;month='.$pmonth.'#module">이전</a></span>';
		$next		= '<span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$nyear.'&amp;month='.$nmonth.'#module">다음</a></span>';
		if($monthly)
		{
			for($i=1;$i<13;$i++) {
				$next .= '&nbsp;&nbsp;<span class="btnPack small white"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$this->Year.'&amp;month='.$i.'#module">'.$i.'월</a></span>';
			}
		}
		return $prev.'&nbsp;<span class="btnPack metal medium strong"><a href="javascript:;" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'\', \'&amp;cate='.__CATE__.'&amp;menu=&amp;sub=&amp;type=searchDate&amp;mode=dialog\',300,130)">'.$this->Year.'년&nbsp;'.$this->Month.'월</a></span>&nbsp;'.$next;
	}

	# 큰 네비게이션(
	function setNavi2($monthly=false)
	{
		$nmonth		= ($this->Month == 12) ? 1 : $this->Month + 1;
		$pmonth		= ($this->Month == 1) ? 12 : $this->Month - 1;
		$nyear		= ($this->Month == 12) ? $this->Year + 1 : $this->Year;
		$pyear		= ($this->Month == 1) ? $this->Year - 1 : $this->Year;
		$prev		= '<span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$pyear.'&amp;month='.$pmonth.'#module">이전</a></span>';
		$next		= '<span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$nyear.'&amp;month='.$nmonth.'#module">다음</a></span>';
		if($monthly)
		{
			for($i=1;$i<13;$i++) {
				$next .= '&nbsp;&nbsp;<span class="btnPack small white"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$this->Year.'&amp;month='.$i.'#module">'.$i.'월</a></span>';
			}
		}
		return $prev.'&nbsp;<span class="btnPack metal medium strong"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$this->Year.'&amp;month='.$this->Month.'#module">'.$this->Year.'년&nbsp;'.$this->Month.'월</a></span>&nbsp;'.$next;
	}

	# 작은 네비게이션
	function setNaviSmall($cate, $position, $height)
	{
		$nmonth		= ($this->Month == 12) ? 1 : $this->Month + 1;
		$pmonth		= ($this->Month == 1) ? 12 : $this->Month - 1;
		$nyear		= ($this->Month == 12) ? $this->Year + 1 : $this->Year;
		$pyear		= ($this->Month == 1) ? $this->Year - 1 : $this->Year;
		$prev		= '<span class="btnPack metal small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;position='.$position.'&amp;year='.$pyear.'&amp;month='.$pmonth.'#content">PREV</a></span>&nbsp;';
		$next		= '&nbsp;<span class="btnPack metal small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;position='.$position.'&amp;year='.$nyear.'&amp;month='.$nmonth.'#content">NEXT</a></span>';
		return $prev.'<span class="btnPack black small strong"><a href="'.$GLOBALS['cfg']['droot'].'?'.__PARM__.'&amp;year='.$this->Year.'&amp;month='.$this->Month.'#content">'.$this->Year.' . '.$this->Month.'</a></span>'.$next;
	}

	# 년간 네비게이션
	function setNaviYear()
	{
		$nyear		= $this->Year + 1;
		$pyear		= $this->Year - 1;
		$prev			= '<div style="margin:0 auto;width:620px"><span class="btnPack black small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$pyear.'#module">이전해</a></span>';
		for($i=1;$i<13;$i++) {
			$next .= '&nbsp;<span class="btnPack small';
			$next .= ($this->Month == $i) ? ' blue' : ' white';
			$next .= '"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$this->Year.'&amp;month='.$i.'#module">'.str_pad($i, 2, "0", STR_PAD_LEFT).'월</a></span>';
		}
		$next .= '&nbsp;<span class="btnPack black small"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$nyear.'#module">다음해</a></span></div>';
		return $prev.'&nbsp;<span class="btnPack blue small"><a href="javascript:;">'.$this->Year.'년</a></span>'.$next;
	}
	
	# 년간 네비게이션
	function setNaviYearAjax()
	{
		$nyear		= $this->Year + 1;
		$pyear		= $this->Year - 1;
		$prev			= '<div style="margin:0 auto"><span class="btnPack white small"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdReserve/manage/_controll.php?type=reserveListMonth&amp;year='.$pyear.'\',\'\',\'300\')">이전해</a></span>&nbsp;';
		for($i=1;$i<13;$i++) {
			$next .= '&nbsp;<span class="btnPack small';
			$next .= ($this->Month == $i) ? ' blue' : ' gray';
			$next .= '"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdReserve/manage/_controll.php?type=reserveListMonth&amp;year='.$this->Year.'&amp;month='.$i.'\',\'\',\'300\')">'.str_pad($i, 2, "0", STR_PAD_LEFT).'월</a></span>&nbsp;';
		}
		$next .= '&nbsp;<span class="btnPack white small"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdReserve/manage/_controll.php?type=reserveListMonth&amp;year='.$nyear.'\',\'\',\'300\')">다음해</a></span></div>';
		return $prev.'&nbsp;<span class="btnPack blue small"><a href="javascript:;">'.$this->Year.'년</a></span>&nbsp;'.$next;
	}

	# 년간 네비게이션(오혜진 추가)
	function setNaviYearAjax01()
	{
		$nyear		= $this->Year + 1;
		$pyear		= $this->Year - 1;
		$prev			= '<div style="margin:0 auto"><span class="btnPack white small"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdReserve01/manage/_controll.php?type=reserveListMonth&amp;year='.$pyear.'\',\'\',\'300\')">이전해</a></span>&nbsp;';
		for($i=1;$i<13;$i++) {
			$next .= '&nbsp;<span class="btnPack small';
			$next .= ($this->Month == $i) ? ' blue' : ' gray';
			$next .= '"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdReserve01/manage/_controll.php?type=reserveListMonth&amp;year='.$this->Year.'&amp;month='.$i.'\',\'\',\'300\')">'.str_pad($i, 2, "0", STR_PAD_LEFT).'월</a></span>&nbsp;';
		}
		$next .= '&nbsp;<span class="btnPack white small"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdReserve01/manage/_controll.php?type=reserveListMonth&amp;year='.$nyear.'\',\'\',\'300\')">다음해</a></span></div>';
		return $prev.'&nbsp;<span class="btnPack blue small"><a href="javascript:;">'.$this->Year.'년</a></span>&nbsp;'.$next;
	}

	# 년간 네비게이션(오혜진 추가)
	function setNaviYearAjaxApply()
	{
		$nyear		= $this->Year + 1;
		$pyear		= $this->Year - 1;
		$prev			= '<div style="margin:0 auto"><span class="btnPack white small"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdApply/manage/_controll.php?type=listMonth&amp;year='.$pyear.'\',\'\',\'300\')">이전해</a></span>&nbsp;';
		for($i=1;$i<13;$i++) {
			$next .= '&nbsp;<span class="btnPack small';
			$next .= ($this->Month == $i) ? ' blue' : ' gray';
			$next .= '"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdApply/manage/_controll.php?type=listMonth&amp;year='.$this->Year.'&amp;month='.$i.'\',\'\',\'300\')">'.str_pad($i, 2, "0", STR_PAD_LEFT).'월</a></span>&nbsp;';
		}
		$next .= '&nbsp;<span class="btnPack white small"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/mdApply/manage/_controll.php?type=listMonth&amp;year='.$nyear.'\',\'\',\'300\')">다음해</a></span></div>';
		return $prev.'&nbsp;<span class="btnPack blue small"><a href="javascript:;">'.$this->Year.'년</a></span>&nbsp;'.$next;
	}

	# 년간 네비게이션(공통)
	function setNaviYearAjaxModule($module='mdRental',$type='listMonth')
	{
		$nyear		= $this->Year + 1;
		$pyear		= $this->Year - 1;
		$prev			= '<div style="margin:0 auto"><span class="btnPack white small"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/'.$module.'/manage/_controll.php?type='.$type.'&amp;year='.$pyear.'\',\'\',\'300\')">이전해</a></span>&nbsp;';
		for($i=1;$i<13;$i++) {
			$next .= '&nbsp;<span class="btnPack small';
			$next .= ($this->Month == $i) ? ' blue' : ' gray';
			$next .= '"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/'.$module.'/manage/_controll.php?type='.$type.'&amp;year='.$this->Year.'&amp;month='.$i.'\',\'\',\'300\')">'.str_pad($i, 2, "0", STR_PAD_LEFT).'월</a></span>&nbsp;';
		}
		$next .= '&nbsp;<span class="btnPack white small"><a href="javascript:;" onclick="$.insert(\'#module\', \'../modules/'.$module.'/manage/_controll.php?type='.$type.'&amp;year='.$nyear.'\',\'\',\'300\')">다음해</a></span></div>';
		return $prev.'&nbsp;<span class="btnPack blue small"><a href="javascript:;">'.$this->Year.'년</a></span>&nbsp;'.$next;
	}

	# 년간 네비게이션(공통)
	function setNaviOnlyYear()
	{
		$nyear		= $this->Year + 1;
		$pyear		= $this->Year - 1;
		$prev			= '<div style="margin:0 auto"><span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.intval($pyear).'">이전해</a></span>&nbsp;';
		$next .= '&nbsp;<span class="btnPack black medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.intval($nyear).'">다음해</a></span></div>';
		return $prev.'&nbsp;<span class="btnPack metal medium strong"><a href="javascript:;">'.$this->Year.'년</a></span>&nbsp;'.$next;
	}

	# 주간 네비게이션
	function setNaviWeek($week)
	{
		if($week)
		{
			$this->Year	= date("Y", strtotime("+".intval($week-$this->week)." week", time()));
			$this->Month= date("m", strtotime("+".intval($week-$this->week)." week", time()));
			$this->Today= date("d", strtotime("+".intval($week-$this->week)." week", time()));
			$this->week	= date("W", mktime(0,0,0,$this->Month,$this->Today,$this->Year));
		}
		$firstWeek	= date("W", mktime(0,0,0,$this->Month,1,$this->Year));
		$weekInfo	= ($this->week >= $firstWeek) ? $this->week - $firstWeek + 1 : $this->week;
		$prev		= '<span class="btnPack metal medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;week='.intval($this->week - 1).'">이전</a></span>&nbsp;';
		$next		= '&nbsp;<span class="btnPack metal medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;week='.intval($this->week + 1).'">다음</a></span>';
		return $prev.'<span class="btnPack black medium strong"><a href="javascript:;" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'\', \'&amp;cate='.__CATE__.'&amp;year='.$this->Year.'&amp;month='.$this->Month.'&amp;type=searchDate&amp;listType=week&amp;mode=dialog\',300,130)">'.$this->Year.'년&nbsp;'.$this->Month.'월&nbsp;-&nbsp;'.str_pad($weekInfo, 2, "0", STR_PAD_LEFT).'째주</a></span>'.$next;
	}

	# 일간 네비게이션
	function setNaviDay($days)
	{
		if($days)
		{
			$this->Year	= date("Y", strtotime("+".$days." day", time()));
			$this->Month= date("m", strtotime("+".$days." day", time()));
			$this->Today= date("d", strtotime("+".$days." day", time()));
		}
		$prev		= '<span class="btnPack metal medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;days='.intval($days - 1).'">이전</a></span>&nbsp;';
		$next		= '&nbsp;<span class="btnPack metal medium"><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;days='.intval($days + 1).'">다음</a></span>';
		return $prev.'<span class="btnPack black medium strong"><a href="javascript:;" onclick="$.dialog(\''.$_SERVER['PHP_SELF'].'\', \'&amp;cate='.__CATE__.'&menu=&sub=&amp;type=searchDate&amp;listType=days&amp;mode=dialog\',300,130)">'.$this->Year.'년&nbsp;'.$this->Month.'월&nbsp;'.$this->Today.'일</a></span>'.$next;
	}

	# 상단 요일열 추가
	function setWeek($css)
	{
		switch($GLOBALS['cfg']['site']['lang'])
		{
			case 'en' : $array = $this->weekTitleEn; break;
			case 'cn' : $array = $this->weekTitleCn; break;
			case 'kr' : default : $array = $this->weekTitleKr; break;
		}
		foreach($array as $key=>$week)
		{
			$color = $this->weekColorArray[$key];
			$result .= '<td scope="col" class="docCalHead'.$css;
			$result .= ($key == 0) ? " first" : null;
			$result .= '"><p><strong style="color:'.$color.';">'.$week.'</strong></p></td>'.PHP_EOL;
		}
		return $result;
	}

	# 작은 캘린더 출력
	function setMini($data)
	{
		//달력의 1일이 시작하기전 공백셀 추가
		if($this->startWeek > 0)
		{
			for($cell=0;$cell<$this->startWeek;$cell++){
				$blank = $this->beforeMonthLastDay-(($this->startWeek-1)-$cell);
				$result .= '<td scope="row" class="bg_gray small center"><span style="color:#999">'.$blank.'</span></td>'.PHP_EOL;
			}
		}
		//실제 날짜들어갈 셀
		for($this->day=1; $this->day<=$this->thisMonthLastDay; $this->day++)
		{
			$this->day	= str_pad($this->day, 2, "0", STR_PAD_LEFT);
			$thisCell	= ($this->startWeek+($this->day-1))%7;		//0 ~ 6 요일
			$color		= $this->weekColorArray[$thisCell];
			$day		= ($this->Today == $this->day) ? '<u class="strong">'.$this->day.'</u>' : $this->day;
			$result 	.= '<td scope="row"';
			if($this->getData($data, 'Y') > 0)
			{
				$result .= ' class="small"><div class="issue"><p class="icon"><img src="'.__SKIN__.'image/icon/icon_today_s.gif" width="11" height="11" alt="issue" /></p><p><a href="'.$GLOBALS['cfg']['droot'].'?cate='.__CATE__.'&amp;menu='.$GLOBALS['menu'].'&amp;sub='.$GLOBALS['sub'].'&amp;year='.$this->Year.'&amp;month='.$this->Month.'&amp;day='.$this->day.'#list" target="_top" style="color:'.$color.'">'.$day.'</a>';
			}
			else
			{
				$result .= ' class="small" onclick="alert(\''.$this->Year.'년'.$this->Month.'월'.$this->day.'일 일정이 없습니다\');"><div class="issue"><p style="color:'.$color.';">'.$day;
			}
			$result .= '</p></div></td>'.PHP_EOL;
			if($thisCell == 6) $result .= "</tr>".PHP_EOL;
		}
		//$thisCell 에는 마지막의 셀의 위치를 가지고 있으므로 마지막 공백 계산후 남은공백 셀 추가
		$remainCell = 6-$thisCell;
		if($remainCell > 0)
		{
			for($cell=0;$cell<$remainCell;$cell++)
			{
				$blank = $cell+1;
				$result .= '<td scope="row" class="bg_gray small"><span style="color:#999">'.$blank.'</span></td>'.PHP_EOL;
			}
		}
		/*for($cell=1;$cell<43;$cell++)
		{
			$result .= '<td scope="row" class="bg_gray small"><span style="color:#999;">'.$cell.'</span></td>'.PHP_EOL;
			if(($thisCell+$cell)%7 == 6) $result .= '</tr>'.PHP_EOL;
		}*/
		return $result;
	}

	/***
	 * 큰 캘린더 - 문서게시물 모듈 출력
	 */
	function setList($data)
	{
		$result = '';
		//달력의 1일이 시작하기전 공백셀 추가
		if($this->startWeek>0)
		{
			for($cell=0;$cell<$this->startWeek;$cell++)
			{
				$blank = $this->beforeMonthLastDay-(($this->startWeek-1)-$cell);
				$result .= '<td scope="row" class="bg_gray big"><span style="color:#999">'.$blank.'</span></td>'.PHP_EOL;
			}
		}
		//실제 날짜들어갈 셀
		for($this->day=1;$this->day<=$this->thisMonthLastDay;$this->day++)
		{
			$this->day	= str_pad($this->day, 2, "0", STR_PAD_LEFT);
			$thisCell 	= ($this->startWeek+($this->day-1))%7;		//0 ~ 6 요일
			//$holiday	= $this->getHoliday(str_pad($this->day, 2, "0", STR_PAD_LEFT));
			$color		= ($holiday) ? "#ff6600" : $this->weekColorArray[$thisCell];
			$url		= '<a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;year='.$this->Year.'&amp;month='.$this->Month.'&amp;day='.$this->day.'&amp;type=input">';
			$result .= '<td scope="row"';
			if($this->Today == $this->day)
			{
				$result .= ' class="date big"><p style="color:'.$color.';margin-bottom:5px;">'.$url.'<u class="now">'.str_pad($this->day, 2, "0", STR_PAD_LEFT).'</u>.</a>';
			}
			else
			{
				$result .= ' class="big"><p style="color:'.$color.';margin-bottom:5px;">'.$url.'<span>'.str_pad($this->day, 2, "0", STR_PAD_LEFT).'.</span></a>';
			}
			//$result .= '<span class="holiday">&nbsp;'.$holiday.'</span></p>';
			$result .= '<ol>'.$this->getData($data).'</ol>';
			$result .= '</td>'.PHP_EOL;
			if($thisCell == 6) { $result .= '</tr>'.PHP_EOL; }
		}
		//$thisCell 에는 마지막의 셀의 위치를 가지고 있으므로 마지막 공백 계산후 남은공백 셀 추가
		$remainCell = 6-$thisCell;
		if($remainCell > 0)
		{
			for($cell=0;$cell<$remainCell;$cell++)
			{
				$blank = $cell+1;
				$result .= '<td scope="row" class="bg_gray big"><span style="color:#999">'.$blank.'</span></td>'.PHP_EOL;
			}
		}
		return $result;
	}

	//일정 문서/게시물 데이터
	function getData($data, $all=null)
	{
		$query = " AND idxTrash='0' AND ".$this->Year.$this->Month.$this->day." BETWEEN FROM_UNIXTIME(regDate,'%Y%m%d') AND FROM_UNIXTIME(endDate,'%Y%m%d') ";
		if($data == 'Y')
		{
			$query = " SELECT * FROM `".$GLOBALS['cfg']['cate']['mode']."__content` WHERE cate like '".__CATE__."%".$query;
		}
		else
		{
			$query = " SELECT * FROM `".$GLOBALS['cfg']['cate']['mode']."__content` WHERE cate='".__CATE__."'".$query;
		}

		$rst	= @mysql_query($query);
		if($all == 'Y')
		{
			$record = @mysql_num_rows($rst);
		}
		else
		{
			while($Row = @mysql_fetch_array($rst))
			{
				$record .= '<li class="list"><strong>ㆍ</strong><a href="'.$_SERVER['PHP_SELF'].'?'.__PARM__.'&amp;type=view&amp;num='.$Row['seq'].'&amp;currentPage=1&amp;sh=&amp;shc=&amp;year='.$this->Year.'&amp;month='.$this->Month.'&amp;day='.$day.'" class="actUnder">'.Functions::cutStr($Row['subject'],$GLOBALS['cfg']['module']['cutContent'],"...").'</a></li>';
			}
		}
		//$record .= $date;
		return $record;
	}

	function getHoliday($day)
	{
		$date	= strtotime($this->Year."-".$this->Month."-".$day);
		//$rst	= @mysql_fetch_array(@mysql_query("SELECT subject FROM `".$GLOBALS['cfg']['cate']['mode']."__content` WHERE ('".$date."' BETWEEN regDate AND endDate) AND kind='HO'"));
		$rst	= @mysql_query("SELECT subject FROM `".$GLOBALS['cfg']['cate']['mode']."__content` WHERE cate='".__CATE__."' AND ('".$date."' BETWEEN regDate AND endDate) AND kind='HO'");
		//echo "SELECT subject FROM `".$GLOBALS['cfg']['cate']['mode']."__content` WHERE cate='".__CATE__."' AND ('".$date."' BETWEEN regDate AND endDate) AND kind='HO'";
		while($Row = @mysql_fetch_array($rst)) { $result .= $Row[0]."<br />"; }
		return ($result) ? preg_replace("/\<br \/\>$/","",$result) : null;
	}

	/*공휴일 여부 */
	function isHoliday($date,$type='S') {
		$isHoliday = false;

		/*
		db::disConnect();
		db::connect(1);
		db::selectDB("commonSql");
		if(db::CheckTable("site__calendar")) {

		$db->DisConnect();
		$db->Connect(1);
		$db->selectDB("commonSql");
		if($db->CheckTable("site__calendar"))
		{*/
			if($type=='S') {
				$query = 'SELECT solar_date, memo FROM `site__calendar` WHERE solar_date = "'.$date.'" ';
			} else {
				$query = 'SELECT solar_date, memo FROM `site__calendar` WHERE unix_timestamp(solar_date) = '.$date;
			}
			$HolidayRows = @mysql_fetch_array(@mysql_query($query));

			$HolidayMemo = $HolidayRows['memo'];
			if(trim($HolidayMemo) != "")
				$isHoliday = true;
		/*}
		$db->DisConnect();
		$db->Connect(1);
		$db->selectDB(__DB__);
		
		}
		db::disConnect();
		db::connect(1);
		db::selectDB(__DB__)
		*/

		return $isHoliday;
	}

}

?>
