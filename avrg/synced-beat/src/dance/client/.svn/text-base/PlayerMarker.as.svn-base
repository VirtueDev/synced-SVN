package dance.client {

import flash.display.Sprite;
import flash.events.Event;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;

import com.threerings.text.TextFieldUtil;

import dance.data.DanceOptions;
import dance.data.ScoreCard;

public class PlayerMarker extends Sprite
{
    public function PlayerMarker (name :String, opts :DanceOptions, card :ScoreCard)
    {
        var content :Sprite = new Sprite();

        content.addChild(TextFieldUtil.createField(Messages.en.xlate("l_hudTitle", name, opts.level),
            { textColor: DanceView.DIFFICULTY_COLORS[opts.difficulty], selectable: false,
                 autoSize: TextFieldAutoSize.LEFT, outlineColor: 0x00000 },
            { font: "_sans", size: 12, bold: true }));

        _score = TextFieldUtil.createField("0",
            { textColor: 0xc0c0c0, selectable: false,
                 autoSize: TextFieldAutoSize.LEFT, outlineColor: 0x00000 },
            { font: "_sans", size: 12, bold: true });
        _score.y = content.height;
        content.addChild(_score);

        _combo = TextFieldUtil.createField(Messages.en.xlate("l_combo", 999), // For measurement
            { textColor: 0xffffff, selectable: false,
                 autoSize: TextFieldAutoSize.LEFT, outlineColor: 0x00000 },
            { font: "_sans", size: 16, bold: true });
        _combo.y = content.height;
        content.addChild(_combo);

        content.x = 4;
        content.y = 4;
        addChild(content);

        graphics.beginFill(0, 0.6);
        graphics.lineStyle(1, 0xc0c0c0);
        graphics.drawRoundRect(0, 0, this.width+4*2, this.height+4*2, 16);
        graphics.endFill();

        update(card);
    }

    public function update (card :ScoreCard) :void
    {
        _score.text = Messages.en.xlate("l_score") + ": " + card.score;

        if (card.combo > 1) {
            _combo.visible = true;
            _combo.text = Messages.en.xlate("l_combo", card.combo);
            _combo.x = (_combo.parent.width - _combo.textWidth)/2;
        } else {
            _combo.visible = false;
        }
    }

    protected var _score :TextField;
    protected var _combo :TextField;
}

}
