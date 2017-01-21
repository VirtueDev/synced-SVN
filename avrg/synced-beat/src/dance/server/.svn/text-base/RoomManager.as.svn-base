package dance.server {

import flash.events.TimerEvent;
import flash.utils.getTimer;
import flash.utils.ByteArray;
import flash.utils.Dictionary;
import flash.utils.Timer;

import com.whirled.ControlEvent;
import com.whirled.avrg.*;
import com.whirled.net.*;

import aduros.net.REMOTE;
import aduros.net.BatchInvoker;
import aduros.util.F;

import dance.data.Codes;
import dance.data.DanceOptions;
import dance.data.DanceResults;
import dance.data.ScoreCard;
import dance.data.ScoreRecord;
import dance.data.ScoreSummary;

public class RoomManager
{
    public function RoomManager (ctrl :RoomSubControlServer)
    {
        _ctrl = ctrl;

        _ctrl.addEventListener(AVRGameRoomEvent.PLAYER_LEFT, onPlayerLeft);
        _ctrl.addEventListener(AVRGameRoomEvent.ROOM_UNLOADED, onRoomUnloaded);

        _invoker = new BatchInvoker(_ctrl);
        _invoker.start(200);
    }

    public function get ctrl () :RoomSubControlServer
    {
        return _ctrl;
    }

    /** All the players in this room. */
    public function get players () :Dictionary
    {
        return _players;
    }

    protected function onPlayerLeft (event :AVRGameRoomEvent) :void
    {
        var playerId :int = event.value as int;
        _invoker.push(F.callback(_ctrl.props.setIn, Codes.PROP_OPTIONS, playerId, null, true));
    }

    protected function onRoomUnloaded (event :AVRGameRoomEvent) :void
    {
        _invoker.stop();
    }

    REMOTE function update (playerId :int, cardBytes :ByteArray) :void
    {
        _invoker.push(F.callback(_ctrl.props.setIn, Codes.PROP_CARDS, playerId, cardBytes, true));
    }

    REMOTE function didStartDance (playerId :int, optionsBytes :ByteArray) :void
    {
        // Broadcast their options
        _invoker.push(F.callback(_ctrl.props.setIn, Codes.PROP_OPTIONS, playerId,
            DanceOptions.fromBytes(optionsBytes).toBytes())); // Reserialize to ensure bytes are kosher
//
//        var dance :Dance;
//        if (songURL in _dances) {
//            dance = DanceManager(_dances[songURL]);
//        } else {
//            dance = new Dance();
//            _dances[songURL] = dance;
//        }
//
//        dance.dancers.add(playerId);
    }

    REMOTE function didStopDance (playerId :int, songURL :String, status :int, summaryBytes :ByteArray) :void
    {
        var player :Player = _players[playerId];
        var record :ScoreRecord = new ScoreRecord();
        record.playerId = playerId;
        record.name = player.name;
        record.country = player.country;
        record.opts = DanceOptions.fromBytes(_ctrl.props.get(Codes.PROP_OPTIONS)[playerId]);
        record.summary = ScoreSummary.fromBytes(summaryBytes);

        if (status == Codes.STOP_COMPLETE) {
            var groupDance :Dance;
            if (songURL in _dances) {
                groupDance = _dances[songURL];
            } else {
                groupDance = new Dance();
                groupDance.songURL = songURL;
                _dances[songURL] = groupDance;
            }
            groupDance.currentRecords.push(record);

            if (groupDance.timer == null) {
                groupDance.timer = new Timer(4000, 1);
                groupDance.timer.addEventListener(TimerEvent.TIMER_COMPLETE, function (event :TimerEvent) :void {
                    _invoker.push(F.callback(finish, groupDance));
                    delete _dances[groupDance.songURL];
                });
                groupDance.timer.start();
            }
        } else {
            // The player just hit cancel
            var soloDance :Dance = new Dance();
            soloDance.songURL = songURL;
            soloDance.currentRecords = [record];
            _invoker.push(F.callback(finish, soloDance));
        }
    }

    protected function mergeScoreRecords (propName :String, dance :Dance) :Array
    {
        var top :Array = Server.ctrl.props.get(propName) as Array;
        if (top == null) {
            top = dance.currentRecords;
        } else {
            top = F.map(ScoreRecord.fromBytes, top).concat(dance.currentRecords);
        }
//        try {
//            top = (dance.songURL in dict) ?
//                F.map(ScoreRecord.fromBytes, dict[dance.songURL]).concat(dance.currentRecords) : dance.currentRecords;
//        } catch (error :Error) {
//            top = dance.currentRecords; // TODO: Temporary bullshit hack that will likely backfire until I can track this data corruption down
//        }

        top.sort(ScoreRecord.compare);
        top = top.slice(0, 5);

        Server.ctrl.props.set(propName,
            F.map(function (r :ScoreRecord) :ByteArray { return r.toBytes() }, top),
            true);

        return top;
    }

    protected function finish (dance :Dance) :void
    {
        dance.currentRecords.sort(ScoreRecord.compare);

        Server.depositToDJ(_ctrl.getMusicOwnerId(), 0.03*F.filter(didPlayerTry, dance.currentRecords).length);

        checkClear(ServerCodes.PROP_LAST_DAILY, 1000*60*60*24, ServerCodes.PROP_PREFIX_HIGHSCORE_DAILY, "dailyBest");
        checkClear(ServerCodes.PROP_LAST_MONTHLY, 1000*60*60*24*30, ServerCodes.PROP_PREFIX_HIGHSCORE_MONTHLY, "monthlyBest");

        var results :DanceResults = new DanceResults();
        results.current = dance.currentRecords;
        results.dailyBest = mergeScoreRecords(ServerCodes.propHighscoreDaily(dance.songURL), dance);
        results.monthlyBest = mergeScoreRecords(ServerCodes.propHighscoreMonthly(dance.songURL), dance);

        for each (var record :ScoreRecord in dance.currentRecords) {
            var player :Player = _players[record.playerId];
            if (player != null) {
                if (record.summary.rating > 0) {
                    player.stats.submit("xp", Math.ceil(record.summary.rating*100));
                }
                player.stats.submit("bestCombo", record.summary.bestCombo);
                player.stats.submit("bestScore", record.summary.finalScore);
                player.ctrl.completeTask(Codes.TASK_DANCE, record.summary.rating);
            }
        }

        _ctrl.sendMessage(Codes.MSG_RESULTS, results.toBytes()); // TODO: Send song url back
    }

    protected function checkClear (stampName :String, threshold :Number, propPrefix :String, statName :String) :void
    {
        var now :Number = new Date().time;
        if (now - (Server.ctrl.props.get(stampName) as Number) > threshold) {
            for (var songProp :String in Server.ctrl.props.getPropertyNames(propPrefix)) {
                var records :Array = Server.ctrl.props.get(songProp) as Array;
                if (records != null && records.length > 0) { // Should never happen, but who knows...
                    var best :ScoreRecord = ScoreRecord.fromBytes(records[0]);
                    Server.submitOffline(best.playerId, statName, 1);
                }
                Server.ctrl.props.set(songProp, null);
            }
            Server.ctrl.props.set(stampName, now, true);
        }
    }

    protected static function didPlayerTry (record :ScoreRecord) :Boolean
    {
        return record.summary.finalScore > 0;
    }

    protected var _ctrl :RoomSubControlServer;
    protected var _players :Dictionary = new Dictionary(); // playerId -> Player

    protected var _dances :Dictionary = new Dictionary(); // songURL -> Dance

    protected var _invoker :BatchInvoker;
}

}
