package dance.client {

import flash.events.Event;
import flash.events.EventDispatcher;

import com.threerings.util.Set;
import com.threerings.util.Sets;
import com.threerings.util.ValueEvent;

import aduros.util.EncryptedInt;
import aduros.util.F;

import dance.data.Codes;
import dance.data.Note;
import dance.data.Song;
import dance.data.Track;
import dance.data.Window;

public class DanceModel extends EventDispatcher
{
    public static const DANCE_STARTED :String = "DanceStarted";
    public static const DANCE_ENDED :String = "DanceEnded";

    public static const SCORE_UPDATED :String = "ScoreUpdated";
    public static const COMBO_UPDATED :String = "ComboUpdated";
    public static const LIFE_UPDATED :String = "LifeUpdated";

    public static const BOO :String = "Boo";

    /** The intervals in milliseconds for Miss/OK/Good/Perfect. */
    public static const TIER_TIMES :Array = [ 150, 100, 50 ]; // TODO: Tweak

    /** Millisecs of silence prepended to each song. */
    public static const INITIAL_SILENCE :int = 3000;

    public var startedOn :int;

    public var difficulty :int;

    public function get song () :Song
    {
        return _song;
    }

    public function get nowBeat () :Number
    {
        return _nowBeat;
    }

    public function get track () :Track
    {
        return _track;
    }

    public function play (song :Song) :void
    {
        stop();

        _song = song;
        _removedNotes.clear();
        _score.value = 0;
        _combo.value = 0;
        _life = 0.8;
        _tiersInBeats = F.map(_song.timeToBeats, TIER_TIMES);

        _track = _song.tracks[difficulty];
        _endBeat = _track.beats[_track.beats.length-1] + _song.timeToBeats(3000);

        _window = new Window(_track, _tiersInBeats[0], 0);
        _window.addEventListener(Window.ROLL_OUT, onRollOut);

        nowTime = startedOn;

        dispatchEvent(new Event(DANCE_STARTED));
    }

    public function stop (status :int = 0) :void
    {
        if (_song != null) {
            dispatchEvent(new ValueEvent(DANCE_ENDED, status));
        }

        _song = null;
        _window = null;
        _track = null;
    }

    public function get nowTime () :uint
    {
        return _nowTime;
    }

    public function set nowTime (time :uint) :void
    {
        _nowTime = time;
        _nowBeat = _song.timeToBeats(_nowTime - startedOn + _song.offset - DanceModel.INITIAL_SILENCE);

        if (_nowBeat > _endBeat) {
            stop(Codes.STOP_COMPLETE);
            return;
        }

        _window.advanceTo(_nowBeat);
    }

    protected function onRollOut (event :Event) :void
    {
        var noteId :int = _window.nextRollOut;
        var note :Note = _track.notes[noteId];

        if (_removedNotes.add(noteId)) {
            if (note.type == Note.TYPE_HOLD_BEGIN) {
                var tail :int = _track.findHoldTail(noteId);

                _removedNotes.add(tail); // Also remove the tail note
                dispatchEvent(new HoldEvent(HoldEvent.HOLD_MISSED, noteId, tail));

            } else if (note.type == Note.TYPE_HOLD_END) {
                var head :int = _track.findHoldHead(noteId);
                dispatchEvent(new HoldEvent(HoldEvent.HOLD_COMPLETE, head, noteId));

            } else if (note.type == Note.TYPE_TAP) {
                dispatchEvent(new NoteEvent(NoteEvent.NOTE_MISSED, noteId));
            }
        }
    }

    public function get score () :int
    {
        return _score.value;
    }

    public function set score (score :int) :void
    {
        _score.value = score;
        dispatchEvent(new Event(SCORE_UPDATED));
    }

    public function get combo () :int
    {
        return _combo.value;
    }

    public function set combo (combo :int) :void
    {
        _combo.value = combo;
        dispatchEvent(new Event(COMBO_UPDATED));
    }

    public function get life () :Number
    {
        return _life;
    }

    public function set life (life :Number) :void
    {
        var newLife :Number = Math.max(0, Math.min(life, 1));
        if (newLife != _life) {
            _life = newLife;
            dispatchEvent(new Event(LIFE_UPDATED));
        }
    }

    public function get multiplier () :int
    {
        return getMultiplier(combo);
    }

    public static function getMultiplier (combo :int) :int
    {
        return Math.min(combo/10 + 1, 4);
    }

    public function stepOn (pad :int) :void
    {
        for (var noteId :int = _window.nextRollOut;
            noteId < _track.beats.length && _track.beats[noteId] < _nowBeat+_tiersInBeats[0]; ++noteId) {

            var note :Note = _track.notes[noteId];

            if (note.pad == pad && _removedNotes.add(noteId)) {

                if (note.type == Note.TYPE_TAP) {
                    var offsetBeats :Number = Math.abs(_track.beats[noteId] - _nowBeat);

                    // Find the tier of this tap
                    for (var tier :int = 1; tier < _tiersInBeats.length; ++tier) {
                        if (offsetBeats > _tiersInBeats[tier]) {
                            break;
                        }
                    }
                    dispatchEvent(new NoteHitEvent(NoteHitEvent.NOTE_HIT, noteId, tier-1));

                } else if (note.type == Note.TYPE_HOLD_BEGIN) {
                    dispatchEvent(new HoldEvent(HoldEvent.HOLD_STARTED, noteId, _track.findHoldTail(noteId)));

                } else if (note.type == Note.TYPE_MINE) {
                    continue; // Not currently supported, ignore
                }

                return;
            }
        }

        dispatchEvent(new Event(BOO));
    }

    public function stepOff (pad :int) :void
    {
        for (var noteId :int = _window.nextRollOut; noteId < _track.beats.length; ++noteId) {

            var note :Note = _track.notes[noteId];

            if (note.pad == pad && !_removedNotes.contains(noteId)) {
                var head :int = _track.findHoldHead(noteId);
                if (head != -1) {
                    var tail :int = _track.findHoldTail(head);

                    if (_removedNotes.add(tail)) {
                        if (_track.beats[tail] - _nowBeat <= _tiersInBeats[0]) {
                            dispatchEvent(new HoldEvent(HoldEvent.HOLD_COMPLETE, head, tail));
                        } else {
                            dispatchEvent(new HoldEvent(HoldEvent.HOLD_FAILED, head, tail));
                        }
                    }
                }

                return;
            }
        }
    }

    protected var _song :Song;
    protected var _startedOn :uint;

    protected var _track :Track;
    protected var _window :Window;

    protected var _nowTime :uint;
    protected var _nowBeat :Number;

    /** The beat that this song should end. */
    protected var _endBeat :Number;

    protected var _score :EncryptedInt = new EncryptedInt();
    protected var _combo :EncryptedInt = new EncryptedInt();
    protected var _life :Number;

    /** Set of notes that are no longer in play, because they've been missed or hit. */
    protected var _removedNotes :Set = Sets.newSetOf(int);

    protected var _tiersInBeats :Array;
}

}
