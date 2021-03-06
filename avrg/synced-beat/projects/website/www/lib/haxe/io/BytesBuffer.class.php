<?php

class haxe_io_BytesBuffer {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("haxe.io.BytesBuffer::new");
		$製pos = $GLOBALS['%s']->length;
		$this->b = "";
		$GLOBALS['%s']->pop();
	}}
	public $b;
	public function addByte($byte) {
		$GLOBALS['%s']->push("haxe.io.BytesBuffer::addByte");
		$製pos = $GLOBALS['%s']->length;
		$this->b .= chr($byte);
		$GLOBALS['%s']->pop();
	}
	public function add($src) {
		$GLOBALS['%s']->push("haxe.io.BytesBuffer::add");
		$製pos = $GLOBALS['%s']->length;
		$this->b .= $src->b;
		$GLOBALS['%s']->pop();
	}
	public function addBytes($src, $pos, $len) {
		$GLOBALS['%s']->push("haxe.io.BytesBuffer::addBytes");
		$製pos = $GLOBALS['%s']->length;
		if($pos < 0 || $len < 0 || $pos + $len > $src->length) {
			throw new HException(haxe_io_Error::$OutsideBounds);
		}
		$this->b .= substr($src->b, $pos, $len);
		$GLOBALS['%s']->pop();
	}
	public function getBytes() {
		$GLOBALS['%s']->push("haxe.io.BytesBuffer::getBytes");
		$製pos = $GLOBALS['%s']->length;
		$bytes = new haxe_io_Bytes(strlen($this->b), $this->b);
		$this->b = null;
		{
			$GLOBALS['%s']->pop();
			return $bytes;
		}
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
	function __toString() { return 'haxe.io.BytesBuffer'; }
}
