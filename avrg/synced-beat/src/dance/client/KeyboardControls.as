package dance.client {

import flash.events.EventDispatcher;
import flash.events.IEventDispatcher;
import flash.events.KeyboardEvent;

import com.threerings.util.Set;
import com.threerings.util.Sets;
import com.threerings.util.ValueEvent;

/**
 * TODO: Fix major PITA with key repeats http://stackoverflow.com/questions/433254/as3-detect-long-key-presses ?
 * You know, it's probably just my fucked up Ubuntu install. It wouldn't be the first input device problem on here.
 * But... if this is even fairly common, it needs a workaround (enter frame listeners, ugh).
 *
 * TODO: Document and move to libaduros
 */
public class KeyboardControls extends EventDispatcher
{
    public static const COMMAND_DOWN :String = "CommandDown";
    public static const COMMAND_UP :String = "CommandUp";

    public function KeyboardControls (source :IEventDispatcher, controls :Object)
    {
        _controls = controls;

        source.addEventListener(KeyboardEvent.KEY_DOWN, onKeyDown);
        source.addEventListener(KeyboardEvent.KEY_UP, onKeyUp);
    }

    protected function onKeyDown (event :KeyboardEvent) :void
    {
        if (event.keyCode in _controls) {
            var command :int = _controls[event.keyCode];

            if (_commandsDown.add(command)) {
                dispatchEvent(new ValueEvent(COMMAND_DOWN, command));
            }
        }
    }

    protected function onKeyUp (event :KeyboardEvent) :void
    {
        if (event.keyCode in _controls) {
            var command :int = _controls[event.keyCode];

            if (_commandsDown.remove(command)) {
                dispatchEvent(new ValueEvent(COMMAND_UP, command));
            }
        }
    }

    public function isCommandDown (command :int) :Boolean
    {
        return _commandsDown.contains(command);
    }

    protected var _controls :Object;
    protected var _commandsDown :Set = Sets.newSetOf(int);
}

}
