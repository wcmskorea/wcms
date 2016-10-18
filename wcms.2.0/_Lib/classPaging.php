<?php
class Paging
{
	var $total;        // number of row == count(*)
	var $row;        // display number of row
	var $block;      // display number of page block
	var $totalblock;     // total number of block
	var $current;    // current page number
	var $end;        // end page number (= total number of page)
	var $prev;       // number of previous page block
	var $next;       // number of next page block

	var $url;          // self filename == $_SERVER[PHP_SELF]
	var $param;    // parameter name of page variable
	var $qstr;        // added query string (other parameter of HTTP GET method)
	var $deco;      // decorative string of this object's shapeXXX functions

	var $a_ary_Result;    // return result associative array

	// initiate member variable
	function Paging($total, $current, $row, $block)
	{
	    $this->mode         = "text";
	    $this->total		= $total;
	    $this->row			= $row;
	    $this->block		= $block;
	    $this->current		= ($total > $row) ? $current : 1;
	    $this->end			= @ceil($total/$row);
	    $this->totalblock	= @ceil($this->end/$block);
	    $this->prev			= $block * (@ceil($current/$block) - 1);
	    $this->next			= $block * (@ceil($current/$block)) + 1;
	    $this->url			= "./index.php";
	    $this->param		= "currentPage";

	    $this->a_ary_Result['NumRec']		= $this->total;
	    $this->a_ary_Result['NumBlock']		= $this->totalblock;
	    $this->a_ary_Result['CurrentPage']	= $this->current;
	    $this->a_ary_Result['EndPage']		= $this->end;
	    $this->a_ary_Result['LimitIndex']	= $this->row * ($this->current - 1);
	    $this->a_ary_Result['LimitNum']		= ($this->current < $this->end) ? $this->row : $this->row;
	    $this->a_ary_Result['LimitQuery']	= " Limit ". $this->a_ary_Result['LimitIndex'] .", ". $this->a_ary_Result['LimitNum'];
    	return true;
	}

	// add query string this format : &name1=value1&name2=value2
	function addQueryString($qstr)
	{
		$this->qstr .= str_replace("currentPage","currentPaged",$qstr);
		return true;
	}

	// set parameter name
	function setParamName($param)
	{
		$this->param = $param;
		return true;
	}

	// decorate page-number string : ... 3 4 [5] 6 7 ...
	function decoNumber($cl, $cr, $ol, $or)
	{
		$this->deco[c][l] = $cl;
		$this->deco[c][r] = $cr;
		$this->deco[o][l] = $ol;
		$this->deco[o][r] = $or;
		return true;
	}

	// decorate prefix, suffix and between page-numbers
	function decoBlock($prefix, $midfix, $suffix)
	{
		$this->deco[b][p] = $prefix;
		$this->deco[b][m] = $midfix;
		$this->deco[b][s] = $suffix;
		return true;
	}

	// set character which jump page-block : [<<] ... 4 5 6 7 ... [>>]
	function setJumpChar($prev, $next, $start, $end)
	{
		$this->deco[j][p] = $prev;
		$this->deco[j][n] = $next;
		$this->deco[j][s] = $start;
		$this->deco[j][e] = $end;
		return true;
	}

	// return result associative array
	function result($file="")
	{
		$this->setDesign($file);
		$this->setPageLink();
		return $this->a_ary_Result;
   }

