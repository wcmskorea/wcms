<?php
class Category
{
	var $lang;				/* 언어설정 */
	var $cate;				/* 카테코리 코드 */
	var $table;				/* 카테고리 table */
	var $codecount;			/* 카테고리를 몇자로 쪼갤것인지 */
	var $length;			/* 카테고리 코드길이 */
	var $depth;				/* 카테고리 코드길이를 codecount로 나눈값 */
	var $child;				/* 카테고리 코드길이와 codecount를 더한값 */
	var $parent;			/* 카테고리의 상위 카테고리 값 */
	var $sql;
	var $rst;
	var $Rows;

	function Category($cate, $lang)
	{
		$this->lang				= $lang;
		$this->cate				= $cate;
		$this->table			= "`site__`";
		$this->catecount		= 3;
		$this->length			= strlen($this->cate);
		$this->depth			= $this->length / $this->catecount;
		$this->child			= $this->length + $this->catecount;
		$this->parent			= ($this->length > 3) ? $this->length - 3 : $this->length;
	}

	/* ------------------------------------------------------------------------------------
	 | 서브페이지 카테고리 네비게이션 출력
	 |--------------------------------------------------------------------------------------
	 | ex) Home > 카테고리1 > 카테고리2
	 |--------------------------------------------------------------------------------------
	 | Last : 이성준 (2009년 6월 15일 월요일)
	 */
	function printHistory($sub=null)
	{
		if($this->cate)
		{
			$return	.= (!$sub) ? '<a href="./" class="actGray">Home</a><span class="colorGray"> &gt; </span>' : null;
			for($i=1;$i<=$this->depth;$i++)
			{
				$parent = substr($this->cate, 0, $i * $this->catecount);
				if($parent != '000')
				{
					$where = $where."cate='".$parent."'" ;
					if($i != $this->depth) { $where = $where." OR ";}
				}
			}
			if($this->length > 3 && substr($cate,0,3) == '000') { $this->depth = 2; }

			$sql = "SELECT cate,name,LENGTH(cate) AS Length from ".$this->table." WHERE skin='".$GLOBALS['cfg']['skin']."' AND (".$where.") AND status<>'hide' ORDER BY Length";
			$rst = @mysql_query($sql);
			$n = 0;
			while($Rows = @mysql_fetch_array($rst))
			{
				$return .= ($n) ? '<span class="colorGray"> &gt; </span>' : null;
				if(substr($Rows[cate],0,3) != '000')
				{
					$return	.= ($Rows[cate] != $this->cate) ? '<a href="'.$_SERVER[PHP_SELFT].'?cate='.$Rows[cate].'" class="actGray">'.$Rows[name].'</a>' : '<strong>'.$Rows[name].'</strong>';
				}
				else
				{
					$return	.= ($Rows[cate] != $this->cate) ? $Rows[name] : '<strong>'.$GLOBALS['cfg']['cate']['name'].'</strong>';
				}
				$n++;
			}
		}
		else
		{
			$return	.= " <strong>Home</strong>";
		}
		return $return;
	}

	/* ------------------------------------------------------------------------------------
	 | 게시물 카테고리 출력
	 |--------------------------------------------------------------------------------------
	 | ex) 카테고리1 > 카테고리2 > 게시물 제
	 |--------------------------------------------------------------------------------------
	 | Last : 이성준 (2009년 6월 15일 월요일)
	 */
	function printHistoryTitle($cate)
	{
		$this->cate		= $cate;
		$this->length	= strlen($this->cate);
		$this->depth	= $this->length / $this->catecount;
		for($i=1;$i<=$this->depth;$i++)
		{
			$parent = substr($this->cate, 0, $i * $this->catecount);
			if($parent != '000')
			{
				$where = $where."cate='$parent'" ;
				if($i != $this->depth) { $where = $where." OR ";}
			}
		}
		if($this->length > 3 && substr($this->cate,0,3) == '000') $this->depth = 2;
		$sql		= "SELECT cate,name,LENGTH(cate) AS Length from ".$this->table." WHERE skin='".$GLOBALS['cfg']['skin']."' AND ".$where." ORDER BY Length";
		$rst		= @mysql_query($sql);
		while($Rows = @mysql_fetch_array($rst))
		{
			$return	.= $Rows['name']." > ";
		}
		return $return;
	}

