<?php
/* --------------------------------------------------------------------------------------
| 최근 게시물 (캘린더형) : Add-on
|----------------------------------------------------------------------------------------
| Parent	: /addon/display.asp
| Lastest	: 이성준 (2009년 6월 26일 금요일)
*/
require_once __PATH__."/_Lib/classCalendar.php";
$cal		= new Calendar($GLOBALS['cfg']['cate']['mode']."__content");
$cal->Year	= ($_GET['year']) ? $_GET['year'] : date("Y"); //년도
$cal->Month	= ($_GET['month']) ? str_pad($_GET['month'], 2, "0", STR_PAD_LEFT) : date("m"); //월
$cal->Today	= ($_GET['day']) ? str_pad($_GET['day'], 2, "0", STR_PAD_LEFT) : date("d"); //일
$cal->configSetting();

$this->result .= '<div style="padding:0 '.$this->config['docPad'].' 0 '.$this->config['docPad'].'; height:158px; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top; overflow:hidden;">'.PHP_EOL;
$this->result .= '<div class="pd5 center">'.$cal->setNaviSmall($this->cate, $this->displayPos, preg_replace('/px|%/',null,$this->config['height'])).'</div>'.PHP_EOL;
$this->result .= '<table summary="'.$this->cateName.'" class="docCalMini">'.PHP_EOL;
$this->result .= '<caption>'.$this->cateName.'</caption>'.PHP_EOL;
$this->result .= '<colgroup><col width="20">'.PHP_EOL;
$this->result .= '<col width="20">'.PHP_EOL;
$this->result .= '<col width="20">'.PHP_EOL;
$this->result .= '<col width="20">'.PHP_EOL;
$this->result .= '<col width="20">'.PHP_EOL;
$this->result .= '<col width="20">'.PHP_EOL;
$this->result .= '<col width="20"></colgroup>'.PHP_EOL;
$this->result .= '<thead>'.PHP_EOL;
$this->result .= '<tr>'.$cal->setWeek("Recent").'</tr>'.PHP_EOL;
$this->result .= '</thead><tbody>'.PHP_EOL;
$this->result .= '<tr>'.$cal->setMini($this->config['useUnion']).'</tr>'.PHP_EOL;
$this->result .= '</tbody></table></div>'.PHP_EOL;
?>
