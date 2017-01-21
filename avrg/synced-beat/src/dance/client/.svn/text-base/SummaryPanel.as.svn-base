package dance.client {

import flash.display.InteractiveObject;
import flash.display.Bitmap;
import flash.display.Sprite;
import flash.events.MouseEvent;
import flash.geom.Rectangle;
import flash.text.StyleSheet;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;
import flash.utils.ByteArray;
import flash.net.navigateToURL;
import flash.net.URLRequest;

import com.gskinner.motion.GTweeny;

import com.threerings.text.TextFieldUtil;
import com.threerings.util.Command;
import com.threerings.util.DateUtil;
import com.threerings.util.StringUtil;
import com.threerings.util.ValueEvent;

import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.display.DisplayUtil;
import aduros.display.ImageButton;
import aduros.display.ToolTipManager;
import aduros.util.F;

import dance.data.Codes;
import dance.data.DanceResults;
import dance.data.ScoreRecord;
import dance.data.Song;

public class SummaryPanel extends RichComponent
{
    public static const PADDING :Number = 10;
    public static const SPACING :Number = 10;

    public function SummaryPanel (model :DanceModel)
    {
        registerListener(Game.ctrl.local, AVRGameControlEvent.SIZE_CHANGED, F.adapt(updateBounds));
        registerListener(Game.ctrl.room, MessageReceivedEvent.MESSAGE_RECEIVED, onRoomMessage);

        /** Keep a reference to the last played song. */
        _song = model.song;
    }

    override public function transitionIn () :void
    {
        var bounds :Rectangle = updateBounds();

        this.x = bounds.width;
        new GTweeny(this, 1, {
            x: bounds.width - WIDTH
        });
    }

    override public function transitionOut () :GTweeny
    {
        // Free it
        _song = null;

        var bounds :Rectangle = updateBounds();
        return new GTweeny(this, 0.2, {
            y: -height
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

    protected function row (msg :String, value :String) :String
    {
        return "<span class='stat'><span class='name'>" + Messages.en.xlate(msg) + "</span>" +
            "&nbsp;&nbsp;&nbsp;<span class='value'>" + value + "</span></span><br>";
    }

    protected function onRecordSelect (event :ValueEvent) :void
    {
        var list :RecordList = event.target as RecordList;
        var index :int = event.value as int;
        var record :ScoreRecord = list.getRecord(index);

        var me :Boolean = (record.playerId == Game.ctrl.player.getPlayerId());

        var html :String = "<body>";

        html += "<p class='header'>";
        if (record.country != null) {
            html += "<img id='flag' src='" + DanceView.getFlagURL(record.country) + "'" +
                "width='16' height='11' align='right'>";
        }
        html += Messages.en.xlate("l_summaryHeader", record.name);

        html += "</p><br><p>";
        html += row("l_score", StringUtil.formatNumber(record.summary.finalScore) + "&nbsp;&nbsp;/&nbsp;&nbsp;" +
            "<b>" + Messages.en.xlate("l_accuracy", Math.floor(record.summary.accuracy*100)) +"</b>");
        html += row("l_difficulty", "<font color='#" +
            DanceView.DIFFICULTY_COLORS[record.opts.difficulty].toString(16) + "'>" +
            Messages.en.xlate("l_difficulty"+record.opts.difficulty) + "</font>" +
            (me ? "&nbsp;&nbsp;<a href='" + DanceView.link(DanceController.SWITCH_STATE, DanceView.STATE_WAITING) + "'>" + Messages.en.xlate("l_difficultyChange") + "</a>" : ""));
        html += row("l_bestCombo", StringUtil.formatNumber(record.summary.bestCombo));
        for (var tier :int = record.summary.tiersHit.length-1; tier >= 0; --tier) {
            html += row("l_tier"+tier, StringUtil.formatNumber(record.summary.tiersHit[tier]));
        }
        html += row("l_holds", StringUtil.formatNumber(record.summary.holds));
        html += row("l_boos", StringUtil.formatNumber(record.summary.boos));
        html += row("l_misses", StringUtil.formatNumber(record.summary.misses));
        html += row("l_recordedOn", DateUtil.getConversationalDateString(record.createdOn));
        html += "</p>";

        if (me) {
            html += "<p><b>" + Messages.en.xlate("l_share") + "</b><br><br>";

            html += "<a href='" + DanceView.link(DanceController.SHARE_WHIRLED, 
                Messages.en.xlate("m_feedFacebook", StringUtil.formatNumber(record.summary.finalScore), _song.title)) +
                "'><img align='left' width='16' height='16' src='" + DanceView.getIconURL("whirled") + "'/>" +
                Messages.en.xlate("l_whirled") + "</a><br><br>";

            html += "<a href='" + DanceView.link(DanceController.SHARE_FACEBOOK, 
                Messages.en.xlate("m_feedFacebook", StringUtil.formatNumber(record.summary.finalScore), _song.title)) +
                "'><img width='16' height='16' src='" + DanceView.getIconURL("facebook") + "'/>" +
                Messages.en.xlate("l_facebook") + "</a><br><br>";

            html += "<p><a href='" + DanceView.link(DanceController.SHARE_TWITTER, 
                Messages.en.xlate("m_feedTwitter", StringUtil.formatNumber(record.summary.finalScore), _song.title)) +
                "'><img width='16' height='16' src='" + DanceView.getIconURL("twitter") + "'/>" +
                Messages.en.xlate("l_twitter") + "</a><br><br>";

            html += "</p>";

        } else {
            html += "<p><a href='" + DanceView.link(DanceController.SHOW_PROFILE, record.playerId) +
                "'>" + Messages.en.xlate("l_showProfile") + "</a></p>";
        }

        html += "</body>";

        var tf :TextField = new TextField();
        tf.multiline = true;
        tf.wordWrap = true;
        tf.width = DETAIL_WIDTH;
        tf.autoSize = TextFieldAutoSize.LEFT;
        DanceView.applyStyle(tf);
        tf.htmlText = html;

        var flag :InteractiveObject = tf.getImageReference("flag") as InteractiveObject;
        if (flag != null) {
            ToolTipManager.instance.attach(flag, Messages.en.xlate("t_flag", record.country.toUpperCase()));
        }

        DisplayUtil.removeAllChildren(_recordContainer);
        _recordContainer.addChild(tf);

        var newY :Number = list.y + RecordList.ROW_HEIGHT*index;
        if (_cursor.visible) {
            new GTweeny(_cursor, 0.1, {y: newY});
        } else {
            _cursor.y = newY;
            _cursor.visible = true;
        }
    }

    protected function onRoomMessage (event :MessageReceivedEvent) :void
    {
        if (event.name == Codes.MSG_RESULTS) {

            // Only run once
            Game.ctrl.room.removeEventListener(MessageReceivedEvent.MESSAGE_RECEIVED, onRoomMessage);

            var content :Sprite = new Sprite();
            content.x = PADDING;
            content.y = PADDING;
            addChild(content);

            var difficulty :Sprite = new Sprite();
            var selector :DifficultySelector = new DifficultySelector(500, 20);
            const outcrop :Number = 50;
            var mask :Sprite = new Sprite();
            mask.graphics.beginFill(0);
            mask.graphics.drawRect(0, 216-outcrop, selector.width, 20+outcrop);
            mask.graphics.endFill();

            selector.mask = mask;
            difficulty.addChild(selector);
            difficulty.addChild(mask);

            difficulty.rotation = 90;
            difficulty.x = 216-outcrop;
            addChild(difficulty);

            _cursor = new Sprite();
            _cursor.graphics.beginFill(0x188ab5);
            _cursor.graphics.drawRect(0, 0, RecordList.WIDTH, RecordList.ROW_HEIGHT);
            _cursor.graphics.endFill();
            _cursor.visible = false;
            content.addChild(_cursor);

            var title :TextField = new TextField();
            title.selectable = false;
            title.width = WIDTH;
            title.multiline = true;
            title.wordWrap = true;
            title.autoSize = TextFieldAutoSize.LEFT;
            DanceView.applyStyle(title);
            title.htmlText = "<body><span class='title'>" + DanceView.escapeHTML(_song.title) + "</span> " +
                "<span class='artist'>by " + DanceView.escapeHTML(_song.artist) + "</span></body>";
            content.addChild(title);

            var close :ImageButton = new ImageButton(new DanceView.STOP_ICON(),
                Messages.en.xlate("t_close"));
            Command.bind(close, MouseEvent.CLICK, DanceController.SWITCH_STATE, DanceView.STATE_WAITING);
            close.addEventListener(MouseEvent.CLICK, F.callback(new DanceView.CLICK_SOUND().play));
            close.x = WIDTH - 2*PADDING - close.width;
            content.addChild(close);

            var results :DanceResults = DanceResults.fromBytes(ByteArray(event.value));

            var lists :Array = [
                new RecordList(results.current),
                new RecordList(results.dailyBest),
                new RecordList(results.monthlyBest)
            ];

            for (var ii :int = 0; ii < lists.length; ++ii) {
                var header :TextField = TextFieldUtil.createField(Messages.en.xlate("l_summaryList"+ii),
                    { textColor: 0xffffff, width: RecordList.WIDTH,
                        autoSize: TextFieldAutoSize.LEFT, selectable: false, outlineColor: 0x00000 },
                    { font: "_sans", size: 12 });
                header.x = -PADDING/2; // Looks nice
                header.y = SPACING + (ii > 0 ?
                    lists[ii-1].y + lists[ii-1].height :
                    title.y + title.height + SPACING);
                content.addChild(header);

                var list :RecordList = lists[ii];
                list.addEventListener(RecordList.SELECT, onRecordSelect);
                list.y = header.y + header.height;
                content.addChild(list);
            }

            _recordContainer = new Sprite();
            _recordContainer.x = RecordList.WIDTH + SPACING;
            _recordContainer.y = title.y + title.height + 2*SPACING;
            content.addChild(_recordContainer);

            // Select myself to start
            var myId :int = Game.ctrl.player.getPlayerId();
            for (ii = 0; ii < results.current.length; ++ii) {
                if (myId == results.current[ii].playerId) {
                    lists[0].dispatchSelect(ii);
                    // Play a sound if they didn't suck/afk
                    if (results.current[ii].summary.finalScore > 0) {
                        new CHEER_SOUND().play();
                    }
                    break;
                }
            }

            graphics.beginFill(0, 0.6);
            graphics.lineStyle(1, 0xc0c0c0);
            graphics.drawRoundRect(0, 0, WIDTH, this.height+2*PADDING, 16);
            graphics.endFill();

            var ny :Number = PADDING + title.y + title.height + SPACING;

            graphics.lineStyle(1, 0xffffff);
            graphics.moveTo(0, ny);
            graphics.lineTo(WIDTH, ny);

            graphics.moveTo(PADDING + RecordList.WIDTH, ny);
            graphics.lineTo(PADDING + RecordList.WIDTH, this.height);

            var promo :Sprite = new Sprite();
            promo.addChild(new ENCORE_PROMO());
            promo.x = -promo.width - 80;
            ToolTipManager.instance.attach(promo, Messages.en.xlate("t_promo"));
            promo.addEventListener(MouseEvent.CLICK, function (e :MouseEvent) :void {
                navigateToURL(new URLRequest("http://ddrencore.com"), "_blank");
            });
            addChild(promo);
        }
    }

    [Embed(source="../../../res/cheer.mp3")]
    public static const CHEER_SOUND :Class;

    [Embed(source="../../../res/DDR Encore.png")]
    public static const ENCORE_PROMO :Class;

    protected static const DETAIL_WIDTH :int = 250;
    protected static const WIDTH :int = RecordList.WIDTH + DETAIL_WIDTH + 2*PADDING;

    protected var _cursor :Sprite;
    protected var _recordContainer :Sprite;

    protected var _song :Song;
}

}
