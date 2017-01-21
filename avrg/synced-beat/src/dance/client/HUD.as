package dance.client {

import flash.display.DisplayObject;
import flash.events.Event;
import flash.geom.Point;
import flash.utils.Dictionary;

import com.whirled.avrg.*;

public class HUD extends Component
{
    public function HUD ()
    {
        registerListener(Game.ctrl.room, AVRGameRoomEvent.PLAYER_MOVED, onPlayerMoved);
        registerListener(Game.ctrl.room, AVRGameRoomEvent.PLAYER_LEFT, onPlayerLeft);
        registerListener(Game.ctrl.local, AVRGameControlEvent.SIZE_CHANGED, onSizeChanged);
    }

    public function setMarker (playerId :int, disp :DisplayObject) :void
    {
        removeMarker(playerId);

        addChild(disp);
        _markers[playerId] = disp;

        updateMarker(playerId);
    }

    public function removeMarker (playerId :int) :void
    {
        if (playerId in _markers) {
            removeChild(_markers[playerId]);
            delete _markers[playerId];
        }
    }

    public function clearMarkers () :void
    {
        for each (var marker :DisplayObject in _markers) {
            removeChild(marker);
        }
        _markers = new Dictionary();
    }

    public function getMarker (playerId :int) :DisplayObject
    {
        return _markers[playerId];
    }

    protected function updateMarker (playerId :int) :AVRGameAvatar
    {
        var marker :DisplayObject = _markers[playerId];
        var avatar :AVRGameAvatar = Game.ctrl.room.getAvatarInfo(playerId);

        if (marker != null && avatar != null) {
            var hotspot :Point = Game.ctrl.local.locationToPaintable(avatar.x, avatar.y, avatar.z);

            marker.x = hotspot.x - marker.width/2;
            marker.y = hotspot.y - marker.height/2;

            return avatar;

        } else {
            return null;
        }
    }

    protected function onPlayerMoved (event :AVRGameRoomEvent) :void
    {
        var playerId :int = event.value as int;


        addEventListener(Event.ENTER_FRAME, function (event :Event) :void {
            var avatar :AVRGameAvatar = updateMarker(playerId);
            if (avatar == null || !avatar.isMoving) {
                removeEventListener(Event.ENTER_FRAME, arguments.callee);
            }
        });
    }

    protected function onPlayerLeft (event :AVRGameRoomEvent) :void
    {
        var playerId :int = event.value as int;

        removeMarker(playerId);
    }

    protected function onSizeChanged (event :AVRGameControlEvent) :void
    {
        for (var playerId :String in _markers) {
            updateMarker(int(playerId));
        }
    }

    protected var _markers :Dictionary = new Dictionary(); // playerId -> DisplayObject
}

}
