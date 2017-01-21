<?php

class SongInfoManager extends php_db_Manager {
	public function __construct() { if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("SongInfoManager::new");
		$�spos = $GLOBALS['%s']->length;
		parent::__construct(_hx_qtype("SongInfo"));
		$GLOBALS['%s']->pop();
	}}
	public function getPage($page, $query) {
		$GLOBALS['%s']->push("SongInfoManager::getPage");
		$�spos = $GLOBALS['%s']->length;
		$sql = new StringBuf();
		$sql->b .= "SELECT * FROM ";
		$sql->b .= $this->table_name;
		if($query !== null) {
			$this->filter($sql, $query);
		}
		$sql->b .= " ORDER BY uploadedOn DESC ";
		$sql->b .= " LIMIT ";
		$sql->b .= 20;
		$sql->b .= " OFFSET ";
		$sql->b .= 20 * $page;
		{
			$�tmp = $this->objects($sql->b, false);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function filter($sql, $query) {
		$GLOBALS['%s']->push("SongInfoManager::filter");
		$�spos = $GLOBALS['%s']->length;
		$query = $this->quote("%" . $query . "%");
		$sql->b .= " WHERE title LIKE ";
		$sql->b .= $query;
		$sql->b .= " OR artist LIKE ";
		$sql->b .= $query;
		$sql->b .= " OR uploaderName LIKE ";
		$sql->b .= $query;
		$GLOBALS['%s']->pop();
	}
	public function getSongCount($query) {
		$GLOBALS['%s']->push("SongInfoManager::getSongCount");
		$�spos = $GLOBALS['%s']->length;
		$sql = new StringBuf();
		$sql->b .= "SELECT COUNT(*) FROM ";
		$sql->b .= $this->table_name;
		if($query !== null) {
			$this->filter($sql, $query);
		}
		{
			$�tmp = $this->execute($sql->b)->getIntResult(0);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static $PAGE_LENGTH = 20;
	function __toString() { return 'SongInfoManager'; }
}
