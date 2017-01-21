<?php

class php_io__Process_Stdin extends haxe_io_Output {
	public function __construct($p) {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.io._Process.Stdin::new");
		$»spos = $GLOBALS['%s']->length;
		$this->p = $p;
		$this->buf = haxe_io_Bytes::alloc(1);
		$GLOBALS['%s']->pop();
	}}
	public $p;
	public $buf;
	public function close() {
		$GLOBALS['%s']->push("php.io._Process.Stdin::close");
		$»spos = $GLOBALS['%s']->length;
		parent::close();
		fclose($this->p);
		$GLOBALS['%s']->pop();
	}
	public function writeByte($c) {
		$GLOBALS['%s']->push("php.io._Process.Stdin::writeByte");
		$»spos = $GLOBALS['%s']->length;
		$this->buf->b[0] = chr($c);
		$this->writeBytes($this->buf, 0, 1);
		$GLOBALS['%s']->pop();
	}
	public function writeBytes($b, $pos, $l) {
		$GLOBALS['%s']->push("php.io._Process.Stdin::writeBytes");
		$»spos = $GLOBALS['%s']->length;
		$s = $b->readString($pos, $l);
		if(feof($this->p)) {
			$»tmp = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r;
			");
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$r = fwrite($this->p, $s, $l);
		if($r === false) {
			$»tmp2 = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(haxe_io_Error::Custom(\"An error occurred\"));
				return \$»r2;
			");
			$GLOBALS['%s']->pop();
			return $»tmp2;
		}
		{
			$GLOBALS['%s']->pop();
			return $r;
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
	function __toString() { return 'php.io._Process.Stdin'; }
}
