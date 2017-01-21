package dance.client {

import flash.events.Event;
import flash.utils.getTimer;

import aduros.util.F;

import dance.data.DanceOptions;
import dance.data.Note;
import dance.data.ScoreCard;
import dance.data.ScoreSummary;

public class DanceWatcher
{
    public function DanceWatcher (model :DanceModel)
    {
        _model = model;

        _model.addEventListener(DanceModel.DANCE_STARTED, onDanceStarted);

        _model.addEventListener(NoteHitEvent.NOTE_HIT, onNoteHit);
        _model.addEventListener(NoteEvent.NOTE_MISSED, onMissed);
        _model.addEventListener(HoldEvent.HOLD_MISSED, onMissed);
        _model.addEventListener(HoldEvent.HOLD_COMPLETE, onHoldComplete);
        _model.addEventListener(HoldEvent.HOLD_FAILED, onHoldFailed);
        _model.addEventListener(DanceModel.BOO, onBoo);
        _model.addEventListener(DanceModel.COMBO_UPDATED, onComboUpdated);
    }

    public function createCard () :ScoreCard
    {
        var card :ScoreCard = new ScoreCard();
        card.score = _model.score;
        card.combo = _model.combo;

        return card;
    }

    public static function isHittable (note :Note) :Boolean
    {
        return note.type == Note.TYPE_TAP || note.type == Note.TYPE_HOLD_END;
    }

    public function createSummary () :ScoreSummary
    {
        var summary :ScoreSummary = new ScoreSummary();
        summary.finalScore = _model.score;
        summary.bestCombo = _bestCombo;
        summary.tiersHit = _tiersHit;
        summary.holds = _holds;
        summary.boos = _boos;
        summary.misses = _misses;

        var totalHits :int = _model.track.notes.filter(F.adapt(isHittable)).length;
        var myHits :int = F.foldl(F.plus, 0, _tiersHit) + _holds;
        summary.accuracy = myHits / totalHits;

        var notes :Array = _model.track.notes;
        var perfectScore :Number = 0;
        for (var ii :int = 0; ii < notes.length; ++ii) {
            if (notes[ii].type == Note.TYPE_TAP) {
                perfectScore += DanceController.TAP_SCORE*DanceModel.TIER_TIMES.length * DanceModel.getMultiplier(ii+1);
            } else if (notes[ii].type == Note.TYPE_HOLD_END) {
                perfectScore += DanceController.HOLD_SCORE * DanceModel.getMultiplier(ii+1);
            }
        }
        var timeLength :Number = getTimer() - _model.startedOn;
        summary.rating = timeLength/(1000*60*1.5) * (_model.score/perfectScore) * (0.8+_model.track.level/5);

        return summary;
    }

    public function createOptions () :DanceOptions
    {
        var opts :DanceOptions = new DanceOptions();
        opts.difficulty = _model.difficulty;
        opts.level = Game.level;

        return opts;
    }

    protected function onDanceStarted (event :Event) :void
    {
        _tiersHit = DanceModel.TIER_TIMES.map(F.konst(0));
        _holds = 0;
        _boos = 0;
        _misses = 0;
        _bestCombo = 0;
    }

    protected function onNoteHit (event :NoteHitEvent) :void
    {
        _tiersHit[event.tier] += 1;
    }

    protected function onMissed (event :Event) :void
    {
        _misses += 1;
    }

    protected function onHoldComplete (event :Event) :void
    {
        _holds += 1;
    }

    protected function onHoldFailed (event :Event) :void
    {
        _misses += 1; // Not technically a miss, but let's pretend
    }

    protected function onBoo (event :Event) :void
    {
        _boos += 1;
    }

    protected function onComboUpdated (event :Event) :void
    {
        _bestCombo = Math.max(_model.combo, _bestCombo);
    }

    protected var _model :DanceModel;

    protected var _tiersHit :Array;
    protected var _holds :int;
    protected var _boos :int;
    protected var _misses :int;

    protected var _bestCombo :int;
}

}
