<?php

class php_io__Process_Stdout extends haxe_io_Input {
	public function __construct($p) {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.io._Process.Stdout::new");
		$»spos = $GLOBALS['%s']->length;
		$this->p = $p;
		$this->buf = haxe_io_Bytes::alloc(1);
		$GLOBALS['%s']->pop();
	}}
	public $p;
	public $buf;
	public function readByte() {
		$GLOBALS['%s']->push("php.io._Process.Stdout::readByte");
		$»spos = $GLOBALS['%s']->length;
		if($this->readBytes($this->buf, 0, 1) === 0) {
			throw new HException(haxe_io_Error::$Blocked);
		}
		{
			$»tmp = ord($this->buf->b[0]);
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function readBytes($str, $pos, $l) {
		$GLOBALS['%s']->push("php.io._Process.Stdout::readBytes");
		$»spos = $GLOBALS['%s']->length;
		if(feof($this->p)) {
			$»tmp = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r;
			");
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$r = fread($this->p, $l);
		if($r === "") {
			$»tmp2 = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r2;
			");
			$GLOBALS['%s']->pop();
			return $»tmp2;
		}
		if($r === false) {
			$»tmp3 = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(haxe_io_Error::Custom(\"An error occurred\"));
				return \$»r3;
			");
			$GLOBALS['%s']->pop();
			return $»tmp3;
		}
		$b = haxe_io_Bytes::ofString($r);
		$str->blit($pos, $b, 0, strlen($r));
		{
			$»tmp4 = strlen($r);
			$GLOBALS['%s']->pop();
			return $»tmp4;
		}
		$GLOBALS['%s']->pop();
	}
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'php.io._Process.Stdout'; }
}
