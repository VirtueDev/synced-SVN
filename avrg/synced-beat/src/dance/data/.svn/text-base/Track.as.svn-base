package dance.data {

import flash.utils.ByteArray;

public class Track
{
    /**
     * The sorted beat values that each note in this track occurs at. The index of a note in this
     * array is its note ID.
     */
    public var beats :Array = []; // of Number

    /** Maps note ID to Note. */
    public var notes :Array = []; // of Note

    /** The difficulty level, in number of foots, usually 1-10. */
    public var level :int;

//    /**
//     * Returns the first note on or after the beat. Will return the length of the array if
//     * the beat is after the last note.
//     */
//    public function getNoteAfter (targetBeat :Number) :int
//    {
//        var low :int = 0;
//        var high :int = beats.length;
//
//        while (low < high) {
//            var mid :int = (low+high)/2;
//            var beat :Number = beats[mid];
//
//            if (beat < targetBeat) {
//                low = mid + 1;
//            } else if (beat > targetBeat) {
//                high = mid;
//            } else {
//                return mid;
//            }
//        }
//
//        return high;
//    }
//
//    public function getNotesBetween (from :Number, to :Number) :Array
//    {
//        var result :Array = [];
//
//        for (var ii :int = getNoteAfter(from); ii < beats.length && beats[ii] < to; ++ii) {
//            result.push(ii);
//        }
//
//        return result;
//    }

    /** Seek forward to the first matching end hold for a note. */
    public function findHoldTail (head :int) :int
        // throws Error
    {
        var pad :int = Note(notes[head]).pad;

        for (var tail :int = head+1; tail < notes.length; ++tail) {
            if (notes[tail].pad == pad) {
                return (notes[tail].type == Note.TYPE_HOLD_END) ? tail : -1;
            }
        }

        return -1;
    }

    /** Seek backward to the first matching start hold for a note. */
    public function findHoldHead (tail :int) :int
        // throws Error
    {
        var pad :int = Note(notes[tail]).pad;

        for (var head :int = tail-1; tail >= 0; --head) {
            if (notes[head].pad == pad) {
                return (notes[head].type == Note.TYPE_HOLD_BEGIN) ? head : -1;
            }
        }

        return -1;
    }
}

}
