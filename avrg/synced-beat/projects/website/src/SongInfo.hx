import php.db.Object;

class SongInfo extends Object
{
    public var id :Int;

    public var uploaderId :Int;
    public var uploaderName :String;
    public var uploadOn :Date;

    public var title :String;
    public var artist :String;
    public var bpm :Float;

    // Bitfield of difficulties supported by this song: Beginner = 0 to Guru = 5
    public var difficulties :Int;

    public static var manager = new SongInfoManager();
}
