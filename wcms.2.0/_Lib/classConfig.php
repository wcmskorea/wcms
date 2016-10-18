<?php
/**
 * Author : Sung-Jun, Lee (http://www.aceoa.com)
 * Lastest : 2011. 2. 7.
 */
class Config
{
	var $config;
	var $configs;
	var $configed;
	var $cfg;
	var $site;
	var $file;
	var $fp;

	function Config($configed=null)
	{
		$this->configs = array('site', 'payment', 'mileage', 'track', 'orderStatus', 'locate', 'merchant', 'headColor');
		$this->configed = $configed;
		//Functions::showArray($this->configed);
		//exit();
	}

	function configHeader()
	{
		$this->config = '<?php '.PHP_EOL;
		$this->config .= ';header("HTTP/1.0 404 Not found");'.PHP_EOL;
		$this->config .= ';die();'.PHP_EOL;
		$this->config .= ';/*'.PHP_EOL;
		$this->config .= ';사이트 설정, 기본정보, 결제정보, 적립정보, 배송정보 환경설정'.PHP_EOL;
	}

	function configFooter()
	{
		$this->config .= ';*/'.PHP_EOL;
		$this->config .= '?>'.PHP_EOL;
	}

	function configMake($except=array())
	{
		$this->configHeader();
		foreach($this->configs AS $val)
		{
			$this->config .= ''.PHP_EOL;
			$this->config .= '['.$val.']'.PHP_EOL;
			foreach($this->configed[$val] AS $key=>$val)
			{
				if(!in_array($key, $except))
				{
					$this->config .= $key.' = "'.$val.'";'.PHP_EOL;
				}
			}
		}
		$this->configFooter();
		return true;
	}

	function configSave($file)
	{
		if(is_writable($file))
		{
			$fp = fopen($file, w);
			if(fwrite($fp, $this->config))
			{
				fclose($fp);
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			Functions::alt("[".$file."]환경설정 파일의 쓰기권한이 설정되어야 적용됩니다.");
		}
	}
}
?>
