package dance.client {

import flash.display.Sprite;
import flash.events.MouseEvent;
import flash.geom.Rectangle;

import com.gskinner.motion.GTweeny;

import com.threerings.util.Command;

import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.display.ImageButton;
import aduros.util.F;

import dance.data.Codes;

public class OptionsPanel extends RichComponent
{
    public static const PADDING :Number = 5;
    public static const SPACING :Number = 5;

    public function OptionsPanel (model :DanceModel)
    {
        registerListener(Game.ctrl.local, AVRGameControlEvent.SIZE_CHANGED, F.adapt(updateBounds));

        var level :LevelDisplay = new LevelDisplay();
        level.y = PADDING;
        addChild(level);

        var buttons :Sprite = new Sprite();
        buttons.x = this.width;
        addChild(buttons);

        var locator :ImageButton = new ImageButton(new SEARCH_ICON(),
            Messages.en.xlate("t_locate"));
        Command.bind(locator, MouseEvent.CLICK, DanceController.LOCATE_PEERS);
        locator.x = buttons.width + SPACING + PADDING;
        locator.y = PADDING;
        //GraphicsUtil.throttleClicks(locator);
        buttons.addChild(locator);

        var enqueue :ImageButton = new ImageButton(new ENQUEUE_ICON(),
            Messages.en.xlate("t_addMusic"));
        Command.bind(enqueue, MouseEvent.CLICK, DanceController.ADD_MUSIC);
        enqueue.x = buttons.width + SPACING + PADDING;
        enqueue.y = PADDING;
        buttons.addChild(enqueue);

        var invite :ImageButton = new ImageButton(new INVITE_ICON(),
            Messages.en.xlate("t_invite"));
        Command.bind(invite, MouseEvent.CLICK, DanceController.INVITE);
        invite.x = buttons.width + SPACING + PADDING;
        invite.y = PADDING;
        buttons.addChild(invite);

        var quit :ImageButton = new ImageButton(new EXIT_ICON(),
            Messages.en.xlate("t_quit"));
        Command.bind(quit, MouseEvent.CLICK, DanceController.QUIT);
        quit.x = buttons.width + SPACING + PADDING;
        quit.y = PADDING;
        buttons.addChild(quit);

        this.scrollRect = new Rectangle(-5, -5, 5+this.width+2*PADDING, 5+this.height+2*PADDING);
        level.y = scrollRect.height+scrollRect.y - level.height;

        buttons.graphics.beginFill(0, 0.6);
        buttons.graphics.lineStyle(1, 0xc0c0c0);
        buttons.graphics.drawRoundRect(0, 0, buttons.width+100, buttons.height+100, 16);
        buttons.graphics.endFill();
    }

    override public function transitionIn () :void
    {
        var bounds :Rectangle = updateBounds();

        this.y = bounds.height;
        new GTweeny(this, 1, {
            y: bounds.height - scrollRect.height
        });
    }

    override public function transitionOut () :GTweeny
    {
        var bounds :Rectangle = updateBounds();

        return new GTweeny(this, 0.2, {
            y: bounds.height
        });
    }

    protected function updateBounds () :Rectangle
    {
        var bounds :Rectangle = Game.ctrl.local.getPaintableArea();

        if (bounds != null) {
            this.x = bounds.width - this.scrollRect.width;
            this.y = bounds.height - this.scrollRect.height;
        }

        return bounds;
    }

    // Control bar buttons
    [Embed(source="../../../res/search.png")]
    protected static const SEARCH_ICON :Class;
    [Embed(source="../../../res/audiocd.png")]
    protected static const ENQUEUE_ICON :Class;
    [Embed(source="../../../res/invite.png")]
    protected static const INVITE_ICON :Class;
    [Embed(source="../../../res/exit.png")]
    protected static const EXIT_ICON :Class;
}

}
