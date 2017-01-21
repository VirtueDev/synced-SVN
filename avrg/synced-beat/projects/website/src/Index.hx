import templo.Loader;
import php.db.Manager;
import php.db.Mysql;
import php.Lib;
import php.Web;
import php.io.File;
import php.io.Process;

class Index
{
    public static var DWI_LEVELS = [ "BEGINNER", "BASIC", "ANOTHER", "MANIAC", "SMANIAC" ];
    public static var WB_LEVELS = [ "beginner", "light", "standard", "heavy", "guru" ];

    static function creation () :String
    {
        var loader = new Loader("creation.mtt");
        return loader.execute({});
    }

    static function upload () :String
    {
        var params = Web.getParams();

        var song = new SongInfo();
        song.uploaderId = Std.parseInt(params.get("uploaderId"));
        if (song.uploaderId == null) {
            song.uploaderId = 0;
        }
        song.uploaderName = params.get("uploaderName");
        song.insert();

        var smPath = untyped __var__("_FILES", "sm")["tmp_name"];
        var musicPath = untyped __var__("_FILES", "music")["tmp_name"];
        var musicName = untyped __var__("_FILES", "music")["name"];

        var params = ["sm2amf.py",
            "-s", smPath,
            "-m", musicPath,
            "-i", Std.string(song.id),
            "-u", Std.string(song.uploaderId),
            "-d", "songs",
            "-p", "",
        ];
        // Hack to fix sox file detection
        if (StringTools.endsWith(musicName.toLowerCase(), ".mp3")) {
            params.push("-t");
            params.push("mp3");
        }
        var sm2amf = new Process("/home/aduros/local/bin/python2.4", params);

        if (sm2amf.exitCode() == 0) {
            song.sync(); // Not winning any efficiency awards here

        } else {
            song.delete();
            var line :String;
            Lib.print("<h3>Not the face!</h3><p>It broke. Your stepfile or music may be corrupt or not supported.</p><pre>");
            try {
                while ((line = sm2amf.stderr.readLine()) != null) {
                    Lib.println(line);
                }
                Lib.print("</pre>");
            } catch (error :Dynamic) { }
            return "";
        }
        
        var smContent = File.getContent(smPath);
        for (level in 0...WB_LEVELS.length) {
            if ((song.difficulties & (1 << level)) != 0) {
                // Sorry, but this is just too cool
                var req = new haxe.Http("http://hawknest.stacken.kth.se:8080/cgi-bin/stepchart4-cgi.pl");
                req.setParameter("dwi", smContent);
                req.setParameter("panels", "4");
                req.setParameter("level", DWI_LEVELS[level]);
                req.setParameter("speed", "1");
                req.setParameter("turn", "OFF");
                req.setParameter("freeze", "ON");
                req.setParameter("freezestyle", "new");
                req.onData = function (png) File.putContent("songs/"+song.id+"_" + WB_LEVELS[level] + ".png", png);
                req.request(true);
            }
        }

        var loader = new Loader("complete.mtt");
        return loader.execute({song: song});
    }

    static function search () :String
    {
        var params = Web.getParams();
        var page :Int = if (params.exists("p")) cast params.get("p") else 0;

        var query = params.get("q");
        if (StringTools.trim(query).length == 0) {
            query = null;
        }

        var results = SongInfo.manager.getPage(page, query);
        var count = SongInfo.manager.getSongCount(query);
        var top = null;
        if (query == null && page == 0) {
            top = Manager.cnx.request("SELECT uploaderId,uploaderName,count(*) AS count FROM SongInfo WHERE uploaderId != 0 AND MONTH(uploadedOn) = MONTH(NOW()) GROUP BY uploaderId ORDER BY count DESC LIMIT 5");
        }
        var loader = new Loader("list.mtt");

        return loader.execute({
            songs: results,
            page: page,
            query: query,
            lastPage: Math.ceil(count/SongInfoManager.PAGE_LENGTH)-1,
            top: top,
            count: count,
        });
    }

    static function detail () :String
    {
        var params = Web.getParams();
        var song = SongInfo.manager.get(cast params.get("id"));

        var loader = new Loader("detail.mtt");
        return loader.execute({
            song: song,
            whirledUrl: "http://syncedonline.com/#shop-7_1_s" + StringTools.replace(song.title, " ", "%-"),
        });
    }

    static function mp3 () :String
    {
        var params = Web.getParams();
        var song = SongInfo.manager.get(cast params.get("id"));

        Web.setHeader("Content-Disposition", "attachment; filename=\"" + song.title + " - " + song.artist + ".mp3\"");
        Lib.printFile("songs/" + song.id + ".mp3");
        return "";
    }

    static function main ()
    {
        // Ghetto
        var controllers = new Hash<Void -> String>();
        controllers.set("creation", creation);
        controllers.set("search", search);
        controllers.set("upload", upload);
        controllers.set("detail", detail);
        controllers.set("mp3", mp3);

        var params = Web.getParams();
        var controller = search;
        if (params.exists("do")) {
            controller = controllers.get(params.get("do"));
            if (controller == null) {
                Web.setReturnCode(404);
                return;
            }
        }

        Manager.cnx = Mysql.connect({
            host: "localhost",
            port: 3306,
            database: "whirledbeat",
            user: "root",
            pass: "",
            socket: null
        });
        Manager.initialize();

        Loader.MACROS = null;
        Loader.TMP_DIR = Web.getCwd() + "tpl/";
        Loader.OPTIMIZED = true;

        try {
            Lib.print(controller());

        } catch (e :Dynamic) { // No finally in haxe?
            Manager.cleanup();
            Manager.cnx.close();
            throw e;
        }
    }
}
