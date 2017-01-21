package dance.client {

import flash.display.Bitmap;
import flash.display.BitmapData;
import flash.display.PixelSnapping;
import flash.display.Sprite;
import flash.geom.Point;
import flash.geom.Rectangle;

public class NoteSprite extends Sprite
{
    /** Square pixel dimensions of a single note. */
    public static const SIZE :int = 64;

    /** Maps pad number to arrow rotation degrees. */
    public static const ROTATIONS :Array = [ 90, 0, 180, 270 ];

    public function NoteSprite (pad :int, tint :int)
    {
        if (tint >= _tints.length) {
            Game.log.error("Tint not available!", "tint", tint);
        }

        var bitmap :Bitmap = new Bitmap(BitmapData(_tints[tint]), PixelSnapping.ALWAYS);

        // Center it on this sprite
        bitmap.x = -SIZE/2;
        bitmap.y = -SIZE/2;

        this.rotation = ROTATIONS[pad];
        //this.cacheAsBitmap = true;

        addChild(bitmap);
    }

    public static function generateTints () :Array
    {
        var colors :Array = [
            0xff0000,
            0x0000ff,
            0x662d91,
            0xffff00,
            0xff00ff,
            0xf7941d,
            0x00ffff,
            0x00c600,
            0x848484,
        ].reverse();
        var base :BitmapData = new NOTES_BASE().bitmapData;

        return colors.map(function (color :int, ..._) :BitmapData {

            var bd :BitmapData = new BitmapData(SIZE, SIZE);
            bd.threshold(base, new Rectangle(0, 0, SIZE, SIZE), new Point(0, 0), "==",
                0xff0000, 0xff000000 | color, 0xffffff, true);

            return bd;
        });
    }

    [Embed(source="../../../res/notes_red.png")]
    protected static const NOTES_BASE :Class;

    protected static var _tints :Array = generateTints();
}

}
