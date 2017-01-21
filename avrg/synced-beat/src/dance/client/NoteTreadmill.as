package dance.client {

import flash.display.Bitmap;
import flash.display.Sprite;
import flash.events.Event;
import flash.filters.ColorMatrixFilter;
import flash.utils.ByteArray;
import flash.utils.Dictionary;
import flash.utils.getTimer;

import com.gskinner.motion.GTweeny;
import mx.effects.easing.Exponential;

import com.threerings.util.Command;
import com.threerings.util.MethodQueue;
import com.threerings.util.ValueEvent;

import com.whirled.*;
import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.display.ImageButton;
import aduros.display.ToolTipManager;

import dance.data.Song;
import dance.data.Track;
import dance.data.Note;
import dance.data.Window;

public class NoteTreadmill extends Component
{
    public static const LOOKAHEAD_PIXELS :int = 400;
    public static const LOOKBEHIND_PIXELS :int = 50;

    public static const WIDTH :int = 4*NoteSprite.SIZE;

    public function NoteTreadmill (model :DanceModel)
    {
        _model = model;

        registerListener(model, NoteEvent.NOTE_MISSED, onNoteMissed);
        registerListener(model, NoteHitEvent.NOTE_HIT, onNoteHit);
        registerListener(model, HoldEvent.HOLD_MISSED, onHoldMissed);
        registerListener(model, HoldEvent.HOLD_STARTED, onHoldStarted);
        registerListener(model, HoldEvent.HOLD_COMPLETE, onHoldComplete);
        registerListener(model, HoldEvent.HOLD_FAILED, onHoldFailed);
        registerListener(this, Event.ENTER_FRAME, onEnterFrame);

        _window = new Window(_model.track, LOOKBEHIND_BEATS, LOOKAHEAD_BEATS);
        _window.addEventListener(Window.ROLL_IN, onRollIn);
        _window.addEventListener(Window.ROLL_OUT, onRollOut);

        graphics.lineStyle(2, 0x00ff00);
        graphics.moveTo(0, LOOKAHEAD_PIXELS+LOOKBEHIND_PIXELS);
        graphics.lineTo(NoteSprite.SIZE*4, LOOKAHEAD_PIXELS+LOOKBEHIND_PIXELS);

        var conveyorMask :Sprite = new Sprite();
        conveyorMask.graphics.beginFill(0);
        conveyorMask.graphics.drawRect(0, 0, NoteSprite.SIZE*4, LOOKAHEAD_PIXELS+LOOKBEHIND_PIXELS);
        conveyorMask.graphics.endFill();

        _conveyor = new Sprite();
        _conveyor.mask = conveyorMask;
        addChild(_conveyor);
        addChild(conveyorMask);

        var holdsMask :Sprite = new Sprite();
        holdsMask.graphics.beginFill(0);
        holdsMask.graphics.drawRect(0, LOOKBEHIND_PIXELS, NoteSprite.SIZE*4, LOOKAHEAD_PIXELS);
        holdsMask.graphics.endFill();

        _holds = new Sprite();
        _holds.mask = holdsMask;
        _conveyor.addChild(_holds);
        addChild(holdsMask);
    }

    protected function onRollIn (event :Event) :void
    {
        var noteId :int = _window.lastRollIn;
        var track :Track = _model.track;
        var note :Note = track.notes[noteId];

        // We don't care about end holds, they're already added to the display when we
        // find a start hold
        if (note.type == Note.TYPE_HOLD_END) {
            return;
        }

        var beat :Number = track.beats[noteId];
        var frac :Number = beat - int(beat);
        var tint :int;

        if (frac != 0) {
            for (tint = 0; tint < BEAT_TINTS.length-1; ++tint) {
                var x :Number = BEAT_TINTS[tint]/frac;
                // If frac divides evenly into x
                if (Math.abs(Math.round(x)-x) < 0.00001) {
                    break;
                }
            }
        } else {
            tint = BEAT_TINTS.length-1;
        }
//        Game.log.info("Adding note", "beat", beat, "frac", frac, "tint", tint);
//        if (tint < 0) {
//            Game.log.warning("Oh snap, missing tint?", "beat", beat);
//            tint = BEAT_TINTS.length-1; // Fallback
//        }

        var sprite :Sprite = new NoteSprite(note.pad, tint);
        sprite.x = NoteSprite.SIZE*note.pad + NoteSprite.SIZE/2;
        sprite.y = NoteSprite.SIZE*track.beats[noteId];

        // Add the corresponding end note
        if (note.type == Note.TYPE_HOLD_BEGIN) {
            var tailId :int = track.findHoldTail(noteId);
            var length :Number = track.beats[tailId] - track.beats[noteId];

            var tail :Sprite = new HoldTailSprite(note.pad, NoteSprite.SIZE*length);
            tail.x = sprite.x - NoteSprite.SIZE/2;
            tail.y = sprite.y;

            _conveyor.addChild(tail);
            _noteSprites[tailId] = tail;
        }

        _conveyor.addChild(sprite);
        _noteSprites[noteId] = sprite;
    }

    protected function onRollOut (event :Event) :void
    {
        removeFromConveyor(_window.nextRollOut);
    }

    protected function onNoteMissed (event :NoteEvent) :void
    {
        var track :Track = _model.track;
        var note :Note = track.notes[event.noteId];

        dimNote(event.noteId);
        hideExplosion(note.pad);
    }

    protected function onNoteHit (event :NoteHitEvent) :void
    {
        var track :Track = _model.track;
        var note :Note = track.notes[event.noteId];

        removeFromConveyor(event.noteId);
        showExplosion(note.pad);
    }

    protected function showExplosion (pad :int) :void
    {
        if (pad in _explosions) {
            _explosions[pad].beginning();
        } else {
            var explosion :Bitmap = new EXPLOSION();
            explosion.x = NoteSprite.SIZE*pad + NoteSprite.SIZE/2 - explosion.width/2;
            explosion.y = LOOKBEHIND_PIXELS - explosion.height/2;
            addChild(explosion);

            var tween :GTweeny = new GTweeny(explosion, 0.2, {alpha: 0.4, ease: Exponential.easeIn});
            tween.addEventListener(Event.COMPLETE, function (event :Event) :void {
                removeChild(explosion);
                delete _explosions[pad];
            });
            _explosions[pad] = tween;
        }
        // TODO: Tint explosion
    }

    protected function hideExplosion (pad :int) :void
    {
        if (pad in _explosions) {
            var tween :GTweeny = _explosions[pad];
            tween.setPosition(tween.duration, false);
        }
    }

    protected function onHoldMissed (event :HoldEvent) :void
    {
        var track :Track = _model.track;
        var note :Note = track.notes[event.head];
        hideExplosion(note.pad);

        dimNote(event.head);
        dimNote(event.tail);
    }

    protected function onHoldStarted (event :HoldEvent) :void
    {
        removeFromConveyor(event.head);

        var tail :HoldTailSprite = HoldTailSprite(removeFromConveyor(event.tail));
        if (tail != null) {
            _holds.addChild(tail);
            _noteSprites[event.tail] = tail;
            tail.setActive(true);
        }
    }

    protected function onHoldComplete (event :HoldEvent) :void
    {
        var track :Track = _model.track;
        var note :Note = track.notes[event.tail];
        showExplosion(note.pad);

        removeFromConveyor(event.tail);
    }

    protected function onHoldFailed (event :HoldEvent) :void
    {
        // Remove it from the masked holds part of the conveyor and add it unmasked and dimmed
        var tail :HoldTailSprite = HoldTailSprite(removeFromConveyor(event.tail));

        if (tail != null) {
            _conveyor.addChild(tail);
            _noteSprites[event.tail] = tail;
            dimNote(event.tail);

            var track :Track = _model.track;
            var note :Note = track.notes[event.head];
            hideExplosion(note.pad);
        }
    }

    protected function onEnterFrame (event :Event) :void
    {
        // This frame handler is still called while PlayingPanel is transitioning out and there is no song loaded
        if (_model.song == null) {
            return;
        }

        // Yeah yeah, the view is controlling the model. For performance reasons I'd rather keep the
        // number of frame handlers down.
        _model.nowTime = getTimer();

        // If this update has ended the song
        if (_model.song == null) {
            return;
        }

        var nowBeat :Number = _model.nowBeat;

        _conveyor.y = -NoteSprite.SIZE*nowBeat + LOOKBEHIND_PIXELS;
        _window.advanceTo(nowBeat);
    }

    protected function dimNote (noteId :int) :void
    {
        if (noteId in _noteSprites) {
            _noteSprites[noteId].filters = DESATURATE;
        }
    }

    protected function removeFromConveyor (noteId :int) :Sprite
    {
        var sprite :Sprite = Sprite(_noteSprites[noteId]);

        if (sprite != null) {
            sprite.parent.removeChild(sprite);
            delete _noteSprites[noteId];
        }

        return sprite;
    }

    [Embed(source="../../../res/Down Tap Explosion Bright.png")]
    protected static const EXPLOSION :Class;

    /** Filter to apply to missed notes. */
    protected static const DESATURATE :Array = [ new ColorMatrixFilter([
        1/3, 1/3, 1/3, 0, 0,
        1/3, 1/3, 1/3, 0, 0,
        1/3, 1/3, 1/3, 0, 0,
          0,   0,   0, 1, 0
    ]) ];

    protected static const LOOKAHEAD_BEATS :Number = (LOOKAHEAD_PIXELS + NoteSprite.SIZE/2) / NoteSprite.SIZE;
    protected static const LOOKBEHIND_BEATS :Number  = (LOOKBEHIND_PIXELS + NoteSprite.SIZE/2) / NoteSprite.SIZE;

    protected static const BEAT_TINTS :Array = [4,8,12,16,24,32,48,64,192].reverse().map(function (x :Number, ..._) :Number {
        return 1 - 4*1/x;
    });

    protected var _model :DanceModel;
    protected var _window :Window;

    protected var _conveyor :Sprite;
    protected var _holds :Sprite;
    protected var _explosions :Dictionary = new Dictionary(); // pad -> GTweeny

    protected var _noteSprites :Dictionary = new Dictionary();
}

}
