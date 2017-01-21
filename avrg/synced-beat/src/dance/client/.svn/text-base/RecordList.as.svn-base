package dance.client {

import flash.display.Loader;
import flash.display.Sprite;
import flash.net.URLRequest;
import flash.events.MouseEvent;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;

import com.threerings.text.TextFieldUtil;
import com.threerings.util.ValueEvent;

import aduros.util.F;

import dance.data.ScoreRecord;

public class RecordList extends Sprite
{
    public static const SELECT :String = "Selected"; // ValueEvent

    public static const WIDTH :int = 220;
    public static const ROW_HEIGHT :int = 16;

    public function RecordList (records :Array)
    {
        _records = records;

        for (var ii :int = 0; ii < _records.length; ++ii) {
            var record :ScoreRecord = _records[ii];

            var row :Sprite = new Sprite();
            row.y = ROW_HEIGHT*ii;

            var tf :TextField = TextFieldUtil.createField(Messages.en.xlate("l_playerEntry", ii+1, record.name),
                { textColor: DanceView.DIFFICULTY_COLORS[record.opts.difficulty], width: WIDTH,
                    autoSize: TextFieldAutoSize.LEFT, selectable: false, outlineColor: 0x00000 },
                { font: "_sans", size: 12, bold: true });
            row.addChild(tf);

            if (record.country != null) {
                var flag :Loader = new Loader();
                flag.load(new URLRequest(DanceView.getFlagURL(record.country)));
                flag.x = row.width + 8;
                flag.y = 2;
                row.addChild(flag);
            }

            row.graphics.beginFill(0, 0);
            row.graphics.drawRect(0, 0, WIDTH, ROW_HEIGHT);
            row.graphics.endFill();

            row.addEventListener(MouseEvent.CLICK, F.callback(dispatchSelect, ii));
            addChild(row);
        }
    }

    public function getRecord (index :int) :ScoreRecord
    {
        return _records[index];
    }

    public function dispatchSelect (index :int) :void
    {
        dispatchEvent(new ValueEvent(SELECT, index));
    }

    protected var _records :Array; // of ScoreRecord
}

}
