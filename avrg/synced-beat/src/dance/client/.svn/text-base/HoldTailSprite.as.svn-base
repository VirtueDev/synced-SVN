package dance.client {

import flash.display.Bitmap;
import flash.display.BitmapData;
import flash.display.PixelSnapping;
import flash.display.Sprite;
import flash.geom.Matrix;
import flash.geom.Point;
import flash.geom.Rectangle;

public class HoldTailSprite extends Sprite
{
    public function HoldTailSprite (pad :int, length :Number)
    {
        _pad = pad;
        _length = length;

        setActive(false);
    }

    public function setActive (active :Boolean) :void
    {
        var bodies :Array;
        var caps :Array;

        if (active) {
            bodies = BODIES_ACTIVE;
            caps = CAPS_ACTIVE;
        } else {
            bodies = BODIES_INACTIVE;
            caps = CAPS_INACTIVE;
        }

        var end :Number = _length-NoteSprite.SIZE/2;

        var bd :BitmapData = bodies[_pad];

        var matrix :Matrix = new Matrix();
        matrix.translate(0, end-bd.height);

        graphics.clear();
        graphics.beginBitmapFill(bodies[_pad], matrix);
        graphics.drawRect(0, 0, NoteSprite.SIZE, end+1); // An extra pixel to avoid the flicker I'm getting
        graphics.endFill();

        if (_cap != null) {
            removeChild(_cap);
        }
        _cap = new Bitmap(caps[_pad]);
        _cap.y = end;

        addChild(_cap);
    }

    [Embed(source="../../../res/Left Hold Body Active.png")]
    protected static const LEFT_BODY_ACTIVE :Class;
    [Embed(source="../../../res/Left Hold Body Inactive.png")]
    protected static const LEFT_BODY_INACTIVE :Class;
    [Embed(source="../../../res/Left Hold BottomCap Active.png")]
    protected static const LEFT_CAP_ACTIVE :Class;
    [Embed(source="../../../res/Left Hold BottomCap Inactive.png")]
    protected static const LEFT_CAP_INACTIVE :Class;

    [Embed(source="../../../res/Down Hold Body Active.png")]
    protected static const DOWN_BODY_ACTIVE :Class;
    [Embed(source="../../../res/Down Hold Body Inactive.png")]
    protected static const DOWN_BODY_INACTIVE :Class;
    [Embed(source="../../../res/Down Hold BottomCap Active.png")]
    protected static const DOWN_CAP_ACTIVE :Class;
    [Embed(source="../../../res/Down Hold BottomCap Inactive.png")]
    protected static const DOWN_CAP_INACTIVE :Class;

    [Embed(source="../../../res/Up Hold Body Active.png")]
    protected static const UP_BODY_ACTIVE :Class;
    [Embed(source="../../../res/Up Hold Body Inactive.png")]
    protected static const UP_BODY_INACTIVE :Class;
    [Embed(source="../../../res/Up Hold BottomCap Active.png")]
    protected static const UP_CAP_ACTIVE :Class;
    [Embed(source="../../../res/Up Hold BottomCap Inactive.png")]
    protected static const UP_CAP_INACTIVE :Class;

    [Embed(source="../../../res/Right Hold Body Active.png")]
    protected static const RIGHT_BODY_ACTIVE :Class;
    [Embed(source="../../../res/Right Hold Body Inactive.png")]
    protected static const RIGHT_BODY_INACTIVE :Class;
    [Embed(source="../../../res/Right Hold BottomCap Active.png")]
    protected static const RIGHT_CAP_ACTIVE :Class;
    [Embed(source="../../../res/Right Hold BottomCap Inactive.png")]
    protected static const RIGHT_CAP_INACTIVE :Class;

    protected static const BODIES_ACTIVE :Array = [
        new LEFT_BODY_ACTIVE().bitmapData,
        new DOWN_BODY_ACTIVE().bitmapData,
        new UP_BODY_ACTIVE().bitmapData,
        new RIGHT_BODY_ACTIVE().bitmapData,
    ];

    protected static const CAPS_ACTIVE :Array = [
        new LEFT_CAP_ACTIVE().bitmapData,
        new DOWN_CAP_ACTIVE().bitmapData,
        new UP_CAP_ACTIVE().bitmapData,
        new RIGHT_CAP_ACTIVE().bitmapData,
    ];

    protected static const BODIES_INACTIVE :Array = [
        new LEFT_BODY_INACTIVE().bitmapData,
        new DOWN_BODY_INACTIVE().bitmapData,
        new UP_BODY_INACTIVE().bitmapData,
        new RIGHT_BODY_INACTIVE().bitmapData,
    ];

    protected static const CAPS_INACTIVE :Array = [
        new LEFT_CAP_INACTIVE().bitmapData,
        new DOWN_CAP_INACTIVE().bitmapData,
        new UP_CAP_INACTIVE().bitmapData,
        new RIGHT_CAP_INACTIVE().bitmapData,
    ];

    protected var _pad :int;
    protected var _length :Number; // Pixels

    protected var _cap :Bitmap;
}

}
