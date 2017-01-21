package dance.data {

import flash.utils.ByteArray;

/** Periodically sent to the server and distributed to all clients. */
public class ScoreCard
{
    public var score :int;
    public var combo :int;

    public function toBytes () :ByteArray
    {
        var ba :ByteArray = new ByteArray();

        ba.writeInt(score);
        ba.writeInt(combo);

        return ba;
    }

    public static function fromBytes (ba :ByteArray) :ScoreCard
    {
        var card :ScoreCard = new ScoreCard();

        card.score = ba.readInt();
        card.combo = ba.readInt();

//        ba.position = 0; // Be kind, rewind

        return card;
    }
}

}
