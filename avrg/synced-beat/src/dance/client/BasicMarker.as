package dance.client {

import flash.display.Sprite;
import flash.events.Event;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;

import com.threerings.text.TextFieldUtil;

import dance.data.DanceOptions;
import dance.data.ScoreCard;

public class BasicMarker extends Sprite
{
    public function BasicMarker (name :String, level :int)
    {
        var content :Sprite = new Sprite();

        content.addChild(TextFieldUtil.createField(Messages.en.xlate("l_hudTitle", name, level),
            { textColor: 0xffffff, selectable: false,
                 autoSize: TextFieldAutoSize.LEFT, outlineColor: 0x00000 },
            { font: "_sans", size: 12, bold: true }));

        content.x = 4;
        content.y = 4;
        addChild(content);

        graphics.beginFill(0, 0.6);
        graphics.lineStyle(1, 0xc0c0c0);
        graphics.drawRoundRect(0, 0, this.width+4*2, this.height+4*2, 16);
        graphics.endFill();
    }
}

}
