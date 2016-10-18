<?php
class DbMySQL
{
	var $sDbName = '';
	var $sLastQuery;
	var $oBatch;
	var $oPrepare;
	var $bLock;
	var $bMagic;
	var $oConn;
	var $oRs;
	var $sql;
	var $temp;
	var $data;

	function DbMySQL($sDbName=null)
	{
		$this->server	= 1;
		$this->dbUser	= $GLOBALS['cfg']['db']['dbUser'];
		$this->dbPass	= $GLOBALS['cfg']['db']['dbPass'];
		$this->oConn	= null;
		$this->debug	= null;
		$this->oRs		= array();
		$this->bLock	= false;
		$this->bMagic	= get_magic_quotes_gpc();
		$this->data		= array();
	}

	function connect($server=1)
	{
		if ($this->oConn==null && !is_resource($this->oConn))
		{
			switch($server)
			{
				case "1": default:
					$this->oConn = @mysql_connect('localhost', $this->dbUser, $this->dbPass) or die ('<p class="pd10">데이터베이스 연결 실패입니다!</p>');
					break;
				case "2":
					$this->dbUser = "";
					$this->dbPass = "";
					$this->oConn = @mysql_connect('', $this->dbUser, $this->dbPass) or die ('<p class="pd10">서비스 정보가 없습니다!</p>');
					break;
			}
			//$this->query("set names ".str_replace("-",null,$GLOBALS['cfg']['charset'])); //UTF-8을 위한 설정
		}
	}

	function disConnect()
	{
		if ($this->bLock) $this->UnLock();
		$this->FreeResult();
		if ($this->oConn!=null && is_resource($this->oConn))
		{
			mysql_close($this->oConn);
			$this->oConn = null;
		}
	}

	function selectDB($sDbName)
	{
		$this->sDBName = ($sDbName) ? $sDbName : $this->sDBName;
		return mysql_select_db($sDbName, $this->oConn);
	}

	function &query($sQuery='', $sKey=0)
	{
		$this->msg('질의를 입력해주세요.', !$sQuery);
		$this->sLastQuery = $sQuery;
		if($this->debug) { die($sQuery); }
		$this->oRs[$sKey] = @mysql_query($sQuery, $this->oConn);
		$this->msg("[".$sQuery."]-".@mysql_error($this->oConn), !$this->oRs[$sKey]);
	}
	function &queryForce($sQuery='', $sKey=0)
	{
		$this->Msg('질의를 입력해주세요.', !$sQuery);
		if($this->debug) { die($sQuery); }
		@mysql_query($sQuery, $this->oConn);
	}

	function &fetch($sKey=0)
	{
		if (is_resource($this->oRs[$sKey]))
		{
			return mysql_fetch_assoc($this->oRs[$sKey]);
		}
		else
		{
			return false;
		}
	}

	function &object($sKey=0)
	{
		if (is_resource($this->oRs[$sKey]))
		{
			return mysql_fetch_object($this->oRs[$sKey]);
		}
		else
		{
			return false;
		}
	}

	function &fetchRow($sKey=0)
	{
		if (is_resource($this->oRs[$sKey]))
		{
			return mysql_fetch_row($this->oRs[$sKey]);
		}
		else
		{
			return false;
		}
	}

	function &queryFetch($sQuery, $sKey=0)
	{
		$this->msg('질의를 입력해주세요.', !$sQuery);
		$this->sLastQuery = $sQuery;
		if($this->debug) { die($sQuery); }
		$this->oRs[$sKey] = mysql_query($sQuery, $this->oConn);
		$this->msg("[".$sQuery."]-".@mysql_error($this->oConn), !$this->oRs[$sKey]);
		return mysql_fetch_assoc($this->oRs[$sKey]);
	}

	 function &selectQuery($sQuery, $sKey=0) {
		$this->msg('질의를 입력해주세요.', !$sQuery);
		$this->sLastQuery = $sQuery;
		if($this->debug) { die($sQuery); }
		$this->oRs[$sKey] = mysql_query($sQuery, $this->oConn);
		$this->msg("[".$sQuery."]-".@mysql_error($this->oConn), !$this->oRs[$sKey]);
		while($data = mysql_fetch_assoc($this->oRs[$sKey])) {
			$arr[] = $data;
		}
		return $arr;
	}

	function &queryObj($sQuery, $sKey=0)
	{
		$this->msg('질의를 입력해주세요.', !$sQuery);
		$this->sLastQuery = $sQuery;
		$this->oRs[$sKey] = mysql_query($sQuery, $this->oConn);
		$this->msg("[".$sQuery."]-".@mysql_error($this->oConn), !$this->oRs[$sKey]);
		return mysql_fetch_object($this->oRs[$sKey]);
	}

	function &queryFields($sQuery, $sKey=0)
	{
		$this->msg('질의를 입력해주세요.', !$sQuery);
		$this->sLastQuery = $sQuery;
		$this->oRs[$sKey] = mysql_query($sQuery, $this->oConn);
		$this->msg("[".$sQuery."]-".@mysql_error($this->oConn), !$this->oRs[$sKey]);
		return mysql_list_fields($this->oRs[$sKey]);
	}

	function &queryFetchOne($sQuery, $sKey=0)
	{
		$this->msg('질의를 입력해주세요.', !$sQuery);
		$this->sLastQuery = $sQuery;
		if($this->debug) { die($sQuery); }
		$this->oRs[$sKey] = mysql_query($sQuery, $this->oConn);
		$this->msg("[".$sQuery."]-".@mysql_error($this->oConn), !$this->oRs[$sKey]);
		$oTmp = mysql_fetch_array($this->oRs[$sKey]);
		return $oTmp[0];
	}

