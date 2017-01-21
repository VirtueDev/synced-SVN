package {

import flash.display.Sprite;
import flash.events.Event;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;

import com.threerings.text.TextFieldUtil;

import com.whirled.*;

[SWF(width="161", height="170")]
public class Rooster extends Sprite
{
    public function Rooster ()
    {
        _ctrl = new AvatarControl(this);

        _api = new WhirledBeatAPI(_ctrl);
        _api.addEventListener(WhirledBeatAPI.AVATAR_LEVEL_CHANGED, onAvatarLevelChanged);
        _api.addEventListener(WhirledBeatAPI.DANCE_STARTED, onDanceStarted);
        _api.addEventListener(WhirledBeatAPI.DANCE_ENDED, onDanceEnded);

        _label = TextFieldUtil.createField("",
            { embedFonts: true, selectable: false, multiline: true,
                 autoSize: TextFieldAutoSize.LEFT, outlineColor: 0},
            { font: "dance", size: 24, color: 0xffff00 });
        _label.y = 170*0.7;

        addChild(new ROOSTER());
        addChild(_label);

        invalidate();
    }

    public function onDanceStarted (event :Event) :void
    {
        // Do nothing
    }

    public function onDanceEnded (event :Event) :void
    {
        // Do nothing
    }

    public function onAvatarLevelChanged (event :Event) :void
    {
        invalidate();
    }

    public function invalidate () :void
    {
        _label.text = "Level " + _api.avatarLevel;
    }

    [Embed(source="../res/rooster.jpg")]
    protected static const ROOSTER :Class; 

    [Embed(source="../../../res/elements.ttf", fontFamily="dance")]
    public static const DANCE_FONT :Class;

    protected var _label :TextField;
    protected var _ctrl :AvatarControl;
    protected var _api :WhirledBeatAPI;
}

}
