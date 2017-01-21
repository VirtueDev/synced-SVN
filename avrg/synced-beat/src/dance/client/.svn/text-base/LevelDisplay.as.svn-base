package dance.client {

import flash.display.DisplayObject;
import flash.events.Event;
import flash.events.MouseEvent;
import flash.geom.Point;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;
import flash.utils.Dictionary;

import com.threerings.text.TextFieldUtil;
import com.threerings.util.StringUtil;
import com.threerings.util.Util;

import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.display.ToolTipManager;
import aduros.util.F;

import dance.DanceTools;
import dance.data.Codes;

public class LevelDisplay extends Component
{
    public static const WIDTH :Number = 180;
    public static const HEIGHT :Number = 24;

    public function LevelDisplay ()
    {
        registerListener(Game.ctrl.player.props, PropertyChangedEvent.PROPERTY_CHANGED, onPlayerPropertyChanged);

        addEventListener(MouseEvent.ROLL_OVER, F.callback(showDetail, true));
        addEventListener(MouseEvent.ROLL_OUT, F.callback(showDetail, false));

        // TODO: Dynamic tool tips
        var level :Number = Game.level;
        for each (var unlockLevel :int in Util.keys(MILESTONES).sort(Array.NUMERIC)) {
            if (level < unlockLevel) {
                ToolTipManager.instance.attach(this, Messages.en.xlate("l_nextUnlock",
                    unlockLevel, Messages.en.xlate(MILESTONES[unlockLevel])));
                break;
            }
        }

        addChild(_label);
        update();
    }

    protected function onPlayerPropertyChanged (event :PropertyChangedEvent) :void
    {
        if (event.name == "@xp") {
            update();
        }
    }

    protected function update () :void
    {
        var frac :Number = Game.levelUpProgress;

        graphics.clear();
        graphics.beginFill(0x205989);
        graphics.drawRect(0, 0, frac*WIDTH, HEIGHT);
        graphics.endFill();
        graphics.beginFill(0, 0.6);
        graphics.drawRect(frac*WIDTH, 0, (1-frac)*WIDTH, HEIGHT);
        graphics.endFill();

        showDetail(false);
    }

    protected function showDetail (hovering :Boolean) :void
    {
        if (hovering) {
            _label.text = StringUtil.formatNumber(Game.xp) + " / " + StringUtil.formatNumber(DanceTools.levelToXp(Game.level+1));
        } else {
            _label.text = Messages.en.xlate("l_level", Game.level);
        }

        _label.x = WIDTH/2 - _label.textWidth/2;
        _label.y = HEIGHT/2 - _label.height/2;
    }

    // For display purposes only, changes here must be synced with StatTracker
    public static const MILESTONES :Object = {
        //2: "l_unlockDifficulty1",
        2: "l_unlockSong0",
        4: "l_unlockTrophy0",
        //5: "l_unlockDifficulty2",
        //7: "l_unlockAvatarUpgrade0",
        7: "l_unlockDifficulty3",
        9: "l_unlockSong1",
        11: "l_unlockTrophy1",
        12: "l_unlockSong2",
        17: "l_unlockTrophy2",
        18: "l_unlockSong3",
        20: "l_unlockAvatarUpgrade0",
        21: "l_unlockTrophy3",
        25: "l_unlockDifficulty4",
        28: "l_unlockAvatarUpgrade1",
        30: "l_unlockTrophy4",
        35: "l_unlockSong4",
        40: "l_unlockTrophy5",
        45: "l_unlockAvatarUpgrade2",
        50: "l_unlockSong5",
        60: "l_unlockAvatarUpgrade3"
    };

    protected var _label :TextField = TextFieldUtil.createField("",
            { textColor: 0xffffff, selectable: false,
                 autoSize: TextFieldAutoSize.LEFT, outlineColor: 0x00000 },
            { size: 14, bold: true });
}

}
