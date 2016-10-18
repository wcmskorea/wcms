<?php
/**--------------------------------------------------------------------------------------
 * 최근 게시물 (웹진형) : Add-on
 *---------------------------------------------------------------------------------------
 * Relationship : ./lib/classDisplay.php
 * Last (2009.11.20 : 이성준)
 *---------------------------------------------------------------------------------------
 */
$this->result .= '<div class="recentBody" style="height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/recent_bg_'.$this->cate.'.gif) no-repeat;">'.PHP_EOL;

$Rows = @mysql_fetch_assoc(@mysql_query(" SELECT content FROM `".$this->config['module']."__content` WHERE cate='".$this->cate."' ORDER BY idx DESC Limit 1"));
$Rows['content'] = str_replace("&nbsp;", null, $Rows['content']);

$this->result .= '<div class="textContent">'.$Rows['content'].'</div>'.PHP_EOL;
$this->result .= '</div>'.PHP_EOL;
unset($Rows);
?>