	// set Design
	function setDesign($file="")
	{
		if(preg_match('/tab/', $this->mode))
		{
			$this->decoNumber('<strong>', '</strong>', "", "");
			$this->decoBlock("", "", "");
			$this->setJumpChar('<img src="/common/image/button/btn_goFirst.gif" alt="이전으로" />', '<img src="/common/image/button/btn_goLast.gif" width="7" height="5" alt="다음으로" />', '<img src="/common/image/button/btn_goFirstest.gif" width="7" height="5" alt="맨처음으로" />', '<img src="/common/image/button/btn_goLastest.gif" width="7" height="5" alt="맨끝으로" />');
			$this->deco[j][prev] = "<a href=\"javascript:;\" onclick=\"$.insert('#".$this->mode."', '".$file."', '&amp;".$this->param."=".$this->prev.$this->qstr."',300)\">".$this->deco[j][p]."</a>";
			$this->deco[j][next] = "<a href=\"javascript:;\" onclick=\"$.insert('#".$this->mode."', '".$file."', '&amp;".$this->param."=".$this->next.$this->qstr."',300)\">".$this->deco[j][n]."</a>";
			$this->deco[j][start] = "<a href=\"javascript:;\" onclick=\"$.insert('#".$this->mode."', '".$file."', '&amp;".$this->param."=1".$this->qstr."',300)\"  class=\"first\">".$this->deco[j][s]."</a>";
			$this->deco[j][end]  = "<a href=\"javascript:;\" onclick=\"$.insert('#".$this->mode."', '".$file."', '&amp;".$this->param."=".$this->end.$this->qstr."',300)\">".$this->deco[j][e]."</a>";
			$this->deco[c][ThisPage] = $this->deco[c][l] . $this->current . $this->deco[c][r];
			$this->deco[c][PageLink] = "<a href=\"javascript:;\" onclick=\"$.insert('#".$this->mode."', '".$file."', '&amp;".$this->param."=@@i@@".$this->qstr."',300)\">".$this->deco[o][l]."@@i@@".$this->deco[o][r]."</a>";
		}
		else
		{
			switch ($this->mode)
			{
				case "Text": default:
					$this->decoNumber('<strong>', '</strong>', "", "");
					$this->decoBlock("", "", "");
					$this->setJumpChar('<span class="quick"><img src="/common/image/button/btn_goFirst.gif" alt="이전으로" /></span>', '<span class="quick"><img src="/common/image/button/btn_goLast.gif" width="7" height="5" alt="다음으로" /></span>', '<span class="quick"><img src="/common/image/button/btn_goFirstest.gif" width="7" height="5" alt="처음으로" /></span>', '<span class="quick"><img src="/common/image/button/btn_goLastest.gif" width="7" height="5" alt="맨끝으로" /></span>');
					$this->deco[j][prev] = "<a href=\"".$this->url."?".$this->param."=".$this->prev.$this->qstr."\">".$this->deco[j][p]."</a>";
					$this->deco[j][next] = "<a href=\"".$this->url."?".$this->param."=".$this->next.$this->qstr."\">".$this->deco[j][n]."</a>";
					$this->deco[j][start] = "<a href=\"".$this->url."?".$this->param."=1".$this->qstr."\" class=\"first\">".$this->deco[j][s]."</a>";
					$this->deco[j][end]  = "<a href=\"".$this->url."?".$this->param."=".$this->end.$this->qstr."\">".$this->deco[j][e]."</a>";
					$this->deco[c][ThisPage] = $this->deco[c][l] . $this->current . $this->deco[c][r];
					$this->deco[c][PageLink] = "<a href=\"".$this->url."?".$this->param."=@@i@@".$this->qstr."\">".$this->deco[o][l]."@@i@@".$this->deco[o][r]."</a>";
				break;
				case "module":
					$this->decoNumber('<strong>', '</strong>', "", "");
					$this->decoBlock("", "", "");
					$this->setJumpChar('<img src="/common/image/button/btn_goFirst.gif" alt="이전으로" />', '<img src="/common/image/button/btn_goLast.gif" width="7" height="5" alt="다음으로" />', '<img src="/common/image/button/btn_goFirstest.gif" width="7" height="5" alt="맨처음으로" />', '<img src="/common/image/button/btn_goLastest.gif" width="7" height="5" alt="맨끝으로" />');
					$this->deco[j][prev] = "<a href=\"javascript:;\" onclick=\"$.insert('#module', '".$file."', '&amp;".$this->param."=".$this->prev.$this->qstr."',300)\">".$this->deco[j][p]."</a>";
					$this->deco[j][next] = "<a href=\"javascript:;\" onclick=\"$.insert('#module', '".$file."', '&amp;".$this->param."=".$this->next.$this->qstr."',300)\">".$this->deco[j][n]."</a>";
					$this->deco[j][start] = "<a href=\"javascript:;\" onclick=\"$.insert('#module', '".$file."', '&amp;".$this->param."=1".$this->qstr."',300)\"  class=\"first\">".$this->deco[j][s]."</a>";
					$this->deco[j][end]  = "<a href=\"javascript:;\" onclick=\"$.insert('#module', '".$file."', '&amp;".$this->param."=".$this->end.$this->qstr."',300)\">".$this->deco[j][e]."</a>";
					$this->deco[c][ThisPage] = $this->deco[c][l] . $this->current . $this->deco[c][r];
					$this->deco[c][PageLink] = "<a href=\"javascript:;\" onclick=\"$.insert('#module', '".$file."', '&amp;".$this->param."=@@i@@".$this->qstr."',300)\">".$this->deco[o][l]."@@i@@".$this->deco[o][r]."</a>";
				 break;
				 case "dialog":
					$this->decoNumber('<strong>', '</strong>', "", "");
					$this->decoBlock("", "", "");
					$this->setJumpChar('<img src="/common/image/button/btn_goFirst.gif" alt="이전으로" />', '<img src="/common/image/button/btn_goLast.gif" width="7" height="5" alt="다음으로" />', '<img src="/common/image/button/btn_goFirstest.gif" width="7" height="5" alt="맨처음으로" />', '<img src="/common/image/button/btn_goLastest.gif" width="7" height="5" alt="맨끝으로" />');
					$this->deco[j][prev] = "<a href=\"".$this->url."?".$this->param."=".$this->prev.$this->qstr."\">".$this->deco[j][p]."</a>";
					$this->deco[j][next] = "<a href=\"".$this->url."?".$this->param."=".$this->next.$this->qstr."\">".$this->deco[j][n]."</a>";
					$this->deco[j][start] = "<a href=\"".$this->url."?".$this->param."=1".$this->qstr."\" class=\"first\">".$this->deco[j][s]."</a>";
					$this->deco[j][end]  = "<a href=\"".$this->url."?".$this->param."=".$this->end.$this->qstr."\">".$this->deco[j][e]."</a>";
					$this->deco[c][ThisPage] = $this->deco[c][l] . $this->current . $this->deco[c][r];
					$this->deco[c][PageLink] = "<a href=\"javascript:;\" onclick=\"$.dialog('".$file."', '&amp;".$this->param."=@@i@@".$this->qstr."',".$this->width.",".$this->height.")\">".$this->deco[o][l]."@@i@@".$this->deco[o][r]."</a>";
				 break;
			}
		}
		return true;
	}

