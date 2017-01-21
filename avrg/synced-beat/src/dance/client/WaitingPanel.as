package dance.client {

import flash.display.InteractiveObject;
import flash.display.Sprite;
import flash.events.MouseEvent;
import flash.geom.Rectangle;
import flash.text.StyleSheet;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;
import flash.utils.ByteArray;
import mx.effects.easing.Bounce;

import com.gskinner.motion.GTweeny;

import com.threerings.text.TextFieldUtil;
import com.threerings.util.Command;
import com.threerings.util.ValueEvent;

import com.whirled.avrg.*;
import com.whirled.net.*;
import com.whirled.ControlEvent;

import aduros.display.DisplayUtil;
import aduros.display.ImageButton;
import aduros.display.ToolTipManager;
import aduros.util.F;

import dance.data.Codes;
import dance.data.DanceResults;
import dance.data.ScoreRecord;
import dance.data.Song;

public class WaitingPanel extends RichComponent
{
    public static const PADDING :Number = 20;
    public static const SPACING :Number = 10;

    public function WaitingPanel (model :DanceModel)
    {
        registerListener(Game.ctrl.local, AVRGameControlEvent.SIZE_CHANGED, F.adapt(updateBounds));
        registerListener(Game.ctrl.room, ControlEvent.MUSIC_ID3, F.adapt(updateMusic));
        registerListener(Game.ctrl.room, ControlEvent.MUSIC_STARTED, F.adapt(updateMusic));
        registerListener(Game.ctrl.room, ControlEvent.MUSIC_STOPPED, F.adapt(updateMusic));

        var difficulty :DifficultySelector = new DifficultySelector(WIDTH);
        const startY :Number = -150;
        difficulty.y = startY;
        difficulty.addEventListener(MouseEvent.ROLL_OVER, function (event :MouseEvent) :void {
            new GTweeny(difficulty, 0.2, {y: 24});
        });
        difficulty.addEventListener(MouseEvent.ROLL_OUT, function (event :MouseEvent) :void {
            new GTweeny(difficulty, 0.2, {y: startY});
        });
        addChild(difficulty);

        var infobox :Sprite = new Sprite();
        infobox.graphics.beginFill(0, 0.6);
        infobox.graphics.lineStyle(1, 0xc0c0c0);
        infobox.graphics.drawRoundRect(0, 0, WIDTH, 20, 16);
        infobox.graphics.endFill();

        DanceView.applyStyle(_state);
        infobox.addChild(_state);
        addChild(infobox);

        updateMusic();
    }

    override public function transitionIn () :void
    {
        var bounds :Rectangle = updateBounds();

        this.y = -bounds.height;
        new GTweeny(this, 1, {
            y: 0
        });
    }

    override public function transitionOut () :GTweeny
    {
        var bounds :Rectangle = updateBounds();

        return new GTweeny(this, 0.2, {
            y: -bounds.height
        });
    }

    protected function updateBounds () :Rectangle
    {
        var bounds :Rectangle = Game.ctrl.local.getPaintableArea();

        if (bounds != null) {
            this.x = bounds.width - WIDTH;
        }

        return bounds;
    }

    protected function updateMusic () :void
    {
        var id3 :Object = Game.ctrl.room.getMusicId3();
        if (id3 != null) {
            if (id3 != null && id3.comment != null && id3.comment.indexOf("ddr=") != -1) {
                // Currently dancing
                _state.htmlText = Messages.en.xlate("l_musicValid");
            } else {
                _state.htmlText = Messages.en.xlate("l_musicInvalid");
            }
        } else {
            _state.htmlText = Messages.en.xlate("l_musicNone");
            // No music in room
        }
        _state.x = WIDTH/2 - _state.width/2;
    }

    protected static const WIDTH :Number = 450;

    protected var _state :TextField = TextFieldUtil.createField("",
        { textColor: 0xffffff, selectable: false,
            autoSize: TextFieldAutoSize.LEFT, outlineColor: 0x00000 },
        { font: "_sans", size: 14, bold: true });
}

}
