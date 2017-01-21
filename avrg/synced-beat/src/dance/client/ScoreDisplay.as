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
import com.threerings.util.StringUtil;
import com.threerings.util.ValueEvent;

import aduros.display.ToolTipManager;

import dance.data.Song;
import dance.data.Track;
import dance.data.Note;

public class ScoreDisplay extends Component
{
    public function ScoreDisplay (model :DanceModel)
    {
        _model = model;

        registerListener(_model, DanceModel.SCORE_UPDATED, onScoreUpdated);
        registerListener(_model, DanceModel.LIFE_UPDATED, onLifeUpdated);

        _score = TextFieldUtil.createField("0",
            { textColor: 0xffffff, selectable: false, embedFonts: true,
                 autoSize: TextFieldAutoSize.LEFT, outlineColor: 0x00000 },
            { font: "dance", size: 40, bold: true });

        addChild(_score);
    }

    protected function onScoreUpdated (event :Event) :void
    {
        // TODO: Graphical effect?
        _score.text = StringUtil.formatNumber(_model.score);
    }

    protected function onLifeUpdated (event :Event) :void
    {
        if (_model.life == 0) {
            _score.textColor = 0xff0000;
        }
    }

    protected var _model :DanceModel;

    protected var _score :TextField;
}

}
