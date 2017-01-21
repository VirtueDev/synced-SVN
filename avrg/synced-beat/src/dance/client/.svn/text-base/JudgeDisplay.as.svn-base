package dance.client {

import flash.display.Bitmap;
import flash.display.Sprite;
import flash.events.Event;
import flash.text.TextField;
import flash.text.TextFieldAutoSize;

import com.gskinner.motion.GTweeny;
import mx.effects.easing.Exponential;

import com.threerings.text.TextFieldUtil;
import com.threerings.util.Command;
import com.threerings.util.MethodQueue;
import com.threerings.util.Util;
import com.threerings.util.ValueEvent;

import aduros.display.ToolTipManager;

import dance.data.Song;
import dance.data.Track;
import dance.data.Note;

public class JudgeDisplay extends Component
{
    public function JudgeDisplay (model :DanceModel)
    {
        _model = model;

        registerListener(_model, NoteHitEvent.NOTE_HIT, onNoteHit);
        registerListener(_model, NoteEvent.NOTE_MISSED, onMissed);
        registerListener(_model, HoldEvent.HOLD_COMPLETE, onHoldComplete);
        registerListener(_model, HoldEvent.HOLD_FAILED, onHoldFailed);
        registerListener(_model, DanceModel.BOO, onBoo);
    }

    protected function onNoteHit (event :NoteHitEvent) :void
    {
        show(TIER_CLASSES[event.tier], {scaleX: 1.2}, {scaleX: 1});
    }

    protected function onMissed (event :Event) :void
    {
        show(MISSED, null, {y: 20});
    }

    protected function onHoldComplete (event :Event) :void
    {
        show(SWEET, {scaleX: 1.2}, {scaleX: 1});
    }

    protected function onHoldFailed (event :Event) :void
    {
        show(HOLD_IT, null, {y: 20});
    }

    protected function onBoo (event :Event) :void
    {
        show(BOO, null, {scaleX: 1.5, scaleY: 0.5});
    }

    protected function show (imageClass :Class, initProps :Object = null, destProps :Object = null) :void
    {
        if (_judgement != null) {
            removeChild(_judgement);
            _tween.pause();
        }

        _judgement = new Sprite();
        _judgement.x = NoteTreadmill.WIDTH/2 - _judgement.width/2;

        var image :Bitmap = new imageClass();
        image.x = -image.width/2;
        image.y = -image.height/2;
        _judgement.addChild(image);

        if (destProps == null) {
            destProps = {};
        }
        if (initProps != null) {
            Util.init(_judgement, initProps);
        }

        _tween = new GTweeny(_judgement, 0.2, destProps, {
            nextTween: new GTweeny(_judgement, 2, {alpha: 0}, {autoPlay: false, delay: 3})
        });

        addChild(_judgement);
    }

    protected var _model :DanceModel;

    protected var _judgement :Sprite;
    protected var _tween :GTweeny;

    [Embed(source="../../../res/judge/perfect.png")]
    protected static const PERFECT :Class;
    [Embed(source="../../../res/judge/good.png")]
    protected static const GOOD :Class;
    [Embed(source="../../../res/judge/ok.png")]
    protected static const OK :Class;
    [Embed(source="../../../res/judge/sweet.png")]
    protected static const SWEET :Class;
    [Embed(source="../../../res/judge/hold_it.png")]
    protected static const HOLD_IT :Class;
    [Embed(source="../../../res/judge/missed.png")]
    protected static const MISSED :Class;
    [Embed(source="../../../res/judge/boo.png")]
    protected static const BOO :Class;

    protected static const TIER_CLASSES :Array = [ OK, GOOD, PERFECT ];
}

}