	/* ------------------------------------------------------------------------------------
	 | 카테고리 디스플레이 가로형
	 |--------------------------------------------------------------------------------------
	 | Last : 이성준 (2009년 6월 15일 월요일)
	 */
	function printDisplay($width=null,$padding=0)
	{
		$sql		= "SELECT cate,name,mode,url,target from ".$this->table." WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='".$this->child."' AND cate like '".$this->cate."%' ORDER BY sort,cate ASC";
		$rst		= @mysql_query($sql);
		if(@mysql_num_rows($rst) < 1)
		{
			$this->parent = ($this->length > 0) ? substr($this->cate, "-".$this->length, ($this->length - $this->catecount)) : $this->cate;
			$sql	= "SELECT cate,name,mode,url,target from ".$this->table." WHERE skin='".$GLOBALS['cfg']['skin']."' AND LENGTH(cate)='".$this->length."' AND cate like '".$this->parent."%' ORDER BY sort,cate ASC";
			$rst	= @mysql_query($sql);
		}
		$return	.= '<div class="subCategoryCell"><ul>';
		$wide = ($width) ? 100/str_replace('%', null, $width) : null;
		$recCount = @mysql_num_rows($rst);
		$count = 1;
		if($recCount >= $wide)
		{
			$wideCount = $wide + 1;
		}	else {
			$wide = $recCount;
			$wideCount = $wide + 1;
			$width = str_replace($width, (100/$wide).'%', $width);
		}
		while($Rows = @mysql_fetch_array($rst))
		{
			$droot = ($GLOBALS['cfg']['skin'] == 'default') ? $GLOBALS['cfg']['droot'] : $GLOBALS['cfg']['droot'].$GLOBALS['cfg']['skin'].'/';
			$moveCate = (substr($Rows[mode],0,2) == "md" || $Rows[mode] == "") ? $Rows[cate] : $Rows[mode];
			$url = ($Rows[mode] == 'url') ? $Rows[url] : $droot.'?cate='.$moveCate;
			$class = ($width && $wideCount % $wide == 0) ? "end" : "cell";

			if($Rows[cate] == $this->cate)
			{
				$class .= " active";
				$return	.= '<li style="width:'.$width.'"><a href="'.$url.'#content" target="'.$Rows[target].'" style="padding:'.$padding.'" class="'.$class.'"><strong>'.$Rows[name].'</strong></a></li>';
			}
			else
			{
				$return	.= '<li style="width:'.$width.'"><a href="'.$url.'#content" target="'.$Rows[target].'" style="padding:'.$padding.'" class="'.$class.'"><strong>'.$Rows[name].'</strong></a></li>';
			}
			$count++;
			$wideCount++;			
		}
		$return	.= '</ul><div class="clear"></div></div>';
		return $return;
	}

	/**------------------------------------------------------------------------------------
	 * 해당 카테고리 정보 출력
	 *--------------------------------------------------------------------------------------
	*/
	function getCategoryInfo($cateCode,$skin) {
		$this->query = ' SELECT cate,name FROM `site__` WHERE skin="'.$skin.'" AND cate = "'.$cateCode.'" ';
		$this->rst = @mysql_fetch_array(@mysql_query($this->query));

		return $this->rst;
	}

	/**------------------------------------------------------------------------------------
	 * 해당 카테고리의 접근 권한 출력
	 *--------------------------------------------------------------------------------------
	*/
	function getCategoryPerm($cateCode,$skin) {
		$this->query = ' SELECT cate,perm FROM `site__` WHERE skin="'.$skin.'" AND cate = "'.$cateCode.'" ';
		$query =@mysql_query($this->query);
		$Rows = @mysql_fetch_array($query);

		//['0']관리, ['1']접근, ['2']열람권한, ['3']작성, ['4']답변
		$this->rst =  explode(',',$Rows['perm']);
		return $this->rst;
	}

}
?>
