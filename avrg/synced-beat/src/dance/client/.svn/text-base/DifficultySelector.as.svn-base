package dance.client {

import flash.display.InteractiveObject;
import flash.display.Sprite;
import flash.events.MouseEvent;
import flash.filters.ColorMatrixFilter;
import flash.geom.Rectangle;
import flash.text.StyleSheet;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;
import flash.utils.ByteArray;

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

public class DifficultySelector extends Sprite
{
    public function DifficultySelector (length :Number, offset :Number = 50)
    {
        _length = length;
        _offset = 50;

        var difficultiesUnlocked :int = Game.difficultiesUnlocked;

        var icons :Array = [ DIFFICULTY_0, DIFFICULTY_1, DIFFICULTY_2, DIFFICULTY_3, DIFFICULTY_4 ];
        for (var ii :int = 0; ii < Codes.DIFFICULTIES; ++ii) {
            var button :ImageButton;
            if (ii < difficultiesUnlocked) {
                button = new ImageButton(new icons[ii], Messages.en.xlate("l_difficulty"+ii));
                Command.bind(button, MouseEvent.CLICK, DanceController.UPDATE_DIFFICULTY, ii);
                button.addEventListener(MouseEvent.CLICK, F.callback(setDifficulty, ii));
                button.addEventListener(MouseEvent.CLICK, F.callback(new DanceView.CLICK_SOUND().play));
            } else {
                button = new ImageButton(new DIFFICULTY_LOCKED(),
                    Messages.en.xlate("l_difficulty"+ii) +
                    Messages.en.xlate("l_unlockedAt", search(LevelDisplay.MILESTONES, "l_unlockDifficulty"+ii)));
            }
            addChild(button);
            _buttons[ii] = button;
        }
        addEventListener(MouseEvent.MOUSE_OVER, function (event :MouseEvent) :void {
            var button :ImageButton = event.target as ImageButton;
            button.filters = null;
            var difficulty :int = getChildIndex(button);
            for (var ii :int = difficulty+1; ii < _buttons.length; ++ii) {
                _buttons[ii].x += 192 - (_length-192)/_buttons.length - 4;
            }
        });
        addEventListener(MouseEvent.MOUSE_OUT, function (event :MouseEvent) :void {
            setDifficulty(Game.difficulty);
        });

        setDifficulty(Game.difficulty);
    }

    protected static function search (haystack :Object, needle :Object) :String
    {
        for (var key :String in haystack) {
            if (haystack[key] == needle) {
                return key;
            }
        }
        return null;
    }

    protected function setDifficulty (difficulty :int) :void
    {
        for (var ii :int = 0; ii < _buttons.length; ++ii) {
            var button :ImageButton = _buttons[ii];
//            button.parent.setChildIndex(button, ii);
            button.x = ii * (_length-192)/_buttons.length;
            button.y = 0;
            button.filters = DESATURATE;
        }
        var current :ImageButton = _buttons[difficulty];
        if (current != null) {
            current.y = _offset;
            current.filters = null;
        }
    }

    [Embed(source="../../../res/ScreenSelectDifficulty picture1.png")]
    protected static const DIFFICULTY_0 :Class;
    [Embed(source="../../../res/ScreenSelectDifficulty picture2.png")]
    protected static const DIFFICULTY_1 :Class;
    [Embed(source="../../../res/ScreenSelectDifficulty picture3.png")]
    protected static const DIFFICULTY_2 :Class;
    [Embed(source="../../../res/ScreenSelectDifficulty picture4.png")]
    protected static const DIFFICULTY_3 :Class;
    [Embed(source="../../../res/ScreenSelectDifficulty picture5.png")]
    protected static const DIFFICULTY_4 :Class;

    [Embed(source="../../../res/difficulty_locked.png")]
    protected static const DIFFICULTY_LOCKED :Class;

    // TODO: Share constant from NoteTreadmill
    protected static const DESATURATE :Array = [ new ColorMatrixFilter([
        1/3, 1/3, 1/3, 0, 0,
        1/3, 1/3, 1/3, 0, 0,
        1/3, 1/3, 1/3, 0, 0,
        0, 0, 0, 1, 0
    ]) ];

    protected var _length :Number;
    protected var _offset :Number;
    protected var _buttons :Array = []; // of ImageButton
}

}
