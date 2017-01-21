<?php

class SongInfo extends php_db_Object {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("SongInfo::new");
		$»spos = $GLOBALS['%s']->length;
		parent::__construct();
		$GLOBALS['%s']->pop();
	}}
	public $id;
	public $uploaderId;
	public $uploaderName;
	public $uploadOn;
	public $title;
	public $artist;
	public $bpm;
	public $difficulties;
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	static $manager;
	function __toString() { return 'SongInfo'; }
}
SongInfo::$manager = new SongInfoManager();
