<?php

class php_db__Mysql_MysqlConnection implements php_db_Connection{
	public function __construct($c) {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::new");
		$製pos = $GLOBALS['%s']->length;
		$this->c = $c;
		$GLOBALS['%s']->pop();
	}}
	public $c;
	public function close() {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::close");
		$製pos = $GLOBALS['%s']->length;
		mysql_close($this->c);
		unset($this->c);
		$GLOBALS['%s']->pop();
	}
	public function request($s) {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::request");
		$製pos = $GLOBALS['%s']->length;
		$h = mysql_query($s, $this->c);
		if($h === false) {
			throw new HException("Error while executing " . $s . " (" . mysql_error($this->c) . ")");
		}
		{
			$裨mp = new php_db__Mysql_MysqlResultSet($h);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function escape($s) {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::escape");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = mysql_real_escape_string($s, $this->c);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function quote($s) {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::quote");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = "'" . mysql_real_escape_string($s, $this->c) . "'";
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function lastInsertId() {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::lastInsertId");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = mysql_insert_id($this->c);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function dbName() {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::dbName");
		$製pos = $GLOBALS['%s']->length;
		{
			$GLOBALS['%s']->pop();
			return "MySQL";
		}
		$GLOBALS['%s']->pop();
	}
	public function startTransaction() {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::startTransaction");
		$製pos = $GLOBALS['%s']->length;
		$this->request("START TRANSACTION");
		$GLOBALS['%s']->pop();
	}
	public function commit() {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::commit");
		$製pos = $GLOBALS['%s']->length;
		$this->request("COMMIT");
		$GLOBALS['%s']->pop();
	}
	public function rollback() {
		$GLOBALS['%s']->push("php.db._Mysql.MysqlConnection::rollback");
		$製pos = $GLOBALS['%s']->length;
		$this->request("ROLLBACK");
		$GLOBALS['%s']->pop();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->蜿ynamics[$m]) && is_callable($this->蜿ynamics[$m]))
			return call_user_func_array($this->蜿ynamics[$m], $a);
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	function __toString() { return 'php.db._Mysql.MysqlConnection'; }
}
