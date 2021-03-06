<?php

class php_net_Socket {
	public function __construct($s) {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.net.Socket::new");
		$製pos = $GLOBALS['%s']->length;
		$this->__s = $s;
		$this->input = new php_io_FileInput($this->__s);
		$this->output = new php_io_FileOutput($this->__s);
		$GLOBALS['%s']->pop();
	}}
	public $__s;
	public $input;
	public $output;
	public $custom;
	public $isUdp;
	public function assignHandler() {
		$GLOBALS['%s']->push("php.net.Socket::assignHandler");
		$製pos = $GLOBALS['%s']->length;
		$this->input->__f = $this->__s;
		$this->output->__f = $this->__s;
		$GLOBALS['%s']->pop();
	}
	public function close() {
		$GLOBALS['%s']->push("php.net.Socket::close");
		$製pos = $GLOBALS['%s']->length;
		fclose($this->__s);
		{
			$this->input->__f = null;
			$this->output->__f = null;
		}
		$this->input->close();
		$this->output->close();
		$GLOBALS['%s']->pop();
	}
	public function read() {
		$GLOBALS['%s']->push("php.net.Socket::read");
		$製pos = $GLOBALS['%s']->length;
		$b = "";
		while (!feof($this->__s)) $b .= fgets($this->__s);
		{
			$GLOBALS['%s']->pop();
			return $b;
		}
		$GLOBALS['%s']->pop();
	}
	public function write($content) {
		$GLOBALS['%s']->push("php.net.Socket::write");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = fwrite($this->__s, $content);
			$GLOBALS['%s']->pop();
			$裨mp;
			return;
		}
		$GLOBALS['%s']->pop();
	}
	public function connect($host, $port) {
		$GLOBALS['%s']->push("php.net.Socket::connect");
		$製pos = $GLOBALS['%s']->length;
		$errs = null;
		$errn = null;
		$r = stream_socket_client((($this->isUdp ? "udp" : "tcp")) . "://" . $host->_ip . ":" . $port, $errn, $errs);
		php_net_Socket::checkError($r, $errn, $errs);
		$this->__s = $r;
		$this->assignHandler();
		$GLOBALS['%s']->pop();
	}
	public function listen($connections) {
		$GLOBALS['%s']->push("php.net.Socket::listen");
		$製pos = $GLOBALS['%s']->length;
		;
		$GLOBALS['%s']->pop();
	}
	public function shutdown($read, $write) {
		$GLOBALS['%s']->push("php.net.Socket::shutdown");
		$製pos = $GLOBALS['%s']->length;
		$r = null;
		if(function_exists("stream_socket_shutdown")) {
			$rw = ($read && $write ? 2 : (($write ? 1 : (($read ? 0 : 2)))));
			$r = stream_socket_shutdown($this->__s, $rw);
		}
		else {
			$r = fclose($this->__s);
		}
		php_net_Socket::checkError($r, 0, "Unable to Shutdown");
		$GLOBALS['%s']->pop();
	}
	public function bind($host, $port) {
		$GLOBALS['%s']->push("php.net.Socket::bind");
		$製pos = $GLOBALS['%s']->length;
		$errs = null;
		$errn = null;
		$r = stream_socket_server((($this->isUdp ? "udp" : "tcp")) . "://" . $host->_ip . ":" . $port, $errn, $errs, ($this->isUdp ? STREAM_SERVER_BIND : STREAM_SERVER_BIND | STREAM_SERVER_LISTEN));
		php_net_Socket::checkError($r, $errn, $errs);
		$this->__s = $r;
		$this->assignHandler();
		$GLOBALS['%s']->pop();
	}
	public function accept() {
		$GLOBALS['%s']->push("php.net.Socket::accept");
		$製pos = $GLOBALS['%s']->length;
		$r = stream_socket_accept($this->__s);
		php_net_Socket::checkError($r, 0, "Unable to accept connections on socket");
		{
			$裨mp = new php_net_Socket($r);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function hpOfString($s) {
		$GLOBALS['%s']->push("php.net.Socket::hpOfString");
		$製pos = $GLOBALS['%s']->length;
		$parts = _hx_explode(":", $s);
		if($parts->length === 2) {
			{
				$裨mp = _hx_anonymous(array("host" => new php_net_Host($parts[0]), "port" => Std::parseInt($parts[1])));
				$GLOBALS['%s']->pop();
				return $裨mp;
			}
		}
		else {
			{
				$裨mp2 = _hx_anonymous(array("host" => new php_net_Host(_hx_substr($parts[1], 2, null)), "port" => Std::parseInt($parts[2])));
				$GLOBALS['%s']->pop();
				return $裨mp2;
			}
		}
		$GLOBALS['%s']->pop();
	}
	public function peer() {
		$GLOBALS['%s']->push("php.net.Socket::peer");
		$製pos = $GLOBALS['%s']->length;
		$r = stream_socket_get_name($this->__s, true);
		php_net_Socket::checkError($r, 0, "Unable to retrieve the peer name");
		{
			$裨mp = $this->hpOfString($r);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function host() {
		$GLOBALS['%s']->push("php.net.Socket::host");
		$製pos = $GLOBALS['%s']->length;
		$r = stream_socket_get_name($this->__s, false);
		php_net_Socket::checkError($r, 0, "Unable to retrieve the host name");
		{
			$裨mp = $this->hpOfString($r);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function setTimeout($timeout) {
		$GLOBALS['%s']->push("php.net.Socket::setTimeout");
		$製pos = $GLOBALS['%s']->length;
		$s = intval($timeout);
		$ms = intval(($timeout - $s) * 1000000);
		$r = stream_set_timeout($this->__s, $s, $ms);
		php_net_Socket::checkError($r, 0, "Unable to set timeout");
		$GLOBALS['%s']->pop();
	}
	public function setBlocking($b) {
		$GLOBALS['%s']->push("php.net.Socket::setBlocking");
		$製pos = $GLOBALS['%s']->length;
		$r = stream_set_blocking($this->__s, $b);
		php_net_Socket::checkError($r, 0, "Unable to block");
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
	static function newUdpSocket() {
		$GLOBALS['%s']->push("php.net.Socket::newUdpSocket");
		$製pos = $GLOBALS['%s']->length;
		$s = new php_net_Socket(null);
		$s->isUdp = true;
		{
			$GLOBALS['%s']->pop();
			return $s;
		}
		$GLOBALS['%s']->pop();
	}
	static function checkError($r, $code, $msg) {
		$GLOBALS['%s']->push("php.net.Socket::checkError");
		$製pos = $GLOBALS['%s']->length;
		if(!$r === false) {
			$GLOBALS['%s']->pop();
			return;
		}
		throw new HException(haxe_io_Error::Custom("Error [" . $code . "]: " . $msg));
		$GLOBALS['%s']->pop();
	}
	static function getType($isUdp) {
		$GLOBALS['%s']->push("php.net.Socket::getType");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = ($isUdp ? SOCK_DGRAM : SOCK_STREAM);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function getProtocol($isUdp) {
		$GLOBALS['%s']->push("php.net.Socket::getProtocol");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = ($isUdp ? getprotobyname("udp") : getprotobyname("tcp"));
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	function __toString() { return 'php.net.Socket'; }
}
