package dance.data {

import flash.events.Event;
import flash.events.EventDispatcher;

/** A rolling window (cursor) handy for keeping track of scrolling notes. */
public class Window extends EventDispatcher
{
    public static const ROLL_IN :String = "RollIn"; // Event
    public static const ROLL_OUT :String = "RollOut"; // Event

    public function Window (track :Track, sizeLeft :Number, sizeRight :Number) :void
    {
        _track = track;
        _sizeLeft = sizeLeft;
        _sizeRight = sizeRight;
    }

    public function get nextRollOut () :int
    {
        return _minId;
    }

    public function get lastRollIn () :int
    {
        return _maxId;
    }

    public function advanceTo (nowBeat :Number) :void
    {
//        if (hasEventListener(ROLL_OUT)) {
            var minBeat :Number = nowBeat - _sizeLeft;
            while (_track.beats[_minId] < minBeat) {
                dispatchEvent(new Event(ROLL_OUT));
                _minId += 1;
            }
//        }

        if (hasEventListener(ROLL_IN)) {
            var maxBeat :Number = nowBeat + _sizeRight;
            while (_track.beats[_maxId] < maxBeat) {
                dispatchEvent(new Event(ROLL_IN));
                _maxId += 1;
            }
        }
    }

    protected var _track :Track;
    protected var _sizeLeft :Number;
    protected var _sizeRight :Number;

    protected var _minId :int;
    protected var _maxId :int;

}

}
