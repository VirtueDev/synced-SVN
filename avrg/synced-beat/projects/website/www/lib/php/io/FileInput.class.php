<?php

class php_io_FileInput extends haxe_io_Input {
	public function __construct($f) {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.io.FileInput::new");
		$»spos = $GLOBALS['%s']->length;
		$this->__f = $f;
		$GLOBALS['%s']->pop();
	}}
	public $__f;
	public function readByte() {
		$GLOBALS['%s']->push("php.io.FileInput::readByte");
		$»spos = $GLOBALS['%s']->length;
		if(feof($this->__f)) {
			$»tmp = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r;
			");
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$r = fread($this->__f, 1);
		if($r === false) {
			$»tmp2 = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(haxe_io_Error::Custom(\"An error occurred\"));
				return \$»r2;
			");
			$GLOBALS['%s']->pop();
			return $»tmp2;
		}
		{
			$»tmp3 = ord($r);
			$GLOBALS['%s']->pop();
			return $»tmp3;
		}
		$GLOBALS['%s']->pop();
	}
	public function readBytes($s, $p, $l) {
		$GLOBALS['%s']->push("php.io.FileInput::readBytes");
		$»spos = $GLOBALS['%s']->length;
		if(feof($this->__f)) {
			$»tmp = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(new haxe_io_Eof());
				return \$»r;
			");
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$r = fread($this->__f, $l);
		if($r === false) {
			$»tmp2 = eval("if(isset(\$this)) \$»this =& \$this;throw new HException(haxe_io_Error::Custom(\"An error occurred\"));
				return \$»r2;
			");
			$GLOBALS['%s']->pop();
			return $»tmp2;
		}
		$b = haxe_io_Bytes::ofString($r);
		$s->blit($p, $b, 0, strlen($r));
		{
			$»tmp3 = strlen($r);
			$GLOBALS['%s']->pop();
			return $»tmp3;
		}
		$GLOBALS['%s']->pop();
	}
	public function close() {
		$GLOBALS['%s']->push("php.io.FileInput::close");
		$»spos = $GLOBALS['%s']->length;
		parent::close();
		if($this->__f !== null) {
			fclose($this->__f);
		}
		$GLOBALS['%s']->pop();
	}
	public function seek($p, $pos) {
		$GLOBALS['%s']->push("php.io.FileInput::seek");
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
		$GLOBALS['%s']->push("php.io.FileInput::tell");
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
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->»dynamics[$m]) && is_callable($this->»dynamics[$m]))
			return call_user_func_array($this->»dynamics[$m], $a);
		else
			throw new HException('Unable to call «'.$m.'»');
	}
	function __toString() { return 'php.io.FileInput'; }
}
