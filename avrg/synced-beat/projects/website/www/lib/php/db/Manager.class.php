<?php

class php_db_Manager {
	public function __construct($classval) {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("php.db.Manager::new");
		$製pos = $GLOBALS['%s']->length;
		$this->cls = $classval;
		$clname = Type::getClassName($this->cls);
		$this->table_name = $this->quoteField(((_hx_field($this->cls, "TABLE_NAME") !== null) ? $this->cls->TABLE_NAME : _hx_explode(".", $clname)->pop()));
		$this->table_keys = (_hx_field($this->cls, "TABLE_IDS") !== null ? $this->cls->TABLE_IDS : new _hx_array(array("id")));
		$apriv = $this->cls->PRIVATE_FIELDS;
		$apriv = ($apriv === null ? new _hx_array(array()) : $apriv->copy());
		$apriv->push("__cache__");
		$apriv->push("__noupdate__");
		$apriv->push("__manager__");
		$apriv->push("update");
		$this->table_fields = new HList();
		$stub = Type::createEmptyInstance($this->cls);
		$instance_fields = Type::getInstanceFields($this->cls);
		$scls = Type::getSuperClass($this->cls);
		while($scls !== null) {
			{
				$_g = 0; $_g1 = Type::getInstanceFields($scls);
				while($_g < $_g1->length) {
					$remove = $_g1[$_g];
					++$_g;
					$instance_fields->remove($remove);
					unset($remove);
				}
			}
			$scls = Type::getSuperClass($scls);
			unset($remove,$_g1,$_g);
		}
		{
			$_g2 = 0;
			while($_g2 < $instance_fields->length) {
				$f = $instance_fields[$_g2];
				++$_g2;
				$isfield = !Reflect::isFunction(Reflect::field($stub, $f));
				if($isfield) {
					$_g12 = 0;
					while($_g12 < $apriv->length) {
						$f2 = $apriv[$_g12];
						++$_g12;
						if($f == $f2) {
							$isfield = false;
							break;
						}
						unset($f2);
					}
				}
				if($isfield) {
					$this->table_fields->add($f);
				}
				unset($isfield,$f2,$f,$_g12);
			}
		}
		php_db_Manager::$managers->set($clname, $this);
		$rl = null;
		try {
			$rl = $this->cls->RELATIONS();
		}catch(Exception $蜜) {
		$_ex_ = ($蜜 instanceof HException) ? $蜜->e : $蜜;
		;
		{ $e = $_ex_;
		{
			$GLOBALS['%e'] = new _hx_array(array());
			while($GLOBALS['%s']->length >= $製pos) $GLOBALS['%e']->unshift($GLOBALS['%s']->pop());
			$GLOBALS['%s']->push($GLOBALS['%e'][0]);
			{
				$GLOBALS['%s']->pop();
				return;
			}
		}}}
		{
			$_g3 = 0;
			while($_g3 < $rl->length) {
				$r = $rl[$_g3];
				++$_g3;
				$this->table_fields->remove($r->prop);
				$this->table_fields->remove("get_" . $r->prop);
				$this->table_fields->remove("set_" . $r->prop);
				$this->table_fields->remove($r->key);
				$this->table_fields->add($r->key);
				unset($r);
			}
		}
		$GLOBALS['%s']->pop();
	}}
	public $table_name;
	public $table_fields;
	public $table_keys;
	public $cls;
	public function get($id, $lock) {
		$GLOBALS['%s']->push("php.db.Manager::get");
		$製pos = $GLOBALS['%s']->length;
		if($lock === null) {
			$lock = true;
		}
		if($this->table_keys->length !== 1) {
			throw new HException("Invalid number of keys");
		}
		if($id === null) {
			$GLOBALS['%s']->pop();
			return null;
		}
		$x = php_db_Manager::$object_cache->get($id . $this->table_name);
		if($x !== null && (!$lock || !$x->__noupdate__)) {
			$GLOBALS['%s']->pop();
			return $x;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $this->quoteField($this->table_keys[0]);
		$s->b .= " = ";
		$this->addQuote($s, $id);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
		}
		{
			$裨mp = $this->object($s->b, $lock);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function getWithKeys($keys, $lock) {
		$GLOBALS['%s']->push("php.db.Manager::getWithKeys");
		$製pos = $GLOBALS['%s']->length;
		if($lock === null) {
			$lock = true;
		}
		$x = $this->getFromCache($keys, false);
		if($x !== null && (!$lock || !$x->__noupdate__)) {
			$GLOBALS['%s']->pop();
			return $x;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addKeys($s, $keys);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
		}
		{
			$裨mp = $this->object($s->b, $lock);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function delete($x) {
		$GLOBALS['%s']->push("php.db.Manager::delete");
		$製pos = $GLOBALS['%s']->length;
		$s = new StringBuf();
		$s->b .= "DELETE FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		$this->execute($s->b);
		$GLOBALS['%s']->pop();
	}
	public function search($x, $lock) {
		$GLOBALS['%s']->push("php.db.Manager::search");
		$製pos = $GLOBALS['%s']->length;
		if($lock === null) {
			$lock = true;
		}
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		if($lock) {
			$s->b .= php_db_Manager::$FOR_UPDATE;
		}
		{
			$裨mp = $this->objects($s->b, $lock);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function addCondition($s, $x) {
		$GLOBALS['%s']->push("php.db.Manager::addCondition");
		$製pos = $GLOBALS['%s']->length;
		$first = true;
		if($x !== null) {
			$_g = 0; $_g1 = Reflect::fields($x);
			while($_g < $_g1->length) {
				$f = $_g1[$_g];
				++$_g;
				if($first) {
					$first = false;
				}
				else {
					$s->b .= " AND ";
				}
				$s->b .= $this->quoteField($f);
				$d = Reflect::field($x, $f);
				if($d === null) {
					$s->b .= " IS NULL";
				}
				else {
					$s->b .= " = ";
					$this->addQuote($s, $d);
				}
				unset($f,$d);
			}
		}
		if($first) {
			$s->b .= "1";
		}
		$GLOBALS['%s']->pop();
	}
	public function all($lock) {
		$GLOBALS['%s']->push("php.db.Manager::all");
		$製pos = $GLOBALS['%s']->length;
		if($lock === null) {
			$lock = true;
		}
		{
			$裨mp = $this->objects("SELECT * FROM " . $this->table_name . ($lock ? php_db_Manager::$FOR_UPDATE : ""), $lock);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function count($x) {
		$GLOBALS['%s']->push("php.db.Manager::count");
		$製pos = $GLOBALS['%s']->length;
		$s = new StringBuf();
		$s->b .= "SELECT COUNT(*) FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addCondition($s, $x);
		{
			$裨mp = $this->execute($s->b)->getIntResult(0);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function quote($s) {
		$GLOBALS['%s']->push("php.db.Manager::quote");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = php_db_Manager::$cnx->quote($s);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function result($sql) {
		$GLOBALS['%s']->push("php.db.Manager::result");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = php_db_Manager::$cnx->request($sql)->next();
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function results($sql) {
		$GLOBALS['%s']->push("php.db.Manager::results");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = php_db_Manager::$cnx->request($sql)->results();
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function doInsert($x) {
		$GLOBALS['%s']->push("php.db.Manager::doInsert");
		$製pos = $GLOBALS['%s']->length;
		$this->unmake($x);
		$s = new StringBuf();
		$fields = new HList();
		$values = new HList();
		$蜴t = $this->table_fields->iterator();
		while($蜴t->hasNext()) {
		$f = $蜴t->next();
		{
			$v = Reflect::field($x, $f);
			if($v !== null) {
				$fields->add($this->quoteField($f));
				$values->add($v);
			}
			unset($v);
		}
		}
		$s->b .= "INSERT INTO ";
		$s->b .= $this->table_name;
		$s->b .= " (";
		$s->b .= $fields->join(",");
		$s->b .= ") VALUES (";
		$first = true;
		$蜴t2 = $values->iterator();
		while($蜴t2->hasNext()) {
		$v2 = $蜴t2->next();
		{
			if($first) {
				$first = false;
			}
			else {
				$s->b .= ", ";
			}
			$this->addQuote($s, $v2);
			;
		}
		}
		$s->b .= ")";
		$this->execute($s->b);
		if($this->table_keys->length === 1 && Reflect::field($x, $this->table_keys[0]) === null) {
			$x->{$this->table_keys[0]} = php_db_Manager::$cnx->lastInsertId();
		}
		$this->addToCache($x);
		$GLOBALS['%s']->pop();
	}
	public function doUpdate($x) {
		$GLOBALS['%s']->push("php.db.Manager::doUpdate");
		$製pos = $GLOBALS['%s']->length;
		$this->unmake($x);
		$s = new StringBuf();
		$s->b .= "UPDATE ";
		$s->b .= $this->table_name;
		$s->b .= " SET ";
		$cache = Reflect::field($x, php_db_Manager::$cache_field);
		$mod = false;
		$蜴t = $this->table_fields->iterator();
		while($蜴t->hasNext()) {
		$f = $蜴t->next();
		{
			$v = Reflect::field($x, $f);
			$vc = Reflect::field($cache, $f);
			if(!_hx_equal($v, $vc)) {
				if($mod) {
					$s->b .= ", ";
				}
				else {
					$mod = true;
				}
				$s->b .= $this->quoteField($f);
				$s->b .= " = ";
				$this->addQuote($s, $v);
				$cache->{$f} = $v;
			}
			unset($vc,$v);
		}
		}
		if(!$mod) {
			$GLOBALS['%s']->pop();
			return;
		}
		$s->b .= " WHERE ";
		$this->addKeys($s, $x);
		$this->execute($s->b);
		$GLOBALS['%s']->pop();
	}
	public function doDelete($x) {
		$GLOBALS['%s']->push("php.db.Manager::doDelete");
		$製pos = $GLOBALS['%s']->length;
		$s = new StringBuf();
		$s->b .= "DELETE FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$this->addKeys($s, $x);
		$this->execute($s->b);
		$GLOBALS['%s']->pop();
	}
	public function doSync($i) {
		$GLOBALS['%s']->push("php.db.Manager::doSync");
		$製pos = $GLOBALS['%s']->length;
		php_db_Manager::$object_cache->remove($this->makeCacheKey($i));
		$i2 = $this->getWithKeys($i, !$i->__noupdate__);
		{
			$_g = 0; $_g1 = Reflect::fields($i);
			while($_g < $_g1->length) {
				$f = $_g1[$_g];
				++$_g;
				Reflect::deleteField($i, $f);
				unset($f);
			}
		}
		{
			$_g2 = 0; $_g12 = Reflect::fields($i2);
			while($_g2 < $_g12->length) {
				$f2 = $_g12[$_g2];
				++$_g2;
				$i->{$f2} = Reflect::field($i2, $f2);
				unset($f2);
			}
		}
		$i->{php_db_Manager::$cache_field} = Reflect::field($i2, php_db_Manager::$cache_field);
		$this->addToCache($i);
		$GLOBALS['%s']->pop();
	}
	public function objectToString($it) {
		$GLOBALS['%s']->push("php.db.Manager::objectToString");
		$製pos = $GLOBALS['%s']->length;
		$s = new StringBuf();
		$s->b .= $this->table_name;
		if($this->table_keys->length === 1) {
			$s->b .= "#";
			$s->b .= Reflect::field($it, $this->table_keys[0]);
		}
		else {
			$s->b .= "(";
			$first = true;
			{
				$_g = 0; $_g1 = $this->table_keys;
				while($_g < $_g1->length) {
					$f = $_g1[$_g];
					++$_g;
					if($first) {
						$first = false;
					}
					else {
						$s->b .= ",";
					}
					$s->b .= $this->quoteField($f);
					$s->b .= ":";
					$s->b .= Reflect::field($it, $f);
					unset($f);
				}
			}
			$s->b .= ")";
		}
		{
			$裨mp = $s->b;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function cacheObject($x, $lock) {
		$GLOBALS['%s']->push("php.db.Manager::cacheObject");
		$製pos = $GLOBALS['%s']->length;
		$o = Type::createEmptyInstance($this->cls);
		{
			$_g = 0; $_g1 = Reflect::fields($x);
			while($_g < $_g1->length) {
				$field = $_g1[$_g];
				++$_g;
				$o->{$field} = Reflect::field($x, $field);
				unset($field);
			}
		}
		$o->__init_object();
		$this->addToCache($o);
		$o->{php_db_Manager::$cache_field} = Type::createEmptyInstance($this->cls);
		if(!$lock) {
			$o->__noupdate__ = true;
		}
		{
			$GLOBALS['%s']->pop();
			return $o;
		}
		$GLOBALS['%s']->pop();
	}
	public function make($x) {
		$GLOBALS['%s']->push("php.db.Manager::make");
		$製pos = $GLOBALS['%s']->length;
		;
		$GLOBALS['%s']->pop();
	}
	public function unmake($x) {
		$GLOBALS['%s']->push("php.db.Manager::unmake");
		$製pos = $GLOBALS['%s']->length;
		;
		$GLOBALS['%s']->pop();
	}
	public function quoteField($f) {
		$GLOBALS['%s']->push("php.db.Manager::quoteField");
		$製pos = $GLOBALS['%s']->length;
		$fsmall = strtolower($f);
		if($fsmall == "read" || $fsmall == "desc" || $fsmall == "out" || $fsmall == "group" || $fsmall == "version" || $fsmall == "option") {
			$裨mp = "`" . $f . "`";
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		{
			$GLOBALS['%s']->pop();
			return $f;
		}
		$GLOBALS['%s']->pop();
	}
	public function addQuote($s, $v) {
		$GLOBALS['%s']->push("php.db.Manager::addQuote");
		$製pos = $GLOBALS['%s']->length;
		if(is_int($v) || is_null($v)) {
			$s->b .= $v;
		}
		else {
			if(is_bool($v)) {
				$s->b .= ($v ? 1 : 0);
			}
			else {
				$s->b .= php_db_Manager::$cnx->quote(Std::string($v));
			}
		}
		$GLOBALS['%s']->pop();
	}
	public function addKeys($s, $x) {
		$GLOBALS['%s']->push("php.db.Manager::addKeys");
		$製pos = $GLOBALS['%s']->length;
		$first = true;
		{
			$_g = 0; $_g1 = $this->table_keys;
			while($_g < $_g1->length) {
				$k = $_g1[$_g];
				++$_g;
				if($first) {
					$first = false;
				}
				else {
					$s->b .= " AND ";
				}
				$s->b .= $this->quoteField($k);
				$s->b .= " = ";
				$f = Reflect::field($x, $k);
				if($f === null) {
					throw new HException(("Missing key " . $k));
				}
				$this->addQuote($s, $f);
				unset($k,$f);
			}
		}
		$GLOBALS['%s']->pop();
	}
	public function execute($sql) {
		$GLOBALS['%s']->push("php.db.Manager::execute");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = php_db_Manager::$cnx->request($sql);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function select($cond) {
		$GLOBALS['%s']->push("php.db.Manager::select");
		$製pos = $GLOBALS['%s']->length;
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $cond;
		$s->b .= php_db_Manager::$FOR_UPDATE;
		{
			$裨mp = $s->b;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function selectReadOnly($cond) {
		$GLOBALS['%s']->push("php.db.Manager::selectReadOnly");
		$製pos = $GLOBALS['%s']->length;
		$s = new StringBuf();
		$s->b .= "SELECT * FROM ";
		$s->b .= $this->table_name;
		$s->b .= " WHERE ";
		$s->b .= $cond;
		{
			$裨mp = $s->b;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function object($sql, $lock) {
		$GLOBALS['%s']->push("php.db.Manager::object");
		$製pos = $GLOBALS['%s']->length;
		$r = php_db_Manager::$cnx->request($sql)->next();
		if($r === null) {
			$GLOBALS['%s']->pop();
			return null;
		}
		$c = $this->getFromCache($r, $lock);
		if($c !== null) {
			$GLOBALS['%s']->pop();
			return $c;
		}
		$o = $this->cacheObject($r, $lock);
		$this->make($o);
		{
			$GLOBALS['%s']->pop();
			return $o;
		}
		$GLOBALS['%s']->pop();
	}
	public function objects($sql, $lock) {
		$GLOBALS['%s']->push("php.db.Manager::objects");
		$製pos = $GLOBALS['%s']->length;
		$me = $this;
		$l = php_db_Manager::$cnx->request($sql)->results();
		$l2 = new HList();
		$蜴t = $l->iterator();
		while($蜴t->hasNext()) {
		$x = $蜴t->next();
		{
			$c = $this->getFromCache($x, $lock);
			if($c !== null) {
				$l2->add($c);
			}
			else {
				$o = $this->cacheObject($x, $lock);
				$this->make($o);
				$l2->add($o);
			}
			unset($o,$c);
		}
		}
		{
			$GLOBALS['%s']->pop();
			return $l2;
		}
		$GLOBALS['%s']->pop();
	}
	public function dbClass() {
		$GLOBALS['%s']->push("php.db.Manager::dbClass");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = $this->cls;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function initRelation($o, $r) {
		$GLOBALS['%s']->push("php.db.Manager::initRelation");
		$製pos = $GLOBALS['%s']->length;
		$manager = $r->manager;
		$hkey = $r->key;
		$lock = $r->lock;
		if($lock === null) {
			$lock = true;
		}
		if($manager === null || $manager->table_keys === null) {
			throw new HException(("Invalid manager for relation " . $this->table_name . ":" . $r->prop));
		}
		if($manager->table_keys->length !== 1) {
			throw new HException(("Relation " . $r->prop . "(" . $r->key . ") on a multiple key table"));
		}
		$o->{"get_" . $r->prop} = array(new _hx_lambda(array("hkey" => &$hkey, "lock" => &$lock, "manager" => &$manager, "o" => &$o, "r" => &$r, "製pos" => &$製pos), null, array(), "{
			\$GLOBALS['%s']->push(\"php.db.Manager::initRelation@465\");
			\$製pos2 = \$GLOBALS['%s']->length;
			{
				\$裨mp = \$manager->get(Reflect::field(\$o, \$hkey), \$lock);
				\$GLOBALS['%s']->pop();
				return \$裨mp;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0');
		$o->{"set_" . $r->prop} = array(new _hx_lambda(array("hkey" => &$hkey, "lock" => &$lock, "manager" => &$manager, "o" => &$o, "r" => &$r, "製pos" => &$製pos), null, array('f'), "{
			\$GLOBALS['%s']->push(\"php.db.Manager::initRelation@468\");
			\$製pos2 = \$GLOBALS['%s']->length;
			\$o->{\$hkey} = Reflect::field(\$f, \$manager->table_keys[0]);
			{
				\$GLOBALS['%s']->pop();
				return \$f;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute1');
		$GLOBALS['%s']->pop();
	}
	public function makeCacheKey($x) {
		$GLOBALS['%s']->push("php.db.Manager::makeCacheKey");
		$製pos = $GLOBALS['%s']->length;
		if($this->table_keys->length === 1) {
			$k = Reflect::field($x, $this->table_keys[0]);
			if($k === null) {
				throw new HException(("Missing key " . $this->table_keys[0]));
			}
			{
				$裨mp = Std::string($k) . $this->table_name;
				$GLOBALS['%s']->pop();
				return $裨mp;
			}
		}
		$s = new StringBuf();
		{
			$_g = 0; $_g1 = $this->table_keys;
			while($_g < $_g1->length) {
				$k2 = $_g1[$_g];
				++$_g;
				$v = Reflect::field($x, $k2);
				if($k2 === null) {
					throw new HException(("Missing key " . $k2));
				}
				$s->b .= $v;
				$s->b .= "#";
				unset($v,$k2);
			}
		}
		$s->b .= $this->table_name;
		{
			$裨mp2 = $s->b;
			$GLOBALS['%s']->pop();
			return $裨mp2;
		}
		$GLOBALS['%s']->pop();
	}
	public function addToCache($x) {
		$GLOBALS['%s']->push("php.db.Manager::addToCache");
		$製pos = $GLOBALS['%s']->length;
		php_db_Manager::$object_cache->set($this->makeCacheKey($x), $x);
		$GLOBALS['%s']->pop();
	}
	public function getFromCache($x, $lock) {
		$GLOBALS['%s']->push("php.db.Manager::getFromCache");
		$製pos = $GLOBALS['%s']->length;
		$c = php_db_Manager::$object_cache->get($this->makeCacheKey($x));
		if($c !== null && $lock && $c->__noupdate__) {
			$c->__noupdate__ = false;
		}
		{
			$GLOBALS['%s']->pop();
			return $c;
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
	static $cnx;
	static $object_cache;
	static $cache_field = "__cache__";
	static $FOR_UPDATE = "";
	static $managers;
	static function setConnection($c) { return call_user_func_array(self::$setConnection, array($c)); }
	public static $setConnection = null;
	static function initialize() {
		$GLOBALS['%s']->push("php.db.Manager::initialize");
		$製pos = $GLOBALS['%s']->length;
		;
		$GLOBALS['%s']->pop();
	}
	static function cleanup() {
		$GLOBALS['%s']->push("php.db.Manager::cleanup");
		$製pos = $GLOBALS['%s']->length;
		php_db_Manager::$object_cache = new Hash();
		$GLOBALS['%s']->pop();
	}
	function __toString() { return 'php.db.Manager'; }
}
php_db_Manager::$object_cache = new Hash();
php_db_Manager::$managers = new Hash();
php_db_Manager::$setConnection = array(new _hx_lambda(array(), null, array('c'), "{
	\$GLOBALS['%s']->push(\"php.db.Manager::cleanup@44\");
	\$製pos = \$GLOBALS['%s']->length;
	_hx_qtype(\"php.db.Manager\")->{\"cnx\"} = \$c;
	if(\$c !== null) {
		php_db_Manager::\$FOR_UPDATE = (\$c->dbName() == \"MySQL\" ? \" FOR UPDATE\" : \"\");
	}
	{
		\$GLOBALS['%s']->pop();
		return \$c;
	}
	\$GLOBALS['%s']->pop();
}"), 'execute1');
