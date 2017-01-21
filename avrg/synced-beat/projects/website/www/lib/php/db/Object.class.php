<?php

class php_db_Object {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.db.Object::new");
		$»spos = $GLOBALS['%s']->length;
		$this->__init_object();
		$GLOBALS['%s']->pop();
	}}
	public $__cache__;
	public $__noupdate__;
	public $__manager__;
	public function __init_object() {
		$GLOBALS['%s']->push("php.db.Object::__init_object");
		$»spos = $GLOBALS['%s']->length;
		$this->__noupdate__ = false;
		$this->__manager__ = php_db_Manager::$managers->get(Type::getClassName(Type::getClass($this)));
		$rl = null;
		try {
			$rl = $this->__manager__->cls->RELATIONS();
		}catch(Exception $»e) {
		$_ex_ = ($»e instanceof HException) ? $»e->e : $»e;
		;
		{ $e = $_ex_;
		{
			$GLOBALS['%e'] = new _hx_array(array());
			while($GLOBALS['%s']->length >= $»spos) $GLOBALS['%e']->unshift($GLOBALS['%s']->pop());
			$GLOBALS['%s']->push($GLOBALS['%e'][0]);
			{
				$GLOBALS['%s']->pop();
				return;
			}
		}}}
		{
			$_g = 0;
			while($_g < $rl->length) {
				$r = $rl[$_g];
				++$_g;
				$this->__manager__->initRelation($this, $r);
				unset($r);
			}
		}
		$GLOBALS['%s']->pop();
	}
	public function insert() {
		$GLOBALS['%s']->push("php.db.Object::insert");
		$»spos = $GLOBALS['%s']->length;
		$this->__manager__->doInsert($this);
		$GLOBALS['%s']->pop();
	}
	public function update() {
		$GLOBALS['%s']->push("php.db.Object::update");
		$»spos = $GLOBALS['%s']->length;
		if($this->__noupdate__) {
			throw new HException("Cannot update not locked object");
		}
		$this->__manager__->doUpdate($this);
		$GLOBALS['%s']->pop();
	}
	public function sync() {
		$GLOBALS['%s']->push("php.db.Object::sync");
		$»spos = $GLOBALS['%s']->length;
		$this->__manager__->doSync($this);
		$GLOBALS['%s']->pop();
	}
	public function delete() {
		$GLOBALS['%s']->push("php.db.Object::delete");
		$»spos = $GLOBALS['%s']->length;
		$this->__manager__->doDelete($this);
		$GLOBALS['%s']->pop();
	}
	public function toString() {
		$GLOBALS['%s']->push("php.db.Object::toString");
		$»spos = $GLOBALS['%s']->length;
		{
			$»tmp = $this->__manager__->objectToString($this);
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
	function __toString() { return $this->toString(); }
}
