package dance.client {

import flash.display.Sprite;
import flash.events.Event;
import flash.geom.Rectangle;
import flash.utils.*;

import com.threerings.ui.KeyboardCodes;
import com.threerings.util.Log;

import com.whirled.ControlEvent;
import com.whirled.avrg.AVRGameControl;
import com.whirled.avrg.AVRGameControlEvent;

import nl.demonsters.debugger.MonsterDebugger;

import aduros.display.ToolTipManager;
import aduros.game.Metrics;
import aduros.util.F;

import dance.DanceTools;
import dance.data.Codes;

public class Game extends Sprite
{
    public static var ctrl :AVRGameControl;
    public static const log :Log = Log.getLog(Game);
    public static var metrics :Metrics;

    public static function get difficulty () :int
    {
        return int(ctrl.player.props.get(Codes.PROP_DIFFICULTY));
    }

    public static function get xp () :int
    {
        return Math.floor(Math.max(0, int(ctrl.player.props.get("@xp"))));
    }

    public static function get xpUntilNextLevel () :int
    {
        return DanceTools.levelToXp(level+1) - xp;
    }

    /** Number in [0,1) percent to next level. */
    public static function get levelUpProgress () :Number
    {
        var level :int = Game.level;
        var startXp :int = DanceTools.levelToXp(level);
        return (xp-startXp) / (DanceTools.levelToXp(level+1)-startXp);
    }

    public static function get level () :int
    {
        return DanceTools.xpToLevel(xp);
    }

    public static function get difficultiesUnlocked () :int
    {
        // FIXME: F.partial not working?
        var level :int = Game.level;
        return 3 + [7, 25].filter(F.adapt(function (x :Number) :Boolean { return level >= x })).length;
    }

    /** Send a list of states to the avatar, hopefully it will support one of them. */
    public static function setAvatarState (... states) :void
    {
        ctrl.player.doBatch(function () :void {
            for each (var state :String in states) {
                ctrl.player.setAvatarState(state);
            }
        });
    }

    public static function getDJName (playerId :int) :String
    {
        var name :String = ctrl.room.getOccupantName(playerId);
        if (name != null) {
            if (name.indexOf("DJ ") == 0) {
                return name;
            } else {
                return "DJ " + name;
            }
        } else {
            return ctrl.room.getRoomName() + " Management"; // TODO: i18n
        }
    }

    public function Game ()
    {
        ctrl = new AVRGameControl(this);
        if (!ctrl.isConnected()) {
            log.error("Not connected. Bailing!");
            return;
        }

        metrics = new Metrics(ctrl, this, BuildConfig.ANALYTICS_ID);

//        var debugger :MonsterDebugger = new MonsterDebugger(this);

        log.info("Starting Dance", "compiled", BuildConfig.WHEN, "debug", BuildConfig.DEBUG);

        if (BuildConfig.DEBUG) {
            ctrl.local.feedback("Debug version: Compiled on " + BuildConfig.WHEN);
        }

        // Am I stubborn enough to put in personal Dvorak controls...? Yes.
        if (Game.ctrl.player.getPlayerId() == 878) {
            FocusOverlay.CONTROLS[KeyboardCodes.H] = DanceController.PAD_LEFT;
            FocusOverlay.CONTROLS[KeyboardCodes.T] = DanceController.PAD_DOWN;
            FocusOverlay.CONTROLS[KeyboardCodes.N] = DanceController.PAD_UP;
            FocusOverlay.CONTROLS[KeyboardCodes.S] = DanceController.PAD_RIGHT;
        }

        // Set up the ToolTipManager
        ToolTipManager.instance.screen = this;
        ctrl.local.addEventListener(AVRGameControlEvent.SIZE_CHANGED, F.adapt(updateToolTipBounds));
        updateToolTipBounds();

        var controller :DanceController = new DanceController();
        addChild(controller.view);

        addEventListener(Event.ADDED_TO_STAGE, F.justOnce(onFirstAdded));
    }

    protected function onFirstAdded (event :Event) :void
    {
        var version :String = root.loaderInfo.url.replace(/.*\/([0-9a-f]+).swf$/i, "$1");
        var last :String = Game.ctrl.player.props.get(Codes.PROP_VERSION) as String;

        if (version != last) {
            log.info("Updated version detected", "version", version, "last", last);
            ctrl.local.feedback(Messages.en.xlate(
                last == null ? "m_welcomeNewbie" : "m_welcomeUpdated"));
            ctrl.player.props.set(Codes.PROP_VERSION, version);

        } else {
            ctrl.local.feedback(Messages.en.xlate("m_welcome"));
        }
    }

    protected function updateToolTipBounds () :void
    {
        var bounds :Rectangle = ctrl.local.getPaintableArea();

        if (bounds != null) {
            ToolTipManager.instance.bounds = bounds;
        }
    }
}

}
