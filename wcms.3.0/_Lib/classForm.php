<?php
/**
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2010. 4. 29.
 */
class Form
{
	var $type;
	var $label;
	var $name;
	var $value;
	var $class;
	var $style;
	var $rst;

	function Form($type)
	{
		$this->type = $type;
	}

	function addStart($label, $id, $cols=0, $rows=0, $require='N')
	{
		$this->label	= $label;
		$this->id			= $id;
		$this->cols		= $cols;
		$this->rows		= $rows;
		$this->require	= $require;
		$this->class	= ($this->require == 'M') ? 'input_blue' : 'input_gray';
		if($this->type == 'table')
		{
			$this->rst	= ($this->cols) ? '<tr>'.PHP_EOL : null;
			$this->rst 	.= '<th scope="row" id="'.$this->id.'_th"';
			$this->rst	.= ($this->rows) ? ' rowspan="'.$this->rows.'"' : null;
			$this->rst	.= '><label for="'.$this->id.'"';
			$this->rst	.= ($this->require == 'M') ? ' class="required"><span class="colorRed" title="필수입력항목">*</span>' : '>';
			$this->rst	.= '<strong>'.$this->label.'</strong></label></th>'.PHP_EOL.'<td id="'.$this->id.'_td"';
			$this->rst	.= ($this->rows) ? ' rowspan="'.$this->rows.'"' : null;
			$this->rst	.= ($this->cols && $this->cols != 1) ? ' colspan="'.$this->cols.'"' : null;
			$this->rst	.= '><ol>';
		}
		return $this->rst;
	}

	function addEnd($close=false)
	{
		if($this->type == 'table')
		{
			$this->rst .= (!$close) ? '</ol>'.PHP_EOL.'</td>' : '</ol>'.PHP_EOL.'</td>'.PHP_EOL.'</tr>'.PHP_EOL;
		}
		echo(PHP_EOL.$this->rst);
		unset($i, $prefix, $this->mesg, $this->addTd, $this->rst);
	}

	function addHtml($str)
	{
		return $this->rst .= $str;
	}

