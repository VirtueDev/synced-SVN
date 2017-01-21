package dance.data {

import flash.utils.ByteArray;

/** Persisted server-side and also shipped to the client. */
public class ScoreRecord
{
    public var version :int = 0;
    public var createdOn :Date = new Date();

    public var playerId :int;
    public var name :String;

    /** Two letter country code this player logged from. */
    public var country :String;

    public var opts :DanceOptions;
    public var summary :ScoreSummary;

    public function toBytes () :ByteArray
    {
        var ba :ByteArray = new ByteArray();

        ba.writeInt(version);
        ba.writeDouble(createdOn.getTime());
        ba.writeInt(playerId);
        ba.writeUTF(name);
        ba.writeObject(country);
        ba.writeBytes(opts.toBytes());
        ba.writeBytes(summary.toBytes());

        return ba;
    }

    public static function fromBytes (ba :ByteArray) :ScoreRecord
    {
        var record :ScoreRecord = new ScoreRecord();

        record.version = ba.readInt();
        record.createdOn.setTime(ba.readDouble());
        record.playerId = ba.readInt();
        record.name = ba.readUTF();
        record.country = ba.readObject();
        record.opts = DanceOptions.fromBytes(ba);
        record.summary = ScoreSummary.fromBytes(ba);

        return record;
    }

    public static function compare (left :ScoreRecord, right :ScoreRecord) :int
    {
        if (left.summary.finalScore > right.summary.finalScore) {
            return -1;
        } else if (left.summary.finalScore < right.summary.finalScore) {
            return 1;
        } else {
            return 0;
        }
    }
}

}
