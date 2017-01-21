package dance.client {

import flash.display.Sprite;
import flash.events.Event;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;

import com.gskinner.motion.GTweeny;
import mx.effects.easing.Exponential;

import com.threerings.text.TextFieldUtil;
import com.threerings.util.Command;
import com.threerings.util.MethodQueue;
import com.threerings.util.ValueEvent;

import aduros.display.ToolTipManager;

import dance.data.Song;
import dance.data.Track;
import dance.data.Note;

public class LifeBar extends Component
{
    public function LifeBar (model :DanceModel)
    {
        _model = model;

        registerListener(_model, DanceModel.LIFE_UPDATED, onLifeUpdated);

        redraw();
    }

    protected function redraw () :void
    {
        if (_model.life == 0) {
            graphics.beginFill(0xff0000);
            graphics.drawRect(0, 0, 4*NoteSprite.SIZE, 12);
            graphics.endFill();

        } else {
            graphics.beginFill((_model.life >= 0.95) ? 0x00ff00 : 0x00cc00);
            graphics.drawRect(0, 0, 4*NoteSprite.SIZE*_model.life, 12);
            graphics.endFill();

            graphics.beginFill(0x666666);
            graphics.drawRect(4*NoteSprite.SIZE*_model.life, 0, 4*NoteSprite.SIZE*(1-_model.life), 12);
            graphics.endFill();
        }
    }

    protected function onLifeUpdated (event :Event) :void
    {
        redraw();
    }

    protected var _model :DanceModel;

    protected var _combo :TextField;
}

}
