package dance.client {

import flash.display.Sprite;
import flash.events.MouseEvent;
import flash.geom.Rectangle;
import flash.utils.ByteArray;
import flash.utils.Dictionary;

import com.gskinner.motion.GTweeny;

import com.threerings.util.Command;

import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.display.ImageButton;
import aduros.util.F;

import dance.data.Codes;
import dance.data.DanceOptions;

import nl.demonsters.debugger.MonsterDebugger;

public class BasicHUDPanel extends RichComponent
{
    public function BasicHUDPanel (model :DanceModel)
    {
        _hud = new HUD();
        addChild(_hud);

        registerListener(Game.ctrl.room.props, ElementChangedEvent.ELEMENT_CHANGED, onRoomElementChanged);
        registerListener(Game.ctrl.player, AVRGamePlayerEvent.ENTERED_ROOM, F.adapt(init));
        registerListener(Game.ctrl.player, AVRGamePlayerEvent.LEFT_ROOM, F.adapt(_hud.clearMarkers));

        init();
    }

    protected function init () :void
    {
        _hud.clearMarkers(); // Just in case

        for each (var playerId :int in Game.ctrl.room.getPlayerIds()) {
            showMarker(playerId);
        }
    }

    protected function showMarker (playerId :int) :void
    {
        var dict :Dictionary = Dictionary(Game.ctrl.room.props.get(Codes.PROP_OPTIONS));
        if (dict != null && dict[playerId] != null) {
            var ba :ByteArray = ByteArray(dict[playerId]);
            var opts :DanceOptions = DanceOptions.fromBytes(ba);
            ba.position = 0; // Be kind, rewind. Yes this is necessary.
            _hud.setMarker(playerId,
                new BasicMarker(Game.ctrl.room.getOccupantName(playerId), opts.level));
        }
    }

    protected function onRoomElementChanged (event :ElementChangedEvent) :void
    {
        if (event.name == Codes.PROP_OPTIONS) {
            showMarker(event.key);
        }
    }

    protected var _hud :HUD;
}

}
