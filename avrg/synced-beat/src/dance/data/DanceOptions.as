package dance.data {

import flash.utils.ByteArray;

/** Sent to the server once on dance started and distributed to clients. */
public class DanceOptions
{
    public var difficulty :int;

    /** Player's level, to show off. */
    public var level :int;

    public function toBytes () :ByteArray
    {
        var ba :ByteArray = new ByteArray();

        ba.writeByte(difficulty);
        ba.writeInt(level);

        return ba;
    }

    public static function fromBytes (ba :ByteArray) :DanceOptions
    {
        var opts :DanceOptions = new DanceOptions();

        opts.difficulty = ba.readByte();
        opts.level = ba.readInt();

        return opts;
    }
}

}
