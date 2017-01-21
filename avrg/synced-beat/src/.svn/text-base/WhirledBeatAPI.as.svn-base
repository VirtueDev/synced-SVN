package {

import flash.events.Event;
import flash.events.EventDispatcher;

import com.whirled.*;

/**
 * Dispatched when the dance has started and a song is available.
 */
[Event(name="DanceStarted", type="flash.events.Event")]

/**
 * Dispatched when a dance has ended, after which the song will not be available.
 */
[Event(name="DanceEnded", type="flash.events.Event")]

/**
 * Easy API for Whirled Beat enabled entities.
 */
public class WhirledBeatAPI extends EventDispatcher
{
    public static const DANCE_STARTED :String = "DanceStarted";
    public static const DANCE_ENDED :String = "DanceEnded";
    public static const AVATAR_LEVEL_CHANGED :String = "AvatarLevelChanged";

    public function WhirledBeatAPI (ctrl :EntityControl)
    {
        _ctrl = ctrl;

        _ctrl.registerPropertyProvider(propertyProvider);
        _ctrl.addEventListener(ControlEvent.MEMORY_CHANGED, onMemoryChanged);
    }

    /**
     * Gets the currently playing Whirled Beat song as an object containing the following properties:
     * title (String), artist (String), bpm (Number), credit (String), difficulty (int). Null if there
     * is no dance currently playing.
     */
    public function get song () :Object
    {
        return _song;
    }

    /** Gets the player's level in Whirled Beat, or 1 if unset or this entity is not an avatar. */
    public function get avatarLevel () :int
    {
        return Math.max(_ctrl.getMemory(AVATAR_LEVEL) as int, 1);
    }

    private function propertyProvider (name :String) :Object
    {
        if (name == "ddr:started") {
            return dispatchDanceStarted as Function;
        } else if (name == "ddr:ended") {
            dispatchDanceEnded();
            return true;
        } else if (name == "ddr:setLevel") {
            return setLevel as Function;
        } else {
            return null;
        }
    }

    private function onMemoryChanged (event :ControlEvent) :void
    {
        if (event.name == AVATAR_LEVEL) {
            dispatchEvent(new Event(AVATAR_LEVEL_CHANGED));
        }
    }

    private function dispatchDanceStarted (song :Object) :void
    {
        _song = song;
        dispatchEvent(new Event(DANCE_STARTED));
    }

    private function dispatchDanceEnded () :void
    {
        dispatchEvent(new Event(DANCE_ENDED));
        _song = null;
    }

    private function setLevel (level :int) :void
    {
        // If modified
        if (level != avatarLevel) {
            _ctrl.setMemory(AVATAR_LEVEL, level);
        }
    }

    /** Memory key set by the game containing the player's level in game (as int). Only set for avatars. */
    private static const AVATAR_LEVEL :String = "ddr:level";

    private var _ctrl :EntityControl;
    private var _song :Object;
}

}
