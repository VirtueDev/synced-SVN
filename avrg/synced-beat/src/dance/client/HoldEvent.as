package dance.client {

import flash.events.Event;

public class HoldEvent extends Event
{
    public static const HOLD_MISSED :String = "HoldMissed"; // HoldEvent
    public static const HOLD_STARTED :String = "HoldStarted"; // HoldEvent
    public static const HOLD_COMPLETE :String = "HoldComplete"; // HoldEvent
    public static const HOLD_FAILED :String = "HoldFailed"; // HoldEvent

    public var head :int;
    public var tail :int;

    public function HoldEvent (type :String, head :int, tail :int)
    {
        super(type);

        this.head = head;
        this.tail = tail;
    }
}

}
