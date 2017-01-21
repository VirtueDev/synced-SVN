package dance.client {

import flash.display.Bitmap;
import flash.display.BitmapData;
import flash.display.PixelSnapping;
import flash.display.Sprite;
import flash.events.Event;
import flash.geom.Matrix;
import flash.geom.Point;
import flash.geom.Rectangle;
import flash.utils.setTimeout;
import flash.utils.clearInterval;
import flash.utils.setInterval;

import com.gskinner.motion.GTweeny;
import mx.effects.easing.Exponential;

import aduros.display.ToolTipManager;

import dance.data.Song;
import dance.data.Track;
import dance.data.Note;

public class Receptor extends Sprite
{
    public function Receptor (model :DanceModel)
    {
        _model = model;

        for (var ii :int = 0; ii < 4; ++ii) {
            var bmp :Bitmap = new Bitmap(_frames[0], PixelSnapping.ALWAYS);

            var matrix :Matrix = new Matrix();
            matrix.translate(-NoteSprite.SIZE/2, -NoteSprite.SIZE/2);
            matrix.rotate(NoteSprite.ROTATIONS[ii]*Math.PI/180);
            matrix.translate(ii*NoteSprite.SIZE + NoteSprite.SIZE/2, 0);

            bmp.transform.matrix = matrix;

            addChild(bmp);
            _receptors.push(bmp);
        }

        addEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
        addEventListener(Event.REMOVED_FROM_STAGE, onRemovedFromStage);
    }

    protected function onAddedToStage (event :Event) :void
    {
        var beatsUntilNextBeat :Number = Math.ceil(_model.nowBeat) - _model.nowBeat;
        var timeUntilNextBeat :Number = _model.song.beatsToTime(beatsUntilNextBeat);

        setTimeout(startPulsing, timeUntilNextBeat);
    }

    protected function onRemovedFromStage (event :Event) :void
    {
        clearInterval(_interval);
    }

    protected function startPulsing () :void
    {
        var timePerBeat :Number = _model.song.beatsToTime(1);
        var timeOn :Number = timePerBeat * (1/8);
        var timeOff :Number = timePerBeat - timeOn;

        setFrame(2);

        _interval = setInterval(function () :void {
            setFrame(1);
            setTimeout(setFrame, timeOn, 2);
        }, timePerBeat);
    }

    protected function setFrame (frame :int) :void
    {
        for each (var bmp :Bitmap in _receptors) {
            bmp.bitmapData = _frames[frame];
        }
    }

    protected static function generateFrames () :Array
    {
        var source :BitmapData = Bitmap(new RECEPTOR_SPRITES()).bitmapData;
        var frames :Array = new Array(3);

        for (var ii :int = 0; ii < frames.length; ++ii) {
            var bd :BitmapData = new BitmapData(NoteSprite.SIZE, NoteSprite.SIZE);
            bd.copyPixels(source,
                new Rectangle(ii*NoteSprite.SIZE, 0, NoteSprite.SIZE, NoteSprite.SIZE), new Point(0, 0));

            frames[ii] = bd;
        }

        return frames;
    }

    protected var _model :DanceModel;

    protected var _receptors :Array = [];

    protected var _interval :uint;

    [Embed(source="../../../res/receptors.png")]
    protected static const RECEPTOR_SPRITES :Class;

    protected static const _frames :Array = generateFrames();
}

}
