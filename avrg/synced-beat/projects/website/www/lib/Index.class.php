<?php

class Index {
	public function __construct(){}
	static $DWI_LEVELS;
	static $WB_LEVELS;
	static function creation() {
		$GLOBALS['%s']->push("Index::creation");
		$製pos = $GLOBALS['%s']->length;
		$loader = new templo_Loader("creation.mtt");
		{
			$裨mp = $loader->execute(_hx_anonymous(array()));
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function upload() {
		$GLOBALS['%s']->push("Index::upload");
		$製pos = $GLOBALS['%s']->length;
		$params = php_Web::getParams();
		$song = new SongInfo();
		$song->uploaderId = Std::parseInt($params->get("uploaderId"));
		if($song->uploaderId === null) {
			$song->uploaderId = 0;
		}
		$song->uploaderName = $params->get("uploaderName");
		$song->insert();
		$smPath = _hx_array_get($_FILES["sm"], "tmp_name");
		$musicPath = _hx_array_get($_FILES["music"], "tmp_name");
		$musicName = _hx_array_get($_FILES["music"], "name");
		$params1 = new _hx_array(array("sm2amf.py", "-s", $smPath, "-m", $musicPath, "-i", Std::string($song->id), "-u", Std::string($song->uploaderId), "-d", "songs", "-p", ""));
		if(StringTools::endsWith(_hx_string_call($musicName, "toLowerCase", array()), ".mp3")) {
			$params1->push("-t");
			$params1->push("mp3");
		}
		$sm2amf = new php_io_Process("/home/aduros/local/bin/python2.4", $params1);
		if($sm2amf->exitCode() === 0) {
			$song->sync();
		}
		else {
			$song->delete();
			$line = null;
			php_Lib::hprint("<h3>Not the face!</h3><p>It broke. Your stepfile or music may be corrupt or not supported.</p><pre>");
			try {
				while(($line = $sm2amf->stderr->readLine()) !== null) {
					php_Lib::println($line);
					;
				}
				php_Lib::hprint("</pre>");
			}catch(Exception $蜜) {
			$_ex_ = ($蜜 instanceof HException) ? $蜜->e : $蜜;
			;
			{ $error = $_ex_;
			{
				$GLOBALS['%e'] = new _hx_array(array());
				while($GLOBALS['%s']->length >= $製pos) $GLOBALS['%e']->unshift($GLOBALS['%s']->pop());
				$GLOBALS['%s']->push($GLOBALS['%e'][0]);
				;
			}}}
			{
				$GLOBALS['%s']->pop();
				return "";
			}
		}
		$smContent = php_io_File::getContent($smPath);
		{
			$_g1 = 0; $_g = Index::$WB_LEVELS->length;
			while($_g1 < $_g) {
				$level = $_g1++;
				if(($song->difficulties & (1 << $level)) !== 0) {
					$req = new haxe_Http("http://hawknest.stacken.kth.se:8080/cgi-bin/stepchart4-cgi.pl");
					$req->setParameter("dwi", $smContent);
					$req->setParameter("panels", "4");
					$req->setParameter("level", Index::$DWI_LEVELS[$level]);
					$req->setParameter("speed", "1");
					$req->setParameter("turn", "OFF");
					$req->setParameter("freeze", "ON");
					$req->setParameter("freezestyle", "new");
					$req->onData = array(new _hx_lambda(array("_ex_" => &$_ex_, "_g" => &$_g, "_g1" => &$_g1, "error" => &$error, "level" => &$level, "line" => &$line, "musicName" => &$musicName, "musicPath" => &$musicPath, "params" => &$params, "params1" => &$params1, "req" => &$req, "sm2amf" => &$sm2amf, "smContent" => &$smContent, "smPath" => &$smPath, "song" => &$song, "蜜" => &$蜜, "製pos" => &$製pos), null, array('png'), "{
						\$GLOBALS['%s']->push(\"Index::upload@79\");
						\$製pos2 = \$GLOBALS['%s']->length;
						php_io_File::putContent(\"songs/\" . \$song->id . \"_\" . Index::\$WB_LEVELS[\$level] . \".png\", \$png);
						\$GLOBALS['%s']->pop();
					}"), 'execute1');
					$req->request(true);
				}
				unset($req,$level);
			}
		}
		$loader = new templo_Loader("complete.mtt");
		{
			$裨mp = $loader->execute(_hx_anonymous(array("song" => $song)));
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function search() {
		$GLOBALS['%s']->push("Index::search");
		$製pos = $GLOBALS['%s']->length;
		$params = php_Web::getParams();
		$page = ($params->exists("p") ? $params->get("p") : 0);
		$query = $params->get("q");
		if(strlen(trim($query)) === 0) {
			$query = null;
		}
		$results = SongInfo::$manager->getPage($page, $query);
		$count = SongInfo::$manager->getSongCount($query);
		$top = null;
		if($query === null && $page === 0) {
			$top = php_db_Manager::$cnx->request("SELECT uploaderId,uploaderName,count(*) AS count FROM SongInfo WHERE uploaderId != 0 AND MONTH(uploadedOn) = MONTH(NOW()) GROUP BY uploaderId ORDER BY count DESC LIMIT 5");
		}
		$loader = new templo_Loader("list.mtt");
		{
			$裨mp = $loader->execute(_hx_anonymous(array("songs" => $results, "page" => $page, "query" => $query, "lastPage" => Math::ceil($count / 20) - 1, "top" => $top, "count" => $count)));
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function detail() {
		$GLOBALS['%s']->push("Index::detail");
		$製pos = $GLOBALS['%s']->length;
		$params = php_Web::getParams();
		$song = SongInfo::$manager->get($params->get("id"), null);
		$loader = new templo_Loader("detail.mtt");
		{
			$裨mp = $loader->execute(_hx_anonymous(array("song" => $song, "whirledUrl" => "http://syncedonline.com/#shop-7_1_s" . str_replace(" ", "%-", $song->title))));
			$GLOBALS['%s']->pop();
			return $裨mp;
		}
		$GLOBALS['%s']->pop();
	}
	static function mp3() {
		$GLOBALS['%s']->push("Index::mp3");
		$製pos = $GLOBALS['%s']->length;
		$params = php_Web::getParams();
		$song = SongInfo::$manager->get($params->get("id"), null);
		header("Content-Disposition" . ": " . "attachment; filename=\"" . $song->title . " - " . $song->artist . ".mp3\"");
		php_Lib::printFile("songs/" . $song->id . ".mp3");
		{
			$GLOBALS['%s']->pop();
			return "";
		}
		$GLOBALS['%s']->pop();
	}
	static function main() {
		$GLOBALS['%s']->push("Index::main");
		$製pos = $GLOBALS['%s']->length;
		$controllers = new Hash();
		$controllers->set("creation", isset(Index::$creation) ? Index::$creation: array("Index", "creation"));
		$controllers->set("search", isset(Index::$search) ? Index::$search: array("Index", "search"));
		$controllers->set("upload", isset(Index::$upload) ? Index::$upload: array("Index", "upload"));
		$controllers->set("detail", isset(Index::$detail) ? Index::$detail: array("Index", "detail"));
		$controllers->set("mp3", isset(Index::$mp3) ? Index::$mp3: array("Index", "mp3"));
		$params = php_Web::getParams();
		$controller = isset(Index::$search) ? Index::$search: array("Index", "search");
		if($params->exists("do")) {
			$controller = $controllers->get($params->get("do"));
			if($controller === null) {
				php_Web::setReturnCode(404);
				{
					$GLOBALS['%s']->pop();
					return;
				}
			}
		}
		php_db_Manager::setConnection(php_db_Mysql::connect(_hx_anonymous(array("host" => "localhost", "port" => 3306, "database" => "whirledbeat", "user" => "root", "pass" => "", "socket" => null))));
		php_db_Manager::initialize();
		templo_Loader::$MACROS = null;
		templo_Loader::$TMP_DIR = dirname($_SERVER["SCRIPT_FILENAME"]) . "/" . "tpl/";
		templo_Loader::$OPTIMIZED = true;
		try {
			php_Lib::hprint(call_user_func_array($controller, array()));
		}catch(Exception $蜜) {
		$_ex_ = ($蜜 instanceof HException) ? $蜜->e : $蜜;
		;
		{ $e = $_ex_;
		{
			$GLOBALS['%e'] = new _hx_array(array());
			while($GLOBALS['%s']->length >= $製pos) $GLOBALS['%e']->unshift($GLOBALS['%s']->pop());
			$GLOBALS['%s']->push($GLOBALS['%e'][0]);
			php_db_Manager::cleanup();
			php_db_Manager::$cnx->close();
			throw new HException($e);
		}}}
		$GLOBALS['%s']->pop();
	}
	function __toString() { return 'Index'; }
}
Index::$DWI_LEVELS = new _hx_array(array("BEGINNER", "BASIC", "ANOTHER", "MANIAC", "SMANIAC"));
Index::$WB_LEVELS = new _hx_array(array("beginner", "light", "standard", "heavy", "guru"));
