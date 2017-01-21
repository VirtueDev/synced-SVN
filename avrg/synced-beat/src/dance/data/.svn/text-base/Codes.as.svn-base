package dance.data {

import com.whirled.net.NetConstants;

public class Codes
{
    public static const DIFFICULTIES :int = 5;

    /** All compatible songs should be searchable with this keyword. */
    public static const SONG_KEYWORD :String = "WhirledBeat";

    public static const GAME_ID :int = 2819;

    public static const PARLORS :Array = [ 3507888, 3507886, 3507890, 3507884 ];

    public static const PROP_CARDS :String = "a"; // Room (ScoreCard)
    public static const PROP_DIFFICULTY :String = NetConstants.makePersistent("c"); // Player (int)
    public static const PROP_OPTIONS :String = "d"; // Room (DanceOptions)
    public static const PROP_DJ_PAYOUT :String = NetConstants.makePersistent("g"); // Player (ScoreRecord)
    public static const PROP_VERSION :String = NetConstants.makePersistent("h"); // Player (String)
    public static const PROP_COUNTRY :String = "i"; // Player (String)
    public static const PROP_LAST_LOGIN :String = NetConstants.makePersistent("j"); // Player (Number)

    public static const MAX_DJ_PAYOUT :Number = 10;
    public static const TASK_DJ :String = "DJ";
    public static const TASK_LOYALTY :String = "loyalty";
    public static const TASK_DANCE :String = "dance";

    public static const MSG_SERVICE_ROOM :String = "a"; // Agent
    public static const MSG_SERVICE_PLAYER :String = "b"; // Player
    public static const MSG_SERVICE_GAME :String = "c"; // Agent
    public static const MSG_RECEIVER_GAME :String = "d"; // Game
    public static const MSG_RESULTS :String = "e"; // Room (ScoreSummary)

    public static const TROPHY_AVATAR_BUYER :String = "avatarBuyer";

    public static const STOP_INTERRUPT :int = 0;
    public static const STOP_SURRENDER :int = 1;
    public static const STOP_COMPLETE :int = 2;

    public static function isAdmin (playerId :int) :Boolean
    {
        return playerId == 878;
    }
}

}
