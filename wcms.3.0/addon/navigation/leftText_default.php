<?php
/* --------------------------------------------------------------------------------------
| Left Sub Navigation : TEXT 방식
|----------------------------------------------------------------------------------------
| Last (2010년 1월 23일 토요일 : 이성준)
*/
$this->cate = ($this->caching) ? $this->cacheCate : __CATE__;
$this->result .= '<h2>서브메뉴</h2>';

$len  = ($GLOBALS['cfg']['skin'] == 'default') ? 3 : 9;
$subTitle = @mysql_fetch_array(@mysql_query(" SELECT name FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='".$len."' AND cate<>'000' AND SUBSTRING(cate,1,".$len.")='".substr($this->cate,0,$len)."' AND status='normal' "));

/* 서브 타이틀 예외처리 */
if(substr($this->cate, 0, 6) === '000999') { $subTitle['0'] = '이용안내'; }
if(substr($this->cate, 0, 6) === '000002') { $subTitle['0'] = '회원정보'; }
if(!$subTitle['0']) { $subTitle['0'] = '기타'; }

$this->result .= '<div class="sub_menu">';
$this->config['useTitle'] = 'image';
$this->result .= $this->printTitle("subMenuTitle", $subTitle['0'], substr($this->cate, 0, $len));
$this->result .= '<ul class="parents">';

if(substr($this->cate,0,6) === '000002')
{
	$rst = @mysql_query(" SELECT cate,name,mode,url,target FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)>'6' AND SUBSTRING(cate,1,6)='000002' AND status='normal' ORDER BY sort ASC ");
}
else if(substr($this->cate,0,6) === '000999')
{
	$rst = @mysql_query(" SELECT cate,name,mode,url,target FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)>'6' AND SUBSTRING(cate,1,6)='000999' AND status='normal' ORDER BY sort ASC ");
}
else
{
	$rst = @mysql_query(" SELECT cate,name,mode,url,target FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='".intval($len+3)."' AND SUBSTRING(cate,1,".$len.")='".substr($this->cate,0,$len)."' AND SUBSTRING(cate,1,3)<>'000' AND status='normal' ORDER BY sort ASC ");
}

$n = 1;
while($sRows = @mysql_fetch_array($rst))
{
	if($sRows['cate'] === '000002001' && $_SESSION['uid']) { $sRows['name'] = '로그아웃'; }
	$moveCate = (substr($sRows['mode'],0,2) == "md" || $sRows['mode'] == "") ? $sRows['cate'] : $sRows['mode'];
	$this->result .= ($n < 2) ? '<li class="parent first">' : '<li class="parent">';
	if($sRows['mode'] == 'url')
	{
		$this->result .= '<a href="'.$sRows['url'].'" target="'.$sRows['target'].'" class="actBold';
	}
	else
	{
		$this->result .= '<a href="'.$cfg['droot'].'index.php?cate='.$moveCate.'" target="'.$sRows['target'].'" class="actBold';
	}
	$this->result .= ($sRows['cate'] == substr($this->cate,0,intval($len+3))) ? ' active">'.$sRows['name'].'</a>' : '">'.$sRows['name'].'</a>';

	if($sRows['cate'] == substr($this->cate,0,6))
	{
		$rst2 = @mysql_query(" SELECT cate,name,mode,url,target FROM `site__` WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='".intval($len+6)."' AND SUBSTRING(cate,1,".intval($len+3).")='".$sRows['cate']."' AND status='normal' ORDER BY sort ASC ");

		if(mysql_num_rows($rst2)>0) //2012-09-10 오혜진 3차 메뉴가 없을 경우 <ul class="childes"></ul> 출력되지 않도록 함
		{
			$this->result .= '<ul class="childes">';

			while($ssRows = @mysql_fetch_array($rst2))
			{
				if($ssRows['cate'] === '000002001' && $_SESSION['uid']) { $ssRows['name'] = '로그아웃'; }
				$moveCate = (substr($ssRows['mode'],0,2) == "md" || $ssRows['mode'] == "") ? $ssRows['cate'] : $ssRows['mode'];

				$this->result .= '<li class="childe">';
				if($ssRows['mode'] == 'url')
				{
					$this->result .= '<a href="'.$ssRows['url'].'" target="'.$ssRows['target'].'"';
				}
				else
				{
					$this->result .= '<a href="'.$cfg['droot'].'index.php?cate='.$moveCate.'" target="'.$ssRows['target'].'"';
				}
				$this->result .= ($ssRows['cate'] == $this->cate) ? ' class="active">'.$ssRows['name'].'</a>' : '>'.$ssRows['name'].'</a>';
				$this->result .= '</li>';
			}
			$this->result .= '</ul>';
		}
	}
	$this->result .= '</li>';
	$n++;
}
$this->result .= '</ul>';
$this->result .= '</div>';
?>
