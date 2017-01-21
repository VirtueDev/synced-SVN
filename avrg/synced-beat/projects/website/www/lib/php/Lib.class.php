<?php

class php_Lib {
	public function __construct(){}
	static function hprint($v) {
		$GLOBALS['%s']->push("php.Lib::print");
		$�spos = $GLOBALS['%s']->length;
		echo(Std::string($v));
		$GLOBALS['%s']->pop();
	}
	static function println($v) {
		$GLOBALS['%s']->push("php.Lib::println");
		$�spos = $GLOBALS['%s']->length;
		php_Lib::hprint($v);
		php_Lib::hprint("\x0A");
		$GLOBALS['%s']->pop();
	}
	static function dump($v) {
		$GLOBALS['%s']->push("php.Lib::dump");
		$�spos = $GLOBALS['%s']->length;
		var_dump($v);
		$GLOBALS['%s']->pop();
	}
	static function serialize($v) {
		$GLOBALS['%s']->push("php.Lib::serialize");
		$�spos = $GLOBALS['%s']->length;
		{
			$�tmp = serialize($v);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function unserialize($s) {
		$GLOBALS['%s']->push("php.Lib::unserialize");
		$�spos = $GLOBALS['%s']->length;
		{
			$�tmp = unserialize($s);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function extensionLoaded($name) {
		$GLOBALS['%s']->push("php.Lib::extensionLoaded");
		$�spos = $GLOBALS['%s']->length;
		{
			$�tmp = extension_loaded($name);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function isCli() {
		$GLOBALS['%s']->push("php.Lib::isCli");
		$�spos = $GLOBALS['%s']->length;
		{
			$�tmp = 0 == strncasecmp(PHP_SAPI, 'cli', 3);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function printFile($file) {
		$GLOBALS['%s']->push("php.Lib::printFile");
		$�spos = $GLOBALS['%s']->length;
		$h = fopen($file, "r");
		{
			$�tmp = fpassthru($h);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function toPhpArray($a) {
		$GLOBALS['%s']->push("php.Lib::toPhpArray");
		$�spos = $GLOBALS['%s']->length;
		{
			$�tmp = $a->�a;
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function toHaxeArray($a) {
		$GLOBALS['%s']->push("php.Lib::toHaxeArray");
		$�spos = $GLOBALS['%s']->length;
		{
			$�tmp = new _hx_array($a);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function hashOfAssociativeArray($arr) {
		$GLOBALS['%s']->push("php.Lib::hashOfAssociativeArray");
		$�spos = $GLOBALS['%s']->length;
		$h = new Hash();
		reset($arr); while(list($k, $v) = each($arr)) $h->set($k, $v);
		{
			$GLOBALS['%s']->pop();
			return $h;
		}
		$GLOBALS['%s']->pop();
	}
	static function rethrow($e) {
		$GLOBALS['%s']->push("php.Lib::rethrow");
		$�spos = $GLOBALS['%s']->length;
		if(isset($__e__)) throw $__e__;
		if(Std::is($e, Exception)) {
			$__rtex__ = $e;
			throw $__rtex__;
		}
		else {
			throw new HException($e);
		}
		$GLOBALS['%s']->pop();
	}
	static function appendType($o, $path, $t) {
		$GLOBALS['%s']->push("php.Lib::appendType");
		$�spos = $GLOBALS['%s']->length;
		$name = $path->shift();
		if($path->length === 0) {
			$o->$name = $t;
		}
		else {
			$so = (isset($o->$name) ? $o->$name : _hx_anonymous(array()));
			php_Lib::appendType($so, $path, $t);
			$o->$name = $so;
		}
		$GLOBALS['%s']->pop();
	}
	static function getClasses() {
		$GLOBALS['%s']->push("php.Lib::getClasses");
		$�spos = $GLOBALS['%s']->length;
		$path = null;
		$o = _hx_anonymous(array());
		reset(php_Boot::$qtypes);
		while(($path = key(php_Boot::$qtypes)) !== null) {
			php_Lib::appendType($o, _hx_explode(".", $path), php_Boot::$qtypes[$path]);
			next(php_Boot::$qtypes);
			;
		}
		{
			$GLOBALS['%s']->pop();
			return $o;
		}
		$GLOBALS['%s']->pop();
	}
	function __toString() { return 'php.Lib'; }
}
