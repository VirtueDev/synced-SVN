<?php

class php_io_FileOutput extends haxe_io_Output {
	public function __construct($f) {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.io.FileOutput::new");
		$»spos = $GLOBALS['%s']->length;
		$this->__f = $f;
		$GLOBALS['%s']->pop();
	}}
	public $__f;
	public function writeByte($c) {
		$GLOBALS['%s']->push("php.io.FileOutput::writeByte");
		$»spos = $GLOBALS['%s']->length;
		$r = fwrite($this->__f, chr($c));
		if($r === false) {
			$»tmp = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(haxe_io_Error::Custom(\"An error occurred\"));
				return \$»r;
			");
			$GLOBALS['%s']->pop();
			$»tmp;
			return;
		}
		{
			$GLOBALS['%s']->pop();
			$r;
			return;
		}
		$GLOBALS['%s']->pop();
	}
	public function writeBytes($b, $p, $l) {
		$GLOBALS['%s']->push("php.io.FileOutput::writeBytes");
		$»spos = $GLOBALS['%s']->length;
		$s = $b->readString($p, $l);
		if(feof($this->__f)) {
			$»tmp = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r;
			");
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$r = fwrite($this->__f, $s, $l);
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
	public function flush() {
		$GLOBALS['%s']->push("php.io.FileOutput::flush");
		$»spos = $GLOBALS['%s']->length;
		$r = fflush($this->__f);
		if($r === false) {
			throw new HException(haxe_io_Error::Custom("An error occurred"));
		}
		$GLOBALS['%s']->pop();
	}
	public function close() {
		$GLOBALS['%s']->push("php.io.FileOutput::close");
		$»spos = $GLOBALS['%s']->length;
		parent::close();
		if($this->__f !== null) {
			fclose($this->__f);
		}
		$GLOBALS['%s']->pop();
	}
	public function seek($p, $pos) {
		$GLOBALS['%s']->push("php.io.FileOutput::seek");
		$»spos = $GLOBALS['%s']->length;
		$w = null;
		$»t = ($pos);
		switch($»t->index) {
		case 0:
		{
			$w = SEEK_SET;
		}break;
		case 1:
		{
			$w = SEEK_CUR;
		}break;
		case 2:
		{
			$w = SEEK_END;
		}break;
		}
		$r = fseek($this->__f, $p, $w);
		if($r === false) {
			throw new HException(haxe_io_Error::Custom("An error occurred"));
		}
		$GLOBALS['%s']->pop();
	}
	public function tell() {
		$GLOBALS['%s']->push("php.io.FileOutput::tell");
		$»spos = $GLOBALS['%s']->length;
		$r = ftell($this->__f);
		if($r === false) {
			$»tmp = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(haxe_io_Error::Custom(\"An error occurred\"));
				return \$»r;
			");
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		{
			$GLOBALS['%s']->pop();
			return $r;
		}
		$GLOBALS['%s']->pop();
	}
	public function eof() {
		$GLOBALS['%s']->push("php.io.FileOutput::eof");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = feof($this->__f);
			$GLOBALS['%s']->pop();
			return $»tmp;
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
	function __toString() { return 'php.io.FileOutput'; }
}
