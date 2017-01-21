package dance.data {

import flash.utils.ByteArray;

/** Broadcasted to clients when all the players have finished dancing. */
public class DanceResults
{
    public var current :Array; // of ScoreRecord
    public var dailyBest :Array; // of ScoreRecord
    public var monthlyBest :Array; // of ScoreRecord

    public function toBytes () :ByteArray
    {
        var ba :ByteArray = new ByteArray();

        ba.writeInt(current.length);
        for each (var record :ScoreRecord in current) {
            ba.writeBytes(record.toBytes());
        }
        ba.writeInt(dailyBest.length);
        for each (record in dailyBest) {
            ba.writeBytes(record.toBytes());
        }
        ba.writeInt(monthlyBest.length);
        for each (record in monthlyBest) {
            ba.writeBytes(record.toBytes());
        }

        return ba;
    }

    public static function fromBytes (ba :ByteArray) :DanceResults
    {
        var result :DanceResults = new DanceResults();

        result.current = new Array(ba.readInt());
        for (var ii :int = 0; ii < result.current.length; ++ii) {
            result.current[ii] = ScoreRecord.fromBytes(ba);
        }
        result.dailyBest = new Array(ba.readInt());
        for (ii = 0; ii < result.dailyBest.length; ++ii) {
            result.dailyBest[ii] = ScoreRecord.fromBytes(ba);
        }
        result.monthlyBest = new Array(ba.readInt());
        for (ii = 0; ii < result.monthlyBest.length; ++ii) {
            result.monthlyBest[ii] = ScoreRecord.fromBytes(ba);
        }

        return result;
    }
}

}
