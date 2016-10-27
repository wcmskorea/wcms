<?php
/**
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2010. 5. 18.
 */
$this->result .= '<div class="footerContent">';
$this->result .= '<ul>';
$this->result .= '<li class="link" style="width:25%;"><a href="./?cate=001" class="actGray">회사소개</a></li>';
$this->result .= '<li class="link" style="width:25%;"><a href="./?cate=000999002" class="actGray">이용약관</a></li>';
$this->result .= '<li class="link" style="width:25%;"><a href="./?cate=000999003" class="actGray">개인정보취급방침</a></li>';
//$this->result .= '<li class="link" style="width:20%;"><a href="./?cate=000999005" class="actGray">저작권안내·신고</a></li>';
//$this->result .= '<li class="link" style="width:20%;"><a href="./?cate=000999004" class="actGray">이메일무단수집거부</a></li>';
$this->result .= '<li class="link" style="width:25%;"><a href="./?cate=001005" class="actGray">찾아오시는 길</a></li>';
$this->result .= '</ul>';
$this->result .= '<div class="clear"></div>';
$this->result .= '<div class="address"><strong>'.$GLOBALS['cfg']['site']['groupName'].'</strong>&nbsp;&nbsp;'.$GLOBALS['cfg']['site']['address'].'&nbsp;&nbsp;전화: '.$GLOBALS['cfg']['site']['phone'].'&nbsp;&nbsp;팩스: '.$GLOBALS['cfg']['site']['fax'].'<br />대표이사: '.$GLOBALS['cfg']['site']['ceo'].'&nbsp;&nbsp;사업자등록번호: '.$GLOBALS['cfg']['site']['groupNo'].'&nbsp;&nbsp;통신판매신고번호: '.$GLOBALS['cfg']['site']['ecommerceNo'].'&nbsp;&nbsp;개인정보취급담당: '.$GLOBALS['cfg']['site']['operator'].'</div>';
$this->result .= '<address>Copyright ⓒ <strong>'.date('Y', $GLOBALS['cfg']['site']['dateReg']).'</strong> <a>'.strtoupper($GLOBALS['cfg']['site']['siteName']).'</a> All rights reserved.</address>';
$this->result .= '</div><div class="clear"></div>';

?>
