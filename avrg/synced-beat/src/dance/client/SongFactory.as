package dance.client {

import flash.events.Event;
import flash.events.EventDispatcher;
import flash.events.IOErrorEvent;
import flash.events.SecurityErrorEvent;
import flash.net.URLLoader;
import flash.net.URLLoaderDataFormat;
import flash.net.URLRequest;
import flash.utils.ByteArray;

import com.threerings.util.ValueEvent;

import aduros.i18n.MessageUtil;
import aduros.util.F;

import dance.data.Song;

public class SongFactory extends EventDispatcher
{
    public static const SONG_LOADED :String = "SongLoaded";
    public static const SONG_ERROR :String = "SongError";

    public function load (ident :String) :void
    {
        var match :Array = ident.match(/(\w+):\/\/(.*)/);

        if (match == null) {
            dispatchError(MessageUtil.pack("e_missing_protocol", ident));
        }

        var protocol :String = match[1];
        var address :String = match[2];

        switch (protocol) {
            case "pack": // Official song
                loadHttp(BuildConfig.DATA_URL + "/packs/" + address + ".beat", ident);
                break;

            case "user": // User song
                loadHttp(BuildConfig.DATA_URL + "/songs/" + address + ".beat", ident);
                break;

            case "http": // User self hosted song
                loadHttp(ident, ident);
                break;

            default:
                dispatchError(MessageUtil.pack("e_unsupported_protocol", ident));
        }
    }

    protected function loadHttp (httpUrl :String, ident :String) :void
    {
        var loader :URLLoader = new URLLoader();
        loader.dataFormat = URLLoaderDataFormat.BINARY;

        loader.addEventListener(Event.COMPLETE, function (event :Event) :void {
            var ba :ByteArray = ByteArray(loader.data);
            dispatchLoaded(ident, ba);
        });
        loader.addEventListener(IOErrorEvent.IO_ERROR,
            function (event :IOErrorEvent) :void {
                dispatchError(MessageUtil.pack("e_io_error", ident, event.text));
            });
        loader.addEventListener(SecurityErrorEvent.SECURITY_ERROR,
            function (event :SecurityErrorEvent) :void {
                dispatchError(MessageUtil.pack("e_security_error", ident, event.text));
            });

        loader.load(new URLRequest(httpUrl));
    }

    protected function dispatchError (cause :Object) :void
    {
        dispatchEvent(new ValueEvent(SONG_ERROR, cause));
    }

    protected function dispatchLoaded (songURL :String, ba :ByteArray) :void
    {
        if (hasEventListener(SONG_LOADED)) {
            ba.uncompress();
            var song :Song = Song.fromBytes(ba);
            song.url = songURL;
            dispatchEvent(new ValueEvent(SONG_LOADED, song));
        }
    }
}

}
