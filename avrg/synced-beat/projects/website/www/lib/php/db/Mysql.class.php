<?php

class php_db_Mysql {
	public function __construct(){}
	static function connect($params) {
		$GLOBALS['%s']->push("php.db.Mysql::connect");
		$»spos = $GLOBALS['%s']->length;
		$c = mysql_connect($params->host . (($params->port === null ? "" : ":" . $params->port)) . (($params->socket === null ? "" : ":" . $params->socket)), $params->user, $params->pass);
		if(!mysql_select_db($params->database, $c)) {
			throw new HException("Unable to connect to " . $params->database);
		}
		{
			$»tmp = new php_db__Mysql_MysqlConnection($c);
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	function __toString() { return 'php.db.Mysql'; }
}
