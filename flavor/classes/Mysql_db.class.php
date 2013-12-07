<?php

class Mysql_db extends Singleton implements Data {

	private $connectionId;
	private $query_result;
	private $transaction = false;
	
	protected function __construct() {
		$this->dbServer = DB_Server . ((DB_Port) ? ':' . DB_Port : '');

		$this->connectionId = @mysql_connect($this->dbServer, DB_User, DB_Password);

		if ($this->connectionId && DB_name != '') {
			if (!@mysql_select_db(DB_name, $this->connectionId)) {
				throw new Exception($this->errorInfo());
			}
		} else {
			throw new Exception($this->errorInfo());
		}
	}
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}
			
	public function query($sql) { 
		if ($sql != '') {
			$this->query_result = @mysql_query($sql, $this->connectionId);
			if ($this->query_result  === false) {
				throw new Exception($this->errorInfo($sql));
			}			
		} else {
			return false;
		}
		return ($this->query_result) ? $this->query_result : false;
	}
	
	public function buildArray($query, $assoc_ary = false) {
		if (!is_array($assoc_ary)) {
			throw new Exception('Is not a valid array');
		}

		$fields = array();
		$values = array();
		if ($query == 'INSERT') {
			foreach ($assoc_ary as $key => $var) {
				$fields[] = "`{$key}`";

				if (is_null($var)) {
					$values[] = 'NULL';
				} elseif (is_string($var)) {
					$values[] = "'" . $this->sql_escape($var) . "'";
				} else {
					$values[] = (is_bool($var)) ? intval($var) : $var;
				}
			}

			$query = ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
		} else if ($query == 'UPDATE' || $query == 'SELECT') {
			$values = array();
			foreach ($assoc_ary as $key => $var) {
				if (is_null($var)) {					
					array_push($values, "`{$key}` = NULL");
				} elseif (is_string($var) and !is_numeric($var)) {
					array_push($values, "`{$key}` = '".$this->sql_escape($var)."'");
				} else {
					array_push($values, (is_bool($var)) ? "`{$key}` = ".intval($var) : "`".$key."` = $var");
				}
			}
			$query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
		}

		return $query;
	}
	
	public function fetchRow() {		
		return mysql_fetch_assoc($this->query_result);
		/* TODO: Add Exception handler */		
	}
	
	public function rowSeek($rowNum) {
		$re = @mysql_data_seek($this->query_result, $rowNum);
		if (!$re) {
			throw new Exception($this->errorInfo());
		}
		return $re;
	}

	public function lastId() {
		$re = ($this->connectionId) ? @mysql_insert_id($this->connectionId) : false;
		if (!$re) {
			throw new Exception($this->errorInfo());
		}
		return $re;
	}
	
	function nextId($table, $primary) {		
		$this->query("select max(".$primary.") as nextId from ".$table);
		$row = @mysql_fetch_row($this->query_result);
		return $row["nextId"] + 1;
	} 
	
	public function numRows() {
		$re = @mysql_num_rows($this->query_result);
		if (!$re) {
			throw new Exception($this->errorInfo());
		}
		return $re;
	}
	
	function affectedRows() {
		$re = ($this->connectionId) ? @mysql_affected_rows($this->connectionId) : false;
		if (!$re) {
			throw new Exception($this->errorInfo());
		}
		return $re;
	}
	
	public function sql_escape($msg) {
		if(get_magic_quotes_gpc()) {
	          $msg = stripslashes($msg);
	    }
		return mysql_real_escape_string($msg, $this->connectionId);
	}
	
	public function errorInfo($sql = '') { 
		return '<u>SQL ERROR</u> <br /><br />' . @mysql_error($this->connectionId) . '<br /><br /><u>SQL ERROR NUMBER</u> <br /><br />' . @mysql_errno($this->connectionId) . (($sql != '') ? '<br /><br /><u>SQL</u><br /><br />' . $sql : '') . '<br />';
	}
	
	public function close() {
		if (!$this->connectionId) {
			return false;
		}

		@mysql_close($this->connectionId);
	}
	
	public function __destruct() {
		$this->close();
	}

}