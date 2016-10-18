<?php
$this->result	.= '<div class="recentAnaly" style="width:'.$this->config['width'].'; height:'.$this->config['height'].'; background:url('.__SKIN__.'image/background/bg_recent_'.strtolower($this->prefix).'.gif) no-repeat left top;">'.PHP_EOL;
$this->result	.= '<p class="today">오늘 방문자 : <span>'.Sess::counting('today').'</span> 명</p>'.PHP_EOL;
$this->result	.= '<p class="holeday">전체 방문자 : <span>'.Sess::counting('all').'</span> 명</p>'.PHP_EOL;
$this->result .= '</div>'.PHP_EOL;
?>
