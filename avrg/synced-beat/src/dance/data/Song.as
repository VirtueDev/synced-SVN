package dance.data {

import flash.utils.ByteArray;
import flash.utils.Dictionary;

public class Song
{
    public var title :String;
    public var artist :String;
    public var credit :String;
    public var offset :Number; // in milliseconds
    public var bpm :Number; // TODO: Support multiple BPMs

    /** The pack://ident or http://web address this song came from, filled in by SongFactory. */
    public var url :String;

    /** The player who uploaded this song. */
    public var uploaderId :int;

    // This will probably never be supported
    //public var stops :Array;

    /** Maps difficulty level to Track. */
    public var tracks :Dictionary; // int -> Track

    public function timeToBeats (time :Number) :Number
    {
        return (bpm*time) / (60*1000)
    }

    public function beatsToTime (beats :Number) :Number
    {
        return (60*1000) * (beats/bpm);
    }

    public static function fromBytes (ba :ByteArray) :Song
    {
        var song :Song = new Song();
        var version :int = ba.readByte();

        song.title = ba.readUTF();
        song.artist = ba.readUTF();
        song.credit = ba.readUTF();
        song.offset = ba.readFloat();
        song.bpm = ba.readFloat();

        if (version >= 1) {
            song.uploaderId = ba.readInt();
        }

        song.tracks = new Dictionary();

        while (ba.bytesAvailable > 0) {
            var difficulty :int = ba.readByte();
            var level :int = ba.readByte();
            var trackData :Array = ba.readObject();

            var track :Track = new Track();
            track.level = level;

            // For each measure
            for (var ii :int = 0; ii < trackData.length; ++ii) {
                var measure :Array = trackData[ii];
                var rowsInMeasure :int = measure.length/4;

                // For each note in measure
                for (var jj :int = 0; jj < measure.length; ++jj) {
                    var type :int = measure[jj] as int;

                    if (type != 0) {
                        var row :int = jj/4;
                        var beat :Number = 4*ii + 4*(row/rowsInMeasure);
                        track.beats.push(beat);
                        track.notes.push(new Note(type, jj%4));
                    }
                }
            }

            song.tracks[difficulty] = track;
        }

        return song;
    }
}

}
