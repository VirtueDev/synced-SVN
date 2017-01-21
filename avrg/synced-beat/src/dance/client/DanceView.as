package dance.client {

import flash.display.Bitmap;
import flash.display.Sprite;
import flash.events.Event;
import flash.events.TextEvent;
import flash.geom.Rectangle;
import flash.text.StyleSheet;
import flash.text.TextField;
import flash.utils.ByteArray;
import flash.utils.getTimer;

import com.gskinner.motion.GTweeny;

import com.threerings.util.Command;
import com.threerings.util.ValueEvent;

import com.whirled.*;
import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.display.ImageButton;
import aduros.display.ToolTipManager;
import aduros.util.F;

import dance.data.Codes;
import dance.data.ScoreCard;

public class DanceView extends Sprite
{
    public static const DIFFICULTY_COLORS :Array = [
        0x04eaec, 0xf6ae26, 0xea54bf, 0x48ea11, 0xe83306 ];

    public static const STATE_WAITING :int = 0;
    public static const STATE_SCORES :int = 1;
    public static const STATE_PLAYING :int = 2;

    [Embed(source="../../../res/stop.png")]
    public static const STOP_ICON :Class;

    [Embed(source="../../../res/elements.ttf", fontFamily="dance")]
    public static const DANCE_FONT :Class;

    [Embed(source="../../../res/click.mp3")]
    public static const CLICK_SOUND :Class;

    public function DanceView (model :DanceModel)
    {
        _model = model;

        addEventListener(TextEvent.LINK, onLink);
    }

    public static function applyStyle (textField :TextField) :void
    {
        textField.styleSheet = new StyleSheet();
        textField.styleSheet.parseCSS(new STYLE().toString());
    }

    /** Get a htmlText URL that will fire a controller command. */
    public static function link (command :String, ... args) :String
    {
        args.unshift(command);
        return "event:" + args.map(F.adapt(escape)).join(",");
    }

    public static function escapeHTML (html :String) :String
    {
        return html.replace("<", "&lt;").replace(">", "&gt;"); // FIXME
    }

    public static function getFlagURL (country :String) :String
    {
        return BuildConfig.DATA_URL + "/flags/" + country.toLowerCase() + ".png";
    }

    public static function getIconURL (icon :String) :String
    {
        return BuildConfig.DATA_URL + "/icons/" + icon + ".png";
    }

    protected function onLink (event :TextEvent) :void
    {
        // Turn a link into a controller command
        var args :Array = event.text.split(",").map(F.adapt(unescape));
        var command :String = args.shift();
        Command.dispatch(this, command, args);
    }

    public function setState (state :int) :void
    {
        switch (state) {
            case STATE_WAITING:
                showComponents([BasicHUDPanel, WaitingPanel, OptionsPanel]);
                break;

            case STATE_SCORES:
                showComponents([BasicHUDPanel, SummaryPanel, OptionsPanel]);
                break;

            case STATE_PLAYING:
                showComponents([PlayingPanel]);
                break;
        }

        Game.metrics.trackState(["Waiting", "Scores", "Playing"][state]);
    }

    protected function showComponents (componentClasses :Array) :void
    {
        var toRemove :Array = _components.filter(function (rc :RichComponent, ..._) :Boolean {
            return !componentClasses.some(function (clazz :Class, ..._) :Boolean {
                return rc is clazz;
            });
        });

        var toAdd :Array = componentClasses.filter(function (clazz :Class, ..._) :Boolean {
            return !_components.some(function (rc :RichComponent, ..._) :Boolean {
                return rc is clazz;
            });
        });

        toRemove.forEach(F.adapt(removeComponent));

        toAdd.forEach(function (clazz :Class, ..._) :void {
            addComponent(RichComponent(new clazz(_model)));
        });
    }

    protected function addComponent (component :RichComponent) :void
    {
        addChild(component);
        _components.push(component);

        component.transitionIn();
    }

    protected function removeComponent (component :RichComponent) :void
    {
        _components.splice(_components.indexOf(component), 1);

        var tweenOut :GTweeny = component.transitionOut();
        if (tweenOut != null) {
            tweenOut.addEventListener(Event.COMPLETE, F.callback(removeChild, component));
        } else {
            removeChild(component);
        }
    }

    [Embed(source="../../../res/style.css", mimeType="application/octet-stream")]
    protected static const STYLE :Class;

    protected var _model :DanceModel;

    protected var _components :Array = []; // of RichComponent
}

}
