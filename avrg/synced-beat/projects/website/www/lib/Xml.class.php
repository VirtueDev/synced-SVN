<?php

class Xml {
	public function __construct() {
		if( !php_Boot::$skip_constructor ) {
		$GLOBALS['%s']->push("Xml::new");
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$�tmp = $this->_nodeName;
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function setNodeName($n) {
		$GLOBALS['%s']->push("Xml::setNodeName");
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$�tmp = $this->_nodeName = $n;
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function getNodeValue() {
		$GLOBALS['%s']->push("Xml::getNodeValue");
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType == Xml::$Element || $this->nodeType == Xml::$Document) {
			throw new HException("bad nodeType");
		}
		{
			$�tmp = $this->_nodeValue;
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function setNodeValue($v) {
		$GLOBALS['%s']->push("Xml::setNodeValue");
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType == Xml::$Element || $this->nodeType == Xml::$Document) {
			throw new HException("bad nodeType");
		}
		{
			$�tmp = $this->_nodeValue = $v;
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function getParent() {
		$GLOBALS['%s']->push("Xml::getParent");
		$�spos = $GLOBALS['%s']->length;
		{
			$�tmp = $this->_parent;
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function get($att) {
		$GLOBALS['%s']->push("Xml::get");
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$�tmp = $this->_attributes->get($att);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function set($att, $value) {
		$GLOBALS['%s']->push("Xml::set");
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		$this->_attributes->set($att, htmlspecialchars($value, ENT_COMPAT, "UTF-8"));
		$GLOBALS['%s']->pop();
	}
	public function remove($att) {
		$GLOBALS['%s']->push("Xml::remove");
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		$this->_attributes->remove($att);
		$GLOBALS['%s']->pop();
	}
	public function exists($att) {
		$GLOBALS['%s']->push("Xml::exists");
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$�tmp = $this->_attributes->exists($att);
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function attributes() {
		$GLOBALS['%s']->push("Xml::attributes");
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType != Xml::$Element) {
			throw new HException("bad nodeType");
		}
		{
			$�tmp = $this->_attributes->keys();
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function iterator() {
		$GLOBALS['%s']->push("Xml::iterator");
		$�spos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		$me = $this;
		$it = null;
		$it = _hx_anonymous(array("cur" => 0, "x" => $me->_children, "hasNext" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "�spos" => &$�spos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::iterator@228\");
			\$�spos2 = \$GLOBALS['%s']->length;
			{
				\$�tmp = \$it->cur < _hx_len(\$it->x);
				\$GLOBALS['%s']->pop();
				return \$�tmp;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0'), "next" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "�spos" => &$�spos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::iterator@231\");
			\$�spos2 = \$GLOBALS['%s']->length;
			{
				\$�tmp = \$it->x[\$it->cur++];
				\$GLOBALS['%s']->pop();
				return \$�tmp;
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
		$�spos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		$me = $this;
		$it = null;
		$it = _hx_anonymous(array("cur" => 0, "x" => $me->_children, "hasNext" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "�spos" => &$�spos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::elements@245\");
			\$�spos2 = \$GLOBALS['%s']->length;
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
				\$�tmp = \$k < \$l;
				\$GLOBALS['%s']->pop();
				return \$�tmp;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0'), "next" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "�spos" => &$�spos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::elements@257\");
			\$�spos2 = \$GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		$me = $this;
		$it = null;
		$it = _hx_anonymous(array("cur" => 0, "x" => $me->_children, "hasNext" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "name" => &$name, "�spos" => &$�spos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::elementsNamed@282\");
			\$�spos2 = \$GLOBALS['%s']->length;
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
				\$�tmp = \$k < \$l;
				\$GLOBALS['%s']->pop();
				return \$�tmp;
			}
			\$GLOBALS['%s']->pop();
		}"), 'execute0'), "next" => array(new _hx_lambda(array("it" => &$it, "me" => &$me, "name" => &$name, "�spos" => &$�spos), null, array(), "{
			\$GLOBALS['%s']->push(\"Xml::elementsNamed@294\");
			\$�spos2 = \$GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
		if($this->_children === null) {
			throw new HException("bad nodetype");
		}
		if($this->_children->length === 0) {
			$GLOBALS['%s']->pop();
			return null;
		}
		{
			$�tmp = $this->_children[0];
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		$GLOBALS['%s']->pop();
	}
	public function firstElement() {
		$GLOBALS['%s']->push("Xml::firstElement");
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
		if($this->nodeType == Xml::$PCData) {
			$�tmp = $this->_nodeValue;
			$GLOBALS['%s']->pop();
			return $�tmp;
		}
		if($this->nodeType == Xml::$CData) {
			$�tmp2 = "<![CDATA[" . $this->_nodeValue . "]]>";
			$GLOBALS['%s']->pop();
			return $�tmp2;
		}
		if($this->nodeType == Xml::$Comment || $this->nodeType == Xml::$DocType || $this->nodeType == Xml::$Prolog) {
			$�tmp3 = $this->_nodeValue;
			$GLOBALS['%s']->pop();
			return $�tmp3;
		}
		$s = "";
		if($this->nodeType == Xml::$Element) {
			$s .= "<";
			$s .= $this->_nodeName;
			$�it = $this->_attributes->keys();
			while($�it->hasNext()) {
			$k = $�it->next();
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
		$�it2 = $this->iterator();
		while($�it2->hasNext()) {
		$x = $�it2->next();
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
		else if(isset($this->�dynamics[$m]) && is_callable($this->�dynamics[$m]))
			return call_user_func_array($this->�dynamics[$m], $a);
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
		$�spos = $GLOBALS['%s']->length;
		$node = Xml::createElement($name);
		while(list($k, $v) = each($attribs)) $node->set($k, $v);
		Xml::$build->addChild($node);
		Xml::$build = $node;
		$GLOBALS['%s']->pop();
	}
	static function __end_element_handler($parser, $name) {
		$GLOBALS['%s']->push("Xml::__end_element_handler");
		$�spos = $GLOBALS['%s']->length;
		Xml::$build = Xml::$build->getParent();
		$GLOBALS['%s']->pop();
	}
	static function __character_data_handler($parser, $data) {
		$GLOBALS['%s']->push("Xml::__character_data_handler");
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
		Xml::$build->addChild(Xml::createPCData($data));
		$GLOBALS['%s']->pop();
	}
	static $xmlChecker;
	static function parse($str) {
		$GLOBALS['%s']->push("Xml::parse");
		$�spos = $GLOBALS['%s']->length;
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
				$�tmp = Xml::$build;
				$GLOBALS['%s']->pop();
				return $�tmp;
			}
		}
		else {
			Xml::$build = Xml::$build->_children[0];
			Xml::$build->_parent = null;
			Xml::$build->_nodeName = null;
			Xml::$build->nodeType = Xml::$Document;
			{
				$�tmp2 = Xml::$build;
				$GLOBALS['%s']->pop();
				return $�tmp2;
			}
		}
		$GLOBALS['%s']->pop();
	}
	static function createElement($name) {
		$GLOBALS['%s']->push("Xml::createElement");
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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
		$�spos = $GLOBALS['%s']->length;
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