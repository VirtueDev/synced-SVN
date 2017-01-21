package dance {

import com.whirled.avrg.*;

public class DanceTools
{
    public static function xpToLevel (xp :int) :int
    {
        var xx :int = 0;
        for (var level :int = 1; level < 99; ++level) {
            xx += Math.floor(level + 300 * Math.pow(2, level/7));
            if (Math.floor(xx/4) > xp) {
                break;
            }
        }
        return level;
    }

    public static function levelToXp (level :int) :Number
    {
        var xp :Number = 0;
        for (var ii :int = 1; ii < level; ++ii) {
            xp += Math.floor(ii + 300 * Math.pow(2, ii/7));
        }
        return Math.floor(xp/4);
    }

    public static function getLoyaltyBonusXp (level :int) :Number
    {
        return 15*(level-1);
    }

    public static function multiPayout (
        ctrl :PlayerSubControlBase, taskId :String, payout :Number) :void
    {
        while (payout > 0) {
            ctrl.completeTask(taskId, Math.min(payout, 1));
            payout -= 1;
        }
    }
}

}
