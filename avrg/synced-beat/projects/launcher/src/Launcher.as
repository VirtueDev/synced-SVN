package {

import flash.display.DisplayObject;
import flash.display.Bitmap;
import flash.display.Sprite;
import flash.events.Event;
import flash.filters.GlowFilter;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;

import com.gskinner.motion.GTweeny;

import com.threerings.text.TextFieldUtil;

import com.whirled.*;

import aduros.util.F;

[SWF(width="169", height="169")]
public class Launcher extends Sprite
{
    public function Launcher ()
    {
        _ctrl = new FurniControl(this);
        _ctrl.addEventListener(ControlEvent.MEMORY_CHANGED, F.adapt(invalidate));

        _api = new WhirledBeatAPI(_ctrl);
        _api.addEventListener(WhirledBeatAPI.DANCE_STARTED, onDanceStarted);
        _api.addEventListener(WhirledBeatAPI.DANCE_ENDED, onDanceEnded);

        var icon :DisplayObject = new ICON();
        icon.x = -icon.width/2;
        icon.scaleY = -1; // Flip vertically
        icon.y = icon.height/2;
        _content = new Sprite();
        _content.x = icon.width/2;
        _content.y = icon.height/2;
        _content.addChild(icon);

        addChild(_content);

        _label = TextFieldUtil.createField("",
            { embedFonts: true, selectable: false, multiline: true,
                 autoSize: TextFieldAutoSize.LEFT, outlineColor: 0},
            { font: "dance", size: 24, color: 0xffffff});
        _label.y = 169*0.7;
        addChild(_label);

        if (_ctrl.getEnvironment() == EntityControl.ENV_ROOM) {
            invalidate();
        } else {
            // Show a demo
            startPulsing(117);
            setText("Michael Jackson\nBillie Jean");
        }
    }

    public function onDanceStarted (event :Event) :void
    {
//        if (_ctrl.hasControl()) {
//            // Send it over the wire so people not in the game can see
//            _ctrl.setMemory("song", {
//                title: _api.song.title,
//                artist: _api.song.artist,
//                bpm: _api.song.bpm
//            });
//        }
        invalidate();
    }

    public function onDanceEnded (event :Event) :void
    {
//        if (_ctrl.hasControl()) {
//            _ctrl.setMemory("song", null);
//        }
        invalidate();
    }

    protected function invalidate () :void
    {
        //var song :Object = _ctrl.getMemory("song");
        var song :Object = _api.song;
        if (song != null) {
            startPulsing(song.bpm);
            setText(song.artist + "\n" + song.title);
        } else {
            stopPulsing();
            setText("");
        }
    }

    protected function startPulsing (bpm :int) :void
    {
        stopPulsing();

        _content.scaleX = _content.scaleY = 1.2;
        _tween = new GTweeny(_content, 60/bpm, {scaleX: 1, scaleY: 1}, {repeat: -1});
        //_content.filters = [ new GlowFilter(0xffffff, 1, 32, 32, 4) ];
    }

    protected function stopPulsing () :void
    {
        if (_tween != null) {
            _tween.pause();
            _tween = null;
            _content.scaleX = _content.scaleY = 1;
            //_content.filters = null;
        }
    }

    protected function setText (text :String) :void
    {
        _label.text = text;
    }

    [Embed(source="../res/arrow.svg")]
    protected static const ICON :Class; 

    [Embed(source="../../../res/elements.ttf", fontFamily="dance")]
    public static const DANCE_FONT :Class;

    protected var _content :Sprite;
    protected var _tween :GTweeny;
    protected var _label :TextField;

    protected var _ctrl :FurniControl;
    protected var _api :WhirledBeatAPI;
}

}
