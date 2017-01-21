<?php

class php_io_Process {
	public function __construct($cmd, $args) {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.io.Process::new");
		$»spos = $GLOBALS['%s']->length;
		$pipes = array();
		$descriptorspec = array(
			array('pipe', 'r'),
			array('pipe', 'w'),
			array('pipe', 'w')
		);
		$this->p = proc_open($cmd . $this->sargs($args), $descriptorspec, $pipes);
		if($this->p === false) {
			throw new HException("Process creation failure : " . $cmd);
		}
		$this->stdin = new php_io__Process_Stdin($pipes[0]);
		$this->stdout = new php_io__Process_Stdout($pipes[1]);
		$this->stderr = new php_io__Process_Stdout($pipes[2]);
		$GLOBALS['%s']->pop();
	}}
	public $p;
	public $stdout;
	public $stderr;
	public $stdin;
	public function sargs($args) {
		$GLOBALS['%s']->push("php.io.Process::sargs");
		$»spos = $GLOBALS['%s']->length;
		$b = "";
		{
			$_g = 0;
			while($_g < $args->length) {
				$arg = $args[$_g];
				++$_g;
				$arg = _hx_explode("\"", $arg)->join("\"");
				if(_hx_index_of($arg, " ", null) >= 0) {
					$arg = "\"" . $arg . "\"";
				}
				$b .= " " . $arg;
				unset($arg);
			}
		}
		{
			$GLOBALS['%s']->pop();
			return $b;
		}
		$GLOBALS['%s']->pop();
	}
	public function getPid() {
		$GLOBALS['%s']->push("php.io.Process::getPid");
		$»spos = $GLOBALS['%s']->length;
		$r = proc_get_status($this->p);
		{
			$»tmp = $r["pid"];
			$GLOBALS['%s']->pop();
			return $»tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function replaceStream($input) {
		$GLOBALS['%s']->push("php.io.Process::replaceStream");
		$»spos = $GLOBALS['%s']->length;
		$fp = fopen("php://memory", "r+");
		while(true) {
			$s = fread($input->p, 8192);
			if($s === false || $s === null || $s == "") {
				break;
			}
			fwrite($fp, $s);
			unset($s);
		}
		rewind($fp);
		$input->p = $fp;
		$GLOBALS['%s']->pop();
	}
	public function exitCode() {
		$GLOBALS['%s']->push("php.io.Process::exitCode");
		$»spos = $GLOBALS['%s']->length;
		$status = proc_get_status($this->p);
		while($status["running"]) {
			php_Sys::sleep(0.01);
			$status = proc_get_status($this->p);
			;
		}
		$this->replaceStream($this->stderr);
		$this->replaceStream($this->stdout);
		$cl = proc_close($this->p);
		{
			$»tmp = (($status["exitcode"]) < 0 ? $cl : $status["exitcode"]);
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
	function __toString() { return 'php.io.Process'; }
}