	// set page link
	function setPageLink()
	{
		$pagelink = $this->deco[b][p];
			//[첫화면버튼] [이전블록버튼] 설정
			if ($this->prev>0) { $pagelink .= $this->deco[j][start] . $this->deco[j][prev]; }

			//페이지링크 설정
			$EndLoopIndex = (($this->next - 1) > $this->end) ? ($this->end + 1) : $this->next;
			for ($i=$this->prev+1; $i < $EndLoopIndex; $i++)
			{
					$pagelink .= $this->deco[b][m];
					if($i == 1){
						if($i == $this->current){
							$pagelink .= str_replace('<strong>', '<strong class="first">', $this->deco[c][ThisPage]);
						} else {
							$pagelink .= str_replace("@@i@@", $i, $this->deco[c][PageLink]);
							$pagelink = str_replace('>'.$i, ' class="first">'.$i, $pagelink);
						}
					} else {
						$pagelink .= ($i==$this->current) ? $this->deco[c][ThisPage] : str_replace("@@i@@", $i, $this->deco[c][PageLink]);
					}
			}
			$pagelink .= $this->deco[b][m];

			//[다음블록버튼] [마지막화면버튼] 설정
			if ($this->end>=$this->next) { $pagelink .= $this->deco[j][next] . $this->deco[j][end]; }
		$this->a_ary_Result[PageLink] = $pagelink . $this->deco[b][s];
		return true;
	}
}
?>
