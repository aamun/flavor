<?php

class appdb {

	public $db;
	
	public function __construct() {		
		$this->registry = registry::getInstance();
		$this->db = $this->registry["db"];
	}
	
	public function findBySql($sql) { 		
		$rs = $this->db->query($sql);
        if($this->db->numRows() != 0){
            $this->record = $this->db->fetchRow();
            $this->isNew = false;
			$this->multipleRecords = false;
        }
		return $this->record;
	}
	
	public function findAllBySql($sql) { 		
		$rs = $this->db->query($sql);
		$rows = array();
		if($this->db->numRows() != 0){
			while($row = $this->db->fetchRow()) $rows[] = $row;
			$this->isNew = false;
			$this->multipleRecords = true;
			$this->record = $rows;
		}
		return $rows;
	}
	
}

?>