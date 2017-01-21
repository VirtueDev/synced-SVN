package dance.client {

import flash.display.Sprite;
import flash.events.Event;
import flash.events.IEventDispatcher;

/** A base for all UI components that need to listen on models only while they're on the stage. */
public class Component extends Sprite
{
    public function Component ()
    {
        addEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
        addEventListener(Event.REMOVED_FROM_STAGE, onRemovedFromStage);
    }

    /** Call this before adding to stage! */
    protected function registerListener (
        dispatcher :IEventDispatcher, event :String, listener :Function) :void
    {
        _events.push([dispatcher, event, listener]);
    }

    protected function onAddedToStage (event :Event) :void
    {
        for each (var item :Array in _events) {
            item[0].addEventListener(item[1], item[2]);
        }
    }

    protected function onRemovedFromStage (event :Event) :void
    {
        for each (var item :Array in _events) {
            item[0].removeEventListener(item[1], item[2]);
        }
    }

    protected var _events :Array = [];
}

}
