<?php

class php_io_File {
	public function __construct(){}
	static function getContent($path) {
		$GLOBALS['%s']->push("php.io.File::getContent");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = file_get_contents($path);
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function getBytes($path) {
		$GLOBALS['%s']->push("php.io.File::getBytes");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = haxe_io_Bytes::ofString(php_io_File::getContent($path));
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function putContent($path, $content) {
		$GLOBALS['%s']->push("php.io.File::putContent");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = file_put_contents($path, $content);
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function read($path, $binary) {
		$GLOBALS['%s']->push("php.io.File::read");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = new php_io_FileInput(fopen($path, ($binary ? "rb" : "r")));
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function write($path, $binary) {
		$GLOBALS['%s']->push("php.io.File::write");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = new php_io_FileOutput(fopen($path, ($binary ? "wb" : "w")));
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function append($path, $binary) {
		$GLOBALS['%s']->push("php.io.File::append");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = new php_io_FileOutput(fopen($path, ($binary ? "ab" : "a")));
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function copy($src, $dst) {
		$GLOBALS['%s']->push("php.io.File::copy");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = copy($src, $dst);
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function stdin() {
		$GLOBALS['%s']->push("php.io.File::stdin");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = new php_io_FileInput(STDIN);
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function stdout() {
		$GLOBALS['%s']->push("php.io.File::stdout");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = new php_io_FileOutput(STDOUT());
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	static function stderr() {
		$GLOBALS['%s']->push("php.io.File::stderr");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = new php_io_FileOutput(STDERR());
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	function __toString() { return 'php.io.File'; }
}
