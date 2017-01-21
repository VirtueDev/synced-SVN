package dance.server {

import com.threerings.util.Hashable;

import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.net.RemoteCaller;
import aduros.game.*;

import dance.DanceTools;
import dance.data.Codes;

public class Player
    implements Hashable
{
    public var room :RoomManager;

    public var playerReceiver :RemoteCaller;

    public var stats :StatTracker;

    public function Player (ctrl :PlayerSubControlServer)
    {
        _ctrl = ctrl;
        _ctrl.addEventListener(AVRGamePlayerEvent.TASK_COMPLETED, onTaskCompleted);
        _ctrl.props.addEventListener(ElementChangedEvent.ELEMENT_CHANGED, onElementChanged);

        playerReceiver = new RemoteCaller(_ctrl, Codes.MSG_SERVICE_PLAYER);

        stats = new StatTracker(STATS, TROPHIES, ctrl);
    }

    protected function onTaskCompleted (event :AVRGamePlayerEvent) :void
    {
        Server.log.info("Holy shit, task completed events work!", "task", event.name, "coins", event.value, "playerId", ctrl.getPlayerId());
//        if (event.name == Codes.TASK_DJ) {
//            var coins :int = event.value as int;
//            stats.submit("djCoins", coins);
//            Server.log.info("DJ earned coins", "amount", coins, "name", name, "playerId", ctrl.getPlayerId());
//        }
    }

    protected function onElementChanged (event :ElementChangedEvent) :void
    {
        if (event.name == Codes.PROP_COUNTRY) {
            if (country != null) {
                stats.submit("continent", ServerCodes.COUNTRIES[country.toUpperCase()]);
            }
        }
    }

    public function get ctrl () :PlayerSubControlServer
    {
        return _ctrl;
    }

    public function get name () :String
    {
        return _ctrl.getPlayerName();
    }

    public function get country () :String
    {
        // Set by the client
        return ctrl.props.get(Codes.PROP_COUNTRY) as String;
    }

    public function get xp () :int
    {
        return int(ctrl.props.get("@xp"));
    }

    public function get level () :int
    {
        return DanceTools.xpToLevel(xp);
    }

    public function get djPayout () :Number
    {
        return ctrl.props.get(Codes.PROP_DJ_PAYOUT) as Number;
    }

    public function set djPayout (payout :Number) :void
    {
        ctrl.props.set(Codes.PROP_DJ_PAYOUT, payout);
    }

    public function equals (other :Object) :Boolean
    {
        return hashCode() == other.hashCode();
    }

    public function hashCode () :int
    {
        return _ctrl.getPlayerId();
    }

    public static const STATS :Object = {
        bestScore: Stat.MAX,
        bestCombo: Stat.MAX,
        djCoins: Stat.ADD, // Coins accumulated for DJing
        continent: Stat.SET,
        xp: Stat.ADD,
        dailyBest: Stat.ADD,
        monthlyBest: Stat.ADD
    };

    protected static const TROPHIES :Object = {
        djCoins: [
            new Trophy(500, "dj0"),
            new Trophy(10000, "dj1"),
            new Trophy(50000, "dj2"),
            new Trophy(125000, "dj3"),
            new Trophy(300000, "dj4"),
            new Trophy(600000, "dj5"),
            new Trophy(1000000, "dj6"),
            //new Trophy(2000000, "dj7"),
        ],
        bestCombo: [
            new Trophy(30, "combo0"),
            new Trophy(60, "combo1"),
            new Trophy(100, "combo2"),
            new Trophy(250, "combo3"),
            new Trophy(500, "combo4"),
        ],
//        bestScore: [
//            new Trophy(1000, "score1"),
//            new Trophy(5000, "score2"),
//        ],
//        continent: [
//            new Trophy("AF", "home_af"),
//            new Trophy("AS", "home_as"),
//            new Trophy("EU", "home_eu"),
//            new Trophy("NA", "home_na"),
//            new Trophy("SA", "home_sa"),
//            new Trophy("OC", "home_oc"),
//        ],
        xp: [
            // Changes must be synced to LevelDisplay!
            //new Trophy(DanceTools.levelToXp(2), "difficulty1"),
            new Trophy(DanceTools.levelToXp(2), "song0", "song0"),
            new Trophy(DanceTools.levelToXp(4), "level0", "level0"),
//            new Trophy(DanceTools.levelToXp(5), "difficulty2"),
            new Trophy(DanceTools.levelToXp(7), "difficulty3"),
            new Trophy(DanceTools.levelToXp(9), "song1", "song1"),
            new Trophy(DanceTools.levelToXp(11), "level1", "level1"),
            new Trophy(DanceTools.levelToXp(12), "song2", "song2"),
            new Trophy(DanceTools.levelToXp(17), "level2", "level2"),
            new Trophy(DanceTools.levelToXp(18), "song3", "song3"),
            new Trophy(DanceTools.levelToXp(21), "level3", "level3"),
            new Trophy(DanceTools.levelToXp(25), "difficulty4"),
            new Trophy(DanceTools.levelToXp(30), "level4", "level4"),
            new Trophy(DanceTools.levelToXp(35), "song4", "song4"),
            new Trophy(DanceTools.levelToXp(40), "level5", "level5"),
            new Trophy(DanceTools.levelToXp(50), "song5", "song5"),
        ],
        dailyBest: [
            new Trophy(1, "daily0"),
            new Trophy(10, "daily1"),
            new Trophy(50, "daily2"),
        ],
        monthlyBest: [
            new Trophy(1, "monthly0"),
            new Trophy(10, "monthly1"),
            new Trophy(50, "monthly2"),
        ]
    };

    protected var _ctrl :PlayerSubControlServer;
}


}