	// 자동 삽입 SQL문 생성 및 쿼리
	function sqlInsert($table, $query, $debug=0, $sKey=0)
	{
		$this->lock(array('`'.$table.'`'=>'READ', '`'.$table.'`'=>'WRITE'), $sKey);
		$this->sql = $query." INTO `".$table."` (";
		$this->temp = " VALUES (";
		$this->query(" SHOW COLUMNS FROM `".$table."` ", $sKey);
		while($row = $this->fetch($sKey))
		{
			$this->sql .= (array_key_exists($row['Field'], $this->data)) ? "`".$row['Field']."`," : null;
			$this->temp .= (array_key_exists($row['Field'], $this->data)) ? "'".$this->data[$row['Field']]."'," : null;
		}
		$this->sql = preg_replace('/,$/', '', $this->sql).")".preg_replace('/,$/', '', $this->temp).")";
		if($debug)
		{
			echo $this->sql;
			die(0);
		}
		$this->query($this->sql, $sKey);
		$result = ($this->getAffectedRows($sKey) > 0) ? true : false;
		$this->unLock($sKey);
		return $result;
	}

	// 자동 수정 SQL문 생성 및 쿼리
	function sqlUpdate($table, $query, $except=array(), $debug=0, $sKey=0)
	{
		$this->lock(array('`'.$table.'`'=>'READ', '`'.$table.'`'=>'WRITE'), $sKey);
		$this->sql = "UPDATE `".$table."` SET ";
		$this->query(" SHOW COLUMNS FROM `".$table."` ", $sKey);
		while($row = $this->fetch($sKey))
		{
			if(!in_array($row['Field'], $except))
			{
				$this->sql .= (array_key_exists($row['Field'], $this->data)) ? "`".$row['Field']."`=" : null;
				$this->sql .= (array_key_exists($row['Field'], $this->data)) ? "'".$this->data[$row['Field']]."'," : null;
			}
		}
		$this->sql = preg_replace('/,$/', '', $this->sql)." WHERE ".$query;
		if($debug)
		{
			echo $this->sql;
			die(0);
		}
		$this->query($this->sql, $sKey);
		$result = ($this->getAffectedRows($sKey) > 0) ? true : false;
		$this->unLock($sKey);
		return $result;
	}

	# 총개수 추출 쿼리
	function getTotalRows($sQuery, $sKey=0)
	{
		$this->msg('질의를 입력해주세요.', !$sQuery);
		$this->sLastQuery = $sQuery;
		$this->oRs[$sKey] = mysql_query($sQuery, $this->oConn);
		$this->msg("[".$sQuery."]-".@mysql_error($this->oConn), !$this->oRs[$sKey]);
		return mysql_num_rows($this->oRs[$sKey]);
	}

	# 총개수 추출 쿼리
	function getTotalCount($table, $query=1)
	{
		$rst = @mysql_fetch_array(@mysql_query("SELECT COUNT(*) FROM `".$table."` WHERE ".$query.""));
		return $rst[0];
	}

	function checkTable($table)
	{
		$this->Query("SHOW TABLES LIKE '".$table."'");
		return $this->getNumRows();
	}

	function passType($cfg, $passwd)
	{
		switch($cfg)
		{
			case "crypt" :
				return $this->queryFetchOne(" SELECT ENCRYPT('".$passwd."') ");
				break;
			case "pw" : default :
				return $this->queryFetchOne(" SELECT PASSWORD('".$passwd."') ");
				break;
			case "oldpw" :
				return $this->queryFetchOne(" SELECT OLD_PASSWORD('".$passwd."') ");
				break;
			case "md5" :
				return $this->queryFetchOne(" SELECT MD5('".$passwd."') ");
				break;
			case "sha256" :
				return hash("sha256", $passwd);
				break;
			case "sha384" :
				return hash("sha384", $passwd);
				break;
			case "sha512" :
				return hash("sha512", $passwd);
				break;
		}
	}

	function lock($oTables)
	{
		$this->Msg('테이블 잠금 변수는 배열 형태이어야 합니다.', !is_array($oTables));
		$sSQL = 'LOCK TABLES ';
		foreach($oTables as $sKey=>$sValue)
		{
			$sSQL .= " $sKey $sValue,";
		}
		$sSQL = preg_replace('/,$/','',$sSQL);
		$this->query($sSQL,'LOCK');
		$this->bLock = TRUE;
	}

	function unLock()
	{
		$this->Query('UNLOCK TABLES','LOCK');
		$this->bLock = false;
	}

	function &getNumRows($sKey=0)
	{
		return mysql_num_rows($this->oRs[$sKey]);
	}

	function &getAffectedRows()
	{
		return mysql_affected_rows($this->oConn);
	}

	function &getLastID()
	{
		return mysql_insert_id($this->oConn);
	}

	function &getLastQuery()
	{
		return $this->sLastQuery;
	}

	function freeResult()
	{
		if (count($this->oRs)>0)
		{
			foreach($this->oRs as $value)
			{
				if (is_resource($value))
				{
					mysql_free_result($value);
				}
			}
			unset($this);
		}
	}

	function msg($sMsg, $bCondition=TRUE)
	{
		if ($bCondition)
		{
			$this->DisConnect();
			//Functions::setLog(__FILE__, $sMsg);
			print('[SERVER] : Ms-Sql Server ( 192.168.0.10 )<br />[ERROR] : '.$sMsg.'<br />[DATETIME] : '.date('Y년 m월 d일 H시 i분 s초').'<br />');
			flush();
			exit;
		}
	}
}
?>
