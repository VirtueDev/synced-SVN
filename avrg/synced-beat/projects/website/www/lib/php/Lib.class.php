<?php

class php_Lib {
	public function __construct(){}
	static function hprint($v) {
		$GLOBALS['%s']->push("php.Lib::print");
		$製pos = $GLOBALS['%s']->length;
		echo(Std::string($v));
		$GLOBALS['%s']->pop();
	}
	static function println($v) {
		$GLOBALS['%s']->push("php.Lib::println");
		$製pos = $GLOBALS['%s']->length;
		php_Lib::hprint($v);
		php_Lib::hprint("\x0A");
		$GLOBALS['%s']->pop();
	}
	static function dump($v) {
		$GLOBALS['%s']->push("php.Lib::dump");
		$製pos = $GLOBALS['%s']->length;
		var_dump($v);
		$GLOBALS['%s']->pop();
	}
	static function serialize($v) {
		$GLOBALS['%s']->push("php.Lib::serialize");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = serialize($v);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function unserialize($s) {
		$GLOBALS['%s']->push("php.Lib::unserialize");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = unserialize($s);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function extensionLoaded($name) {
		$GLOBALS['%s']->push("php.Lib::extensionLoaded");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = extension_loaded($name);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function isCli() {
		$GLOBALS['%s']->push("php.Lib::isCli");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = 0 == strncasecmp(PHP_SAPI, 'cli', 3);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function printFile($file) {
		$GLOBALS['%s']->push("php.Lib::printFile");
		$製pos = $GLOBALS['%s']->length;
		$h = fopen($file, "r");
		{
			$裨mp = fpassthru($h);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function toPhpArray($a) {
		$GLOBALS['%s']->push("php.Lib::toPhpArray");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = $a->蒼;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function toHaxeArray($a) {
		$GLOBALS['%s']->push("php.Lib::toHaxeArray");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = new _hx_array($a);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function hashOfAssociativeArray($arr) {
		$GLOBALS['%s']->push("php.Lib::hashOfAssociativeArray");
		$製pos = $GLOBALS['%s']->length;
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
		$製pos = $GLOBALS['%s']->length;
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
		$製pos = $GLOBALS['%s']->length;
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
		$製pos = $GLOBALS['%s']->length;
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