	function add($element, $name=null, $value=null, $style=null, $valid=null, $addAtt=null)
	{
		$this->element	= $element;
		$this->name		= $name;
		$this->value	= $value;
		$this->style	= $style;
		$this->valid	= $valid;
		$this->addAtt	= $addAtt;

		switch($this->element)
		{

			case 'input' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= (preg_match('/passw/i', $this->id) || preg_match('/idcode/i', $this->id)) ? '<input type="password"' : '<input type="text"';
				$this->class = ($this->require == 'M') ? $this->class.' required' : $this->class;
				$this->rst	.= ' id="'.str_replace("[]",null,$this->name).'" name="'.$this->name.'" title="'.$this->label.'" alt="'.$this->label.'" class="'.$this->class.'" style="'.$this->style.'" '.$this->addAtt.' value="'.$this->value.'"';
				$this->rst	.= ($this->require == 'M') ? ' req="required"' : null;
				$this->rst	.= ($this->valid) ? ' '.$this->valid.' />' : ' />';
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			case 'checkbox' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt">' : null;
				$this->rst	.= '<span class="keeping"><input type="checkbox" id="'.$this->id.'" name="'.$this->name.'" title="'.$this->label.'" alt="'.$this->label.'" class="input_check'.($this->require == 'M' ? ' required' : '').'" '.$this->addAtt.' value="Y"';
				$this->rst	.= ($this->value == 'Y') ? ' checked="checked"' : null;
				$this->rst	.= ($this->require == 'M') ? ' req="required"' : null;
				$this->rst	.= ($this->valid) ? ' '.$this->valid.' />' : ' />';
				$this->rst	.= '<label for="'.$this->id.'" style="'.$this->style.'">'.$this->label.'</label></span>';
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			case 'checkboxs' : //$this->name을 배열로 받는다.
				if(!is_array($this->name)) return null;
				$this->value = (!is_array($this->value)) ? array() : $this->value;
				foreach($this->name AS $key=>$val)
				{
					$this->rst	.= ($this->type == 'table') ? '<li class="opt">' : null;
					$this->rst	.= '<span class="keeping"><input type="checkbox" id="'.$this->id.$key.'" name="'.$this->id.'[]" title="'.$this->label.'" alt="'.$this->label.'" class="input_check'.($this->require == 'M' ? ' required' : '').'" '.$this->addAtt.' value="'.$key.'"';
					$this->rst	.= (in_array($key, $this->value)) ? ' checked="checked"' : null;
					$this->rst	.= ($this->require == 'M') ? ' req="required"' : null;
					$this->rst	.= ($this->valid) ? ' '.$this->valid.' />' : ' />';
					$this->rst	.= '<label for="'.$this->id.$key.'" alt="'.$val.'"';
					$this->rst	.= (in_array($key, $this->value)) ? ' style="'.$this->style.'">' : '>';
					$this->rst	.= $val.'</label></span>';
					$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				}
				break;

			case 'radio' : //$this->name을 배열로 받는다.
				if(!is_array($this->name)) return null;
				$i = 1;
				foreach($this->name AS $key=>$val)
				{
					$prefix = ($i < 2) ? null : $i;
					$val = trim($val);
					$this->rst	.= ($this->type == 'table') ? '<li class="opt">' : null;
					$this->rst	.= '<span class="keeping"><input type="radio" id="'.$this->id.$prefix.'" name="'.$this->id.'" title="'.$this->label.'" alt="'.$this->label.'" class="input_check'.($this->require == 'M' ? ' required' : '').'" '.$this->addAtt.' value="'.$key.'"';
					$this->rst	.= ($this->value == $key || (!$this->value && !$prefix)) ? ' checked="checked"' : null;
					$this->rst	.= ($this->require == 'M') ? ' req="required"' : null;
					$this->rst	.= ($this->valid) ? ' '.$this->valid.' />' : ' />';
					$this->rst	.= '<label for="'.$this->id.$prefix.'"';
					$this->rst	.= ($this->value == $key) ? ' style="'.$this->style.'">' : '>';
					$this->rst	.= $val.'</label></span>&nbsp;';
					$this->rst	.= ($this->type == 'table') ? '</li>' : null;
					$i++;
				}
				break;

			/* 배열의 키와 비교하는 Select문 생성 */
			case 'select' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt">' : null;
				$this->rst	.= '<select id="'.$this->id.'" name="'.$this->id.'" title="'.$this->label.'" alt="'.$this->label.'" class="bg_gray" '.$this->addAtt.' style="'.$this->style.'" onchange="this.style.color=\'#ff3300\'">';
				/* 배열일때 */
				if(is_array($this->name))
				{
					foreach($this->name AS $key=>$val)
					{
						//$key = (is_numeric($key)) ? $val : $key;
						$val = trim($val);
						$this->rst	.= '<option value="'.$key.'"';
						$this->rst	.= ($this->value == $key) ? ' selected="selected" class="colorRed"' : null;
						$this->rst	.= '>'.$val.'</option>';
					}
				/* 숫자일때 */
				} else if(is_numeric($this->name))
				{
					for($i=0; $i<=$this->name; $i++)
					{
						$this->rst	.= '<option value="'.$i.'"';
						$this->rst	.= ($this->value == $i) ? ' selected="selected" class="colorRed"' : null;
						$this->rst	.= '>'.$i.'</option>';
					}
				/* (,)콤마구분일때 */
				} else
				{
					$array = explode(",", $this->name);
					foreach($array AS $val)
					{
						$val = trim($val);
						$this->rst	.= '<option value="'.$val.'"';
						$this->rst	.= ($this->value == $val) ? ' selected="selected" class="colorRed"' : null;
						$this->rst	.= '>'.$val.'</option>';
					}
				}
				$this->rst	.= '</select>';
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 배열의 값과 비교하는 Select문 생성 */
			case 'selectValue' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt">' : null;
				$this->rst	.= '<select id="'.$this->id.'" name="'.$this->id.'" title="'.$this->label.'" alt="'.$this->label.'" class="bg_gray" '.$this->addAtt.' style="'.$this->style.'">';
				foreach($this->name AS $key=>$val)
				{
					//$key = (is_numeric($key)) ? $val : $key;
					$val 		= trim($val);
					$this->rst	.= '<option value="'.$val.'"';
					$this->rst	.= ($this->value == $val) ? ' selected="selected" class="colorRed"' : null;
					$this->rst	.= '>'.$val.'</option>';
				}
				$this->rst	.= '</select>';
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* Textarea 생성 */
			case 'textarea' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt">' : null;
				$this->class = ($this->require == 'M') ? $this->class.' required' : $this->class;
				$this->rst	.= '<textarea id="'.$this->id.'" name="'.$this->name.'" title="'.$this->label.'" alt="'.$this->label.'" style="'.$this->style.'" class="'.$this->class.'" '.$this->addAtt;
				$this->rst	.= ($this->valid) ? ' '.$this->valid.'>' : '>';
				$this->rst	.= $this->value.'</textarea>';
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 생일(년,월,일)선택 Select문 생성 */
			case 'birthDay' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= Functions::insertDate($this->name, $this->value);
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 날짜(년,월,일)선택 Select문 생성 */
			case 'date' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= Functions::insertDate($this->name, $this->value);
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 날짜(년,월,일,시) Select문 생성 */
			case 'datehour' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= Functions::insertDate($this->name, $this->value, "YMDH");
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 날짜(년,월,일,시,분) Select문 생성 */
			case 'datemin' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= Functions::insertDate($this->name, $this->value, "YMDHI");
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 날짜(년,월,일,시,분,초) Select문 생성 */
			case 'datetime' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= Functions::insertDate($this->name, $this->value, "YMDHIS");
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 날짜(월,일) Select문 생성 */
			case 'dateMonthDay' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= Functions::insertDate($this->name, $this->value, "MD");
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 날짜(년,월) Select문 생성 */
			case 'dateYearMonth' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= Functions::insertDate($this->name, $this->value, "YM");
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 시분(시간,분) Select문 생성 */
			case 'hourMin' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= Functions::insertDate($this->name, $this->value, "HI");
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;

			/* 파일선택 생성 */
			case 'file' :
				$this->rst	.= ($this->type == 'table') ? '<li class="opt" style="'.$this->style.'">' : null;
				$this->rst	.= '<input type="file"';
				$this->rst	.= ' id="'.$this->id.'" name="'.$this->name.'" title="'.$this->label.'" alt="'.$this->label.'" class="'.$this->class.'" style="'.$this->style.'" '.$this->addAtt.' value="'.$this->value.'"';
				$this->rst	.= ($this->require == 'M') ? ' req="required"' : null;
				$this->rst	.= ($this->valid) ? ' '.$this->valid.' />' : ' />';
				$this->rst	.= ($this->type == 'table') ? '</li>' : null;
				break;
		}
	}
}
?>
