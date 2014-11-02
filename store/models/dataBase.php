<?php
class dataBase {
	private $db;
	
	function __construct(){
		$this->db = Zend_Registry::get('db');
	}
}
?>