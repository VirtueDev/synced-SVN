package dance.client {

import flash.display.Sprite;
import flash.events.Event;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;
import flash.text.TextFormat;

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

public class ComboDisplay extends Component
{
    public function ComboDisplay (model :DanceModel)
    {
        _model = model;

        registerListener(_model, DanceModel.COMBO_UPDATED, onComboUpdated);

        _combo = TextFieldUtil.createField(
            _model.song.title + " by " + _model.song.artist + " (" + Messages.en.xlate("l_difficulty"+_model.difficulty) + ")",
            { embedFonts: true, selectable: false, multiline: true, wordWrap: true,
                 autoSize: TextFieldAutoSize.LEFT, width: NoteTreadmill.WIDTH, outlineColor: 0x00000 },
            { font: "dance", color: DanceView.DIFFICULTY_COLORS[_model.difficulty], size: 32, bold: true });
        addChild(_combo);

        HIGHLIGHT.color = 0x00ff00;
    }

    protected function onComboUpdated (event :Event) :void
    {
        if (_model.combo > 1) {
            _combo.visible = true;
            _combo.textColor = 0xffffff; // TODO: Remove when refactoring song title hack
            _combo.wordWrap = false; // Ditto
            _combo.text = Messages.en.xlate("l_combo", _model.combo);

            var multiplier :int = _model.multiplier;
            if (multiplier > 1) {
                var length :int = _combo.text.length;
                _combo.appendText(" x" + multiplier);
                _combo.setTextFormat(HIGHLIGHT, length, _combo.text.length);
            }

            _combo.x = 64*4/2 - _combo.textWidth/2;
        } else {
            _combo.visible = false;
        }
    }

    protected static const HIGHLIGHT :TextFormat = new TextFormat();

    protected var _model :DanceModel;

    protected var _combo :TextField;
}

}
