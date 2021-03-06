<?php

class Xml {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("Xml::new");
		$製pos = $GLOBALS['%s']->length;
		;
		$GLOBALS['%s']->pop();
	}}
	public $nodeType;
	//;
	//;
	public $parent;
	public $_nodeName;
	public $_nodeValue;
	public $_attributes;
	public $_children;
	public $_parent;
	public function getNodeName() {
		$GLOBALS['%s']->push("Xml::getNodeName");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$裨mp = $this->_nodeName;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function setNodeName($n) {
		$GLOBALS['%s']->push("Xml::setNodeName");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$裨mp = $this->_nodeName = $n;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function getNodeValue() {
		$GLOBALS['%s']->push("Xml::getNodeValue");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType == Xml::$Element || $this->nodeType == Xml::$Document) {
			throw new HException("bad nodeType");
		}
		{
			$裨mp = $this->_nodeValue;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function setNodeValue($v) {
		$GLOBALS['%s']->push("Xml::setNodeValue");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType == Xml::$Element || $this->nodeType == Xml::$Document) {
			throw new HException("bad nodeType");
		}
		{
			$裨mp = $this->_nodeValue = $v;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function getParent() {
		$GLOBALS['%s']->push("Xml::getParent");
		$製pos = $GLOBALS['%s']->length;
		{
			$裨mp = $this->_parent;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function get($att) {
		$GLOBALS['%s']->push("Xml::get");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$裨mp = $this->_attributes->get($att);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function set($att, $value) {
		$GLOBALS['%s']->push("Xml::set");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		$this->_attributes->set($att, htmlspecialchars($value, ENT_COMPAT, "UTF-8"));
		$GLOBALS['%s']->pop();
	}
	public function remove($att) {
		$GLOBALS['%s']->push("Xml::remove");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		$this->_attributes->remove($att);
		$GLOBALS['%s']->pop();
	}
	public function exists($att) {
		$GLOBALS['%s']->push("Xml::exists");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$裨mp = $this->_attributes->exists($att);
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function attributes() {
		$GLOBALS['%s']->push("Xml::attributes");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$裨mp = $this->_attributes->keys();
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function iterator() {
		$GLOBALS['%s']->push("Xml::iterator");
		$製pos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		$me = $this;
		$it = null;
		$it = _hx_anonymous(array("cur" => 0, "x" => $me->_children, "hasNext" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "製pos" => &$製pos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::iterator@228\");
			\$製pos2 = \$GLOBALS['%s']->length;
			{
				\$裨mp = \$it->cur < _hx_len(\$it->x);
				\$GLOBALS['%s']->pop();
				return \$裨mp;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0'), "next" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "製pos" => &$製pos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::iterator@231\");
			\$製pos2 = \$GLOBALS['%s']->length;
			{
				\$裨mp = \$it->x[\$it->cur++];
				\$GLOBALS['%s']->pop();
				return \$裨mp;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0')));
		{
			$GLOBALS['%s']->pop();
			return $it;
		}
		$GLOBALS['%s']->pop();
	}
	public function elements() {
		$GLOBALS['%s']->push("Xml::elements");
		$製pos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		$me = $this;
		$it = null;
		$it = _hx_anonymous(array("cur" => 0, "x" => $me->_children, "hasNext" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "製pos" => &$製pos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::elements@245\");
			\$製pos2 = \$GLOBALS['%s']->length;
			\$k = \$it->cur;
			\$l = _hx_len(\$it->x);
			while(\$k < \$l) {
				if(_hx_array_get(\$it->x, \$k)->nodeType == Xml::\$Element) {
					break;
				}
				\$k += 1;
				;
			}
			\$it->cur = \$k;
			{
				\$裨mp = \$k < \$l;
				\$GLOBALS['%s']->pop();
				return \$裨mp;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0'), "next" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "製pos" => &$製pos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::elements@257\");
			\$製pos2 = \$GLOBALS['%s']->length;
			\$k = \$it->cur;
			\$l = _hx_len(\$it->x);
			while(\$k < \$l) {
				\$n = \$it->x[\$k];
				\$k += 1;
				if(\$n->nodeType == Xml::\$Element) {
					\$it->cur = \$k;
					{
						\$GLOBALS['%s']->pop();
						return \$n;
					}
				}
				unset(\$n);
			}
			{
				\$GLOBALS['%s']->pop();
				return null;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0')));
		{
			$GLOBALS['%s']->pop();
			return $it;
		}
		$GLOBALS['%s']->pop();
	}
	public function elementsNamed($name) {
		$GLOBALS['%s']->push("Xml::elementsNamed");
		$製pos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		$me = $this;
		$it = null;
		$it = _hx_anonymous(array("cur" => 0, "x" => $me->_children, "hasNext" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "name" => &$name, "製pos" => &$製pos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::elementsNamed@282\");
			\$製pos2 = \$GLOBALS['%s']->length;
			\$k = \$it->cur;
			\$l = _hx_len(\$it->x);
			while(\$k < \$l) {
				\$n = \$it->x[\$k];
				if(\$n->nodeType == Xml::\$Element && \$n->_nodeName == \$name) {
					break;
				}
				\$k++;
				unset(\$n);
			}
			\$it->cur = \$k;
			{
				\$裨mp = \$k < \$l;
				\$GLOBALS['%s']->pop();
				return \$裨mp;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0'), "next" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "name" => &$name, "製pos" => &$製pos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::elementsNamed@294\");
			\$製pos2 = \$GLOBALS['%s']->length;
			\$k = \$it->cur;
			\$l = _hx_len(\$it->x);
			while(\$k < \$l) {
				\$n = \$it->x[\$k];
				\$k++;
				if(\$n->nodeType == Xml::\$Element && \$n->_nodeName == \$name) {
					\$it->cur = \$k;
					{
						\$GLOBALS['%s']->pop();
						return \$n;
					}
				}
				unset(\$n);
			}
			{
				\$GLOBALS['%s']->pop();
				return null;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0')));
		{
			$GLOBALS['%s']->pop();
			return $it;
		}
		$GLOBALS['%s']->pop();
	}
	public function firstChild() {
		$GLOBALS['%s']->push("Xml::firstChild");
		$製pos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		if($this->_children->length === 0) {
			$GLOBALS['%s']->pop();
			return null;
		}
		{
			$裨mp = $this->_children[0];
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	public function firstElement() {
		$GLOBALS['%s']->push("Xml::firstElement");
		$製pos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		$cur = 0;
		$l = $this->_children->length;
		while($cur < $l) {
			$n = $this->_children[$cur];
			if($n->nodeType == Xml::$Element) {
				$GLOBALS['%s']->pop();
				return $n;
			}
			$cur++;
			unset($n);
		}
		{
			$GLOBALS['%s']->pop();
			return null;
		}
		$GLOBALS['%s']->pop();
	}
	public function addChild($x) {
		$GLOBALS['%s']->push("Xml::addChild");
		$製pos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		if($x->_parent !== null) {
			$x->_parent->_children->remove($x);
		}
		$x->_parent = $this;
		$this->_children->push($x);
		$GLOBALS['%s']->pop();
	}
	public function removeChild($x) {
		$GLOBALS['%s']->push("Xml::removeChild");
		$製pos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		$b = $this->_children->remove($x);
		if($b) {
			$x->_parent = null;
		}
		{
			$GLOBALS['%s']->pop();
			return $b;
		}
		$GLOBALS['%s']->pop();
	}
	public function insertChild($x, $pos) {
		$GLOBALS['%s']->push("Xml::insertChild");
		$製pos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		if($x->_parent !== null) {
			$x->_parent->_children->remove($x);
		}
		$x->_parent = $this;
		$this->_children->insert($pos, $x);
		$GLOBALS['%s']->pop();
	}
	public function toString() {
		$GLOBALS['%s']->push("Xml::toString");
		$製pos = $GLOBALS['%s']->length;
		if($this->nodeType == Xml::$PCData) {
			$裨mp = $this->_nodeValue;
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		if($this->nodeType == Xml::$CData) {
			$裨mp2 = "<![CDATA[" . $this->_nodeValue . "]]>";
			$GLOBALS['%s']->pop();
			return $裨mp2;
		}
		if($this->nodeType == Xml::$Comment || $this->nodeType == Xml::$DocType || $this->nodeType == Xml::$Prolog) {
			$裨mp3 = $this->_nodeValue;
			$GLOBALS['%s']->pop();
			return $裨mp3;
		}
		$s = "";
		if($this->nodeType == Xml::$Element) {
			$s .= "<";
			$s .= $this->_nodeName;
			$蜴t = $this->_attributes->keys();
			while($蜴t->hasNext()) {
			$k = $蜴t->next();
			{
				$s .= " ";
				$s .= $k;
				$s .= "=\"";
				$s .= $this->_attributes->get($k);
				$s .= "\"";
				;
			}
			}
			if($this->_children->length === 0) {
				$s .= "/>";
				{
					$GLOBALS['%s']->pop();
					return $s;
				}
			}
			$s .= ">";
		}
		$蜴t2 = $this->iterator();
		while($蜴t2->hasNext()) {
		$x = $蜴t2->next();
		$s .= $x->toString();
		}
		if($this->nodeType == Xml::$Element) {
			$s .= "</";
			$s .= $this->_nodeName;
			$s .= ">";
		}
		{
			$GLOBALS['%s']->pop();
			return $s;
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
	static $Element;
	static $PCData;
	static $CData;
	static $Comment;
	static $DocType;
	static $Prolog;
	static $Document;
	static $build;
	static function __start_element_handler($parser, $name, $attribs) {
		$GLOBALS['%s']->push("Xml::__start_element_handler");
		$製pos = $GLOBALS['%s']->length;
		$node = Xml::createElement($name);
		while(list($k, $v) = each($attribs)) $node->set($k, $v);
		Xml::$build->addChild($node);
		Xml::$build = $node;
		$GLOBALS['%s']->pop();
	}
	static function __end_element_handler($parser, $name) {
		$GLOBALS['%s']->push("Xml::__end_element_handler");
		$製pos = $GLOBALS['%s']->length;
		Xml::$build = Xml::$build->getParent();
		$GLOBALS['%s']->pop();
	}
	static function __character_data_handler($parser, $data) {
		$GLOBALS['%s']->push("Xml::__character_data_handler");
		$製pos = $GLOBALS['%s']->length;
		$lc = ((Xml::$build->_children === null || Xml::$build->_children->length === 0) ? null : Xml::$build->_children[Xml::$build->_children->length - 1]);
		if($lc !== null && Xml::$PCData == $lc->nodeType) {
			$lc->setNodeValue($lc->getNodeValue() . htmlentities($data));
		}
		else {
			if((strlen($data) === 1 && htmlentities($data) != $data) || htmlentities($data) == $data) {
				Xml::$build->addChild(Xml::createPCData(htmlentities($data)));
			}
			else {
				Xml::$build->addChild(Xml::createCData($data));
			}
		}
		$GLOBALS['%s']->pop();
	}
	static function __default_handler($parser, $data) {
		$GLOBALS['%s']->push("Xml::__default_handler");
		$製pos = $GLOBALS['%s']->length;
		Xml::$build->addChild(Xml::createPCData($data));
		$GLOBALS['%s']->pop();
	}
	static $xmlChecker;
	static function parse($str) {
		$GLOBALS['%s']->push("Xml::parse");
		$製pos = $GLOBALS['%s']->length;
		Xml::$build = Xml::createDocument();
		$xml_parser = xml_parser_create();
		xml_set_element_handler($xml_parser, isset(Xml::$__start_element_handler) ? Xml::$__start_element_handler: array("Xml", "__start_element_handler"), isset(Xml::$__end_element_handler) ? Xml::$__end_element_handler: array("Xml", "__end_element_handler"));
		xml_set_character_data_handler($xml_parser, isset(Xml::$__character_data_handler) ? Xml::$__character_data_handler: array("Xml", "__character_data_handler"));
		xml_set_default_handler($xml_parser, isset(Xml::$__default_handler) ? Xml::$__default_handler: array("Xml", "__default_handler"));
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 0);
		$isComplete = Xml::$xmlChecker->match($str);
		if(!$isComplete) {
			$str = "<doc>" . $str . "</doc>";
		}
		if(xml_parse($xml_parser, $str, true) !== 1) {
			throw new HException("Xml parse error (" . xml_error_string($xml_parser) . ") line #" . xml_get_current_line_number($xml_parser));
		}
		xml_parser_free($xml_parser);
		if($isComplete) {
			{
				$裨mp = Xml::$build;
				$GLOBALS['%s']->pop();
				return $裨mp;
			}
		}
		else {
			Xml::$build = Xml::$build->_children[0];
			Xml::$build->_parent = null;
			Xml::$build->_nodeName = null;
			Xml::$build->nodeType = Xml::$Document;
			{
				$裨mp2 = Xml::$build;
				$GLOBALS['%s']->pop();
				return $裨mp2;
			}
		}
		$GLOBALS['%s']->pop();
	}
	static function createElement($name) {
		$GLOBALS['%s']->push("Xml::createElement");
		$製pos = $GLOBALS['%s']->length;
		$r = new Xml();
		$r->nodeType = Xml::$Element;
		$r->_children = new _hx_array(array());
		$r->_attributes = new Hash();
		$r->setNodeName($name);
		{
			$GLOBALS['%s']->pop();
			return $r;
		}
		$GLOBALS['%s']->pop();
	}
	static function createPCData($data) {
		$GLOBALS['%s']->push("Xml::createPCData");
		$製pos = $GLOBALS['%s']->length;
		$r = new Xml();
		$r->nodeType = Xml::$PCData;
		$r->setNodeValue($data);
		{
			$GLOBALS['%s']->pop();
			return $r;
		}
		$GLOBALS['%s']->pop();
	}
	static function createCData($data) {
		$GLOBALS['%s']->push("Xml::createCData");
		$製pos = $GLOBALS['%s']->length;
		$r = new Xml();
		$r->nodeType = Xml::$CData;
		$r->setNodeValue($data);
		{
			$GLOBALS['%s']->pop();
			return $r;
		}
		$GLOBALS['%s']->pop();
	}
	static function createComment($data) {
		$GLOBALS['%s']->push("Xml::createComment");
		$製pos = $GLOBALS['%s']->length;
		$r = new Xml();
		$r->nodeType = Xml::$Comment;
		$r->setNodeValue($data);
		{
			$GLOBALS['%s']->pop();
			return $r;
		}
		$GLOBALS['%s']->pop();
	}
	static function createDocType($data) {
		$GLOBALS['%s']->push("Xml::createDocType");
		$製pos = $GLOBALS['%s']->length;
		$r = new Xml();
		$r->nodeType = Xml::$DocType;
		$r->setNodeValue($data);
		{
			$GLOBALS['%s']->pop();
			return $r;
		}
		$GLOBALS['%s']->pop();
	}
	static function createProlog($data) {
		$GLOBALS['%s']->push("Xml::createProlog");
		$製pos = $GLOBALS['%s']->length;
		$r = new Xml();
		$r->nodeType = Xml::$Prolog;
		$r->setNodeValue($data);
		{
			$GLOBALS['%s']->pop();
			return $r;
		}
		$GLOBALS['%s']->pop();
	}
	static function createDocument() {
		$GLOBALS['%s']->push("Xml::createDocument");
		$製pos = $GLOBALS['%s']->length;
		$r = new Xml();
		$r->nodeType = Xml::$Document;
		$r->_children = new _hx_array(array());
		{
			$GLOBALS['%s']->pop();
			return $r;
		}
		$GLOBALS['%s']->pop();
	}
	function __toString() { return $this->toString(); }
}
{
	Xml::$Element = "element";
	Xml::$PCData = "pcdata";
	Xml::$CData = "cdata";
	Xml::$Comment = "comment";
	Xml::$DocType = "doctype";
	Xml::$Prolog = "prolog";
	Xml::$Document = "document";
}
Xml::$xmlChecker = new EReg("\\s*(<\\?xml|<!DOCTYPE)", "mi");
