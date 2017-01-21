package dance.client {

import flash.display.Sprite;
import flash.events.Event;
import flash.events.FocusEvent;
import flash.events.MouseEvent;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;

import com.threerings.text.TextFieldUtil;
import com.threerings.ui.KeyboardCodes;
import com.threerings.util.Command;
import com.threerings.util.ValueEvent;

import aduros.util.F;

public class FocusOverlay extends Sprite
{
    public function FocusOverlay (width :Number, height :Number)
    {
        graphics.beginFill(0, 0);
        graphics.drawRect(0, 0, width, height);
        graphics.endFill();

        this.focusRect = false;

        addEventListener(MouseEvent.MOUSE_DOWN, F.adapt(focus));
        addEventListener(FocusEvent.FOCUS_IN, onFocusIn);
        addEventListener(FocusEvent.FOCUS_OUT, onFocusOut);
        //addEventListener(Event.ADDED_TO_STAGE, F.adapt(focus));

        var controls :KeyboardControls = new KeyboardControls(this, CONTROLS);
        controls.addEventListener(KeyboardControls.COMMAND_DOWN, onCommandDown);
        controls.addEventListener(KeyboardControls.COMMAND_UP, onCommandUp);

        _message = new Sprite();
        var ready :TextField = TextFieldUtil.createField(
            Messages.en.xlate("l_getReady" + int(Math.random()*3)),
            { embedFonts: true, selectable: false, multiline: true, wordWrap: true,
                 autoSize: TextFieldAutoSize.CENTER, width: NoteTreadmill.WIDTH, outlineColor: 0x00000 },
            { font: "dance", color: 0xffffff, size: 38, bold: true });
        ready.x = width/2 - ready.textWidth/2;
        ready.y = 150;
        _message.addChild(ready);
        addChild(_message);
    }

    public function focus () :void
    {
        stage.focus = this;
    }

    protected function onCommandDown (event :ValueEvent) :void
    {
        // If more controls are ever added, handle them here

        Command.dispatch(this, DanceController.STEP_ON, event.value);
    }

    protected function onCommandUp (event :ValueEvent) :void
    {
        // If more controls are ever added, handle them here

        Command.dispatch(this, DanceController.STEP_OFF, event.value);
    }

    protected function onFocusIn (event :FocusEvent) :void
    {
        if (_message != null && contains(_message)) {
            removeChild(_message);
            _message = null;
        }
    }

    protected function onFocusOut (event :FocusEvent) :void
    {
        _message = new Sprite();
        _message.graphics.beginFill(0, 0.9);
        _message.graphics.drawRect(0, 0, this.width, this.height);
        _message.graphics.endFill();

        var label :TextField = TextFieldUtil.createField(Messages.en.xlate("l_unfocused"),
            { embedFonts: true, selectable: false, multiline: true, wordWrap: true,
                 autoSize: TextFieldAutoSize.CENTER, width: NoteTreadmill.WIDTH, outlineColor: 0x00000 },
            { font: "dance", color: 0xffffff, size: 32, bold: true });
        label.x = _message.width/2 - label.textWidth/2;
        label.y = _message.height/2;

        _message.addChild(label);
        addChild(_message);
    }

    public static const CONTROLS :Object = {
        // Arrow keys
        (int(KeyboardCodes.LEFT)): DanceController.PAD_LEFT,
        (int(KeyboardCodes.DOWN)): DanceController.PAD_DOWN,
        (int(KeyboardCodes.UP)): DanceController.PAD_UP,
        (int(KeyboardCodes.RIGHT)): DanceController.PAD_RIGHT,

        // Home row, right hand, qwerty
        (int(KeyboardCodes.J)): DanceController.PAD_LEFT,
        (int(KeyboardCodes.K)): DanceController.PAD_DOWN,
        (int(KeyboardCodes.L)): DanceController.PAD_UP,
        (int(KeyboardCodes.SEMICOLON)): DanceController.PAD_RIGHT,

        // Home row, right hand, qwerty
        (int(KeyboardCodes.A)): DanceController.PAD_LEFT,
        (int(KeyboardCodes.S)): DanceController.PAD_DOWN,
        (int(KeyboardCodes.D)): DanceController.PAD_UP,
        (int(KeyboardCodes.F)): DanceController.PAD_RIGHT,

        // Number pad
        (int(KeyboardCodes.NUMPAD_4)): DanceController.PAD_LEFT,
        (int(KeyboardCodes.NUMPAD_2)): DanceController.PAD_DOWN,
        (int(KeyboardCodes.NUMPAD_8)): DanceController.PAD_UP,
        (int(KeyboardCodes.NUMPAD_6)): DanceController.PAD_RIGHT
    };

    protected var _message :Sprite;
}

}
