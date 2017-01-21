package dance.client {

import flash.display.Sprite;
import flash.events.Event;
import flash.events.MouseEvent;
import flash.geom.Rectangle;
import flash.utils.ByteArray;
import flash.utils.setTimeout;
import flash.utils.getTimer;

import com.gskinner.motion.GTweeny;

import com.threerings.util.Command;

import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.display.ImageButton;
import aduros.util.F;

import dance.data.Codes;
import dance.data.DanceOptions;
import dance.data.ScoreCard;

public class PlayingPanel extends RichComponent
{
    public function PlayingPanel (model :DanceModel)
    {
        registerListener(Game.ctrl.local, AVRGameControlEvent.SIZE_CHANGED, F.adapt(updateBounds));
        registerListener(Game.ctrl.room.props, ElementChangedEvent.ELEMENT_CHANGED, onRoomElementChanged);

        _hud = new HUD();
        addChild(_hud);

        var treadmill :NoteTreadmill = new NoteTreadmill(model);
        var score :ScoreDisplay = new ScoreDisplay(model);
        var combo :ComboDisplay = new ComboDisplay(model);
        var receptor :Receptor = new Receptor(model);
        var life :LifeBar = new LifeBar(model);
        var judge :JudgeDisplay = new JudgeDisplay(model);
        var overlay :FocusOverlay = new FocusOverlay(NoteSprite.SIZE*4, 500);

        score.y = 500 - score.height + 8;
        combo.y = 200;
        judge.y = combo.y + 70;

        treadmill.y = life.y + life.height;
        receptor.y = treadmill.y + NoteTreadmill.LOOKBEHIND_PIXELS;

        _board = new Sprite();
        _board.addChild(receptor);
        _board.addChild(treadmill);
        _board.addChild(combo);
        _board.addChild(judge);
        _board.addChild(life);
        _board.addChild(score);
        _board.addChild(overlay);

        var stop :ImageButton = new ImageButton(new DanceView.STOP_ICON(),
            Messages.en.xlate("t_close"));
        Command.bind(stop, MouseEvent.CLICK, DanceController.STOP_DANCING);
        stop.addEventListener(MouseEvent.CLICK, F.callback(new DanceView.CLICK_SOUND().play));
        stop.y = overlay.height - stop.height;
        stop.x = overlay.width - stop.width;
        _board.addChild(stop);

        _board.graphics.lineStyle(2, 0x00ff00);
        _board.graphics.lineTo(NoteSprite.SIZE*4, 0);
        addChild(_board);

        overlay.addEventListener(Event.ADDED_TO_STAGE, function (event :Event) :void {
            var timeToStart :int = DanceModel.INITIAL_SILENCE - getTimer() + model.startedOn;
            if (timeToStart > 0) {
                setTimeout(function () :void {
                    if (overlay.stage != null) {
                        overlay.focus();
                    }
                }, timeToStart);
            } else {
                overlay.focus();
            }
        });
    }

    override public function transitionIn () :void
    {
        var bounds :Rectangle = updateBounds();

        _board.x = bounds.width;
        new GTweeny(_board, 0.2, {
            x: bounds.width - _board.width
        });
    }

    override public function transitionOut () :GTweeny
    {
        var bounds :Rectangle = updateBounds();

        return new GTweeny(this, 1, {
            alpha: 0
        });
    }

    protected function updateBounds () :Rectangle
    {
        var bounds :Rectangle = Game.ctrl.local.getPaintableArea();

        if (bounds != null) {
            _board.x = bounds.width - _board.width;
        }

        return bounds;
    }

    protected function onRoomElementChanged (event :ElementChangedEvent) :void
    {
        if (event.name == Codes.PROP_CARDS && event.newValue is ByteArray) {
            var playerId :int = event.key;
            var ba :ByteArray = ByteArray(event.newValue);
            var card :ScoreCard = ScoreCard.fromBytes(ba);
            ba.position = 0;
            var marker :PlayerMarker = _hud.getMarker(playerId) as PlayerMarker;

            if (marker != null) {
                marker.update(card);
            } else {
                ba = Game.ctrl.room.props.get(Codes.PROP_OPTIONS)[playerId];
                if (ba != null) {
                    var opts :DanceOptions = DanceOptions.fromBytes(ba);
                    ba.position = 0; // Sigh

                    _hud.setMarker(playerId,
                        new PlayerMarker(Game.ctrl.room.getOccupantName(playerId), opts, card));
                }
            }
        }
    }

    protected var _hud :HUD;
    protected var _board :Sprite;
}

}
