package dance.data {

import flash.utils.ByteArray;

/** A complete score summary sent to server at the completion of a song. */
public class ScoreSummary
{
    public var finalScore :int;
    public var bestCombo :int;
    public var tiersHit :Array;
    public var holds :int;
    public var boos :int;
    public var misses :int;

    /**
     * Number [0,1] indicating overall pat on the back.
     * Supposed to be kind of a score comparable across different songs (but not difficulties).
     */
    public var accuracy :Number;

    /** Some scoring value, comparable across different songs AND difficulties. */
    public var rating :Number;

    public function toBytes () :ByteArray
    {
        var ba :ByteArray = new ByteArray();

        ba.writeInt(finalScore);
        ba.writeInt(bestCombo);
        ba.writeObject(tiersHit);
        ba.writeInt(holds);
        ba.writeInt(boos);
        ba.writeInt(misses);
        ba.writeDouble(accuracy);
        ba.writeDouble(rating);

        return ba;
    }

    public static function fromBytes (ba :ByteArray) :ScoreSummary
    {
        var summary :ScoreSummary = new ScoreSummary();

        summary.finalScore = ba.readInt();
        summary.bestCombo = ba.readInt();
        summary.tiersHit = ba.readObject();
        summary.holds = ba.readInt();
        summary.boos = ba.readInt();
        summary.misses = ba.readInt();
        summary.accuracy = ba.readDouble();
        summary.rating = ba.readDouble();

        return summary;
    }
}

}
